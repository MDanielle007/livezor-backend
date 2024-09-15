<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FarmerAssociationModel;
use App\Models\FarmerUserAssociationModel;
use App\Models\LivestockModel;
use App\Models\PersonnelDetailsModel;
use CodeIgniter\HTTP\ResponsableInterface;
use CodeIgniter\HTTP\ResponseInterface;
use \Firebase\JWT\JWT;
use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;


class UserController extends ResourceController
{
    private $userModel;
    private $livestock;
    private $personnelDetails;
    private $farmerAssociation;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->livestock = new LivestockModel();
        $this->personnelDetails = new PersonnelDetailsModel();
        $this->farmerAssociation = new FarmerAssociationModel();
        helper('jwt');
    }

    public function index()
    {
        return $this->respond(['message' => 'Hello, World']);
    }

    public function registerUser()
    {
        try {

            $file = $this->request->getFile('userImage');
            $newName = "";

            $uploadPath = WRITEPATH . 'uploads/userImage/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true); // Create directory if it doesn't exist
            }

            if (isset($file)) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move($uploadPath, $newName);
                    // You can also save the file path to your database
                } else {
                    return $this->fail('Invalid file upload');
                }
            }

            $firstName = $this->request->getPost('firstName');
            $lastName = $this->request->getPost('lastName');
            $userType = $this->request->getPost('userType');
            $data = (object) [
                'firstName' => $firstName,
                'userId' => $this->generateUserID($firstName, $lastName, $userType),
                'middleName' => $this->request->getPost('middleName'),
                'lastName' => $lastName,
                'dateOfBirth' => $this->request->getPost('dateOfBirth'),
                'gender' => $this->request->getPost('gender'),
                'civilStatus' => $this->request->getPost('civilStatus'),
                'userType' => $userType,
                'sitio' => $this->request->getPost('sitio'),
                'barangay' => $this->request->getPost('barangay'),
                'city' => $this->request->getPost('city'),
                'province' => $this->request->getPost('province'),
                'phoneNumber' => $this->request->getPost('phoneNumber'),
                'userImage' => $newName,
                'email' => $this->request->getPost('email'),
                'username' => $this->request->getPost('username'),
                'password' => $this->request->getPost('password'),
            ];

            $result = $this->userModel->insertUser($data);

            if ($data->userType == "DA Personnel") {
                $personnelDetails = $this->personnelDetails->insertPersonnelDetails((object) [
                    'userId' => $result,
                    'positionId' => null,
                    'departmentId' => null,
                ]);
            } else if ($data->userType == "Farmer") {
                $haveFarmerAssociation = $this->request->getPost('haveFarmerAssociation');
                if ($haveFarmerAssociation) {
                    $farmerAssociation = $this->farmerAssociation->getFarmerAssociationByName($data->farmerUserAssociation);
                    $farmerAssociationId = null;
                    if (!$farmerAssociation) {
                        $farmerAssociationId = $this->farmerAssociation->insertFarmerAssociation((object) ['farmerAssociationName' => $data->farmerUserAssociation]);
                    } else {
                        $farmerAssociationId = $farmerAssociation['id'];
                    }
                    $fuAssociations = new FarmerUserAssociationModel();
                    $farmerUserAssociation = $fuAssociations->insert(
                        (object) [
                            'userId' => $result,
                            'farmerAssociationId' => $farmerAssociationId
                        ]
                    );
                }
            }

            return $this->respond($result, 200);
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage());
            return $this->respond(['message' => 'Invalid username or password.', 'error' => $th->getMessage(), 'trace' => $th->getTrace()], 401);
        }
    }

    public function uploadUserImage()
    {
        try {
            $file = $this->request->getFile('newImage');
            $newName = "";

            $uploadPath = WRITEPATH . 'uploads/userImage/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true); // Create directory if it doesn't exist
            }

            if (isset($file)) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move($uploadPath, $newName);
                    // You can also save the file path to your database
                } else {
                    return $this->fail('Invalid file upload');
                }
            }

            $id = $this->request->getPost('id');

            $result = $this->userModel->updateUserImage($id, $newName);

            return $this->respond(['result' => $result], 200, 'Profile picture updated successfully');
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to update data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getUserImage($filename)
    {
        try {
            $filePath = WRITEPATH . 'uploads/userImage/' . $filename;

            if (file_exists($filePath)) {
                // Determine the file MIME type
                $mimeType = mime_content_type($filePath);

                // Set the headers for file download
                return $this->response
                    ->setHeader('Content-Type', $mimeType)
                    ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
                    ->setBody(file_get_contents($filePath));
            } else {
                return $this->failNotFound('Image not found.');
            }
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->failNotFound('Image not found.');
        }
    }

    public function getMortalityImage($filename)
    {
        try {
            $filePath = WRITEPATH . 'uploads/mortalities/' . $filename;

            if (file_exists($filePath)) {
                // Determine the file MIME type
                $mimeType = mime_content_type($filePath);

                // Set the headers for file download
                return $this->response
                    ->setHeader('Content-Type', $mimeType)
                    ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
                    ->setBody(file_get_contents($filePath));
            } else {
                return $this->failNotFound('Image not found.');
            }
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->failNotFound('Image not found.');
        }
    }

    private function generateUserID($firstName, $lastName, $userType)
    {
        // Determine the user type code
        switch ($userType) {
            case 'DA Personnel':
                $userTypeCode = 'DAP';
                break;
            case 'Farmer':
                $userTypeCode = 'FMR';
                break;
            case 'Care Taker':
                $userTypeCode = 'CTR';
                break;
            default:
                $userTypeCode = 'UNK'; // Unknown user type
                break;
        }

        // Extract initials from first and last names
        $initials = substr($firstName, 0, 1) . substr($lastName, 0, 1);

        // Get current date and time components
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        $hour = date('H');
        $minute = date('i');


        // Concatenate the parts to form the user ID
        $userID = $userTypeCode . '-' . strtoupper($initials) . $year . $month . $day . $hour . $minute;
        ;

        return $userID;
    }

    public function getAllUsers()
    {
        try {
            $users = $this->userModel->getAllUsers();

            $baseURL = getenv('app.baseURL');
            foreach ($users as &$user) {
                $user['image'] = $baseURL . 'uploads/' . $user['userImage'];
            }
            return $this->respond($users);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()], 401);
        }
    }

    public function getUser($id)
    {
        try {
            $user = $this->userModel->checkUserRole($id);
            if ($user['userRole'] == "DA Personnel") {
                return $this->fail('Fetching user data denied', ResponseInterface::HTTP_FORBIDDEN);
            }

            $user = $this->userModel->getUser($id);
            return $this->respond($user);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateUser()
    {
        try {
            $data = $this->request->getJSON();
            $response = $this->userModel->updateUser($data->id, $data);
            return $this->respond(['result' => $response], 200, 'Profile updated successfully)');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function updateUserPersonalInfo()
    {
        try {
            $data = $this->request->getJSON();
            $header = $this->request->getHeader("Authorization");
            $decoded = decodeToken($header);
            $userType = $decoded->aud;
            if ($userType == 'Farmer') {
                $data->id = $decoded->sub->id;
            }

            $user = $this->userModel->checkUserRole($data->id);
            if ($user['userRole'] == "DA Personnel") {
                return $this->fail('Fetching user data denied', ResponseInterface::HTTP_FORBIDDEN);
            }

            $response = $this->userModel->updateUserPersonalInfo($data->id, $data);
            return $this->respond(['result' => $response], 200, 'Profile updated successfully');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to update profile', 400);
        }
    }

    public function updateUserAccountInfo()
    {
        try {
            $data = (object) [
                'id' => $this->request->getPost('id'),
                'email' => $this->request->getPost('email'),
                'phoneNumber' => $this->request->getPost('phoneNumber'),
                'username' => $this->request->getPost('username'),
            ];
            $header = $this->request->getHeader("Authorization");
            $decoded = decodeToken($header);
            $userType = $decoded->aud;
            if ($userType == 'Farmer') {
                $data->id = $decoded->sub->id;
            }

            $user = $this->userModel->checkUserRole($data->id);
            if ($user['userRole'] == "DA Personnel") {
                return $this->fail('Fetching user data denied', ResponseInterface::HTTP_FORBIDDEN);
            }

            $file = $this->request->getFile('newImage');
            $newName = "";

            $uploadPath = WRITEPATH . 'uploads/userImage/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true); // Create directory if it doesn't exist
            }

            if (isset($file)) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move($uploadPath, $newName);
                    $data->userImage = $newName;
                    // You can also save the file path to your database
                } else {
                    return $this->fail('Invalid file upload');
                }
            }

            $result = $this->userModel->updateUserAccountInfo($data->id, $data);
            if (!$result) {
                return $this->fail($this->userModel->errors());
            }
            return $this->respond(['result' => $result], 200);
            // return $this->respond(['result' => $data], 200);

        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to update data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateUserPassword($id)
    {
        try {
            $data = $this->request->getJSON();
            $response = $this->userModel->updateUserPassword($id, $data->password);
            return $this->respond($response, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateUserRecordStatus($id)
    {
        try {
            $data = $this->request->getJSON();
            $response = $this->userModel->updateUserRecordStatus($id, $data->recordStatus);
            return $this->respond($response, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteUser($id)
    {
        try {
            $response = $this->userModel->deleteUser($id);
            return $this->respond($response, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllAdminFirebaseToken()
    {
        $usersTokens = $this->userModel->getAllUserFirebaseToken('DA Personnel');

        return $this->respond($usersTokens, 200);
    }

    public function getAllFarmerFirebaseToken()
    {
        $usersTokens = $this->userModel->getAllUserFirebaseToken('Farmer');

        return $this->respond($usersTokens, 200);
    }

    public function getUserFirebaseToken($id)
    {
        $userToken = $this->userModel->getUserFirebaseToken($id);

        return $this->respond($userToken[0]['firebase_token'], 200);
    }

    public function getFarmerCount()
    {
        try {
            $farmerCount = $this->userModel->getFarmerCount();

            $data = [
                'farmerCount' => "$farmerCount"
            ];

            return $this->respond($data, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllFarmersBasicInfo()
    {
        try {
            $farmers = $this->userModel->getAllFarmersBasicInfo();

            return $this->respond($farmers, 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    // testing method
    public function getUserName($id)
    {
        try {
            $userName = $this->userModel->getUserName($id);

            return $this->respond(['message' => $userName]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllFarmerLivestockTypeCount()
    {
        try {
            $farmers = $this->userModel->getFarmerNameAddress();

            foreach ($farmers as &$farmer) {
                $farmer['livestock'] = $this->livestock->getFarmerEachLivestockTypeCountData($farmer['id']);
            }

            return $this->respond($farmers, 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllFarmerLivestockTypeCountByAddress()
    {
        try {
            $barangay = $this->request->getGet('barangay');
            $city = $this->request->getGet('city');
            $province = $this->request->getGet('province');

            $farmers = $this->userModel->getFarmerNameSpecifiedAddress($barangay, $city, $province);

            foreach ($farmers as &$farmer) {
                $farmer['livestock'] = $this->livestock->getFarmerEachLivestockTypeCountData($farmer['id']);
            }

            return $this->respond($farmers, 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getFarmerUserInfo()
    {
        try {
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);

            $farmer = $this->userModel->getFarmerUserInfo($userId);
            return $this->respond($farmer, 200);
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
        }
    }

    public function getAdminUserInfo()
    {
        try {
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);

            $admin = $this->userModel->getAdminUserInfo($userId);

            if (!isset($admin['userImage'])) {
                $admin['userImage'] = null; // or set a default image URL if needed
            }

            return $this->respond($admin, 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllAdminUsers()
    {
        try {
            //code...
            $users = $this->userModel->getAllAdminUsers();
            return $this->respond($users, 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

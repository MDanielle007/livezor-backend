<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LivestockModel;
use \Firebase\JWT\JWT;
use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;


class UserController extends ResourceController
{
    private $userModel;
    private $livestock;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->livestock = new LivestockModel();
    }

    public function index()
    {
        return $this->respond(['message' => 'Hello, World']);
    }

    public function loginAuth()
    {
        try {
            $username = $this->request->getJsonVar('username');
            $password = $this->request->getJsonVar('password');
            $notiftoken = $this->request->getJsonVar('token');
            $loginDate = $this->request->getJsonVar('loginDate');

            $user = $this->userModel->getUserByUsername($username);

            if ($user && password_verify($password, $user['password'])) {

                $loginres = $this->userModel->setUserLogin($user['id'], $notiftoken, $loginDate);

                $key = getenv('JWT_SECRET');
                $iat = time(); // current timestamp value
                $exp = $iat + 10800;

                $payload = array(
                    "iss" => "OrMin Livestock Management System",
                    "aud" => $user['user_role'],
                    "sub" => ['id' => $user['id'], 'userId' => $user['user_id']],
                    "iat" => $iat, //Time the JWT issued at
                    "exp" => $exp, // Expiration time of token
                );

                $token = JWT::encode($payload, $key, 'HS256');

                $response = [
                    'login' => true,
                    'message' => 'Login Succesful',
                    'token' => $token,
                    'loginres' => $loginres
                ];
                return $this->respond($response, 200);
            } else {
                return $this->respond([
                    'login' => false,
                    'error' => 'Invalid username or password.'
                ]);
            }
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage());
            return $this->respond(['message' => 'Invalid username or password.', 'error' => $th->getMessage()]);
        }
    }

    public function userLogOut()
    {
        try {
            $userId = $this->request->getJsonVar('id');

            $result = $this->userModel->setUserLogout($userId);

            return $this->respond(['message' => 'Logged Out Successfully', 'success' => $result], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['message' => 'Failed to logout', 'error' => $th->getMessage()], 200);
        }
    }

    public function registerUser()
    {
        try {
            $data = $this->request->getJSON();
            $data->userId = $this->generateUserID($data->firstName, $data->lastName, $data->userType);
            $response = $this->userModel->insertUser($data);
            return $this->respond($response, 200);
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage());
            return $this->respond(['message' => 'Invalid username or password.', 'error' => $th->getMessage()], 401);
        }
    }

    public function uploadUserImage()
    {
        try {
            $file = $this->request->getFile('file');
            $newName = $file->getRandomName();
            if ($file->isValid() && !$file->hasMoved()) {
                $file->move('./uploads', $newName);
                return $this->respond(['path' => $newName], 200);
            }
        } catch (\Throwable $th) {
            return $this->respond(['error' => $th->getMessage()], 401);
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
        $userID = $userTypeCode . '-' . strtoupper($initials) . $year . $month . $day . $hour . $minute;;

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
            $user = $this->userModel->getUser($id);

            $baseURL = getenv('app.baseURL');
            $user['image'] = $baseURL . 'uploads/' . $user['userImage'];

            return $this->respond($user);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateUser($id)
    {
        try {
            $data = $this->request->getJSON();
            $response = $this->userModel->updateUser($id, $data);
            return $this->respond($response, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateUserPersonalInfo($id)
    {
        try {
            $data = $this->request->getJSON();
            $response = $this->userModel->updateUserPersonalInfo($id, $data);
            return $this->respond($response, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateUserAccountInfo($id)
    {
        try {
            $data = $this->request->getJSON();
            $result = $this->userModel->updateUserAccountInfo($id, $data);
            if (!$result) {
                return $this->fail($this->userModel->errors());
            }
            return $this->respond($result, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateUserPassword($id)
    {
        try {
            $data = $this->request->getJSON();
            $response = $this->userModel->updateUserPassword($id, $data);
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

    public function getFarmerUserInfo($id)
    {
        try {
            $farmer = $this->userModel->getFarmerUserInfo($id);
            return $this->respond($farmer, 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }
}

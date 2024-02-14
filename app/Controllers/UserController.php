<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use \Firebase\JWT\JWT;
use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;


class UserController extends ResourceController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
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

            $user = $this->userModel->getUserByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                $key = getenv('JWT_SECRET');
                $iat = time(); // current timestamp value
                $exp = $iat + 10800;

                $payload = array(
                    "iss" => "OrMin Livestock Management System",
                    "aud" => $user['user_role'],
                    "sub" => $user['user_id'],
                    "iat" => $iat, //Time the JWT issued at
                    "exp" => $exp, // Expiration time of token
                );

                $token = JWT::encode($payload, $key, 'HS256');

                $response = [
                    'login' => true,
                    'message' => 'Login Succesful',
                    'token' => $token
                ];
                return $this->respond($response, 200);
            } else {
                return $this->respond(['message' => 'Invalid username or password.'], 401);
            }
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage());
            return $this->respond(['message' => 'Invalid username or password.', 'error' => $th->getMessage()], 401);
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

    public function uploadUserImage(){
        try {
            $file = $this->request->getFile('file');
            $newName = $file->getRandomName();
            if ($file->isValid() && !$file->hasMoved()){
                $file->move('./uploads', $newName);
                return $this->respond(['path'=> $newName], 200);
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

        // Generate a random number
        $randomNumber = mt_rand(1000, 9999); // Generate a random 4-digit number

        // Concatenate the parts to form the user ID
        $userID = $userTypeCode . '-' . strtoupper($initials) . $randomNumber;

        return $userID;
    }
}

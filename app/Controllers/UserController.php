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
            // Get the JSON request body
            $username = $this->request->getJsonVar('username');
            $password = $this->request->getJsonVar('password');

            $user = $this->userModel->where('username', $username)->first();
            if (!$user) {
                return $this->respond(['message' => 'Invalid username or password.'], 401);
            }

            $pwdVerify = password_verify($password, $user['password']);
            if (!$pwdVerify) {
                return $this->respond(['message' => 'Invalid username or password.'], 401);
            }

            $key = getenv('JWT_SECRET');
            $iat = time(); // current timestamp value
            $exp = $iat + 10800;

            $payload = array(
                "iss" => "OrMin Livestock Management System",
                "aud" => $user['userRole'],
                "sub" => $user['userId'],
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
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage());
            return $this->respond(['message' => 'Invalid username or password.'], 401);
        }
    }
}

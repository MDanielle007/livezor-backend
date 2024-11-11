<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use \Firebase\JWT\JWT;

class AuthController extends ResourceController
{
    private $userModel;
    public function __construct()
    {
        $this->userModel = new UserModel();
        helper('jwt');
    }

    public function loginAuth()
    {
        try {
            $username = $this->request->getJsonVar('username');
            $password = $this->request->getJsonVar('password');
            $notiftoken = $this->request->getJsonVar('token');
            $loginDate = $this->request->getJsonVar('loginDate');
            $rememberMe = $this->request->getJsonVar('rememberMe');

            $user = $this->userModel->getUserByUsername($username);

            if ($user && password_verify($password, $user['password'])) {

                $loginres = $this->userModel->setUserLogin($user['id'], $notiftoken, $loginDate);
                if (!$loginres) {
                    return $this->respond([
                        'login' => false,
                        'error' => 'Invalid username or password.'
                    ]);
                }

                $key = getenv('JWT_SECRET');
                $iat = time(); // current timestamp value
                $exp = $iat + (60 * 60 * 24 * 7); // 7 days from now

                if ($rememberMe || $user['user_role'] == 'Farmer') {
                    // if remember me, make the expiration 
                    $exp = $iat + (60 * 60 * 24 * 30); // Example: 30 days from now
                }
                $iss = getenv('JWT_ISS');

                $payload = array(
                    "iss" => $iss,
                    "aud" => $user['user_role'],
                    "sub" => ['id' => $user['id'], 'userId' => $user['user_id']],
                    "iat" => $iat, //Time the JWT issued at
                    "exp" => $exp, // Expiration time of token
                );

                $token = JWT::encode($payload, $key, 'HS256');

                $response = [
                    'login' => $loginres,
                    'message' => 'Login Succesful',
                    'token' => $token,
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
            return $this->respond([
                'login' => false,
                'error' => 'Invalid username or password.'
            ]);
        }
    }

    public function userLogOut()
    {
        try {
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);

            $result = $this->userModel->setUserLogout($userId);

            return $this->respond(['message' => 'Logged Out Successfully', 'success' => $result], 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->respond(['message' => 'Failed to logout', 'error' => $th->getMessage()], 200);
        }
    }

    public function requestPasswordLink()
    {
        try {
            $data = $this->request->getJSON();
            $emailOrUsername = $data->emailOrUsername;

            // Validate input
            if (!$emailOrUsername) {
                return $this->fail('Email or Username is required.', 400);
            }

            $user = $this->userModel->checkUserEmailorUsername($emailOrUsername); //

            if (!$user) {
                return $this->failNotFound('Email or Username not found.');
            }

            // Generate a unique reset token and save it in the database
            $resetToken = bin2hex(random_bytes(32)); // Or use any token generation logic

            $key = getenv('JWT_SECRET');
            $iat = time(); // current timestamp value
            $exp = $iat + (60 * 60 * 3);

            $iss = getenv('JWT_ISS');

            $payload = array(
                "iss" => $iss,
                "aud" => $user['email'],
                "sub" => ['id' => $user['id'], 'userId' => $user['username']],
                "iat" => $iat, //Time the JWT issued at
                "exp" => $exp, // Expiration time of token
            );

            $token = JWT::encode($payload, $key, 'HS256');

            $this->userModel->update($user['id'], (object) ['reset_token' => $resetToken]);

            // Create reset link
            $frontend = getenv('FRONTEND_URL');
            $resetLink = "$frontend/password-reset?token=$token";

            // Send reset email
            $res = $this->sendResetEmail($user['name'], $user['email'], $resetLink);
            if (!$res) {
                throw new \Exception("Failed to send reset email.");
            }
            return $this->respond(['result' => true, 'message' => 'Password reset email sent.']);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('An error occurred while processing your request.', 500);
        }
    }

    private function sendResetEmail($name, $email, $resetLink)
    {
        try {
            $subject = 'Password Reset Request';
            $message = "
            <html>
            <head>
                <title>Password Reset Request</title>
            </head>
            <body>
                <p>Dear " . htmlspecialchars($name) . ",</p>
                <p>We received a request to reset the password for your account associated with this email address. If you made this request, please click the link below to reset your password:</p>
                <p><a href='" . htmlspecialchars($resetLink) . "' style='background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Reset Your Password</a></p>
                <p>If you did not request a password reset, you can safely ignore this email. Rest assured, your account is secure, and no changes have been made.</p>
                <p>For security reasons, the link above will expire in 3 hours. If the link has expired, you can request a new password reset by visiting our website.</p>
                <p>If you need further assistance, feel free to reach out to our support team.</p>
                <p>Best regards,<br>Department of Agriculture IT Support Team</p>
            </body>
            </html>
            ";

            // Use your email sending library or service here
            $emailService = \Config\Services::email();
            $emailService->setTo($email);
            $emailService->setSubject($subject);
            $emailService->setMessage($message);
            $emailService->setMailType('html'); // Set the email format to HTML

            if (!$emailService->send()) {
                log_message('error', $emailService->printDebugger(['headers', 'subject', 'body']));
                return false;
            }
            
            return true;
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage());
            return false;
        }
    }

    public function requestPasswordReset()
    {
        try {
            //code...
            $data = $this->request->getJSON();
            $password = $data->password;
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);

            $response = $this->userModel->updateUserPassword($userId, $password);

            return $this->respond(['result' => $response, 'message' => 'Password reset successfully.']);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('An error occurred while processing your request.', 500);
        }
    }
}

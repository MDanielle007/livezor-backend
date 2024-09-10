<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user_accounts';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['user_id', 'username', 'password', 'email', 'first_name', 'middle_name', 'last_name', 'date_of_birth', 'gender', 'civil_status', 'sitio', 'barangay', 'city', 'province', 'phone_number', 'user_image', 'user_role', 'user_status', 'last_login_date', 'remember_token', 'firebase_token', 'reset_token', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    public function setUserLogin($userId, $token, $loginDate)
    {
        try {
            $bind = [
                'firebase_token' => $token,
                'last_login_date' => $loginDate,
                'user_status' => 'Active'
            ];

            $result = $this->where('id', $userId)->set($bind)->update();
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function setUserLogout($userId)
    {
        $bind = [
            'user_status' => 'Inactive',
            'firebase_token' => null
        ];

        return $this->where('id', $userId)->set($bind)->update();
    }

    public function getAllUsers()
    {
        try {
            $users = $this->select(
                'id,
                user_id as userId,
                username,
                password,
                email,
                first_name as firstName,
                middle_name as middleName,
                last_name as lastName,
                date_of_birth as dateOfBirth,
                gender,
                civil_status as civilStatus,
                sitio,
                barangay,
                city,
                province,
                phone_number as phoneNumber,
                user_image as userImage,
                user_role as userRole,
                user_status as userStatus,
                last_login_date as lastLoginDate'
            )->findAll();

            return $users;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertUser($data)
    {
        try {
            $bind = [
                'first_name' => $data->firstName,
                'user_id' => $data->userId,
                'middle_name' => $data->middleName,
                'last_name' => $data->lastName,
                'date_of_birth' => $data->dateOfBirth,
                'gender' => $data->gender,
                'civil_status' => $data->civilStatus,
                'user_role' => $data->userType,
                'sitio' => $data->sitio,
                'barangay' => $data->barangay,
                'city' => $data->city,
                'province' => $data->province,
                'phone_number' => $data->phoneNumber,
                'user_image' => $data->userImage,
                'email' => $data->email,
                'username' => $data->username,
                'password' => password_hash($data->password, PASSWORD_DEFAULT),
            ];

            $result = $this->insert($bind);
            return $result;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getUser($id)
    {
        try {
            $user = $this->select('
                id,
                user_id as userId,
                username,
                email,
                first_name as firstName,
                middle_name as middleName,
                last_name as lastName,
                date_of_birth as dateOfBirth,
                gender,
                civil_status as civilStatus,
                sitio,
                barangay,
                city,
                province,
                phone_number as phoneNumber,
                user_image as userImage,
                user_role as userRole,
                user_status as userStatus
            ')
                ->where('user_id', $id)
                ->first();

            return $user;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getFarmerUserInfo($id)
    {
        try {
            $whereClause = [
                'user_role' => 'Farmer',
                'record_status' => 'Accessible',
            ];

            $user = $this->select(
                'id,
                user_id as userId,
                username,
                email,
                first_name as firstName,
                middle_name as middleName,
                last_name as lastName,
                date_of_birth as dateOfBirth,
                gender,
                civil_status as civilStatus,
                sitio,
                barangay,
                city,
                province,
                phone_number as phoneNumber,
                user_image as userImage,
                user_role as userRole,'
            )->where($whereClause)->find($id);

            return $user;
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
        }
    }

    public function getAdminUserInfo($id)
    {
        try {
            $whereClause = [
                'user_role' => 'DA Personnel',
                'record_status' => 'Accessible',
            ];

            $user = $this->select(
                'id,
                user_id as userId,
                username,
                email,
                first_name as firstName,
                middle_name as middleName,
                last_name as lastName,
                date_of_birth as dateOfBirth,
                gender,
                civil_status as civilStatus,
                sitio,
                barangay,
                city,
                province,
                phone_number as phoneNumber,    
                user_image as userImage,
                user_role as userRole,'
            )->where($whereClause)->find($id);

            return $user;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function checkUserEmailorUsername($emailOrUsername)
    {
        try {
            $user = $this->select('
                id,
                email,
                username,
                CONCAT(first_name, " ", last_name) as name,
            ')->where('email', $emailOrUsername)
                ->orWhere('username', $emailOrUsername)
                ->first();

            return $user;
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function updateUser($id, $data)
    {
        try {
            $bind = (object)[
                'first_name' => $data->firstName,
                'middle_name' => $data->middleName,
                'last_name' => $data->lastName,
                'date_of_birth' => $data->dateOfBirth,
                'gender' => $data->gender,
                'civil_status' => $data->civilStatus,
                'sitio' => $data->sitio,
                'barangay' => $data->barangay,
                'city' => $data->city,
                'province' => $data->province,
                'phone_number' => $data->phoneNumber,
                'email' => $data->email,
                'username' => $data->username,
            ];

            $result = $this->update($id, $bind);
            return $result;
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function updateUserPersonalInfo($id, $data)
    {
        try {
            $bind = [
                'first_name' => $data->firstName,
                'middle_name' => $data->middleName,
                'last_name' => $data->lastName,
                'date_of_birth' => $data->dateOfBirth,
                'gender' => $data->gender,
                'civil_status' => $data->civilStatus,
                'sitio' => $data->sitio,
                'barangay' => $data->barangay,
                'city' => $data->city,
                'province' => $data->province,
                'phone_number' => $data->phoneNumber,
            ];

            $result = $this->update($id, $bind);
            return $result;
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
        }
    }

    public function updateUserAccountInfo($id, $data)
    {
        try {
            $bind = [
                'user_image' => $data->userImage,
                'email' => $data->email,
                'username' => $data->username,
                'password' => password_hash($data->password, PASSWORD_DEFAULT),
            ];

            $result = $this->update($id, $bind);
            return $result;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function updateUserPassword($id, $password)
    {
        try {
            $bind = [
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ];

            $result = $this->update($id, $bind);
            return $result;
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $th->getMessage();
        }
    }

    public function updateUserRecordStatus($id, $status)
    {
        try {
            $bind = [
                'record_status' => $status,
            ];

            $result = $this->update($id, $bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function deleteUser($id)
    {
        try {
            $result = $this->delete($id);
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getAllUserFirebaseToken($userRole)
    {
        try {
            $result = $this->select('firebase_token')->where('user_role', $userRole)->where('firebase_token IS NOT NULL')->findAll();
            $users = array_column($result, 'firebase_token');
            return $users;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getUserFirebaseToken($id)
    {
        try {
            $result = $this->select('firebase_token')->where('id', $id)->find();
            $token = array_column($result, 'firebase_token');
            return $token;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getBasicUserInfo()
    {
        try {
            $result = $this->select(
                'id,
                first_name as firstName,
                last_name as lastName,
                user_role as userRole, 
                sitio, 
                barangay, 
                city, 
                province, 
                username,
                user_id as userId'
            )
                ->where('user_role', 'Farmer')
                ->findAll();
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getUserName($id)
    {
        try {
            $userData = $this->select(
                'CONCAT(first_name, " ", last_name) as userName'
            )->find($id);

            return $userData;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerCount()
    {
        try {
            $whereClause = [
                'user_role' => 'Farmer',
                'record_status' => 'Accessible',
            ];

            $farmerCount = $this->where($whereClause)->countAllResults();

            return $farmerCount;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllFarmersBasicInfo()
    {
        try {
            $whereClause = [
                'user_role' => 'Farmer',
                'record_status' => 'Accessible',
            ];

            $farmers = $this->select('
                id,
                user_id as userId,
                CONCAT(first_name, " ", last_name) as userName,
                first_name as firstName,
                middle_name as middleName,
                last_name as lastName,
            ')->where($whereClause)->findAll();

            return $farmers;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerCountByCity($city)
    {
        try {
            $whereClause = [
                'user_role' => 'Farmer',
                'record_status' => 'Accessible',
                'city' => $city
            ];

            $userCount = $this->where($whereClause)->countAllResults();

            return $userCount;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerByLivestock($id)
    {
        try {
            $whereClause = [
                'user_accounts.user_role' => 'Farmer',
                'user_accounts.record_status' => 'Accessible',
                'farmer_livestocks.ownership_status' => 'Owned',
                'livestocks.id' => $id
            ];

            $farmer = $this->select('
                user_accounts.id,
                user_accounts.user_id as userId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as userName
            ')
                ->join('farmer_livestocks', 'farmer_livestocks.farmer_id = user_accounts.id')
                ->join('livestocks', 'livestocks.id = farmer_livestocks.livestock_id')
                ->where($whereClause)->findAll();

            return $farmer;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerNameAddress()
    {
        try {
            $whereClause = [
                'user_role' => 'Farmer',
                'record_status' => 'Accessible',
            ];

            $farmer = $this->select('
                id,
                user_id as userId,
                CONCAT(first_name, " ", last_name) as fullName,
                user_role as userRole,
                sitio,
                barangay,
                city,
                province,
                user_image as userImage
            ')
                ->where($whereClause)->findAll();

            return $farmer;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerNameSpecifiedAddress($barangay, $city, $province)
    {
        try {
            $whereClause = [
                'user_role' => 'Farmer',
                'record_status' => 'Accessible',
            ];

            if ($barangay) {
                $whereClause['barangay'] = $barangay;
            }

            if ($city) {
                $whereClause['city'] = $city;
            }

            if ($province) {
                $whereClause['province'] = $province;
            }

            $farmer = $this->select('
                id,
                user_id as userId,
                CONCAT(first_name, " ", last_name) as fullName,
                user_role as userRole,
                sitio,
                barangay,
                city,
                province,
                user_image as userImage
            ')
                ->where($whereClause)->findAll();

            return $farmer;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getIdByUserId($userId)
    {
        try {
            $whereClause = [
                'user_id' => $userId
            ];

            $id = $this->select('id')->where($whereClause)->first();

            return $id;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllAdminUsers()
    {
        try {
            //code...
            $data = $this->select('
                user_accounts.id,
                user_accounts.user_id as userId,
                user_accounts.user_image as userImage,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as fullName,
                user_accounts.user_role as userRole,
                COALESCE(personnel_positions.position_name, "unspecified") as positionName,
                COALESCE(personnel_departments.department_name, "unspecified") as departmentName,
                personnel_details.employee_status as employeeStatus
            ')
                ->join('personnel_details', 'personnel_details.user_id = user_accounts.id','left')
                ->join('personnel_positions', 'personnel_positions.id = personnel_details.position_id','left')
                ->join('personnel_departments', 'personnel_positions.department_id = personnel_departments.id','left')
                ->where('user_accounts.user_role', 'DA Personnel')
                ->find();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    public function updateUserImage($id, $image){
        try {
            $data = [
                'user_image' => $image
            ];

            $res = $this->update($id, $data);
            return $res;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return false;
        }
    }
}

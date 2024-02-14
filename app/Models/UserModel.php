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
    protected $allowedFields = ['user_id', 'username', 'password', 'email', 'first_name', 'middle_name', 'last_name', 'date_of_birth', 'gender', 'civil_status', 'sitio', 'barangay', 'city', 'province', 'phone_number', 'user_image', 'user_role', 'user_status', 'last_login_date', 'created_at', 'updated_at', 'remember_token', 'record_status'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
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
}

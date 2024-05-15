<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonnelDetailsModel extends Model
{
    protected $table = 'personnel_details';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['user_id', 'position_id', 'department_id', 'employee_status', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllPersonnelDetails()
    {
        try {
            $personnelDetails = $this->select('
                personnel_details.id,
                personnel_details.user_id,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as userName
                personnel_positions.position_name as positionName,
                personnel_departments.department_name as departmentName,
            ')
                ->join('personnel_positions', 'personnel_positions.id = personnel_details.position_id')
                ->join('personnel_departments', 'personnel_positions.department_id = personnel_departments.id')
                ->join('user_accounts', 'user_accounts.id = personnel_details.user_id')
                ->where('personnel_details.record_status', 'Accessible')
                ->findAll();

            return $personnelDetails;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getPersonnelDetail($id)
    {
        try {
            $personnelDetails = $this->select('
                personnel_details.id,
                personnel_details.user_id,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as userName
                personnel_positions.position_name as positionName,
                personnel_departments.department_name as departmentName,
            ')
                ->join('personnel_positions', 'personnel_positions.id = personnel_details.position_id')
                ->join('personnel_departments', 'personnel_positions.department_id = personnel_departments.id')
                ->join('user_accounts', 'user_accounts.id = personnel_details.user_id')
                ->where('personnel_details.record_status', 'Accessible')
                ->find($id);

            return $personnelDetails;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getPersonnelDetailByUserId($userId)
    {
        try {
            $personnelDetails = $this->select('
                personnel_details.id,
                personnel_details.user_id,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as userName,
                COALESCE(personnel_positions.position_name, "Edit job position") as positionName,
                personnel_details.position_id as positionId,
                COALESCE(personnel_departments.department_name, "Edit designated department") as departmentName,
                personnel_details.department_id as departmentId,
                personnel_details.employee_status as employeeStatus
            ')
                ->join('personnel_positions', 'personnel_positions.id = personnel_details.position_id', 'left')
                ->join('personnel_departments', 'personnel_departments.id = personnel_positions.department_id', 'left')
                ->join('user_accounts', 'user_accounts.id = personnel_details.user_id', 'inner')
                ->where('personnel_details.record_status', 'Accessible')
                ->where('user_accounts.id', $userId)
                ->find();

            return $personnelDetails[0];
        } catch (\Throwable $th) {
            // You can throw the error or handle it here
            return $th->getMessage();
        }
    }

    public function insertPersonnelDetails($data)
    {
        try {
            $bind = [
                'user_id' => $data->userId,
                'position_id' => $data->positionId,
                'department_id' => $data->departmentId,
            ];

            $personnelDetail = $this->insert($bind);

            return $personnelDetail;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updatePersonnelDetails($id, $data)
    {
        try {
            $bind = [
                'user_id' => $data->userId,
                'position_id' => $data->positionId,
                'department_id' => $data->departmentId,
            ];

            if (isset($data->recordStatus)) {
                $bind['record_status'] = $data->recordStatus;
            }

            $personnelDetails = $this->update($id, $bind);

            return $personnelDetails;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deletePersonnelDetails($id)
    {
        try {
            $personnelDetails = $this->delete($id);

            return $personnelDetails;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

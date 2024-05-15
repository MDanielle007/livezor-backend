<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonnelDepartmentsModel extends Model
{
    protected $table            = 'personnel_departments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['department_name', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getAllDepartments(){
        try {
            $departments = $this->select(
                'id,
                department_name as departmentName'
            )
            ->where('record_status','Accessible')
            ->findAll();

            return $departments;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getDepartment($id){
        try {
            $department = $this->select(
                'id,
                department_name as departmentName'
            )
            ->where('record_status','Accessible')
            ->where('id',$id)
            ->first();

            return $department;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertDepartment($data){
        try {
            $bind = [
                'department_name' => $data
            ];

            $department = $this->insert($bind);

            return $department;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateDepartment($id,$data){
        try {
            $bind = [
                'department_name' => $data
            ];

            if(isset($data->recordStatus)){
                $bind['record_status'] = $data->recordStatus;
            }

            $department = $this->update($id,$bind);

            return $department;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteDepartment($id){
        try {
            $department = $this->delete($id);

            return $department;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

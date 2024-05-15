<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonnelPositionsModel extends Model
{
    protected $table = 'personnel_positions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['position_name', 'department_id', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllPositions()
    {
        try {
            $positions = $this->select('
                personnel_positions.id,
                personnel_positions.position_name as positionName,
                personnel_departments.department_name as departmentName,
            ')
                ->join('personnel_departments', 'personnel_positions.department_id = personnel_departments.id')
                ->where('personnel_positions.record_status', 'Accessible')
                ->findAll();

            return $positions;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getPosition($id)
    {
        try {
            $positions = $this->select('
                personnel_positions.id,
                personnel_positions.position_name as positionName,
                personnel_departments.department_name as departmentName,
            ')
                ->join('personnel_departments', 'personnel_positions.department_id = personnel_departments.id')
                ->where('personnel_positions.record_status', 'Accessible')
                ->find($id);

            return $positions;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getPositionByDepartmentId($departmentId)
    {
        try {
            $positions = $this->select('
                personnel_positions.id,
                personnel_positions.position_name as positionName,
                personnel_departments.department_name as departmentName,
            ')
                ->join('personnel_departments', 'personnel_positions.department_id = personnel_departments.id')
                ->where('personnel_positions.record_status', 'Accessible')
                ->where('personnel_positions.department_id', $departmentId)
                ->findAll();

            return $positions;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertPosition($data)
    {
        try {
            $bind = [
                'position_name' => $data->positionName,
                'department_id' => $data->departmentId
            ];

            $position = $this->insert($bind);

            return $position;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updatePosition($id, $data)
    {
        try {
            $bind = [
                'position_name' => $data->positionName,
                'department_id' => $data->departmentId
            ];

            if (isset($data->recordStatus)) {
                $bind['record_status'] = $data->recordStatus;
            }

            $res = $this->update($id,$bind);

            return $res;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deletePosition($id)
    {
        try {
            $res = $this->delete($id);

            return $res;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

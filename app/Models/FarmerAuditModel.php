<?php

namespace App\Models;

use CodeIgniter\Model;

class FarmerAuditModel extends Model
{
    protected $table = 'farmer_audit';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['livestock_id', 'farmer_id', 'action', 'title', 'description', 'entity_affected', 'timestamp', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllFarmerAuditTrailLogs()
    {
        try {
            $auditTrails = $this->select(
                'farmer_audit.id,
                farmer_audit.livestock_id as livestockId,
                COALESCE(NULLIF(livestocks.livestock_tag_id, ""), "Untagged") as livestockTagId,
                livestocks.livestock_type_id as livestockTypeId,
                livestock_types.livestock_type_name as livestockTypeName,
                farmer_audit.farmer_id as farmerId,
                user_accounts.user_id as farmerUserId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName,
                farmer_audit.action,
                farmer_audit.title,
                farmer_audit.description,
                farmer_audit.entity_affected as entityAffected,
                farmer_audit.timestamp,
                farmer_audit.record_status as recordStatus'
            )
            ->join('user_accounts','user_accounts.id = farmer_audit.farmer_id')
            ->join('livestocks', 'livestocks.id = farmer_audit.livestock_id')
            ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
            ->orderBy('timestamp', 'DESC')->findAll();
            return $auditTrails;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    
    public function getUserAuditTrailForReport($minDate, $maxDate)
    {
        try {
            $whereClause = [
                'farmer_audit.timestamp >=' => $minDate,
                'farmer_audit.timestamp <=' => $maxDate
            ];

            $data = $this->select('
                user_accounts.user_id as farmerUserId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName,
                CONCAT_WS(", ", user_accounts.sitio, user_accounts.barangay, user_accounts.city, user_accounts.province) as fullAddress,
                farmer_audit.action,
                farmer_audit.title,
                farmer_audit.description,
                farmer_audit.entity_affected as entityAffected,
                farmer_audit.timestamp
            ')
            ->join('user_accounts','user_accounts.id = farmer_audit.farmer_id')
            ->where($whereClause)
            ->orderBy('timestamp', 'DESC')
            ->orderBy('user_accounts.user_id', 'ASC')
            ->orderBy('farmerName', 'ASC')
            ->orderBy('farmer_audit.action', 'ASC')
            ->orderBy('farmer_audit.entity_affected', 'ASC')
            ->orderBy('farmer_audit.description', 'ASC')
            ->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getReportData($selectClause, $minDate, $maxDate){
        try {
            $whereClause = [
                'farmer_audit.record_status' => 'Accessible',
                'farmer_audit.timestamp >=' => $minDate,
                'farmer_audit.timestamp <=' => $maxDate
            ];

            $data = $this
            ->select($selectClause)
            ->join('user_accounts','user_accounts.id = farmer_audit.farmer_id')
            ->join('livestocks', 'livestocks.id = farmer_audit.livestock_id')
            ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
            ->join('livestock_breeds', 'livestock_breeds.id = livestocks.livestock_breed_id')
            ->join('livestock_age_class', 'livestock_age_class.id = livestocks.livestock_age_class_id')
            ->where($whereClause)
            ->orderBy('farmer_audit.timestamp', 'DESC')->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerAuditTrailLogs($id)
    {
        try {
            $auditTrails = $this->select(
                'id,
                livestock_id as livestockId,
                farmer_id as farmerId,
                action,
                title,
                description,
                entity_affected as entityAffected,
                timestamp,
                record_status as recordStatus'
            )->where('farmer_id', $id)->orderBy('timestamp', 'DESC')->findAll();
            return $auditTrails;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAuditTrailLogsByEntity($entity)
    {
        try {
            $auditTrails = $this->select(
                'id,
                livestock_id as livestockId,
                farmer_id as farmerId,
                action,
                title,
                description,
                entity_affected as entityAffected,
                timestamp,
                record_status as recordStatus'
            )->where('entity_affected', $entity)->findAll();

            return $auditTrails;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAuditTrailLogsByAction($action)
    {
        try {
            $auditTrails = $this->select(
                'id,
                livestock_id as livestockId,
                farmer_id as farmerId,
                action,
                title,
                description,
                entity_affected as entityAffected,
                timestamp,
                record_status as recordStatus'
            )->where('action', $action)->findAll();

            return $auditTrails;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }


    public function insertAuditTrailLog($data)
    {
        try {
            $bind = [
                'livestock_id' => $data->livestockId,
                'farmer_id' => $data->farmerId,
                'action' => $data->action,
                'title' => $data->title,
                'description' => $data->description,
                'entity_affected' => $data->entityAffected
            ];

            $result = $this->insert($bind);
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateAuditTrailLog($id, $data)
    {
        try {
            $bind = [
                'livestock_id' => $data->livestock_id,
                'farmer_id' => $data->farmerId,
                'action' => $data->action,
                'title' => $data->title,
                'description' => $data->description,
                'entity_affected' => $data->entityAffected
            ];

            $result = $this->update($id, $bind);
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateAuditTrailRecordStatus($id, $status)
    {
        try {
            $bind = [
                'record_status' => $status
            ];

            $result = $this->update($id, $bind);
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteAuditTrailLog($id)
    {
        try {
            $result = $this->delete($id);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

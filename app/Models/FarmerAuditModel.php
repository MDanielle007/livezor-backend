<?php

namespace App\Models;

use CodeIgniter\Model;

class FarmerAuditModel extends Model
{
    protected $table            = 'farmer_audit';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [ 'livestock_id', 'farmer_id', 'action', 'title', 'description', 'entity_affected', 'timestamp', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllFarmerAuditTrailLogs(){
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
        )->findAll();
        return $auditTrails;
    }

    public function getFarmerAuditTrailLogs($id){
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
        )->where('farmer_id',$id)->findAll();
        return $auditTrails;
    }

    public function getAuditTrailLogsByEntity($entity){
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
        )->where('entity_affected',$entity)->findAll();

         return $auditTrails;
    }

    public function getAuditTrailLogsByAction($action){
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
        )->where('action',$action)->findAll();

         return $auditTrails;
    }


    public function insertAuditTrailLog($data){
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
    }

    public function updateAuditTrailLog($id,$data){
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
    }

    public function updateAuditTrailRecordStatus($id, $status){
        $bind = [
            'record_status' => $status
        ];

        $result = $this->update($id, $bind);
        return $result;
    }

    public function deleteAuditTrailLog($id){
        $result = $this->delete($id);

        return $result;
    }
}

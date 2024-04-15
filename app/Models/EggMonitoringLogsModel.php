<?php

namespace App\Models;

use CodeIgniter\Model;

class EggMonitoringLogsModel extends Model
{
    protected $table = 'egg_monitoring_logs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['record_owner', 'user_id', 'egg_batch_group_id', 'action', 'date_conducted', 'remarks', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllEggMonitoringLogs()
    {
        $eggMonitoringLogs = $this->findAll();

        return $eggMonitoringLogs;
    }

    public function getEggMonitoringLog($id)
    {
        $eggMonitoringLog = $this->find($id);

        return $eggMonitoringLog;
    }

    public function insertEggMonitoringLog($data)
    {
        $bind = [
            'record_owner' => $this->recordOwner,
            'user_id' => $this->userId,
            'egg_batch_group_id' => $this->eggBatchGroupId,
            'action' => $this->action,
            'date_conducted' => $this->dateConducted,
            'remarks' => $this->remarks,
        ];

        $result = $this->insert($bind);

        return $result;
    }

    public function updateEggMonitoringLog($id, $data){
        $bind = [
            'record_owner' => $this->recordOwner,
            'user_id' => $this->userId,
            'egg_batch_group_id' => $this->eggBatchGroupId,
            'action' => $this->action,
            'date_conducted' => $this->dateConducted,
            'remarks' => $this->remarks,
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function deleteEggMonitoringLog($id){
        $result = $this->delete($id);
        return $result;
    }
}

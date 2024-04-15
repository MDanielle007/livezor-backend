<?php

namespace App\Models;

use CodeIgniter\Model;

class EggProductionBatchGroupModel extends Model
{
    protected $table = 'egg_production_batch_group';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['record_owner', 'user_id', 'batch_name', 'batch_status', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllEggProductionBatchGroups()
    {
        $eggProductionBatchGroups = $this->findAll();

        return $eggProductionBatchGroups;
    }

    public function getEggProductionBatchGroup($id)
    {
        $eggProductionBatchGroup = $this->find($id);

        return $eggProductionBatchGroup;
    }

    public function getAllActiveEggProductionBatchGroups()
    {
        $whereClause = [
            'batch_status' => 'Active',
        ];

        $eggProductionBatchGroups = $this->select('
            id,
            batch_name as batchName,
        ')->where($whereClause)->findAll();

        return $eggProductionBatchGroups;
    }

    public function getAllActiveBatchWithEggsProduced(){
        $whereClause = [
            'batch_status' => 'Active',
        ];

        $eggProductionBatchGroups = $this->select('
            id,
            batch_name as batchName,
        ')
            
        ->where($whereClause)->findAll();

        return $eggProductionBatchGroups;
    }

    public function checkEggProductionBatch($batchName)
    {
        $whereClause = [
            'batch_name' => $batchName,
            'batch_status' => 'Active',
            'record_status' => 'Accessible'
        ];

        $eggProductionBatch = $this->select('id')->where($whereClause)->findAll();

        if ($eggProductionBatch) {
            return true;
        }else{
            return $this->select('id')->find($batchName);
        }
    }

    public function insertEggProductionBatch($data)
    {
        $bind = [
            'record_owner' => $data->recordOwner,
            'batch_name' => $data->batchName,
            'batch_status' => $data->batchStatus,
        ];

        if (isset($data->userId)) {
            $bind['user_id'] = $data->userId;
        }

        $result = $this->insert($bind);

        return $result;
    }

    public function updateEggProductionBatch($id, $data)
    {
        $bind = [
            'batch_name' => $data->batchName,
            'batch_status' => $data->batchStatus,
        ];

        if (isset($data->userId)) {
            $bind['user_id'] = $data->userId;
        }

        $result = $this->update($id, $bind);

        return $result;
    }

    public function updateEggProductionBatchStatus($id, $status)
    {
        $bind = [
            'batch_status' => $status,
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function updateEggProductionBatchRecordStatus($id, $status)
    {
        $bind = [
            'record_status' => $status,
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function deleteEggProductionBatch($id)
    {
        $result = $this->delete($id);
        return $result;
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class EggProcessingBatchModel extends Model
{
    protected $table = 'egg_processing_batch';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['user_id', 'egg_batch_group_id', 'batch_date', 'machine', 'total_eggs', 'mortalities', 'produced_poultry', 'remarks', 'status', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getEggProcessingBatches()
    {
        try {
            $whereClause = [
                'egg_processing_batch.record_status' => 'Accessible'
            ];

            $eggProBatch = $this->distinct()->select('
                egg_processing_batch.id,
                egg_processing_batch.egg_batch_group_id as eggBatchGroupId,
                egg_production_batch_group.batch_name as batchName,
                egg_processing_batch.batch_date as batchDate,
                egg_processing_batch.machine,
                livestock_types.livestock_type_name as livestockType,
                egg_processing_batch.total_eggs as totalEggs,
                egg_processing_batch.mortalities,
                egg_processing_batch.produced_poultry as producedPoultry,
                egg_processing_batch.remarks,
                egg_processing_batch.status
            ')
                ->join('egg_production_batch_group', 'egg_production_batch_group.id = egg_processing_batch.egg_batch_group_id')
                ->join('livestock_egg_productions', 'livestock_egg_productions.batch_group_id = egg_production_batch_group.id')
                ->join('livestocks', 'livestocks.id = livestock_egg_productions.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->where($whereClause)
                ->orderBy('egg_processing_batch.id', 'DESC')
                ->get()->getResult();

            return $eggProBatch;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getReportData($selectClause, $minDate, $maxDate)
    {
        try {
            $whereClause = [
                'egg_processing_batch.record_status' => 'Accessible',
                'egg_processing_batch.batch_date >=' => $minDate,
                'egg_processing_batch.batch_date <=' => $maxDate
            ];

            $reportData = $this->select($selectClause)
                ->join('egg_production_batch_group', 'egg_production_batch_group.id = egg_processing_batch.egg_batch_group_id')
                ->join('livestock_egg_productions', 'livestock_egg_productions.batch_group_id = egg_production_batch_group.id')
                ->join('livestocks', 'livestocks.id = livestock_egg_productions.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->where($whereClause)
                ->get()->getResult();

            return $reportData;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getEggProcessingBatchForReport($minDate, $maxDate)
    {
        try {
            $whereClause = [
                'egg_processing_batch.record_status' => 'Accessible',
                'egg_processing_batch.batch_date >=' => $minDate,
                'egg_processing_batch.batch_date <=' => $maxDate
            ];

            $reportData = $this->select('
                egg_production_batch_group.batch_name as batchName,
                egg_processing_batch.batch_date as batchDate,
                egg_processing_batch.machine,
                livestock_types.livestock_type_name as livestockTypeName,
                egg_processing_batch.total_eggs as totalEggs,
                egg_processing_batch.mortalities,
                egg_processing_batch.produced_poultry as producedPoultry,
                egg_processing_batch.remarks,
                egg_processing_batch.status
            ')
                ->join('egg_production_batch_group', 'egg_production_batch_group.id = egg_processing_batch.egg_batch_group_id')
                ->join('livestock_egg_productions', 'livestock_egg_productions.batch_group_id = egg_production_batch_group.id')
                ->join('livestocks', 'livestocks.id = livestock_egg_productions.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->where($whereClause)
                ->orderBy('egg_processing_batch.batch_date', 'ASC')
                ->orderBy('egg_production_batch_group.batch_name', 'ASC')
                ->orderBy('egg_processing_batch.status', 'ASC')
                ->orderBy('egg_processing_batch.machine', 'ASC')
                ->findAll();

            return $reportData;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getEggProcessingBatch($id)
    {
        try {
            $whereClause = [
                'egg_processing_batch.record_status' => 'Accessible',
                'egg_processing_batch.id' => $id
            ];

            $eggProBatch = $this->select('
                egg_processing_batch.id,
                egg_processing_batch.egg_batch_group_id as eggBatchGroupId,
                egg_production_batch_group.batch_name as batchName,
                egg_processing_batch.batch_date as batchDate,
                egg_processing_batch.machine,
                livestock_types.livestock_type_name as livestockType,
                egg_processing_batch.total_eggs as totalEggs,
                egg_processing_batch.mortalities,
                egg_processing_batch.produced_poultry as producedPoultry,
                egg_processing_batch.remarks,
                egg_processing_batch.status
            ')
                ->join('egg_production_batch_group', 'egg_production_batch_group.id = egg_processing_batch.egg_batch_group_id')
                ->join('livestock_egg_productions', 'livestock_egg_productions.batch_group_id = egg_production_batch_group.id')
                ->join('livestocks', 'livestocks.id = livestock_egg_productions.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->where($whereClause)
                ->findAll();

            return $eggProBatch;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function insertEggProcessingBatch($data)
    {
        try {
            $bind = [
                'user_id' => $data->userId,
                'egg_batch_group_id' => $data->eggBatchGroupId,
                'batch_date' => $data->batchDate,
                'machine' => $data->machine,
                'total_eggs' => $data->totalEggs,
                'remarks' => $data->remarks,
            ];

            $result = $this->insert($bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function updateEggProcessingBatch($id, $data)
    {
        try {
            $bind = [
                'user_id' => $data->userId,
                'egg_batch_group_id' => $data->eggBatchGroupId,
                'batch_date' => $data->batchDate,
                'status' => $data->status,
                'machine' => $data->machine,
                'total_eggs' => $data->totalEggs,
                'mortalities' => $data->mortalities,
                'produced_poultry' => $data->producedPoultry
            ];

            $result = $this->update($id, $bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

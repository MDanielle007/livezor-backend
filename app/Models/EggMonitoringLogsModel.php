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
    protected $allowedFields = ['record_owner', 'user_id', 'egg_process_batch_id', 'action', 'date_conducted', 'remarks', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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
        try {
            $eggMonitoringLogs = $this->distinct()->select('
                egg_monitoring_logs.id,
                egg_production_batch_group.batch_name as batchName,
                livestock_types.livestock_type_name as livestockType,
                egg_monitoring_logs.action,
                egg_monitoring_logs.remarks,
                egg_monitoring_logs.date_conducted as dateConducted
            ')
                ->join('egg_processing_batch', 'egg_processing_batch.id = egg_monitoring_logs.egg_process_batch_id')
                ->join('egg_production_batch_group', 'egg_production_batch_group.id = egg_processing_batch.egg_batch_group_id')
                ->join('livestock_egg_productions', 'livestock_egg_productions.batch_group_id = egg_production_batch_group.id')
                ->join('livestocks', 'livestocks.id = livestock_egg_productions.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')

                ->findAll();

            return $eggMonitoringLogs;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getReportData($selectClause, $minDate, $maxDate)
    {
        try {
            $whereClause = [
                'egg_monitoring_logs.record_status' => 'Accessible',
                'egg_monitoring_logs.date_conducted >=' => $minDate,
                'egg_monitoring_logs.date_conducted <=' => $maxDate
            ];

            $data = $this->select($selectClause)
                ->join('egg_processing_batch', 'egg_processing_batch.id = egg_monitoring_logs.egg_process_batch_id')
                ->join('egg_production_batch_group', 'egg_production_batch_group.id = egg_processing_batch.egg_batch_group_id')
                ->join('livestock_egg_productions', 'livestock_egg_productions.batch_group_id = egg_production_batch_group.id')
                ->join('livestocks', 'livestocks.id = livestock_egg_productions.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->where($whereClause)
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getEggMonitoringLog($id)
    {
        $eggMonitoringLog = $this->find($id);

        return $eggMonitoringLog;
    }

    public function insertEggMonitoringLog($data)
    {
        $bind = [
            'record_owner' => $data->recordOwner,
            'user_id' => $data->userId,
            'egg_process_batch_id' => $data->eggProcessBatchId,
            'action' => $data->action,
            'date_conducted' => $data->dateConducted,
            'remarks' => $data->remarks,
        ];

        $result = $this->insert($bind);

        return $result;
    }

    public function updateEggMonitoringLog($id, $data)
    {
        $bind = [
            'record_owner' => $data->recordOwner,
            'user_id' => $data->userId,
            'egg_batch_group_id' => $data->eggBatchGroupId,
            'action' => $data->action,
            'date_conducted' => $data->dateConducted,
            'remarks' => $data->remarks,
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function deleteEggMonitoringLog($id)
    {
        $result = $this->delete($id);
        return $result;
    }
}

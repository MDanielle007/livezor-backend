<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EggMonitoringLogsModel;
use App\Models\EggProcessingBatchModel;
use App\Models\EggProductionBatchGroupModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class EggProcessingBatchController extends ResourceController
{
    private $eggProcessBatches;
    private $eggBatch;
    private $eggMonitoringLog;
    public function __construct()
    {
        $this->eggProcessBatches = new EggProcessingBatchModel();
        $this->eggBatch = new EggProductionBatchGroupModel();
        $this->eggMonitoringLog = new EggMonitoringLogsModel();
    }

    public function getEggProcessingBatches()
    {
        try {
            $eggProBatch = $this->eggProcessBatches->getEggProcessingBatches();
            return $this->respond($eggProBatch, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockEggProcessingBatchReportData(){
        try {
            $selectClause = $this->request->getGet('selectClause');
            $minDate = $this->request->getGet('minDate');
            $maxDate = $this->request->getGet('maxDate');

            $eggProBatches = $this->eggProcessBatches->getReportData($selectClause, $minDate, $maxDate);

            return $this->respond($eggProBatches);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getEggMonitoringLogsReportData(){
        try {
            $selectClause = $this->request->getGet('selectClause');
            $minDate = $this->request->getGet('minDate');
            $maxDate = $this->request->getGet('maxDate');

            $eggMonitoringLogs = $this->eggMonitoringLog->getReportData($selectClause, $minDate, $maxDate);

            return $this->respond($eggMonitoringLogs);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }


    public function getEggProcessingBatch($id)
    {
        try {
            $eggProBatch = $this->eggProcessBatches->getEggProcessingBatch($id);
            return $this->respond($eggProBatch, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertEggProcessingBatch()
    {
        try {
            $data = $this->request->getJSON();

            $data->userId = '1';

            $eggProcessBatchId = $this->eggProcessBatches->insertEggProcessingBatch($data);

            $batchStat = $this->eggBatch->updateEggProductionBatchStatus($data->eggBatchGroupId, 'Processing');

            $log = $this->eggMonitoringLog->insertEggMonitoringLog((object) [
                'recordOwner' => 'DA',
                'userId' => $data->userId,
                'eggProcessBatchId' => $eggProcessBatchId,
                'action' => 'Setting',
                'dateConducted' => date('Y-m-d H:i:s'),
                'remarks' => $data->remarks
            ]);

            return $this->respond(['success' => $eggProcessBatchId], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()], 200);
        }
    }

    public function updateEggProcessingBatch($id)
    {
        try {
            $data = $this->request->getJSON();

            $data->userId = '1';

            $res = $this->eggProcessBatches->updateEggProcessingBatch($id, $data);

            if ($data->action == "Extracting") {
                $batchStat = $this->eggBatch->updateEggProductionBatchStatus($data->eggBatchGroupId, 'Inactive');
            }

            $log = $this->eggMonitoringLog->insertEggMonitoringLog((object) [
                'recordOwner' => 'DA',
                'userId' => $data->userId,
                'eggProcessBatchId' => $id,
                'action' => $data->action,
                'dateConducted' => date('Y-m-d H:i:s'),
                'remarks' => $data->remarks
            ]);

            return $this->respond(['success' => $log], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()], 200);
        }
    }

    public function getAllEggMonitoringLogs(){
        try {
            $eggMonitoringLogs = $this->eggMonitoringLog->getAllEggMonitoringLogs();
            return $this->respond($eggMonitoringLogs, 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()], 200);
        }
    }
}

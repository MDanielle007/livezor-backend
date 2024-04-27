<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EggProductionBatchGroupModel;
use App\Models\LivestockEggProductionModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class LivestockEggProductionController extends ResourceController
{
    private $livestockEggProduction;
    private $eggProductionBatchGroup;

    public function __construct()
    {
        $this->livestockEggProduction = new LivestockEggProductionModel();
        $this->eggProductionBatchGroup = new EggProductionBatchGroupModel();
    }

    public function getAllEggProductions()
    {
        try {
            $livestockEggProductions = $this->livestockEggProduction->getAllEggProductions();

            return $this->respond($livestockEggProductions);

        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getEggProduction($id)
    {
        try {
            $livestockEggProductions = $this->livestockEggProduction->getEggProduction($id);

            return $this->respond($livestockEggProductions);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockEggProductionReportData(){
        try {
            $selectClause = $this->request->getGet('selectClause');
            $minDate = $this->request->getGet('minDate');
            $maxDate = $this->request->getGet('maxDate');

            $livestockEggProductions = $this->livestockEggProduction->getReportData($selectClause, $minDate, $maxDate);

            return $this->respond($livestockEggProductions);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllFarmerEggProductions($userId)
    {
        try {
            $livestockEggProductions = $this->livestockEggProduction->getAllFarmerEggProductions($userId);

            return $this->respond($livestockEggProductions);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertEggProduction()
    {
        try {
            $data = $this->request->getJSON();

            $batchName = $data->batch;

            if(is_string($batchName)){
                $data->batchGroupId = $this->eggProductionBatchGroup->insertEggProductionBatch((object)[
                    'recordOwner' => 'Farmer',
                    'batchName' => $batchName,
                    'batchStatus' => 'Active',
                    'userId' => $data->farmerId
                ]);
            }else{
                $data->batchGroupId = $batchName->code;
            }

            $response = "";
            if (isset($data->batchGroupId)) {
                $response = $this->livestockEggProduction->insertEggProductionWithBatch($data);
            } else {
                $response = $this->livestockEggProduction->insertEggProduction($data);
            }

            return $this->respond(['success' => $response, 'message' => 'Livestock Egg Production Successfully Added'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function setEggProductionBatch($id){
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockEggProduction->setEggProductionBatch($id, $data->batchId);

            return $this->respond(['success' => $response, 'message' => 'Livestock Egg Production Successfully Added'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function updateEggProduction($id)
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockEggProduction->updateEggProduction($id, $data);

            return $this->respond(['success' => $response, 'message' => 'Livestock Egg Production Successfully Updated'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateEggProductionRecordStatus($id)
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockEggProduction->updateEggProductionRecordStatus($id, $data->recordStatus);

            return $this->respond(['result' => $response, 'message' => 'Livestock Egg Production Record Status Successfully Updated'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteEggProduction($id)
    {
        try {
            $response = $this->livestockEggProduction->deleteLivestockVaccination($id);

            return $this->respond(['result' => $response, 'message' => 'Livestock Vaccination Successfully Deleted'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getEggProductionCountByMonth(){
        try {
            $livestockEggProductionCount = $this->livestockEggProduction->getEggProductionCountByMonth();

            return $this->respond($livestockEggProductionCount);

        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getCurrentYearEggProductionCount(){
        try {
            $livestockEggProductionCount = $this->livestockEggProduction->getCurrentYearEggProductionCount();

            return $this->respond(['eggProductionCount' => "$livestockEggProductionCount"],200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getTopPoultryTypeEggProducedCount(){
        try {
            $poultryEggProduction = $this->livestockEggProduction->getTopPoultryTypeEggProducedCount();

            return $this->respond($poultryEggProduction,200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }
}

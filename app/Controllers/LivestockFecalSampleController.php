<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LivestockFecalSampleModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class LivestockFecalSampleController extends ResourceController
{
    private $livestockFecalSamples;

    public function __construct()
    {
        $this->livestockFecalSamples = new LivestockFecalSampleModel();
    }

    public function getAllLivestockFecalSample()
    {
        try {
            $livestockFecalSamples = $this->livestockFecalSamples->getAllLivestockFecalSample();
            return $this->respond($livestockFecalSamples);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    
    public function getFecalSampleReportData(){
        try {
            $selectClause = $this->request->getGet('selectClause');
            $minDate = $this->request->getGet('minDate');
            $maxDate = $this->request->getGet('maxDate');

            $livestockFecalSamples = $this->livestockFecalSamples->getReportData($selectClause, $minDate, $maxDate);

            return $this->respond($livestockFecalSamples);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getLivestockFecalSample($id)
    {
        try {
            $livestockFecalSample = $this->livestockFecalSamples->getLivestockFecalSample($id);
            return $this->respond($livestockFecalSample);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllFarmerLivestockFecalSamples($userId)
    {
        try {
            $livestockFecalSamples = $this->livestockFecalSamples->getAllFarmerLivestockFecalSamples($userId);
            return $this->respond($livestockFecalSamples);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertLivestockFecalSample()
    {
        try {
            $data = $this->request->getJSON();

            $result = $this->livestockFecalSamples->insertLivestockFecalSample($data);

            return $this->respond(['success' => $result, 'message' => 'Livestock Fecal Sample Successfully Added']);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertMultipleLivestockFecalSample()
    {
        try {
            $data = $this->request->getJSON();

            $livestocks = $data->livestock;

            $result = null;
            foreach ($livestocks as $ls) {
                $data->livestockId = $ls;

                $result = $this->livestockFecalSamples->insertLivestockFecalSample($data);
            }

            return $this->respond(['success' => $result, 'message' => 'Livestock Fecal Sample Successfully Added']);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function updateLivestockFecalSample($id)
    {
        try {
            $data = $this->request->getJSON();

            $result = $this->livestockFecalSamples->updateLivestockFecalSample($id, $data);

            return $this->respond(['success' => $result, 'message' => 'Livestock Fecal Sample Successfully Updated']);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function deleteLivestockFecalSample($id)
    {
        try {
            $result = $this->livestockFecalSamples->deleteLivestockFecalSample($id);

            return $this->respond(['success' => $result, 'message' => 'Livestock Fecal Sample Successfully Deleted']);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getOverallLivestockFecalSampleCount(){
        try {
            $result = $this->livestockFecalSamples->getOverallLivestockFecalSampleCount();

            return $this->respond(['fecalSampleCount' => "$result"], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerOverallLivestockFecalSampleCount($userId){
        try {
            $result = $this->livestockFecalSamples->getFarmerOverallLivestockFecalSampleCount($userId);

            return $this->respond(['fecalSampleCount' => "$result"], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    
    public function getRecentLivestockFecalSample(){
        try {
            $livestockFecalSample = $this->livestockFecalSamples->getRecentLivestockFecalSample();

            return $this->respond($livestockFecalSample);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getTopLivestockObservations(){
        try {
            $livestockObservations = $this->livestockFecalSamples->getTopLivestockObservations();

            return $this->respond($livestockObservations);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getTopFecalSampleFindings(){
        try {
            $findings = $this->livestockFecalSamples->getTopFecalSampleFindings();

            return $this->respond($findings);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFecalCountLast4Months()
    {
        try {
            $livestockFecalSampleCountLast4Months = $this->livestockFecalSamples->getFecalCountLast4Months();

            return $this->respond($livestockFecalSampleCountLast4Months);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFecalSampleCountByMonth(){
        try {
            $livestockFecalSampleCountByMonth = $this->livestockFecalSamples->getFecalSampleCountByMonth();

            return $this->respond($livestockFecalSampleCountByMonth);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

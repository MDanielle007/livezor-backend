<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LivestockBloodSampleModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class LivestockBloodSampleController extends ResourceController
{
    private $livestockBloodSample;
    public function __construct()
    {
        $this->livestockBloodSample = new LivestockBloodSampleModel();
    }

    public function getAllLivestockBloodSamples()
    {
        try {
            $livestockBloodSamples = $this->livestockBloodSample->getAllLivestockBloodSamples();
            return $this->respond($livestockBloodSamples);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllFarmerLivestockBloodSamples($userId)
    {
        try {
            $livestockBloodSamples = $this->livestockBloodSample->getAllFarmerLivestockBloodSamples($userId);

            return $this->respond($livestockBloodSamples);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockBloodSample($id)
    {
        try {
            $livestockBloodSample = $this->livestockBloodSample->getLivestockBloodSample($id);

            return $this->respond($livestockBloodSample);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertLivestockBloodSample()
    {
        try {
            $data = $this->request->getJSON();

            $result = $this->livestockBloodSample->insertLivestockBloodSample($data);

            return $this->respond(['message' => 'Livestock Blood Sample Successfully Added', 'success' => $result], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertMultipleLivestockBloodSample()
    {
        try {
            $data = $this->request->getJSON();


            $livestocks = $data->livestock;

            $result = null;
            foreach ($livestocks as $ls) {
                $data->livestockId = $ls;

                $result = $this->livestockBloodSample->insertLivestockBloodSample($data);
            }

            return $this->respond(['success' => $result, 'message' => 'Livestock Blood Sample Successfully Added']);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateLivestockBloodSample($id)
    {
        try {
            $data = $this->request->getJSON();

            $result = $this->livestockBloodSample->updateLivestockBloodSample($id, $data);

            return $this->respond(['message' => 'Livestock Blood Sample Successfully Updated', 'success' => $result], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteLivestockBloodSample($id)
    {
        try {
            $response = $this->livestockBloodSample->deleteLivestockBloodSample($id);

            return $this->respond(['message' => 'Livestock Blood Sample Successfully Deleted', 'result' => $response], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getOverallLivestockBloodSampleCount()
    {
        try {
            $result = $this->livestockBloodSample->getOverallLivestockBloodSampleCount();

            return $this->respond(['bloodSampleCount' => "$result"], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerOverallLivestockBloodSampleCount($userId)
    {
        try {
            $result = $this->livestockBloodSample->getFarmerOverallLivestockBloodSampleCount($userId);

            return $this->respond(['bloodSampleCount' => "$result"], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getRecentLivestockBloodSample(){
        try {
            $livestockBloodSample = $this->livestockBloodSample->getRecentLivestockBloodSample();

            return $this->respond($livestockBloodSample, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getTopLivestockObservations(){
        try {
            $livestockObservations = $this->livestockBloodSample->getTopLivestockObservations();

            return $this->respond($livestockObservations);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getTopBloodSampleFindings(){
        try {
            $findings = $this->livestockBloodSample->getTopBloodSampleFindings();

            return $this->respond($findings);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getBloodCountLast4Months(){
        try {
            $livestockBloodSampleCountLast4Months = $this->livestockBloodSample->getBloodCountLast4Months();

            return $this->respond($livestockBloodSampleCountLast4Months);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getBloodSampleCountByMonth(){
        try {
            $livestockBloodSampleCountByMonth = $this->livestockBloodSample->getBloodSampleCountByMonth();

            return $this->respond($livestockBloodSampleCountByMonth);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}


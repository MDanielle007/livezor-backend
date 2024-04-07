<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LivestockModel;
use App\Models\LivestockVaccinationModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class LivestockVaccinationsController extends ResourceController
{
    private $livestockVaccination;
    private $livestock;

    public function __construct()
    {
        $this->livestockVaccination = new LivestockVaccinationModel();
        $this->livestock = new LivestockModel();
    }

    public function getAllLivestockVaccinations(){
        try {
            $livestockVaccinations = $this->livestockVaccination->getAllLivestockVaccinations();

            return $this->respond($livestockVaccinations);

        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getLivestockVaccination($id){
        try {
            $livestockVaccination = $this->livestockVaccination->getLivestockVaccination($id);

            return $this->respond($livestockVaccination);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllFarmerLivestockVaccinations($userId){
        try {
            $livestockVaccinations = $this->livestockVaccination->getAllFarmerLivestockVaccinations($userId);

            return $this->respond($livestockVaccinations);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllFarmerCompleteLivestockVaccinations($userId){
        try {
            $livestockVaccinations = $this->livestockVaccination->getAllFarmerCompleteLivestockVaccinations($userId);

            return $this->respond($livestockVaccinations);

        } catch (\Throwable $th) {
            //throw $th;

            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function insertLivestockVaccination(){
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockVaccination->insertLivestockVaccination($data);

            return $this->respond(['success' => true,'message' => 'Livestock Vaccination Successfully Added'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }    

    public function updateLivestockVaccination($id){
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockVaccination->updateLivestockVaccination($id, $data);

            return $this->respond(['success' => true,'message' => 'Livestock Vaccination Successfully Updated'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateLivestockVaccinationRecordStatus($id){
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockVaccination->updateLivestockVaccinationRecordStatus($id, $data->recordStatus);

            return $this->respond(['result' => $response,'message' => 'Livestock Vaccination Record Status Successfully Updated'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteLivestockVaccination($id){
        try {
            $response = $this->livestockVaccination->deleteLivestockVaccination($id);

            return $this->respond(['result' => $response,'message' => 'Livestock Vaccination Successfully Deleted'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getOverallLivestockVaccinationCount(){
        try {
            $livestockVaccinationCount = $this->livestockVaccination->getOverallLivestockVaccinationCount();

            return $this->respond(['vaccinationCount' => "$livestockVaccinationCount"]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }    

    public function getFarmerOverallLivestockVaccinationCount($userId){
        try {
            $livestockVaccinationCount = $this->livestockVaccination->getFarmerOverallLivestockVaccinationCount($userId);

            return $this->respond(['vaccinationCount' => "$livestockVaccinationCount"]);
        } catch (\Throwable $th) {
            //throw $th;
            // return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getLivestockVaccinationCountInCurrentMonth(){
        try {
            $livestockVaccinationCount = $this->livestockVaccination->getLivestockVaccinationCountInCurrentMonth();

            return $this->respond($livestockVaccinationCount);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getLivestockVaccinationPercetageInCurrentMonth(){
        try {
            $livestockVaccinationCount = $this->livestockVaccination->getLivestockVaccinationCountInCurrentMonth();

            $livestockCount = $this->livestock->getAllLivestockCount();
            
            $vaccinationPercentage = 0;
            if ($livestockCount > 0) {
                $percentage = ($livestockVaccinationCount / $livestockCount) * 100;

                if (floor($percentage) == $percentage) {
                    // Display only whole numbers
                    $vaccinationPercentage =  number_format($percentage, 0);
                } else {
                    // Display up to two decimal places
                    $vaccinationPercentage = number_format($percentage, 2);
                }
            }

            $data = [
                'livestockVaccinationCount' => "$livestockVaccinationCount",
                'livestockVaccinationPercentage' => $vaccinationPercentage."%",
            ];

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getTopVaccines(){
        try {
            $topVaccines = $this->livestockVaccination->getTopVaccines();

            return $this->respond($topVaccines);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getVaccinationCountByMonth(){
        try {
            $vaccinationCountByMonth = $this->livestockVaccination->getVaccinationCountByMonth();

            return $this->respond($vaccinationCountByMonth);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getVaccinationCountLast4Months(){
        try {
            $vaccinationCountLast4Months = $this->livestockVaccination->getVaccinationCountLast4Months();

            return $this->respond($vaccinationCountLast4Months);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

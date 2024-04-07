<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LivestockBreedingsModel;
use App\Models\LivestockModel;
use App\Models\LivestockPregnancyModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class LivestockBreedingsController extends ResourceController
{
    private $livestockBreeding;
    private $livestockPregnancy;
    private $livestock;

    public function __construct()
    {
        $this->livestockBreeding = new LivestockBreedingsModel();
        $this->livestockPregnancy = new LivestockPregnancyModel();
        $this->livestock = new LivestockModel();
    }

    public function getAllLivestockBreedings(){
        try {
            $livestockBreedings = $this->livestockBreeding->getAllLivestockBreedings();

            return $this->respond($livestockBreedings);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockBreeding($id){
        try {
            $livestockBreeding = $this->livestockBreeding->getLivestockBreeding($id);

            return $this->respond($livestockBreeding);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllFarmerLivestockBreedings($userId){
        try {
            $livestockBreedings = $this->livestockBreeding->getAllFarmerLivestockBreedings($userId);

            return $this->respond($livestockBreedings);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertLivestockBreeding(){
        try {
            $data = $this->request->getJSON();

            $breedingId = $this->livestockBreeding->insertLivestockBreeding($data);

            $result = null;

            if($data->breedResult == 'Successful Breeding'){
                $femaleLivestockId = $this->livestock->getFarmerLivestockIdByTag($data->femaleLivestockTagId, $data->farmerId);

                $data->breedingId = $breedingId;
                $data->livestockId = $femaleLivestockId;
                $data->pregnancyStartDate = $data->breedDate;

                $result = $this->livestockPregnancy->insertLivestockPregnancyByBreeding($data);
            }


            return $this->respond(['success' => true,'message' => 'Livestock Breeding Successfully Added'], 200);

        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }    

    public function updateLivestockBreeding($id){
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockBreeding->updateLivestockBreeding($id, $data);

            return $this->respond(['success' => true,'message' => 'Livestock Breeding Successfully Updated'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateLivestockBreedingRecordStatus($id){
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockBreeding->updateLivestockBreedingRecordStatus($id, $data->recordStatus);

            return $this->respond(['result' => $response,'message' => 'Livestock Breeding Record Status Successfully Updated'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteLivestockBreeding($id){
        try {
            $response = $this->livestockBreeding->deleteLivestockBreeding($id);

            return $this->respond(['result' => $response,'message' => 'Livestock Breeding Successfully Deleted'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllBreedingParentOffspringData(){
        try {
            $livestockBreedings = $this->livestockBreeding->getAllBreedingParentOffspringData();
            return $this->respond($livestockBreedings);
        } catch (\Throwable $th) {
            //throw $th;

            return $this->respond(['error' => $th->getMessage()], 200);
        }
    }

    public function getOverallLivestockBreedingCount(){
        try {
            $livestockBreedingCount = $this->livestockBreeding->getOverallLivestockBreedingCount();
            return $this->respond(['breedingCount' =>"$livestockBreedingCount"]);
        } catch (\Throwable $th) {
            //throw $th;

            return $this->respond(['error' => $th->getMessage()], 200);
        }
    }

    public function getOverallLivestockBreedingCountInCurrentYear(){
        try {
            $livestockBreedingCount = $this->livestockBreeding->getOverallLivestockBreedingCountInCurrentYear();
            return $this->respond(['breedingCount' =>"$livestockBreedingCount"]);
        } catch (\Throwable $th) {
            //throw $th;

            return $this->respond(['error' => $th->getMessage()], 200);
        }
    }

    public function getFarmerOverallLivestockBreedingCount($userId){
        try {
            $livestockBreedingCount = $this->livestockBreeding->getFarmerOverallLivestockBreedingCount($userId);
            return $this->respond(['breedingCount' =>"$livestockBreedingCount"]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockBreedingSuccessPercentage(){
        try {
            $successCount = $this->livestockBreeding->getLivestockBreedingCountByResultInCurrentYear("Successful Breeding");

            $livestockBreedings = $this->livestockBreeding->getOverallLivestockBreedingCountInCurrentYear();

            $breedingPercentage = 0;
            if ($livestockBreedings > 0) {
                $percentage = ($successCount / $livestockBreedings) * 100;

                if (floor($percentage) == $percentage) {
                    // Display only whole numbers
                    $breedingPercentage =  number_format($percentage, 0);
                } else {
                    // Display up to two decimal places
                    $breedingPercentage = number_format($percentage, 2);
                }
            }

            return $this->respond(['breedingPercent' =>"$breedingPercentage%"]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockBreedingResultsCount(){
        try {
            $successfulCount = $this->livestockBreeding->getLivestockBreedingCountByResultInCurrentYear("Successful Breeding");
            
            $unsuccessfulCount = $this->livestockBreeding->getLivestockBreedingCountByResultInCurrentYear("Unsuccessful Breeding");

            $data = [
                'Successful Breeding' => $successfulCount,
                'Unsuccessful Breeding' => $unsuccessfulCount
            ];

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getBreedingsCountLast4Months(){
        try {
            $livestockBreedings = $this->livestockBreeding->getBreedingsCountLast4Months();
            return $this->respond($livestockBreedings);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockTypeBreedingsCount(){
        try {
            $livestockBreedings = $this->livestockBreeding->getLivestockTypeBreedingsCount();
            return $this->respond($livestockBreedings);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getBreedingCountByMonth(){
        try {
            $livestockBreedings = $this->livestockBreeding->getBreedingCountByMonth();
            return $this->respond($livestockBreedings);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

}

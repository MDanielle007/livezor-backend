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

}

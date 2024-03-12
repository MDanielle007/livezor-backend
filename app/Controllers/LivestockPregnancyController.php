<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FarmerLivestockModel;
use App\Models\LivestockAgeClassModel;
use App\Models\LivestockBreedingsModel;
use App\Models\LivestockModel;
use App\Models\LivestockOffspringModel;
use App\Models\LivestockPregnancyModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class LivestockPregnancyController extends ResourceController
{
    private $livestockOffspring;
    private $livestockPregnancy;
    private $livestockBreeding;
    private $livestock;
    private $livestockAgeClass;
    private $farmerLivestock;

    public function __construct()
    {
        $this->livestockOffspring = new LivestockOffspringModel();
        $this->livestockPregnancy = new LivestockPregnancyModel();
        $this->livestockBreeding = new LivestockBreedingsModel();
        $this->livestock = new LivestockModel();
        $this->livestockAgeClass = new LivestockAgeClassModel();
        $this->farmerLivestock = new FarmerLivestockModel();
    }

    public function getAllLivestockPregnancies(){
        try {
            $livestockPregnancies = $this->livestockPregnancy->getAllLivestockPregnancies();
            return $this->respond($livestockPregnancies);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockPregnancy($id){
        try {
            $livestockPregnancy = $this->livestockPregnancy->getLivestockPregnancy($id);

            return $this->respond($livestockPregnancy);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllFarmerLivestockPregnancies($id){
        try {
            $livestockPregnancies = $this->livestockPregnancy->getAllFarmerLivestockPregnancies($id);

            return $this->respond($livestockPregnancies);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function addSuccessfulLivestockPregnancy($id)
    {
        try {
            $data = $this->request->getJSON();

            $data->pregnancyId = $id;

            $result = $this->livestockPregnancy->updateLivestockPregnancyOutcomeSuccessful($id, $data);
            $parentLivestock = $this->livestock->getLivestockById($data->parentLivestockId);

            $data->livestockTypeId = $parentLivestock['livestockTypeId'];
            $offspringAgeClass = $this->livestockAgeClass->getLivestockTypeOffspring($parentLivestock['livestockTypeId']);
            $data->livestockAgeClassId = $offspringAgeClass['id'];
            $data->ageDays = 0;
            $data->ageWeeks = 0;
            $data->ageMonths = 0;
            $data->ageYears = 0;
            $data->dateOfBirth = $data->actualDeliveryDate;
            $data->birthDate = $data->actualDeliveryDate;
            $data->acquiredDate = $data->actualDeliveryDate;

            if ($data->maleOffsprings > 0) {
                $data->sex = 'Male';
                for ($i = 1; $i <= $data->maleOffsprings; $i++) {
                    $livestockId = $this->livestock->insertLivestock($data);

                    $data->livestockId = $livestockId;
                    $result = $this->livestockOffspring->insertLivestockOffspring($data);
                    $result = $this->farmerLivestock->associateFarmerLivestock($data);
                }
            }

            if ($data->femaleOffsprings > 0) {
                $data->sex = 'Female';
                for ($i = 1; $i <= $data->femaleOffsprings; $i++) {
                    $livestockId = $this->livestock->insertLivestock($data);

                    $data->livestockId = $livestockId;
                    $result = $this->livestockOffspring->insertLivestockOffspring($data);
                    $result = $this->farmerLivestock->associateFarmerLivestock($data);
                }
            }

            return $this->respond(['result' => $result, 'message' => 'Livestock Pregnancy Successfully Added'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }
}
<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LivestockModel;
use App\Models\LivestockVaccinationModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class LivestockVaccinationsController extends ResourceController
{
    private $livestockVaccination;
    private $livestock;
    private $userModel;

    public function __construct()
    {
        $this->livestockVaccination = new LivestockVaccinationModel();
        $this->livestock = new LivestockModel();
        $this->userModel = new UserModel();
    }

    public function getAllLivestockVaccinations(){
        try {
            $livestockVaccinations = $this->livestockVaccination->getAllLivestockVaccinations();

            foreach($livestockVaccinations as &$livestockVaccination){
                $livestockData =  $this->livestock->getLivestockPrimaryData($livestockVaccination['livestockId']);
                $administratorData = $this->userModel->getUserName($livestockVaccination['vaccineAdministratorId']);

                $livestockVaccination['livestockTagId'] = $livestockData['livestockTagId'];
                $livestockVaccination['livestockTypeId'] = $livestockData['livestockTypeId'];
                $livestockVaccination['livestockAgeClassId'] = $livestockData['livestockAgeClassId'];
                $livestockVaccination['administratorName'] = $administratorData['userName'];
            }

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

    public function insertLivestockVaccination(){
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockVaccination->insertLivestockVaccination($data);

            return $this->respond(['result' => $response,'message' => 'Livestock Vaccination Successfully Added'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }    

    public function updateLivestockVaccination($id){
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockVaccination->updateLivestockVaccination($id, $data);

            return $this->respond(['result' => $response,'message' => 'Livestock Vaccination Successfully Updated'], 200);

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
}

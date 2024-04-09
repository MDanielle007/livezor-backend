<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FarmerAuditModel;
use App\Models\LivestockModel;
use App\Models\LivestockVaccinationModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class LivestockVaccinationsController extends ResourceController
{
    private $livestockVaccination;
    private $livestock;
    private $farmerAudit;
    private $userModel;

    public function __construct()
    {
        $this->livestockVaccination = new LivestockVaccinationModel();
        $this->livestock = new LivestockModel();
        $this->farmerAudit = new FarmerAuditModel();
        $this->userModel = new UserModel();
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

            $livestockTagId = $this->livestock->getLivestockTagIdById($data->livestockId);

            $vaccine = $data->vaccinationName;

            $data->farmerId = $data->vaccineAdministratorId;
            $data->action = "Add";
            $data->title = "Adminster Vaccination";
            $data->description = "Administer $vaccine to Livestock $livestockTagId";
            $data->entityAffected = "Vaccination";

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);

            return $this->respond(['success' => true,'message' => 'Livestock Vaccination Successfully Added'], 200);

        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }    

    public function updateLivestockVaccination($id){
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockVaccination->updateLivestockVaccination($id, $data);

            $livestockTagId = $this->livestock->getLivestockTagIdById($data->livestockId);

            $data->farmerId = $data->vaccineAdministratorId;
            $data->livestockId;
            $data->action = "Edit";
            $data->title = "Update Vaccination Details";
            $data->description = "Update Vaccination of Livestock $livestockTagId";
            $data->entityAffected = "Vaccination";

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);

            return $this->respond(['success' => true,'message' => 'Livestock Vaccination Successfully Updated'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateLivestockVaccinationRecordStatus($id){
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockVaccination->updateLivestockVaccinationRecordStatus($id, $data->recordStatus);

            $livestock = $this->livestock->getLivestockByVaccination($id);
            $livestockTagId = $livestock['livestock_tag_id'];

            $data->farmerId = $data->vaccineAdministratorId;
            $data->livestockId = $livestock['id'];
            $data->action = $data->recordStatus == 'Archived' ? "Archived" : "Edit";
            $data->title = $data->recordStatus == 'Archived' ? "Archived Vaccination Record" : "Unarchived Vaccination Record";
            $data->description = $data->recordStatus == 'Archived' ? "Archived Vaccination of Livestock $livestockTagId" : "Unarchived Vaccination of Livestock $livestockTagId";
            $data->entityAffected = "Vaccination";

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);

            return $this->respond(['result' => $response,'message' => 'Livestock Vaccination Record Status Successfully Updated'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteLivestockVaccination($id){
        try {
            $response = $this->livestockVaccination->deleteLivestockVaccination($id);

            $livestock = $this->livestock->getLivestockByVaccination($id);
            $livestockTagId = $livestock['livestock_tag_id'];
            $data = new \stdClass();
            $data->livestockId = $livestock['id'];
            $data->farmerId = $this->userModel->getFarmerByLivestock($id);
            $data->action = "Delete";
            $data->title = "Delete Vaccination Record";
            $data->description = "Delete Vaccination of Livestock $livestockTagId";
            $data->entityAffected = "Livestock";

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

    public function getVaccinationCountWholeYear(){
        try {
            $vaccinationCountWholeYear = $this->livestockVaccination->getVaccinationCountWholeYear();

            return $this->respond($vaccinationCountWholeYear);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }
}

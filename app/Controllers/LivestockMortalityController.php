<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FarmerAuditModel;
use App\Models\LivestockModel;
use App\Models\LivestockMortalityModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class LivestockMortalityController extends ResourceController
{
    private $livestockMortality;
    private $livestock;
    private $userModel;
    private $farmerAudit;

    public function __construct()
    {
        $this->livestockMortality = new LivestockMortalityModel();
        $this->livestock = new LivestockModel();
        $this->userModel = new UserModel();
        $this->farmerAudit = new FarmerAuditModel();
    }

    public function getAllLivestockMortalities()
    {
        try {
            $livestockMortalities = $this->livestockMortality->getAllLivestockMortalities();

            return $this->respond($livestockMortalities);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockMortality($id)
    {
        try {
            $livestockMortality = $this->livestockMortality->getLivestockMortality($id);

            return $this->respond($livestockMortality);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllFarmerLivestockMortalities($userId)
    {
        try {
            $livestockMortalities = $this->livestockMortality->getAllFarmerLivestockMortalities($userId);

            return $this->respond($livestockMortalities);
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getAllCompleteLivestockMortalities(){
        try {
            $livestockMortalities = $this->livestockMortality->getAllCompleteLivestockMortalities();

            return $this->respond($livestockMortalities);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond($th->getMessage());
        }
    }

    public function insertLivestockMortality()
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockMortality->insertLivestockMortality($data);

            $livestockTagId = $this->livestock->getLivestockTagIdById($data->livestockId);

            $data->farmerId;
            $data->livestockId;
            $data->action = "Add";
            $data->title = "Report Livestock Mortality";
            $data->description = "Report Livestock Mortality of Livestock $livestockTagId";
            $data->entityAffected = "Mortality";

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);

            $data->livestockHealthStatus = 'Dead';
            $result = $this->livestock->updateLivestockHealthStatus($data->livestockId, $data);

            return $this->respond(['success' => $result, 'message' => 'Livestock Mortality Successfully Added'], 200);

        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()], 200);
        }
    }

    public function updateLivestockMortality($id)
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockMortality->updateLivestockMortality($id, $data);

            return $this->respond(['success' => $response, 'message' => 'Livestock Mortality Successfully Updated'], 200);

        } catch (\Throwable $th) {
            return $this->respond(['error' => $th->getMessage()], 200);
        }
    }

    public function updateLivestockMortalityRecordStatus($id)
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockMortality->updateLivestockMortalityRecordStatus($id, $data->recordStatus);

            return $this->respond(['result' => $response, 'message' => 'Livestock Mortality Record Status Successfully Updated'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteLivestockMortality($id)
    {
        try {
            $response = $this->livestockMortality->deleteLivestockMortality($id);

            return $this->respond(['result' => $response, 'message' => 'Livestock Mortality Successfully Deleted'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getOverallLivestockMortalitiesCount(){
        try {
            $livestockMortalities = $this->livestockMortality->getOverallLivestockMortalitiesCount();

            return $this->respond(['mortalityCount' => "$livestockMortalities"]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getOverallLivestockMortalitiesCountInCurrentYear(){
        try {
            $livestockMortalities = $this->livestockMortality->getOverallLivestockMortalitiesCountInCurrentYear();

            return $this->respond(['mortalityCount' => "$livestockMortalities"]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerOverallLivestockMortalitiesCount($userId){
        try {
            $livestockMortalities = $this->livestockMortality->getFarmerOverallLivestockMortalitiesCount($userId);

            return $this->respond(['mortalityCount' => "$livestockMortalities"]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockMortalitiesCountLastMonth(){
        try {
            $livestockMortalitiesCount = $this->livestockMortality->getLivestockMortalitiesCountLastMonth();

            $livestockCount = $this->livestock->getOverallLivestockCount();
            
            $mortalityPercentage = 0;
            if ($livestockCount > 0) {
                $percentage = ($livestockMortalitiesCount / $livestockCount) * 100;

                if (floor($percentage) == $percentage) {
                    // Display only whole numbers
                    $mortalityPercentage =  number_format($percentage, 0);
                } else {
                    // Display up to two decimal places
                    $mortalityPercentage = number_format($percentage, 2);
                }
            }

            $data = [
                'livestockMortalitiesCount' => "$livestockMortalitiesCount",
                'livestockMortalitiesPercentage' => $mortalityPercentage."%",
            ];

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond($th->getMessage());
        }
    }

    public function getTopMortalityCause(){
        try {
            $livestockMortalities = $this->livestockMortality->getTopMortalityCause();

            return $this->respond($livestockMortalities);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getMortalityCountByMonth(){
        try {
            $livestockMortalities = $this->livestockMortality->getMortalityCountByMonth();

            return $this->respond($livestockMortalities);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockTypeMortalityCount(){
        try {
            $livestockMortalities = $this->livestockMortality->getLivestockTypeMortalityCount();

            return $this->respond($livestockMortalities);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getMortalitiesCountLast4Months(){
        try {
            $livestockmortalities = $this->livestockMortality->getMortalitiesCountLast4Months();

            return $this->respond($livestockmortalities);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

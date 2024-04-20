<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FarmerAuditModel;
use App\Models\LivestockBreedingsModel;
use App\Models\LivestockModel;
use App\Models\LivestockPregnancyModel;
use App\Models\LivestockTypeModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class LivestockBreedingsController extends ResourceController
{
    private $livestockBreeding;
    private $livestockPregnancy;
    private $livestock;
    private $userModel;
    private $farmerAudit;
    private $livestockType;

    public function __construct()
    {
        $this->livestockBreeding = new LivestockBreedingsModel();
        $this->livestockPregnancy = new LivestockPregnancyModel();
        $this->livestock = new LivestockModel();
        $this->userModel = new UserModel();
        $this->farmerAudit = new FarmerAuditModel();
        $this->livestockType = new LivestockTypeModel();
    }

    public function getAllLivestockBreedings()
    {
        try {
            $livestockBreedings = $this->livestockBreeding->getAllLivestockBreedings();

            return $this->respond($livestockBreedings);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockBreeding($id)
    {
        try {
            $livestockBreeding = $this->livestockBreeding->getLivestockBreeding($id);

            return $this->respond($livestockBreeding);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllFarmerLivestockBreedings($userId)
    {
        try {
            $livestockBreedings = $this->livestockBreeding->getAllFarmerLivestockBreedings($userId);

            return $this->respond($livestockBreedings);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertLivestockBreeding()
    {
        try {
            $data = $this->request->getJSON();

            $breedingId = $this->livestockBreeding->insertLivestockBreeding($data);
            $data->action = "Add";
            $data->title = "Breed Livestock";
            $data->entityAffected = "Breeding";

            $maleLivestockTagId = $data->maleLivestockTagId;
            $femaleLivestockTagId = $data->femaleLivestockTagId;

            $maleLivestock = $this->livestock->getFarmerLivestockIdByTag($data->maleLivestockTagId, $data->farmerId);

            $data->description = "Breed Livestock $maleLivestockTagId and $femaleLivestockTagId";
            $data->livestockId = $maleLivestock;
            $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);

            $femaleLivestock = $this->livestock->getFarmerLivestockIdByTag($data->femaleLivestockTagId, $data->farmerId);
            $data->livestockId = $femaleLivestock;
            $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);
            $livestockType = $this->livestockType->getLivestockTypeName($data->livestockId);

            $result = null;

            if ($data->breedResult == 'Successful Breeding') {
                $femaleLivestockId = $this->livestock->getFarmerLivestockIdByTag($data->femaleLivestockTagId, $data->farmerId);

                $data->breedingId = $breedingId;
                $data->livestockId = $femaleLivestockId;
                $data->pregnancyStartDate = $data->breedDate;
                $data->expectedDeliveryDate = $this->calculateExpectedDeliveryDate($data->breedDate, $livestockType);

                $result = $this->livestockPregnancy->insertLivestockPregnancyByBreeding($data);
            }


            return $this->respond(['success' => true, 'message' => 'Livestock Breeding Successfully Added', 'result' => $result], 200);

        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    private function calculateExpectedDeliveryDate($breedDate, $livestockType)
    {
        // Define gestation periods for different livestock types (in days)
        $gestationPeriods = [
            'Sheep' => 150,
            'Cattle' => 280,
            'Pig' => 114,
            'Goat' => 150,
            'Carabao' => 309,
            // Add more livestock types and their corresponding gestation periods here
        ];

        // Check if the provided livestock type exists in the gestation periods array
        if (array_key_exists($livestockType, $gestationPeriods)) {
            // Calculate the expected delivery date
            $gestationPeriod = $gestationPeriods[$livestockType];
            $expectedDeliveryDate = date('Y-m-d', strtotime($breedDate . ' + ' . $gestationPeriod . ' days'));
            return $expectedDeliveryDate;
        } else {
            // If the provided livestock type is not found, return null or throw an exception
            return null; // or throw new Exception('Livestock type not found');
        }
    }

    public function updateLivestockBreeding($id)
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockBreeding->updateLivestockBreeding($id, $data);

            $data->action = "Edit";
            $data->title = "Updated Livestock Breeding";
            $data->entityAffected = "Breeding";

            $maleLivestockTagId = $data->maleLivestockTagId;
            $femaleLivestockTagId = $data->femaleLivestockTagId;

            $data->description = "Updated Livestock Breeding of Livestock $maleLivestockTagId and $femaleLivestockTagId";
            $femaleLivestock = $this->livestock->getFarmerLivestockIdByTag($data->femaleLivestockTagId, $data->farmerId);
            $data->livestockId = $femaleLivestock;
            $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);

            return $this->respond(['success' => true, 'message' => 'Livestock Breeding Successfully Updated'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateLivestockBreedingRecordStatus($id)
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockBreeding->updateLivestockBreedingRecordStatus($id, $data->recordStatus);

            $data->farmerId = $data->userId;
            $data->action = $data->recordStatus == 'Archive' ? 'Archive' : "Edit";
            $data->title = $data->recordStatus == 'Archived' ? 'Archived Livestock Breeding Record' : "Updated Livestock Breeding Record";
            $data->entityAffected = $data->recordStatus == 'Archived' ? 'Archived' : "Breeding";

            $maleLivestockTagId = $data->maleLivestockTagId;
            $femaleLivestockTagId = $data->femaleLivestockTagId;

            $data->description = $data->recordStatus == 'Archived' ? "Archived Livestock Breeding of Livestock $maleLivestockTagId and $femaleLivestockTagId" : "Updated Livestock Breeding of Livestock $maleLivestockTagId and $femaleLivestockTagId";
            $data->livestockId = $this->livestock->getFarmerLivestockIdByTag($data->femaleLivestockTagId, $data->farmerId);
            $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);

            return $this->respond(['result' => $response, 'message' => 'Livestock Breeding Record Status Successfully Updated'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteLivestockBreeding($id)
    {
        try {
            $response = $this->livestockBreeding->deleteLivestockBreeding($id);

            $livestock = $this->livestockBreeding->getLivestockByBreeding($id);

            $data = new \stdClass();
            $data->farmerId = $data->userId;
            $data->action = "Delete";
            $data->title = "Dee Livestock Breeding";
            $data->entityAffected = "Breeding";

            $maleLivestockTagId = $livestock['maleLivestockTagId'];
            $femaleLivestockTagId = $livestock['femaleLivestockTagId'];

            $data->description = "Updated Livestock Breeding of Livestock $maleLivestockTagId and $femaleLivestockTagId";
            $data->livestockId = $this->livestock->getFarmerLivestockIdByTag($data->femaleLivestockTagId, $data->userId);
            $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);
            return $this->respond(['result' => $response, 'message' => 'Livestock Breeding Successfully Deleted'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllBreedingParentOffspringData()
    {
        try {
            $livestockBreedings = $this->livestockBreeding->getAllBreedingParentOffspringData();
            return $this->respond($livestockBreedings);
        } catch (\Throwable $th) {
            //throw $th;

            return $this->respond(['error' => $th->getMessage()], 200);
        }
    }

    public function getOverallLivestockBreedingCount()
    {
        try {
            $livestockBreedingCount = $this->livestockBreeding->getOverallLivestockBreedingCount();
            return $this->respond(['breedingCount' => "$livestockBreedingCount"]);
        } catch (\Throwable $th) {
            //throw $th;

            return $this->respond(['error' => $th->getMessage()], 200);
        }
    }

    public function getOverallLivestockBreedingCountInCurrentYear()
    {
        try {
            $livestockBreedingCount = $this->livestockBreeding->getOverallLivestockBreedingCountInCurrentYear();
            return $this->respond(['breedingCount' => "$livestockBreedingCount"]);
        } catch (\Throwable $th) {
            //throw $th;

            return $this->respond(['error' => $th->getMessage()], 200);
        }
    }

    public function getFarmerOverallLivestockBreedingCount($userId)
    {
        try {
            $livestockBreedingCount = $this->livestockBreeding->getFarmerOverallLivestockBreedingCount($userId);
            return $this->respond(['breedingCount' => "$livestockBreedingCount"]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockBreedingSuccessPercentage()
    {
        try {
            $successCount = $this->livestockBreeding->getLivestockBreedingCountByResultInCurrentYear("Successful Breeding");

            $livestockBreedings = $this->livestockBreeding->getOverallLivestockBreedingCountInCurrentYear();

            $breedingPercentage = 0;
            if ($livestockBreedings > 0) {
                $percentage = ($successCount / $livestockBreedings) * 100;

                if (floor($percentage) == $percentage) {
                    // Display only whole numbers
                    $breedingPercentage = number_format($percentage, 0);
                } else {
                    // Display up to two decimal places
                    $breedingPercentage = number_format($percentage, 2);
                }
            }

            return $this->respond(['breedingPercent' => "$breedingPercentage%"]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockBreedingResultsCount()
    {
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

    public function getBreedingsCountLast4Months()
    {
        try {
            $livestockBreedings = $this->livestockBreeding->getBreedingsCountLast4Months();
            return $this->respond($livestockBreedings);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockTypeBreedingsCount()
    {
        try {
            $livestockBreedings = $this->livestockBreeding->getLivestockTypeBreedingsCount();
            return $this->respond($livestockBreedings);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getBreedingCountByMonth()
    {
        try {
            $livestockBreedings = $this->livestockBreeding->getBreedingCountByMonth();
            return $this->respond($livestockBreedings);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

}

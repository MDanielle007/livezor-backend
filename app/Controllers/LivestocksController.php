<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FarmerAuditModel;
use App\Models\LivestockTypeModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\LivestockModel;
use App\Models\FarmerLivestockModel;

class LivestocksController extends ResourceController
{
    private $livestock;
    private $livestockTypes;
    private $farmerLivestock;
    private $userModel;
    private $farmerAudit;

    public function __construct()
    {
        $this->livestock = new LivestockModel();
        $this->livestockTypes = new LivestockTypeModel();
        $this->farmerLivestock = new FarmerLivestockModel();
        $this->userModel = new UserModel();
        $this->farmerAudit = new FarmerAuditModel();
    }

    public function getAllLivestocks()
    {
        try {
            $livestocks = $this->livestock->getAllLivestock();

            return $this->respond($livestocks);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestock($id)
    {
        try {
            $livestock = $this->livestock->getLivestockById($id);

            return $this->respond($livestock);
        } catch (\Throwable $th) {
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getFarmerAllLivestocks($userId)
    {
        try {
            $livestocks = $this->livestock->getFarmerAllLivestock($userId);

            return $this->respond($livestocks);
        } catch (\Throwable $th) {
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function addLivestock()
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestock->insertLivestock($data);

            return $this->respond(['result' => $response, 'message' => 'Livestock Successfully Added'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function addFarmerLivestock()
    {
        try {
            $data = $this->request->getJSON();

            $livestockId = $this->livestock->insertLivestock($data);

            $data->livestockId = $livestockId;

            $response = $this->farmerLivestock->associateFarmerLivestock($data);

            $livestockType = $this->livestockTypes->getLivestockTypeName($data->livestockTypeId);
            $livestockTagId = $data->livestockTagId;

            $data->action = "Add";
            $data->title = "Add New Livestock";
            $data->description = "Add New Livestock $livestockType, $livestockTagId";
            $data->entityAffected = "Livestock";

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);
            return $this->respond(['success' => true, 'message' => 'Livestock Successfully Added'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => 'Failed to add livestock']);
        }
    }

    public function updateLivestock($id)
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestock->updateLivestock($id, $data);

            return $this->respond(['success' => $response, 'message' => 'Livestock Successfully Updated'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => 'Failed to update livestock']);
        }
    }

    public function updateLivestockHealthStatus($id)
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestock->updateLivestockHealthStatus($id, $data);

            return $this->respond(['result' => $response, 'message' => 'Livestock Health Status Successfully Updated'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateLivestockRecordStatus($id)
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestock->updateLivestockRecordStatus($id, $data->recordStatus);

            return $this->respond(['result' => $response, 'message' => 'Livestock Record Status Successfully Updated'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteLivestock($id)
    {
        try {
            $response = $this->livestock->deleteLivestock($id);

            return $this->respond(['result' => $response, 'message' => 'Livestock Successfully Deleted'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockMappingData()
    {
        try {
            //code...
            $mappingData = $this->userModel->getBasicUserInfo();

            foreach ($mappingData as &$md) {
                $md['livestock'] = $this->livestock->getFarmerEachLivestockTypeCountData($md['id']);
            }


            return $this->respond(['farmers' => $mappingData]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    // testing method
    public function getLivestockPrimaryData($id)
    {
        try {
            $livestock = $this->livestock->getLivestockPrimaryData($id);

            return $this->respond($livestock);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getFarmerLivestockIdByTag()
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestock->getFarmerLivestockIdByTag($data->livestockTagId, $data->userId);

            return $this->respond($response);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllLivestockTypeAgeClassCount($userId)
    {
        try {
            $data = $this->livestock->getAllLivestockTypeAgeClassCount();

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getFarmerLivestockTypeAgeClassCount($userId)
    {
        try {
            $data = $this->livestock->getFarmerLivestockTypeAgeClassCount($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllLivestockTypeCount()
    {
        try {
            $data = $this->livestock->getAllLivestockTypeCount();

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getFarmerLivestockTypeCount($userId)
    {
        try {
            $data = $this->livestock->getFarmerLivestockTypeCount($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllLivestockCount()
    {
        try {
            $data = $this->livestock->getAllLivestockCount();

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getFarmerLivestockCount($userId)
    {
        try {
            $data = $this->livestock->getFarmerLivestockCount($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllFarmerLivestockTagIDs($userId)
    {
        try {
            $data = $this->livestock->getAllFarmerLivestockTagIDs($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getFarmerDistinctLivestockType($userId)
    {
        try {
            $data = $this->livestock->getFarmerDistinctLivestockType($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllFarmerLivestocksBySexAndType($userId)
    {
        try {
            $data = $this->livestock->getAllFarmerLivestocksBySexAndType($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

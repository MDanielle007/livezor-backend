<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FarmerAuditModel;
use App\Models\LivestockDewormingModel;
use App\Models\LivestockModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class LivestockDewormingController extends ResourceController
{
    private $livestockDewormings;
    private $livestock;
    private $userModel;
    private $farmerAudit;

    public function __construct()
    {
        $this->livestockDewormings = new LivestockDewormingModel();
        $this->livestock = new LivestockModel();
        $this->userModel = new UserModel();
        $this->farmerAudit = new FarmerAuditModel();
    }

    public function getAllLivestockDewormings()
    {
        try {
            $livestockDewormings = $this->livestockDewormings->getAllLivestockDewormings();

            return $this->respond($livestockDewormings);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockDeworming($id)
    {
        try {
            $livestockDeworming = $this->livestockDewormings->getLivestockDeworming($id);

            return $this->respond($livestockDeworming);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllFarmerLivestockDewormings($userId)
    {
        try {
            $livestockDewormings = $this->livestockDewormings->getAllFarmerLivestockDewormings($userId);

            return $this->respond($livestockDewormings);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertLivestockDeworming()
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockDewormings->insertLivestockDeworming($data);

            $livestockTagId = $this->livestock->getLivestockTagIdById($data->livestockId);

            $dosage = $data->dosage;
            $administrationMethod = $data->administrationMethod;

            $data->farmerId = $data->dewormerId;
            $data->action = "Add";
            $data->title = "Adminster Deworming";
            $data->description = "Deworm $administrationMethod $dosage to Livestock $livestockTagId";
            $data->entityAffected = "Deworming";

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);

            return $this->respond(['success' => $response, 'message' => 'Livestock Deworming Successfully Added'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertMultipleLivestockDeworming()
    {
        try {
            $data = $this->request->getJSON();

            $livestock = $data->livestock;
            $data->farmerId = $data->dewormerId;
            $data->action = "Add";
            $data->title = "Adminster Deworming";
            $data->entityAffected = "Deworming";

            foreach ($livestock as $ls) {
                $response = $this->livestockDewormings->insertLivestockDeworming($data);

                $data->livestockId = $ls;
                $livestockTagId = $this->livestock->getLivestockTagIdById($data->livestockId);

                $dosage = $data->dosage;
                $administrationMethod = $data->administrationMethod;

                $data->description = "Deworm $administrationMethod $dosage to Livestock $livestockTagId";

                $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);
            }

            return $this->respond(['success' => $response, 'message' => 'Livestock Deworming Successfully Added'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage(), 'message' => 'Livestock Deworming Successfully Added'], 200);
        }
    }

    public function updateLivestockDeworming($id)
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockDewormings->updateLivestockDeworming($id, $data);

            $livestockTagId = $this->livestock->getLivestockTagIdById($data->livestockId);

            $data->farmerId = $data->dewormerId;
            $data->action = "Edit";
            $data->title = "Update Deworming Details";
            $data->description = "Update Deworming of Livestock $livestockTagId";
            $data->entityAffected = "Deworming";

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);

            return $this->respond(['success' => $response, 'message' => 'Livestock Deworming Successfully Updated'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateLivestockDewormingRecordStatus($id)
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockDewormings->updateLivestockDewormingRecordStatus($id, $data->recordStatus);

            $livestock = $this->livestock->getLivestockByDeworming($id);
            $livestockTagId = $livestock['livestock_tag_id'];

            $data->farmerId = $data->dewormerId;
            $data->livestockId = $livestock['id'];
            $data->action = $data->recordStatus == 'Archived' ? "Archived" : "Edit";
            $data->title = $data->recordStatus == 'Archived' ? "Archived Deworming Record" : "Unarchived Deworming Record";
            $data->description = $data->recordStatus == 'Archived' ? "Archived Deworming of Livestock $livestockTagId" : "Unarchived Deworming of Livestock $livestockTagId";
            $data->entityAffected = "Deworming";

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);

            return $this->respond(['success' => $response, 'message' => 'Livestock Deworming Record Status Successfully Updated'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteLivestockDewormingRecord($id)
    {
        try {
            $response = $this->livestockDewormings->deleteLivestockDewormingRecord($id);

            $livestock = $this->livestock->getLivestockByDeworming($id);
            $livestockTagId = $livestock['livestock_tag_id'];

            $data = new \stdClass();
            $data->farmerId = $data->userId;
            $data->livestockId = $livestock['id'];
            $data->action = "Delete";
            $data->title = "Deleted Deworming Record";
            $data->description = "Deleted Deworming of Livestock $livestockTagId";
            $data->entityAffected = "Deworming";

            return $this->respond(['success' => $response, 'message' => 'Livestock Deworming Successfully Deleted'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getOverallLivestockDewormingCount()
    {
        try {
            $livestockDewormingCount = $this->livestockDewormings->getOverallLivestockDewormingCount();

            return $this->respond(['dewormingCount' => "$livestockDewormingCount"]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerOverallLivestockDewormingCount($userId)
    {
        try {
            $livestockDewormingCount = $this->livestockDewormings->getFarmerOverallLivestockDewormingCount($userId);

            return $this->respond(['dewormingCount' => "$livestockDewormingCount"]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getDewormingCountLast4Months()
    {
        try {
            $livestockDewormingCountLast4Months = $this->livestockDewormings->getDewormingCountLast4Months();

            return $this->respond($livestockDewormingCountLast4Months);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getTopLivestockTypeDewormedCount()
    {
        try {
            $livestockDeworming = $this->livestockDewormings->getTopLivestockTypeDewormedCount();

            return $this->respond($livestockDeworming);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAdministrationMethodsCount()
    {
        try {
            $livestockDeworming = $this->livestockDewormings->getAdministrationMethodsCount();

            return $this->respond($livestockDeworming);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getDewormingCountByMonth()
    {
        try {
            $livestockDeworming = $this->livestockDewormings->getDewormingCountByMonth();

            return $this->respond($livestockDeworming);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }
}

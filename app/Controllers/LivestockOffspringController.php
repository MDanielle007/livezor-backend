<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LivestockOffspringModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class LivestockOffspringController extends ResourceController
{
    private $livestockOffspring;
    public function __construct()
    {
        $this->livestockOffspring = new LivestockOffspringModel();
    }

    public function getAllLivestockOffspringRecords()
    {
        $livestockOffspringRecords = $this->livestockOffspring->getAllLivestockOffspring();

        return $this->respond($livestockOffspringRecords);
    }

    public function getAllCompleteLivestockOffspringRecord()
    {
        try {
            $livestockOffspringRecords = $this->livestockOffspring->getAllCompleteLivestockOffspring();

            return $this->respond($livestockOffspringRecords);
        } catch (\Throwable $th) {
            return $this->respond($th->getMessage());
        }
    }

    public function getLivestockOffspringRecord($id = null)
    {
        $livestockOffspringRecord = $this->livestockOffspring->getLivestockOffspring($id);

        return $this->respond($livestockOffspringRecord);
    }

    public function getAllFarmerLivestockOffspringRecords($userId)
    {
        $livestockOffspringRecords = $this->livestockOffspring->getAllFarmerLivestockOffspringRecords($userId);

        return $this->respond($livestockOffspringRecords);
    }

    public function insertLivestockOffspringRecord()
    {
        $data = $this->request->getJSON();

        $result = $this->livestockOffspring->insertLivestockOffspring($data);
        if (!$result) {
            return $this->fail($this->livestockOffspring->errors());
        }
        return $this->respond(['message' => 'Livestock Offspring Successfully Added', 'result' => $result], 200);
    }

    public function updateLivestockOffspringRecord($id)
    {
        $data = $this->request->getJSON();

        $result = $this->livestockOffspring->updateLivestockOffspring($id, $data);
        if (!$result) {
            return $this->fail($this->livestockOffspring->errors());
        }
        return $this->respond(['message' => 'Livestock Offspring Successfully Updated', 'result' => $result], 200);
    }

    public function updateLivestockOffspringRecordStatus($id)
    {
        $data = $this->request->getJSON();

        $result = $this->livestockOffspring->updateLivestockOffspringRecordStatus($id, $data->recordStatus);
        if (!$result) {
            return $this->fail($this->livestockOffspring->errors());
        }
        return $this->respond(['message' => 'Livestock Offspring Successfully Updated', 'result' => $result], 200);
    }

    public function deleteLivestockOffspringRecord($id)
    {
        $result = $this->livestockOffspring->deleteLivestockOffspringRecord($id);
        if (!$result) {
            return $this->fail($this->livestockOffspring->errors());
        }
        return $this->respond(['message' => 'Livestock Offspring Successfully Deleted', 'result' => $result], 200);
    }

    public function getLivestockOffspringCount($pregnancyId){
        try {
            $livestockOffspringCount = $this->livestockOffspring->getLivestockOffspringCount($pregnancyId);

            return $this->respond($livestockOffspringCount);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }
}

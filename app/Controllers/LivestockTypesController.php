<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LivestockTypeModel;
use CodeIgniter\RESTful\ResourceController;


class LivestockTypesController extends ResourceController
{
    private $livestockType;

    public function __construct()
    {
        $this->livestockType = new LivestockTypeModel;
    }

    public function insertLivestockType()
    {
        try {
            $data = $this->request->getJSON();
            $data->category = "Livestock";
            $result = $this->livestockType->insertLivestockType($data);
            if (!$result) {
                return $this->respond(['message' => 'New Livestock Type Failed', 'error' => $this->livestockType->errors()], 200);
            }
            return $this->respond(['message' => 'New Livestock Type Successfully Added', 'success' => true], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getLivestockTypes()
    {
        try {
            $livestockTypes = $this->livestockType->getLivestockTypes();

            return $this->respond($livestockTypes, 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getLivestockType($id)
    {
        try {
            $livestockType = $this->livestockType->getLivestockType($id);

            return $this->respond($livestockType, 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getLivestockTypeName($id)
    {
        try {
            $livestockType = $this->livestockType->getLivestockTypeName($id);

            return $this->respond($livestockType, 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function updateLivestockType($id)
    {
        try {
            $data = $this->request->getJSON();
            $data->category = "Livestock";
            $result = $this->livestockType->updateLivestockType($id, $data);
            if (!$result) {
                return $this->fail($this->livestockType->errors());
            }
            return $this->respond(['message' => 'Livestock Type Successfully Updated', 'result' => $result], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function deleteLivestockType($id)
    {
        try {
            $result = $this->livestockType->deleteLivestockType($id);
            if (!$result) {
                return $this->fail($this->livestockType->errors());
            }
            return $this->respond(['message' => 'Livestock Type Successfully Deleted', 'result' => $result], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getLivestockTypesIdAndName()
    {
        try {
            $livestockTypes = $this->livestockType->getAllLivestockTypeIdName();

            return $this->respond($livestockTypes, 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }
}

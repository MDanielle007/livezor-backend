<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LivestockTypeModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class PoultryTypeController extends ResourceController
{
    private $poultryType;
    public function __construct()
    {
        $this->poultryType = new LivestockTypeModel();
    }

    public function getPoultryTypes(){
        try {
            $poultryTypes = $this->poultryType->getPoultryTypes();

            return $this->respond($poultryTypes);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond($th->getMessage());
        }
    }

    public function getPoultryTypesIdAndName()
    {
        try {
            $poultryTypes = $this->poultryType->getAllPoultryTypeIdName();

            return $this->respond($poultryTypes, 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getPoultryType($id)
    {
        try {
            $poultryType = $this->poultryType->getPoultryType($id);

            return $this->respond($poultryType, 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function insertPoultryType(){
        try {
            $data = $this->request->getJSON();
            $data->category = "Poultry";
            $result = $this->poultryType->insertLivestockType($data);
            if (!$result) {
                return $this->respond(['message' => 'New Poultry Type Failed', 'error' => $this->poultryType->errors()], 200);
            }
            return $this->respond(['message' => 'New Poultry Type Successfully Added', 'success' => true], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function updatePoultryType($id){
        try {
            $data = $this->request->getJSON();
            $data->category = "Poultry";
            $result = $this->poultryType->updateLivestockType($id, $data);
            if (!$result) {
                return $this->fail($this->poultryType->errors());
            }
            return $this->respond(['message' => 'Livestock Type Successfully Updated', 'success' => $result], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function deletePoultryType($id)
    {
        try {
            $result = $this->poultryType->deleteLivestockType($id);
            if (!$result) {
                return $this->fail($this->poultryType->errors());
            }
            return $this->respond(['message' => 'Livestock Type Successfully Deleted', 'success' => $result], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

}

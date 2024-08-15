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
            $response = $this->poultryType->insertLivestockType($data);
            if (!$response) {
                return $this->fail($this->poultryType->errors(), ResponseInterface::HTTP_BAD_REQUEST);
            }
            return $this->respond(['result' => $response], 200, 'New Poultry Type Successfully Added');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to insert record', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updatePoultryType($id){
        try {
            $data = $this->request->getJSON();
            $data->category = "Poultry";
            $response = $this->poultryType->updateLivestockType($id, $data);
            if (!$response) {
                return $this->fail($this->poultryType->errors(), ResponseInterface::HTTP_BAD_REQUEST);
            }
            return $this->respond(['result' => $response], 200, 'Poultry Type Successfully Updated');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to update record', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deletePoultryType()
    {
        try {
            $id = $this->request->getGet('type');
            $result = $this->poultryType->deleteLivestockType($id);
            if (!$result) {
                return $this->fail($this->poultryType->errors(), ResponseInterface::HTTP_BAD_REQUEST);
            }
            return $this->respond(['result' => $result], 200, 'Livestock Type Successfully Deleted');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to delete record', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}

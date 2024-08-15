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
            $response = $this->livestockType->insertLivestockType($data);
            if (!$response) {
                return $this->fail($this->livestockType->errors(), ResponseInterface::HTTP_BAD_REQUEST);
            }
            return $this->respond(['result' => $response], 200, 'New Livestock Type Successfully Added');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to insert record', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
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
                return $this->fail($this->livestockType->errors(), ResponseInterface::HTTP_BAD_REQUEST);
            }
            return $this->respond(['result' => $result], 200, 'Livestock Type Successfully Updated');
        } catch (\Throwable $th) {
            //throw $th;log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to update record', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteLivestockType()
    {
        try {
            $id = $this->request->getGet('type');

            $result = $this->livestockType->deleteLivestockType($id);
            if (!$result) {
                return $this->fail($this->livestockType->errors(),ResponseInterface::HTTP_BAD_REQUEST);
            }
            return $this->respond(['result' => $result], 200, 'Livestock Type Successfully Deleted');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to delete record', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
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

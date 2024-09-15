<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FarmInformationModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class FarmInformationController extends ResourceController
{
    private $farms;
    public function __construct()
    {
        $this->farms = new FarmInformationModel();
        helper('jwt');
    }

    public function getAllFarmData()
    {
        try {
            $data = $this->farms->getAllFarmData();
            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllFarmDataById($id)
    {
        try {
            $data = $this->farms->getAllFarmDataById($id);
            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllFarmDataByFarmer()
    {
        try {
            //code...
            $header = $this->request->getHeader("Authorization");
            $decoded = decodeToken($header);
            $userType = $decoded->aud;
            $userId = "";
            if ($userType == 'Farmer') {
                $userId = getTokenUserId($header);
            } else {
                $userId = $this->request->getGet('fui');
            }

            $data = $this->farms->getAllFarmDataByFarmer($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function insertFarm()
    {
        try {
            //code...
            $data = $this->request->getJSON();

            $header = $this->request->getHeader("Authorization");
            $decoded = decodeToken($header);
            $userType = $decoded->aud;
            if ($userType == 'Farmer') {
                $data->farmerId = getTokenUserId($header);
            }

            $result = $this->farms->insertFarm($data);
            return $this->respond($result);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to insert data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateFarm()
    {
        try {
            //code...
            $data = $this->request->getJSON();

            $header = $this->request->getHeader("Authorization");
            $decoded = decodeToken($header);
            $userType = $decoded->aud;
            if ($userType == 'Farmer') {
                $data->farmerId = getTokenUserId($header);
            }

            $result = $this->farms->updateFarm($data->id, $data);
            return $this->respond($result);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to update data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteFarm(){
        try {
            //code...
            $id = $this->request->getGet('farm');

            $result = $this->farms->deleteFarm($id);
            return $this->respond($result);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to delete data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

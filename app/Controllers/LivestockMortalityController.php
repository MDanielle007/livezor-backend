<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LivestockModel;
use App\Models\LivestockMortalityModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class LivestockMortalityController extends ResourceController
{
    private $livestockMortality;
    private $livestock;

    public function __construct()
    {
        $this->livestockMortality = new LivestockMortalityModel();
        $this->livestock = new LivestockModel();
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
            $data->livestockHealthStatus = 'Dead';
            $result = $this->livestock->updateLivestockHealthStatus($data->livestockId, $data);

            return $this->respond(['result' => [$response,$result], 'message' => 'Livestock Mortality Successfully Added'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateLivestockMortality($id)
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockMortality->updateLivestockMortality($id, $data);

            return $this->respond(['result' => $response, 'message' => 'Livestock Mortality Successfully Updated'], 200);

        } catch (\Throwable $th) {
            //throw $th;
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
}

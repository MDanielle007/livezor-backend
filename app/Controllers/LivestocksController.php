<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\LivestockModel;
use App\Models\FarmerLivestockModel;

class LivestocksController extends ResourceController
{
    private $livestock;
    private $farmerLivestock;
    private $userModel;

    public function __construct()
    {
        $this->livestock = new LivestockModel();
        $this->farmerLivestock = new FarmerLivestockModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {

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

            return $this->respond(['result' => $response, 'message' => 'Livestock Successfully Added','data' => $data], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function updateLivestock($id){
        try {
            $data = $this->request->getJSON();

            $response = $this->livestock->updateLivestock($id, $data);

            return $this->respond(['result' => $response,'message' => 'Livestock Successfully Updated'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateLivestockHealthStatus($id){
        try {
            $data = $this->request->getJSON();

            $response = $this->livestock->updateLivestockHealthStatus($id, $data);

            return $this->respond(['result' => $response,'message' => 'Livestock Health Status Successfully Updated'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateLivestockRecordStatus($id){
        try {
            $data = $this->request->getJSON();

            $response = $this->livestock->updateLivestockRecordStatus($id, $data->recordStatus);

            return $this->respond(['result' => $response,'message' => 'Livestock Record Status Successfully Updated'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteLivestock($id){
        try {
            $response = $this->livestock->deleteLivestock($id);

            return $this->respond(['result' => $response,'message' => 'Livestock Successfully Deleted'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockMappingData(){
        try {
            //code...
            $mappingData = $this->userModel->getBasicUserInfo();

            foreach($mappingData as &$md){
                $md['livestock'] = $this->livestock->getFarmerEachLivestockTypeCountData($md['id']);
            }


            return $this->respond(['farmers'=>$mappingData]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }
}

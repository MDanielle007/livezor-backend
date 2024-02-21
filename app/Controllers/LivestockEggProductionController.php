<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LivestockEggProductionModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class LivestockEggProductionController extends ResourceController
{
    private $livestockEggProduction;

    public function __construct()
    {
        $this->livestockEggProduction = new LivestockEggProductionModel();
    }

    public function getAllEggProductions(){
        try {
            $livestockEggProductions = $this->livestockEggProduction->getAllEggProductions();

            return $this->respond($livestockEggProductions);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getEggProduction($id){
        try {
            $livestockVaccination = $this->livestockEggProduction->getEggProduction($id);

            return $this->respond($livestockVaccination);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllFarmerEggProductions($userId){
        try {
            $livestockEggProductions = $this->livestockEggProduction->getAllFarmerEggProductions($userId);

            return $this->respond($livestockEggProductions);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertEggProduction(){
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockEggProduction->insertEggProduction($data);

            return $this->respond(['result' => $response,'message' => 'Livestock Egg Production Successfully Added'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }    

    public function updateEggProduction($id){
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockEggProduction->updateEggProduction($id, $data);

            return $this->respond(['result' => $response,'message' => 'Livestock Egg Production Successfully Updated'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateEggProductionRecordStatus($id){
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockEggProduction->updateEggProductionRecordStatus($id, $data->recordStatus);

            return $this->respond(['result' => $response,'message' => 'Livestock Egg Production Record Status Successfully Updated'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteEggProduction($id){
        try {
            $response = $this->livestockEggProduction->deleteLivestockVaccination($id);

            return $this->respond(['result' => $response,'message' => 'Livestock Vaccination Successfully Deleted'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

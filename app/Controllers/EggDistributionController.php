<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EggDistributionModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class EggDistributionController extends ResourceController
{
    private $eggDistributions;
    public function __construct()
    {
        $this->eggDistributions = new EggDistributionModel();
    }

    public function getAllEggDistributions(){
        try {
            $data = $this->eggDistributions->getAllEggDistributions();
            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getEggDistributionById($id){
        try {
            $data = $this->eggDistributions->getEggDistributionById($id);
            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertEggDistribution(){
        try {
            $data = $this->request->getJSON();

            $eggDistribution = $this->eggDistributions->insertEggDistribution($data);
            return $this->respond(['result' => $eggDistribution, 'message' => 'Egg Distribution Successfully Added'],200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function updateEggDistribution($id){
        try {
            $data = $this->request->getJSON();

            $eggDistribution = $this->eggDistributions->updateEggDistribution($id,$data);
            return $this->respond(['result' => $eggDistribution, 'message' => 'Egg Distribution Successfully Added'],200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateEggDistributionRecordStatus($id){
        try {
            $data = $this->request->getJSON();
            $response = $this->eggDistributions->updateEggDistributionRecordStatus($id, $data->recordStatus);
            return $this->respond(['result' => $response, 'message' => 'Egg Distribution Successfully Added'],200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteEggDistribution($id){
        try {
            $response = $this->eggDistributions->deleteEggDistribution($id);
            return $this->respond(['result' => $response, 'message' => 'Egg Distribution Successfully Added'],200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

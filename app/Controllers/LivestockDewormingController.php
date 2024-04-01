<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LivestockDewormingModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class LivestockDewormingController extends ResourceController
{
    private $livestockDewormings;

    public function __construct()
    {
        $this->livestockDewormings = new LivestockDewormingModel();
    }

    public function getAllLivestockDewormings(){
        try {
            $livestockDewormings = $this->livestockDewormings->getAllLivestockDewormings();

            return $this->respond($livestockDewormings);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockDeworming($id){
        try {
            $livestockDeworming = $this->livestockDewormings->getLivestockDeworming($id);

            return $this->respond($livestockDeworming);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllFarmerLivestockDewormings($userId){
        try {
            $livestockDewormings = $this->livestockDewormings->getAllFarmerLivestockDewormings($userId);

            return $this->respond($livestockDewormings);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertLivestockDeworming(){
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockDewormings->insertLivestockDeworming($data);

            return $this->respond(['success' =>$response, 'message' => 'Livestock Deworming Successfully Added'],200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateLivestockDeworming($id){
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockDewormings->updateLivestockDeworming($id, $data);

            return $this->respond(['success' =>$response, 'message' => 'Livestock Deworming Successfully Updated'],200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    
    public function updateLivestockDewormingRecordStatus($id){
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockDewormings->updateLivestockDewormingRecordStatus($id, $data->recordStatus);

            return $this->respond(['success' =>$response, 'message' => 'Livestock Deworming Record Status Successfully Updated'],200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteLivestockDewormingRecord($id){
        try {
            $response = $this->livestockDewormings->deleteLivestockDewormingRecord($id);

            return $this->respond(['success' => $response, 'message' => 'Livestock Deworming Successfully Deleted'],200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

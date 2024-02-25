<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LivestockAdvisoriesModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class LivestockAdvisoriesController extends ResourceController
{
    private $livestockAdvisories;
    private $userModel;

    public function __construct()
    {
        $this->livestockAdvisories = new LivestockAdvisoriesModel();
        $this->userModel = new UserModel();
        helper('firebasenotifications');
    }

    public function getAllLivestockAdvisories(){
        try {
            $livestockAdvisories = $this->livestockAdvisories->getAllLivestockAdvisories();

            return $this->respond($livestockAdvisories);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockAdvisory($id){
        try {
            $livestockAdvisory = $this->livestockAdvisories->getLivestockAdvisory($id);

            return $this->respond($livestockAdvisory);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllFarmerLivestockAdvisories($userId){
        try {
            $livestockAdvisories = $this->livestockAdvisories->getAllFarmerLivestockAdvisories($userId);

            return $this->respond($livestockAdvisories);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllGeneralLivestockAdvisories(){
        try {
            $livestockAdvisories = $this->livestockAdvisories->getAllGeneralLivestockAdvisories();

            return $this->respond($livestockAdvisories);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function sendLivestockAdvisories()
    {
        try {
            $data = $this->request->getJSON();
            $title = $data->subject;
            $body = $data->content;

            if($data->isGeneral === true){
                $result = $this->livestockAdvisories->sendLivestockAdvisory($data);

                $farmerTokens = $this->userModel->getAllUserFirebaseToken('Farmer');

                $notifRes = sendNotification($title, $body, $farmerTokens);
                return $this->respond(['result' => $result,'message' => 'Livestock Advisory Successfully Sent','notification sent' => $notifRes], 200);
            }

            $targetFarmers = $data->targetFarmers;
            foreach ($targetFarmers as $targetFarmer) {
                $data->targetFarmerId = $targetFarmer;
                $result = $this->userModel->getUserFirebaseToken($targetFarmer);
            }

            return $this->respond(['result' => $data, 'message' => 'Livestock Advisory Successfully Sent'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['result' => $th->getMessage()]);
        }
    }

    public function updateLivestockAdvisory($id){
        try {
            $data = $this->request->getJSON();
            
            $response = $this->livestockAdvisories->updateLivestockAdvisory($id, $data);

            return $this->respond(['result' => $response,'message' => 'Livestock Advisory Successfully Updated'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateLivestockAdvisoryReadStatus($id){
        try {
            $data = $this->request->getJSON();
            
            $response = $this->livestockAdvisories->updateLivestockAdvisoryReadStatus($id, $data);

            return $this->respond(['result' => $response,'message' => 'Livestock Advisory Read Status Successfully Updated'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateLivestockAdvisoryRecordStatus($id){
        try {
            $data = $this->request->getJSON();
            
            $response = $this->livestockAdvisories->updateLivestockAdvisoryRecordStatus($id, $data);

            return $this->respond(['result' => $response,'message' => 'Livestock Advisory Record Status Successfully Updated'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteLivestockAdvisory($id){
        try {
            $response = $this->livestockAdvisories->deleteLivestockAdvisory($id);

            return $this->respond(['result' => $response,'message' => 'Livestock Advisory Successfully Deleted'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

}

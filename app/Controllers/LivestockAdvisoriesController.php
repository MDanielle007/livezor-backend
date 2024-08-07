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
        helper('jwt');
    }

    public function getAllLivestockAdvisories()
    {
        try {
            $livestockAdvisories = $this->livestockAdvisories->getAllLivestockAdvisories();

            return $this->respond($livestockAdvisories);

        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getLivestockAdvisory($id)
    {
        try {
            $livestockAdvisory = $this->livestockAdvisories->getLivestockAdvisory($id);

            $farmerId = $livestockAdvisory['targetFarmerId'] ?? null;

            if ($farmerId && $livestockAdvisory['advisoryType'] == 'Farmer') {
                $farmer = $this->userModel->getUserName($farmerId);
                $livestockAdvisory['farmer'] = $farmer['userName'];
            }


            return $this->respond($livestockAdvisory);

        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllFarmerLivestockAdvisories()
    {
        try {
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);

            $livestockAdvisories = $this->livestockAdvisories->getAllFarmerLivestockAdvisories($userId);

            return $this->respond($livestockAdvisories);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllGeneralLivestockAdvisories()
    {
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
            $body = strip_tags($data->content);

            $notifRes = null;

            if ($data->isGeneral === true) {
                $result = $this->livestockAdvisories->sendLivestockAdvisory($data);

                $farmerTokens = $this->userModel->getAllUserFirebaseToken('Farmer');
                $notifRes = sendNotification($title, $body, $farmerTokens);
            }

            $targetFarmers = $data->targetFarmers;
            foreach ($targetFarmers as $targetFarmer) {
                $data->targetFarmerId = $targetFarmer;
                $farmerTokens = $this->userModel->getUserFirebaseToken($targetFarmer);

                $notifRes = sendNotification($title, $body, $farmerTokens);
                $result = $this->livestockAdvisories->sendLivestockAdvisory($data);
            }
            return $this->respond(['result' => $data, 'message' => 'Livestock Advisory Successfully Sent'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function updateLivestockAdvisory($id)
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockAdvisories->updateLivestockAdvisory($id, $data);

            return $this->respond(['result' => $response, 'message' => 'Livestock Advisory Successfully Updated'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateLivestockAdvisoryReadStatus($id)
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockAdvisories->updateLivestockAdvisoryReadStatus($id, $data);

            return $this->respond(['result' => $response, 'message' => 'Livestock Advisory Read Status Successfully Updated'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['result' => $th->getMessage()]);
        }
    }
}

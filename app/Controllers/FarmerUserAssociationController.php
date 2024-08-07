<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FarmerAssociationModel;
use App\Models\FarmerUserAssociationModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class FarmerUserAssociationController extends ResourceController
{
    private $farmerUserAssociations;
    private $farmerAssociations;
    public function __construct()
    {
        $this->farmerUserAssociations = new FarmerUserAssociationModel();
        $this->farmerAssociations = new FarmerAssociationModel();
        helper('jwt');
    }

    public function getAllFarmerUserAssociations()
    {
        try {
            $data = $this->farmerUserAssociations->getAllFarmerUserAssociations();
            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerUserAssociationsById($id)
    {
        try {
            $data = $this->farmerUserAssociations->getFarmerUserAssociationsById($id);
            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerUserAssociationsByFarmer()
    {
        try {
            $header = $this->request->getHeader("Authorization");
            $decoded = decodeToken($header);
            $userId = $decoded->sub->id;
            $data = $this->farmerUserAssociations->getFarmerUserAssociationsByUser($userId);
            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
        }
    }

    public function getFarmerUserAssociationsByUserId()
    {
        try {
            $userId = $this->request->getGet('farmer');
            $data = $this->farmerUserAssociations->getFarmerUserAssociationsByUserId($userId);
            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
        }
    }

    public function insertFarmerUserAssociation()
    {
        try {
            $data = $this->request->getJSON();
            $header = $this->request->getHeader("Authorization");
            $decoded = decodeToken($header);

            $farmerAssociationId = $this->checkFarmerAssociation($data->farmerAssociationId);
            $data->farmerAssociationId = $farmerAssociationId;

            $data->userId = $decoded->sub->id;
            $res = $this->farmerUserAssociations->insertFarmerUserAssociation($data);
            if ($res) {
                return $this->respond(['message' => 'Farmer Association Successfully Added', 'result' => $res], 200);
            } else {
                return $this->respond(['message' => 'Farmer Association Added Unuccessfully', 'result' => false], 200);
            }
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->respond(['message' => 'Farmer Association Added Unuccessfully', 'result' => false, 'error' => $th->getMessage()], 200);
        }
    }

    private function checkFarmerAssociation($id)
    {
        $farmerAssociation = $this->farmerAssociations->getFarmerAssociationById($id);
        $farmerAssociationId = null;
        if (!$farmerAssociation) {
            $farmerAssociationId = $this->farmerAssociations->insertFarmerAssociation((object) ['farmerAssociationName' => $id]);
            return $farmerAssociationId;
        }else{
            return $id;
        }
    }

    public function insertMultipleFarmerUserAssociations()
    {
        try {
            $data = $this->request->getJSON();

            $farmerAssociation = $this->farmerAssociations->getFarmerAssociationByName($data->farmerUserAssociation);
            $farmerAssociationId = null;
            if (!$farmerAssociation) {
                $farmerAssociationId = $this->farmerAssociations->insertFarmerAssociation((object) ['farmerAssociationName' => $data->farmerUserAssociation]);
            } else {
                $farmerAssociationId = $farmerAssociation['id'];
            }

            $farmers = $data->farmers;

            $result = null;
            foreach ($farmers as $fmr) {
                $result = $this->farmerUserAssociations->insert(
                    (object) [
                        'userId' => $fmr,
                        'farmerAssociationId' => $farmerAssociationId
                    ]
                );
            }

            return $this->respond(['success' => $result, 'message' => 'Farmer Associations Successfully Added']);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage());
            log_message('error', json_encode($th->getTrace()));
        }
    }

    public function insertMultipleFarmerUserAssociationsInAFarmer()
    {
        try {
            $data = $this->request->getJSON();
            $header = $this->request->getHeader("Authorization");
            $decoded = decodeToken($header);

            $userId = $decoded->sub->id;

            $farmerAssociations = $data->farmerAssociations;

            $result = [];
            foreach ($farmerAssociations as $fa) {
                $farmerAssociation = $this->farmerAssociations->getFarmerAssociationByName($data->farmerUserAssociation);
                $farmerAssociationId = null;
                if (!$farmerAssociation) {
                    $farmerAssociationId = $this->farmerAssociations->insertFarmerAssociation((object) ['farmerAssociationName' => $data->farmerUserAssociation]);
                } else {
                    $farmerAssociationId = $farmerAssociation['id'];
                }

                $result[] = $this->farmerUserAssociations->insert(
                    (object) [
                        'userId' => $userId,
                        'farmerAssociationId' => $farmerAssociationId
                    ]
                );
            }

            return $this->respond(['success' => $result, 'message' => 'Farmer Associations Successfully Added']);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }


    public function updateFarmerUserAssociation($id)
    {
        try {
            $data = $this->request->getJSON();

            $res = $this->farmerUserAssociations->updateFarmerUserAssociation($id, $data);
            return $this->respond($res);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteFarmerUserAssociation($id)
    {
        try {
            $response = $this->farmerUserAssociations->deleteFarmerUserAssociation($id);
            return $this->respond($response, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

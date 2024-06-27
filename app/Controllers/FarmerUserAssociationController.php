<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FarmerAssociationModel;
use App\Models\FarmerUserAssociationModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class FarmerUserAssociationController extends ResourceController
{
    private $farmerUserAssociations;
    private $farmerAssociations;
    public function __construct()
    {
        $this->farmerUserAssociations = new FarmerUserAssociationModel();
        $this->farmerAssociations = new FarmerAssociationModel();
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

    public function getFarmerUserAssociationsByUserId($id)
    {
        try {
            $data = $this->farmerUserAssociations->getFarmerUserAssociationsByUserId($id);
            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertFarmerUserAssociation()
    {
        try {
            $data = $this->request->getJSON();

            $res = $this->farmerUserAssociations->insertFarmerUserAssociation($data);
            return $this->respond($res);
        } catch (\Throwable $th) {
            //throw $th;
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
        }
    }

    public function insertMultipleFarmerUserAssociationsInAFarmer()
    {
        try {
            $data = $this->request->getJSON();

            $userId = $data->userId;

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

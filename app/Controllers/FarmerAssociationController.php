<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FarmerAssociationModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class FarmerAssociationController extends ResourceController
{
    private $farmerAssociations;

    public function __construct()
    {
        $this->farmerAssociations = new FarmerAssociationModel();
    }

    public function getAllFarmerAssociationComplete(){
        try {
            $data = $this->farmerAssociations->getAllFarmerAssociationsComplete();
            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerAssociationComplete($id){
        try {
            $data = $this->farmerAssociations->getFarmerAssociationComplete($id);
            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerAssociation(){
        try {
            $data = $this->farmerAssociations->getFarmerAssociation();
            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertFarmerAssociation(){
        try {
            $data = $this->request->getJSON();

            $data = $this->farmerAssociations->insertFarmerAssociation($data);
            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertMultipleFarmerAssociation(){
        try {
            $data = $this->request->getJSON();

            $farmerAssociations = $this->farmerAssociations;

            $result = [];
            foreach ($farmerAssociations as $fa) {
                $farmerAssociation = $this->farmerAssociations->getFarmerAssociationByName($fa->farmerUserAssociation);
                if (!$farmerAssociation) {
                    $result[] = $this->farmerAssociations->insertFarmerAssociation((object) ['farmerAssociationName' => $data->farmerUserAssociation]);
                }
            }
            return $this->respond($result);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateFarmerAssociation($id){
        try {
            $data = $this->request->getJSON();

            $data = $this->farmerAssociations->updateFarmerAssociation($id,$data);
            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }    
    
    public function deleteFarmerAssociation($id){
        try {
            $response = $this->farmerAssociations->deleteFarmerAssociation($id);
            return $this->respond($response, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LivestockTypeModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\LivestockBreedModel;

class LivestockBreedsController extends ResourceController
{
    private $livestockBreed;
    private $livestockType;

    public function __construct()
    {
        $this->livestockBreed = new LivestockBreedModel();
        $this->livestockType = new LivestockTypeModel();
    }

    public function insertLivestockBreed()
    {
        try {
            $data = $this->request->getJSON();
            $response = $this->livestockBreed->insertLivestockBreed($data);
            return $this->respond(['message' => 'New Livestock Breed Successfully Added', 'result' => $response], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockBreeds()
    {
        try {
            $livestockBreeds = $this->livestockBreed->getLivestockBreeds();

            return $this->respond($livestockBreeds, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockBreed($id)
    {
        try {
            $livestockBreed = $this->livestockBreed->getLivestockBreed($id);

            return $this->respond($livestockBreed, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateLivestockBreed($id)
    {
        try {
            $data = $this->request->getJSON();
            $response = $this->livestockBreed->updateLivestockBreed($id, $data);
            return $this->respond(['message' => 'Livestock Breed Successfully Updated', 'result' => $response], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteLivestockBreed($id)
    {
        try {
            $response = $this->livestockBreed->deleteLivestockBreed($id);
            return $this->respond(['message' => 'Livestock Breed Successfully Deleted', 'result' => $response], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockBreedIdAndName()
    {
        try {
            $livestockBreeds = $this->livestockBreed->getLivestockBreedIdAndName();

            return $this->respond($livestockBreeds, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockBreedIdAndNameById($livestockTypeId)
    {
        try {
            $livestockBreeds = $this->livestockBreed->getLivestockBreedIdAndNameById($livestockTypeId);

            return $this->respond($livestockBreeds, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

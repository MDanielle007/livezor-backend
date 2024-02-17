<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\LivestockBreedModel;

class LivestockBreedsController extends ResourceController
{
    private $livestockBreed;

    public function __construct()
    {
        $this->livestockBreed = new LivestockBreedModel;
    }

    public function index()
    {

    }

    public function insertLivestockBreed()
    {
        $data = $this->request->getJSON();
        $response = $this->livestockBreed->insertLivestockBreed($data);
        return $this->respond(['message' => 'New Livestock Breed Successfully Added', 'result' => $response], 200);
    }

    public function getLivestockBreeds()
    {
        $livestockBreeds = $this->livestockBreed->getLivestockBreeds();

        return $this->respond($livestockBreeds, 200);
    }

    public function getLivestockBreed($id)
    {
        $livestockBreed = $this->livestockBreed->getLivestockBreed($id);

        return $this->respond($livestockBreed, 200);
    }

    public function updateLivestockBreed($id)
    {
        $data = $this->request->getJSON();
        $response = $this->livestockBreed->updateLivestockBreed($id, $data);
        return $this->respond(['message' => 'Livestock Breed Successfully Updated', 'result' => $response], 200);
    }

    public function deleteLivestockBreed($id)
    {
        $response = $this->livestockBreed->deleteLivestockBreed($id);
        return $this->respond(['message' => 'Livestock Breed Successfully Deleted', 'result' => $response], 200);
    }
}

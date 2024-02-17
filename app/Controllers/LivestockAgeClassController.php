<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\LivestockAgeClassModel;

class LivestockAgeClassController extends ResourceController
{
    private $livestockAgeClass;

    public function __construct()
    {
        $this->livestockAgeClass = new LivestockAgeClassModel();
    }

    public function index()
    {

    }

    public function insertLivestockAgeClass()
    {
        $data = $this->request->getJSON();
        $response = $this->livestockAgeClass->insertLivestockAgeClass($data);
        return $this->respond(['message' => 'New Livestock Age Classification Successfully Added', 'result' => $response], 200);
    }

    public function getLivestockAgeClasses()
    {
        $livestockAgeClasses = $this->livestockAgeClass->getLivestockAgeClasses();

        return $this->respond($livestockAgeClasses, 200);
    }

    public function getLivestockAgeClass($id)
    {
        $livestockAgeClass = $this->livestockAgeClass->getLivestockAgeClass($id);

        return $this->respond($livestockAgeClass, 200);
    }

    public function updateLivestockAgeClass($id)
    {
        try {
            $data = $this->request->getJSON();
        $response = $this->livestockAgeClass->updateLivestockAgeClass($id, $data);
        return $this->respond(['message' => 'Livestock Age Classification Successfully Updated', 'result' => $response], 200);
        } catch (\Throwable $th) {
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function deleteLivestockAgeClass($id)
    {
        $response = $this->livestockAgeClass->deleteLivestockAgeClass($id);
        return $this->respond(['message' => 'Livestock Age Classification Successfully Deleted', 'result' => $response], 200);
    }
}

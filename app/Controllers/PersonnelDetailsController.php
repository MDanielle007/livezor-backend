<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PersonnelDetailsModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class PersonnelDetailsController extends ResourceController
{
    private $personnelDetails;

    public function __construct()
    {
        $this->personnelDetails = new PersonnelDetailsModel();
    }

    public function getAllPersonnelDetails()
    {
        try {
            $personnelDetails = $this->personnelDetails->getAllPersonnelDetails();
            return $this->respond($personnelDetails);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getPersonnelDetailById($id)
    {
        try {
            $personnelDetails = $this->personnelDetails->getPersonnelDetail($id);
            return $this->respond($personnelDetails);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getPersonnelDetailByUserId()
    {
        try {
            $userId = $this->request->getGet('userId');

            $personnelDetails = $this->personnelDetails->getPersonnelDetailByUserId($userId);
            return $this->respond($personnelDetails);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertPersonnelDetails()
    {
        try {
            $data = $this->request->getJSON();

            $result = $this->personnelDetails->insertPersonnelDetails($data);

            return $this->respond(['message' => 'Personnel Details inserted successfully', 'result' => $result]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function updatePersonnelDetails($id)
    {
        try {
            $data = $this->request->getJSON();

            $result = $this->personnelDetails->updatePersonnelDetails($id, $data);

            return $this->respond(['message' => 'Personnel Details inserted successfully', 'result' => $result]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function deletePersonnelDetails($id){
        try {
            $result = $this->personnelDetails->deletePersonnelDetails($id);

            return $this->respond(['message' => 'Personnel Details deleted successfully', 'result' => $result]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

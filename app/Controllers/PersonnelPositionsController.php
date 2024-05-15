<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PersonnelPositionsModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class PersonnelPositionsController extends ResourceController
{
    private $position;

    public function __construct()
    {
        $this->position = new PersonnelPositionsModel();
    }

    public function getPersonnelPositions()
    {
        try {
            $positions = $this->position->getAllPositions();
            return $this->respond($positions);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getPersonnelPosition($id)
    {
        try {
            $position = $this->position->getPosition($id);
            return $this->respond($position);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getPersonnelPositionByDepartmentId($departmentId)
    {
        try {
            $positions = $this->position->getPositionByDepartmentId($departmentId);
            return $this->respond($positions);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertPersonnelPosition(){
        try {
            $data = $this->request->getJSON();

            $result = $this->position->insertPosition($data);

            return $this->respond(['message' => 'Personnel Position inserted successfully', 'result' => $result]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updatePersonnelPosition($id){
        try {
            $data = $this->request->getJSON();

            $result = $this->position->updatePosition($id, $data);

            return $this->respond(['message' => 'Personnel Position updated successfully', 'result' => $result]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deletePersonnelPosition($id){
        try {
            $result = $this->position->deletePosition($id);

            return $this->respond(['message' => 'Personnel Position deleted successfully', 'result' => $result]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

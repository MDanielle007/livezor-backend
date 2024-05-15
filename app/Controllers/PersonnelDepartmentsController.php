<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PersonnelDepartmentsModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class PersonnelDepartmentsController extends ResourceController
{
    private $departments;

    public function __construct()
    {
        $this->departments = new PersonnelDepartmentsModel();
    }

    public function getAllDepartments(){
        try {
            $departments = $this->departments->getAllDepartments();
            return $this->respond($departments);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getDepartment($id){
        try {
            $department = $this->departments->getDepartment($id);
            return $this->respond($department);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertDepartment(){
        try {
            $data = $this->request->getJSON();

            $department = $this->departments->insertDepartment($data);
            return $this->respond($department);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateDepartment($id){
        try {
            $data = $this->request->getJSON();

            $department = $this->departments->updateDepartment($id,$data);
            return $this->respond($department);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }    
    
    public function deleteDepartment($id){
        try {
            $response = $this->departments->deleteDepartment($id);
            return $this->respond($response, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

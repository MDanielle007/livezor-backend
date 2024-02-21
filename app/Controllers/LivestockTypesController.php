<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LivestockTypeModel;
use CodeIgniter\RESTful\ResourceController;


class LivestockTypesController extends ResourceController
{
    private $livestockType;

    public function __construct(){
        $this->livestockType = new LivestockTypeModel;
    }

    public function index()
    {
        
    }

    public function insertLivestockType(){
        $data = $this->request->getJSON();
        $result = $this->livestockType->insertLivestockType($data);
        if(!$result) {
            return $this->fail($this->livestockType->errors());
        }
        return $this->respond(['message' => 'New Livestock Type Successfully Added', 'result' => $result], 200);
    }

    public function getLivestockTypes(){
        $livestockTypes = $this->livestockType->getLivestockTypes();

        return $this->respond($livestockTypes, 200);
    }

    public function getLivestockType($id){
        $livestockType = $this->livestockType->getLivestockType($id);

        return $this->respond($livestockType, 200);
    }

    public function updateLivestockType($id){
        $data = $this->request->getJSON();
        $result = $this->livestockType->updateLivestockType($id,$data);
        if(!$result) {
            return $this->fail($this->livestockType->errors());
        }
        return $this->respond(['message' => 'Livestock Type Successfully Updated', 'result' => $result], 200);
    }

    public function deleteLivestockType($id){
        $result = $this->livestockType->deleteLivestockType($id);
        if(!$result) {
            return $this->fail($this->livestockType->errors());
        }
        return $this->respond(['message' => 'Livestock Type Successfully Deleted','result' => $result], 200);
    }
}

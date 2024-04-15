<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EggProductionBatchGroupModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class EggProductionBatchGroupController extends ResourceController
{
    private $eggProdBatch;

    public function __construct()
    {
        $this->eggProdBatch = new EggProductionBatchGroupModel();
    }

    public function getAllEggProductionBatchGroups(){
        $eggProdBatchGroups = $this->eggProdBatch->getAllEggProductionBatchGroups();

        return $this->respond($eggProdBatchGroups);
    }

    public function getEggProductionBatchGroup($id = null){
        $eggProdBatchGroup = $this->eggProdBatch->getEggProductionBatchGroup($id);

        return $this->respond($eggProdBatchGroup);
    }

    public function getAllActiveEggProductionBatchGroups(){
        $eggProdBatchGroups = $this->eggProdBatch->getAllActiveEggProductionBatchGroups();

        return $this->respond($eggProdBatchGroups);
    }

    public function checkEggProductionBatch($batchName){
        $result = $this->eggProdBatch->checkEggProductionBatch($batchName);

        return $this->respond($result);
    }

    public function insertEggProductionBatchGroup(){
        $data = $this->request->getJSON();

        $result = $this->eggProdBatch->insertEggProductionBatch($data);

        return $this->respond($result);
    }

    public function updateEggProductionBatch($id){
        $data = $this->request->getJSON();

        $result = $this->eggProdBatch->updateEggProductionBatch($id, $data);

        return $this->respond($result);
    }

    public function updateEggProductionBatchStatus($id){
        $data = $this->request->getJSON();

        $result = $this->eggProdBatch->updateEggProductionBatchStatus($id, $data->batchStatus);

        return $this->respond($result);
    }

    public function deleteEggProductionBatch($id){
        $result = $this->eggProdBatch->deleteEggProductionBatch($id);

        return $this->respond($result);
    }
}

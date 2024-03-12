<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EggInventoryModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class EggInventoryController extends ResourceController
{
    private $eggInventory;

    public function __construct()
    {
        $this->eggInventory = new EggInventoryModel();
    }

    public function getAllEggInventoryRecords()
    {
        $eggInventories = $this->eggInventory->getAllEggInventoryRecords();

        return $this->respond($eggInventories);
    }

    public function getEggInventoryRecord($id)
    {
        $eggInventory = $this->eggInventory->getEggInventoryRecord($id);

        return $this->respond($eggInventory);
    }

    public function getAllFarmerEggInventoryRecords($userId)
    {
        $eggInventory = $this->eggInventory->getAllFarmerEggInventoryRecords($userId);

        return $this->respond($eggInventory);
    }

    public function insertNewFarmerEggInventory()
    {
        $data = $this->request->getJSON();

        $eggInventory = $this->eggInventory->insertNewFarmerEggInventory($data);

        return $this->respond($eggInventory);
    }

    public function updateFarmerEggInventory($id)
    {
        $data = $this->request->getJSON();

        $result = $this->eggInventory->updateFarmerEggInventory($id, $data);

        return $this->respond($result);
    }

    public function updateFarmerEggPossessions()
    {
        $data = $this->request->getJSON();

        $result = $this->eggInventory->updateFarmerEggPossessions($data);

        return $this->respond($result);
    }

    public function updateEggInventoryRecordStatus($id)
    {
        $data = $this->request->getJSON();

        $result = $this->eggInventory->updateEggInventoryRecordStatus($id, $data->recordStatus);

        return $this->respond($result);
    }

    public function deleteEggInventoryRecord($id){
        $result = $this->eggInventory->deleteEggInventoryRecord($id);

        return $this->respond($result);
    }
}

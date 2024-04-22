<?php

namespace App\Models;

use CodeIgniter\Model;

class EggInventoryModel extends Model
{
    protected $table = 'egginventories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['farmer_id', 'livestock_type_id', 'total_eggs', 'eggs_for_sale', 'eggs_for_reproduction', 'last_updated', 'record_status'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public function getAllEggInventoryRecords()
    {
        $eggInventories = $this->findAll();
        return $eggInventories;
    }

    public function getEggInventoryRecord($id)
    {
        $eggInventory = $this->find($id);
        return $eggInventory;
    }

    public function getAllFarmerEggInventoryRecords($userId)
    {
        $whereClause = [
            'farmer_id' => $userId,
            'record_status' => 'Accessible'
        ];

        $eggInventories = $this->where($whereClause)->findAll();

        return $eggInventories;
    }

    public function insertNewFarmerEggInventory($data)
    {
        $bind = [
            'farmer_id' => $data->farmerId,
            'livestock_type_id' => $data->livestockTypeId,
            'total_eggs' => $data->totalEggs,
            'eggs_for_sale' => $data->eggsForSale,
            'eggs_for_reproduction' => $data->eggsForReproduction,
        ];

        $result = $this->insert($bind);

        return $result;
    }

    public function updateFarmerEggInventory($id, $data){
        $bind = [
            'farmer_id' => $data->farmerId,
            'livestock_type_id' => $data->livestockTypeId,
            'total_eggs' => $data->totalEggs,
            'eggs_for_sale' => $data->eggsForSale,
            'eggs_for_reproduction' => $data->eggsForReproduction,
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function updateFarmerEggPossessions($data){
        $whereClause = [
            'farmer_id' => $data->farmerId,
            'livestock_type_id' => $data->livestockTypeId
        ];

        $bind = [
            'total_eggs' => $data->totalEggs,
            'eggs_for_sale' => $data->eggsForSale,
            'eggs_for_reproduction' => $data->eggsForReproduction,
        ];

        $result = $this->set($bind)->where($whereClause)->update();

        return $result;
    }

    public function updateEggInventoryRecordStatus($id, $status){
        $bind = [
          'record_status' => $status
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function deleteEggInventory($id){
        $result = $this->delete($id);
        return $result;
    }
}

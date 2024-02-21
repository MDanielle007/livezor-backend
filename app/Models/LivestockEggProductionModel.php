<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockEggProductionModel extends Model
{
    protected $table            = 'livestock_egg_productions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['farmer_id', 'livestock_id', 'eggs_produced', 'additional_egg_prod_notes', 'date_of_production', 'record_status'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getAllEggProductions()
    {
        $eggProductions = $this->findAll();
        return $eggProductions;
    }

    public function getEggProduction($id)
    {
        $eggProduction = $this->find($id);
        return $eggProduction;
    }

    public function getAllFarmerEggProductions($userId)
    {
        $whereClause = [
            'farmer_id' => $userId,
            'record_status' => 'Accessible'
        ];

        $eggProductions = $this->where($whereClause)->findAll();
        return $eggProductions;
    }

    public function insertEggProduction($data)
    {
        $bind = [
            'farmer_id' => $data->farmerId,
            'livestock_id' => $data->livestockId,
            'eggs_produced' => $data->eggsProduced,
            'additional_egg_prod_notes' => $data->additionalEggProdNotes,
            'date_of_production' => $data->dateOfProduction
        ];

        $result = $this->insert($bind);

        return $result;
    }

    public function updateEggProduction($id, $data)
    {
        $bind = [
            'farmer_id' => $data->farmerId,
            'livestock_id' => $data->livestockId,
            'eggs_produced' => $data->eggsProduced,
            'additional_egg_prod_notes' => $data->additionalEggProdNotes,
            'date_of_production' => $data->dateOfProduction
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function updateEggProductionRecordStatus($id, $status){
        $bind = [
            'record_status' => $status,
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function deleteEggProduction($id){
        $result = $this->delete($id);
        return $result;
    }
}

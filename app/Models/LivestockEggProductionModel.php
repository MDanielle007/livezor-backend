<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockEggProductionModel extends Model
{
    protected $table = 'livestock_egg_productions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['farmer_id', 'livestock_id', 'batch_group_id', 'eggs_produced', 'remarks', 'date_of_production', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllEggProductions()
    {
        $eggProductions = $this->select(
            'livestock_egg_productions.id,
            livestock_egg_productions.farmer_id as farmerId,
            user_accounts.user_id as farmerUserId,
            CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName,
            livestock_egg_productions.livestock_id as livestockId,
            livestocks.livestock_tag_id as livestockTagId,
            livestock_types.livestock_type_name as livestockType,
            livestock_egg_productions.batch_group_id as batchGroupId,
            egg_production_batch_group.batch_name as batchGroupName,
            livestock_egg_productions.eggs_produced as eggsProduced,
            livestock_egg_productions.remarks as remarks,
            livestock_egg_productions.date_of_production as dateOfProduction'
        )
        ->join('user_accounts', 'user_accounts.id = livestock_egg_productions.farmer_id')
        ->join('egg_production_batch_group','egg_production_batch_group.id = livestock_egg_productions.batch_group_id')
        ->join('livestocks','livestocks.id = livestock_egg_productions.livestock_id')
        ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
        ->orderBy('livestock_egg_productions.date_of_production', 'DESC')
        ->findAll();
        return $eggProductions;
    }

    public function getEggProduction($id)
    {
        $eggProduction = $this->select(
            'id,
            farmer_id as farmerId,
            livestock_id as livestockId,
            batch_group_id as batchGroupId,
            eggs_produced as eggsProduced,
            remarks as remarks,
            date_of_production as dateOfProduction'
        )->find($id);
        return $eggProduction;
    }

    public function getAllFarmerEggProductions($userId)
    {
        $whereClause = [
            'livestock_egg_productions.farmer_id' => $userId,
            'livestock_egg_productions.record_status' => 'Accessible'
        ];

        $eggProductions = $this->select(
            'livestock_egg_productions.id,
            livestock_egg_productions.livestock_id as livestockId,
            livestocks.livestock_tag_id as livestockTagId,
            livestock_types.livestock_type_name as livestockType,
            livestock_egg_productions.batch_group_id as batchGroupId,
            egg_production_batch_group.batch_name as batchGroupName,
            livestock_egg_productions.eggs_produced as eggsProduced,
            livestock_egg_productions.remarks as remarks,
            livestock_egg_productions.date_of_production as dateOfProduction'
        )
        ->join('user_accounts', 'user_accounts.id = livestock_egg_productions.farmer_id')
        ->join('egg_production_batch_group','egg_production_batch_group.id = livestock_egg_productions.batch_group_id')
        ->join('livestocks','livestocks.id = livestock_egg_productions.livestock_id')
        ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
        ->where($whereClause)
        ->orderBy('livestock_egg_productions.date_of_production', 'DESC')
        ->findAll();
        return $eggProductions;
    }

    public function insertEggProduction($data)
    {
        $bind = [
            'farmer_id' => $data->farmerId,
            'livestock_id' => $data->livestockId,
            'eggs_produced' => $data->eggsProduced,
            'remarks' => $data->remarks,
            'date_of_production' => $data->dateOfProduction
        ];

        $result = $this->insert($bind);

        return $result;
    }

    public function insertEggProductionWithBatch($data)
    {
        $bind = [
            'farmer_id' => $data->farmerId,
            'livestock_id' => $data->livestockId,
            'batch_group_id' => $data->batchGroupId,
            'eggs_produced' => $data->eggsProduced,
            'remarks' => $data->remarks,
            'date_of_production' => $data->dateOfProduction
        ];

        $result = $this->insert($bind);

        return $result;
    }

    public function setEggProductionBatch($id, $batchId){
        $bind = [
            'batch_group_id' => $batchId
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function updateEggProduction($id, $data)
    {
        $bind = [
            'farmer_id' => $data->farmerId,
            'livestock_id' => $data->livestockId,
            'eggs_produced' => $data->eggsProduced,
            'remarks' => $data->remarks,
            'date_of_production' => $data->dateOfProduction
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function updateEggProductionRecordStatus($id, $status)
    {
        $bind = [
            'record_status' => $status,
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function deleteEggProduction($id)
    {
        $result = $this->delete($id);
        return $result;
    }

    public function getEggProductionCountByMonth()
    {
        // Get the current year
        $currentYear = date('Y');

        // Build the query
        $this->select('MONTH(date_of_production) AS month, SUM(eggs_produced) as count')
            ->where("YEAR(date_of_production)", $currentYear)
            ->groupBy('MONTH(date_of_production)')
            ->orderBy('MONTH(date_of_production)');

        // Execute the query and return the result
        return $this->get()->getResult();
    }

    public function getCurrentYearEggProductionCount()
    {
        try {
            $whereClause = [
                'YEAR(date_of_production)' => date('Y'),
            ];
    
            $livestockVaccinationCount = $this->select('SUM(eggs_produced) as count')->where($whereClause)->get()->getResult();
    
            // Check if any result is returned
            if (!empty($livestockVaccinationCount)) {
                // Access the first element of the result array and retrieve the 'count' property
                return $livestockVaccinationCount[0]->count;
            } else {
                // If no result is found, return 0 or handle accordingly
                return 0;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getTopPoultryTypeEggProducedCount()
    {
        try {
            $whereClause = [
                'livestock_egg_productions.record_status' => 'Accessible'
            ];

            $poultryEggProduction = $this->select('
                livestock_types.livestock_type_name as poultryType,
                SUM(livestock_egg_productions.eggs_produced) as count
            ')
                ->join('livestocks', 'livestocks.id = livestock_egg_productions.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->where($whereClause)
                ->groupBy('livestock_types.livestock_type_name')
                ->orderBy('count', 'DESC')
                ->findAll();

            return $poultryEggProduction;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}

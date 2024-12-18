<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockEggProductionModel extends Model
{
    protected $table = 'livestock_egg_productions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
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
            ->join('egg_production_batch_group', 'egg_production_batch_group.id = livestock_egg_productions.batch_group_id')
            ->join('livestocks', 'livestocks.id = livestock_egg_productions.livestock_id')
            ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
            ->orderBy('livestock_egg_productions.date_of_production', 'DESC')
            ->findAll();
        return $eggProductions;
    }

    public function getReportData($selectClause, $minDate, $maxDate)
    {
        try {
            $whereClause = [
                'livestock_egg_productions.record_status' => 'Accessible',
                'livestock_egg_productions.date_of_production >=' => $minDate,
                'livestock_egg_productions.date_of_production <=' => $maxDate
            ];

            $data = $this->select($selectClause)
                ->join('user_accounts', 'user_accounts.id = livestock_egg_productions.farmer_id')
                ->join('egg_production_batch_group', 'egg_production_batch_group.id = livestock_egg_productions.batch_group_id')
                ->join('livestocks', 'livestocks.id = livestock_egg_productions.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->where($whereClause)
                ->orderBy('livestock_egg_productions.date_of_production', 'DESC')
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    public function getEggProductionsForReport($minDate, $maxDate)
    {
        try {
            $whereClause = [
                'livestock_egg_productions.record_status' => 'Accessible',
                'livestock_egg_productions.date_of_production >=' => $minDate,
                'livestock_egg_productions.date_of_production <=' => $maxDate
            ];

            $data = $this->select('
                COALESCE(NULLIF(livestocks.livestock_tag_id, ""), "Untagged") as livestockTagId,
                livestock_types.livestock_type_name as livestockTypeName,
                user_accounts.user_id as farmerUserId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName,
                CONCAT_WS(", ", user_accounts.sitio, user_accounts.barangay, user_accounts.city, user_accounts.province) as fullAddress,
                egg_production_batch_group.batch_name as batchGroupName,
                livestock_egg_productions.eggs_produced as eggsProduced,
                livestock_egg_productions.remarks,
                livestock_egg_productions.date_of_production as dateOfProduction
            ')
                ->join('user_accounts', 'user_accounts.id = livestock_egg_productions.farmer_id')
                ->join('egg_production_batch_group', 'egg_production_batch_group.id = livestock_egg_productions.batch_group_id')
                ->join('livestocks', 'livestocks.id = livestock_egg_productions.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->where($whereClause)
                ->orderBy('livestock_egg_productions.date_of_production', 'DESC')
                ->orderBy('user_accounts.user_id', 'ASC')
                ->orderBy('farmerName', 'ASC')
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->orderBy('egg_production_batch_group.batch_name', 'ASC')
                ->orderBy('livestock_egg_productions.eggs_produced', 'ASC')
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getEggProduction($id)
    {
        try {
            $eggProduction = $this->select('
            livestock_egg_productions.id,
            livestock_egg_productions.farmer_id as farmerId,
            CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName,
            livestock_egg_productions.livestock_id as livestockId,
            livestock_egg_productions.batch_group_id as batchGroupId,
            egg_production_batch_group.batch_name as batchGroupName,
            livestock_egg_productions.eggs_produced as eggsProduced,
            livestock_egg_productions.remarks as remarks,
            livestock_egg_productions.date_of_production as dateOfProduction
        ')
                ->join('user_accounts', 'user_accounts.id = livestock_egg_productions.farmer_id')
                ->join('egg_production_batch_group', 'egg_production_batch_group.id = livestock_egg_productions.batch_group_id')
                ->find($id);
            return $eggProduction;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
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
            ->join('egg_production_batch_group', 'egg_production_batch_group.id = livestock_egg_productions.batch_group_id')
            ->join('livestocks', 'livestocks.id = livestock_egg_productions.livestock_id')
            ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
            ->where($whereClause)
            ->orderBy('livestock_egg_productions.date_of_production', 'DESC')
            ->findAll();
        return $eggProductions;
    }

    public function insertEggProduction($data)
    {
        try {
            $bind = [
                'farmer_id' => $data->farmerId,
                'livestock_id' => $data->livestockId,
                'eggs_produced' => $data->eggsProduced,
                'remarks' => $data->remarks,
                'date_of_production' => $data->dateOfProduction
            ];
            if (isset($data->batchGroupId)) {
                $bind['batch_group_id'] = $data->batchGroupId;
            }

            $result = $this->insert($bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function setEggProductionBatch($id, $batchId)
    {
        $bind = [
            'batch_group_id' => $batchId
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function updateEggProduction($id, $data)
    {
        try {
            $bind = [
                'farmer_id' => $data->farmerId,
                'livestock_id' => $data->livestockId,
                'eggs_produced' => $data->eggsProduced,
                'remarks' => $data->remarks,
                'date_of_production' => $data->dateOfProduction
            ];

            $result = $this->update($id, $bind);

            return $result;
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
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
        try {
            //code...
            $result = $this->delete($id);
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function getEggProductionCountByMonth()
    {
        // // Get the current year
        // $currentYear = date('Y');

        // // Build the query
        // $this->select('MONTH(date_of_production) AS month, SUM(eggs_produced) as count')
        //     ->where("YEAR(date_of_production)", $currentYear)
        //     ->groupBy('MONTH(date_of_production)')
        //     ->orderBy('MONTH(date_of_production)');

        // // Execute the query and return the result
        // return $this->get()->getResult();
        try {
            // Get the current year and month
            $currentYear = date('Y');
            $currentMonth = date('m');

            // Build the query
            $eggProdCounts = [];
            for ($month = 1; $month <= $currentMonth; $month++) {
                $count = $this->select('SUM(eggs_produced) as count')
                    ->where('YEAR(date_of_production)', $currentYear)
                    ->where('MONTH(date_of_production)', $month)
                    ->countAllResults();
                $eggProdCounts[] = [
                    'month' => $month,
                    'count' => $count
                ];
            }

            return $eggProdCounts;
        } catch (\Throwable $th) {
            //throw $th;
        }
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

    public function getEggProductionCount($type = 'yearly')
    {
        try {
            $whereClause = [];

            switch ($type) {
                case 'yearly':
                    $whereClause['YEAR(date_of_production)'] = date('Y');
                    break;
                case 'monthly':
                    $whereClause['YEAR(date_of_production)'] = date('Y');
                    $whereClause['MONTH(date_of_production)'] = date('m'); // 'm' returns numeric month format (01-12)
                    break;
                case 'weekly':
                    $whereClause['YEAR(date_of_production)'] = date('Y');
                    $whereClause['WEEK(date_of_production, 1)'] = date('W'); // 'W' returns ISO-8601 week number of year
                    break;
                case 'daily':
                    $whereClause['YEAR(date_of_production)'] = date('Y');
                    $whereClause['MONTH(date_of_production)'] = date('m');
                    $whereClause['DAY(date_of_production)'] = date('d');
                    break;
                default:
                    throw new \InvalidArgumentException("Invalid type specified.");
            }

            $eggProductionCount = $this->select('SUM(eggs_produced) as count')
                ->where($whereClause)
                ->get()
                ->getResult();

            // Check if any result is returned
            if (!empty($eggProductionCount)) {
                // Access the first element of the result array and retrieve the 'count' property
                return $eggProductionCount[0]->count ?? 0;
            } else {
                // If no result is found, return 0 or handle accordingly
                return 0;
            }
        } catch (\Throwable $th) {
            // Log error details
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return 0;
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

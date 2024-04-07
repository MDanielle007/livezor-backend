<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockMortalityModel extends Model
{
    protected $table = 'livestock_mortalities';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['livestock_id', 'farmer_id', 'cause_of_death', 'mortality_remarks', 'date_of_death', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllLivestockMortalities()
    {
        try {
            $whereClause = [
                'livestock_mortalities.record_status' => 'Accessible'
            ];

            $livestockMortalities = $this->select(
                'livestock_mortalities.id,
                livestock_mortalities.livestock_id as livestockId,
                livestocks.livestock_tag_id as livestockTagId,
                livestock_types.livestock_type_name as livestockType,
                livestock_mortalities.farmer_id as farmerId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName,
                user_accounts.user_id as farmerUserId,
                livestock_mortalities.cause_of_death as causeOfDeath,
                livestock_mortalities.mortality_remarks as remarks,
                livestock_mortalities.date_of_death as dateOfDeath'
            )->join('livestocks', 'livestocks.id = livestock_mortalities.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('user_accounts', 'user_accounts.id = livestock_mortalities.farmer_id')
                ->where($whereClause)
                ->orderBy('livestock_mortalities.date_of_death', 'DESC')
                ->orderBy('livestock_mortalities.cause_of_death', 'ASC')
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->orderBy('livestock_mortalities.created_at', 'DESC')
                ->findAll();
            return $livestockMortalities;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getLivestockMortality($id)
    {
        $whereClause = [
            'livestock_mortalities.record_status' => 'Accessible'
        ];

        $livestockMortality = $this->select(
            'livestock_mortalities.id,
            livestock_mortalities.livestock_id as livestockId,
            livestocks.livestock_tag_id as livestockTagId,
            livestock_types.livestock_type_name as livestockType,
            livestock_mortalities.farmer_id as farmerId,
            CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName,
            livestock_mortalities.cause_of_death as causeOfDeath,
            livestock_mortalities.mortality_remarks as remarks,
            livestock_mortalities.date_of_death as dateOfDeath'
        )->join('livestocks', 'livestocks.id = livestock_mortalities.livestock_id')
            ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
            ->join('user_accounts', 'user_accounts.id = livestock_mortalities.dewormer_id')
            ->where($whereClause)
            ->find($id);
        return $livestockMortality;
    }

    public function getAllFarmerLivestockMortalities($userId)
    {
        $whereClause = [
            'livestock_mortalities.farmer_id' => $userId,
            'livestock_mortalities.record_status' => 'Accessible'
        ];

        $livestockMortalities = $this->select(
            'livestock_mortalities.id,
            livestock_mortalities.livestock_id as livestockId,
            livestocks.livestock_tag_id as livestockTagId,
            livestock_types.livestock_type_name as livestockType,
            livestock_mortalities.cause_of_death as causeOfDeath,
            livestock_mortalities.mortality_remarks as remarks,
            livestock_mortalities.date_of_death as dateOfDeath'
        )
            ->join('livestocks', 'livestocks.id = livestock_mortalities.livestock_id')
            ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
            ->where($whereClause)
            ->orderBy('livestock_mortalities.date_of_death', 'DESC')
            ->orderBy('livestock_mortalities.cause_of_death', 'ASC')
            ->orderBy('livestocks.livestock_tag_id', 'ASC')
            ->orderBy('livestock_mortalities.created_at', 'DESC')
            ->findAll();

        return $livestockMortalities;
    }

    public function getAllCompleteLivestockMortalities()
    {
        $whereClause = [
            'livestock_mortalities.record_status' => 'Accessible'
        ];

        $livestockMortalities = $this->select(
            'livestock_mortalities.id,
            livestock_mortalities.livestock_id as livestockId,
            livestocks.livestock_tag_id as livestockTagId,
            livestocks.livestock_type_id as livestockTypeId,
            livestock_mortalities.farmer_id as farmerId,
            user_accounts.user_id as farmerUserId,
            CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName,
            livestock_mortalities.cause_of_death as causeOfDeath,
            livestock_mortalities.mortality_remarks as remarks,
            livestock_mortalities.date_of_death as dateOfDeath'
        )->join('livestocks', 'livestocks.id = livestock_mortalities.livestock_id')
            ->join('user_accounts', 'user_accounts.id = livestock_mortalities.farmer_id')
            ->where($whereClause)->findAll();
        return $livestockMortalities;
    }

    public function insertLivestockMortality($data)
    {
        $bind = [
            'farmer_id' => $data->farmerId,
            'livestock_id' => $data->livestockId,
            'cause_of_death' => $data->causeOfDeath,
            'mortality_remarks' => $data->remarks,
            'date_of_death' => $data->dateOfDeath,
        ];

        $result = $this->insert($bind);

        return $result;
    }

    public function updateLivestockMortality($id, $data)
    {
        $bind = [
            'farmer_id' => $data->farmerId,
            'livestock_id' => $data->livestockId,
            'cause_of_death' => $data->causeOfDeath,
            'mortality_remarks' => $data->remarks,
            'date_of_death' => $data->dateOfDeath,
        ];
        $result = $this->update($id, $bind);

        return $result;
    }

    public function updateLivestockMortalityRecordStatus($id, $status)
    {
        $bind = [
            'record_status' => $status,
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function deleteLivestockMortality($id)
    {
        $result = $this->delete($id);
        return $result;
    }

    public function getOverallLivestockMortalitiesCount()
    {
        try {
            $livestockMortalitiesCount = $this->where(['record_status' => 'Accessible'])->countAllResults();

            return $livestockMortalitiesCount;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getOverallLivestockMortalitiesCountInCurrentYear()
    {
        try {
            $livestockMortalitiesCount = $this->where(['record_status' => 'Accessible', 'YEAR(date_of_death)' => date('Y')])->countAllResults();

            return $livestockMortalitiesCount;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerOverallLivestockMortalitiesCount($userId)
    {
        try {
            $livestockMortalitiesCount = $this->where(['farmer_id' => $userId, 'record_status' => 'Accessible'])->countAllResults();

            return $livestockMortalitiesCount;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockMortalitiesCountInCurrentMonth()
    {
        try {
            $whereClause = [
                'record_status' => 'Accessible',
                'MONTH(date_of_death)' => date('m'),
                'YEAR(date_of_death)' => date('Y')
            ];

            $livestockMortalitiesCountInCurrentMonth = $this->where($whereClause)->countAllResults();

            return $livestockMortalitiesCountInCurrentMonth;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getLivestockMortalitiesCountLastMonth()
    {
        try {
            // Get the current date
            $currentDate = date('Y-m-d');

            // Calculate the date one month ago
            $lastMonthDate = date('Y-m-d', strtotime('-1 month', strtotime($currentDate)));

            $whereClause = [
                'record_status' => 'Accessible',
                'date_of_death >=' => $lastMonthDate,
                'date_of_death <=' => $currentDate
            ];

            $livestockMortalitiesCount = $this->where($whereClause)->countAllResults();

            return $livestockMortalitiesCount;
        } catch (\Throwable $th) {
            //throw $th;
            // return 0;
        }
    }

    public function getMortalitiesCountLast4Months()
    {
        try {
            $currentMonth = date('F');
            $currentYear = date('Y');

            $months = [];
            for ($i = 3; $i >= 0; $i--) {
                $month = date('F', strtotime("-$i months"));
                $months[] = $month;
            }

            $mortalityCounts = [];
            foreach ($months as $month) {
                $count = $this->select('COUNT(*) as mortalityCount')
                    ->where('MONTH(date_of_death)', date('m', strtotime($month)))
                    ->where('YEAR(date_of_death)', $currentYear)
                    ->get()
                    ->getRowArray();

                $mortalityCounts[] = [
                    'month' => $month,
                    'mortalityCount' => $count['mortalityCount'] ?? 0,
                ];
            }

            return $mortalityCounts;
        } catch (\Throwable $th) {
            // Handle exceptions
            return [];
        }
    }

    public function getTopMortalityCause()
    {
        try {
            $whereClause = [
                'record_status' => 'Accessible',
                'YEAR(date_of_death)' => date('Y')
            ];

            $this->select('cause_of_death as name, COUNT(*) as value')
                ->where($whereClause)
                ->groupBy('cause_of_death')
                ->orderBy('value', 'DESC')
                ->orderBy('cause_of_death', 'ASC')
                ->limit(10);

            return $this->get()->getResult();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getMortalityCountByMonth()
    {
        try {
            // Get the current year
            $currentYear = date('Y');

            // Build the query
            $this->select('MONTH(date_of_death) AS month, COUNT(*) AS count')
                ->where("YEAR(date_of_death)", $currentYear)
                ->groupBy('MONTH(date_of_death)')
                ->orderBy('MONTH(date_of_death)');

            // Execute the query and return the result
            return $this->get()->getResult();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockTypeMortalityCount()
    {
        try {
            $whereClause = [
                'livestock_mortalities.record_status' => 'Accessible',
                'YEAR(livestock_mortalities.date_of_death)' => date('Y')
            ];

            $this->select('livestock_types.livestock_type_name as name, COUNT(*) as value')
                ->join('livestocks', 'livestocks.id = livestock_mortalities.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->where($whereClause)
                ->groupBy('livestock_types.livestock_type_name')
                ->orderBy('value', 'DESC')
                ->orderBy('livestock_types.livestock_type_name', 'ASC')
                ->limit(10);

            return $this->get()->getResult();
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }
}

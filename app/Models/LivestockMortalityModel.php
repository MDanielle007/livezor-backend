<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockMortalityModel extends Model
{
    protected $table = 'livestock_mortalities';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
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
            // Get the current year and month
            $currentYear = date('Y');
            $currentMonth = date('m');

            // Build the query
            $mortalityCounts = [];
            for ($month = 1; $month <= $currentMonth; $month++) {
                $count = $this->select('COUNT(*) AS count')
                    ->where('YEAR(date_of_death)', $currentYear)
                    ->where('MONTH(date_of_death)', $month)
                    ->countAllResults();
                $mortalityCounts[] = [
                    'month' => $month,
                    'count' => $count
                ];
            }

            return $mortalityCounts;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockMortalityCountByMonthTimeSeries()
    {
        try {
            // Get the earliest and latest dates
            $earliestDate = $this->select('MIN(date_of_death) as earliest_date')->first();
            $latestDate = $this->select('MAX(date_of_death) as latest_date')->first();

            if (!$earliestDate['earliest_date'] || !$latestDate['latest_date']) {
                return []; // No records found
            }

            $startDate = new \DateTime($earliestDate['earliest_date']);
            $endDate = new \DateTime($latestDate['latest_date']);
            // Include the end month in the period
            $endDate->modify('first day of next month');

            // Get the monthly counts from the database
            $dbResults = $this->select('YEAR(livestock_mortalities.date_of_death) as year, MONTH(livestock_mortalities.date_of_death) as month, COUNT(*) as count')
                ->join('livestocks', 'livestocks.id = livestock_mortalities.livestock_id')
                ->where(['livestocks.category' => 'Livestock'])
                ->groupBy('YEAR(livestock_mortalities.date_of_death), MONTH(livestock_mortalities.date_of_death)')
                ->orderBy('YEAR(livestock_mortalities.date_of_death)', 'ASC')
                ->orderBy('MONTH(livestock_mortalities.date_of_death)', 'ASC')
                ->findAll();

            // Create a complete list of months between start and end dates
            $completeResults = [];
            $interval = new \DateInterval('P1M');
            $period = new \DatePeriod($startDate, $interval, $endDate);

            foreach ($period as $dt) {
                $year = $dt->format('Y');
                $month = $dt->format('n'); // Use 'n' to avoid leading zeros
                $completeResults["$year-$month"] = [
                    'year' => (int) $year,
                    'month' => (int) $month,
                    'count' => 0
                ];
            }

            // Merge the database results with the complete list
            foreach ($dbResults as $result) {
                $key = "{$result['year']}-{$result['month']}";
                $completeResults[$key]['count'] = (int) $result['count'];
            }

            // Re-index array to be sequential
            return array_values($completeResults);
        } catch (\Throwable $th) {
            // Log the exception or handle it as needed
            log_message('error', $th->getMessage());
            return [];
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

    public function getReportData($selectClause, $minDate, $maxDate)
    {
        try {
            $whereClause = [
                'livestocks.category' => 'Livestock',
                'livestock_mortalities.record_status' => 'Accessible',
                'livestock_mortalities.date_of_death >=' => $minDate,
                'livestock_mortalities.date_of_death <=' => $maxDate
            ];

            $data = $this->select($selectClause)
                ->join('livestocks', 'livestocks.id = livestock_mortalities.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('livestock_breeds', 'livestock_breeds.id = livestocks.livestock_breed_id')
                ->join('livestock_age_class', 'livestock_age_class.id = livestocks.livestock_age_class_id')
                ->join('user_accounts', 'user_accounts.id = livestock_mortalities.farmer_id')
                ->where($whereClause)
                ->orderBy('livestock_mortalities.date_of_death', 'DESC')
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getMortalitiesForReport( $minDate, $maxDate)
    {
        try {
            $whereClause = [
                'livestock_mortalities.record_status' => 'Accessible',
                'livestock_mortalities.date_of_death >=' => $minDate,
                'livestock_mortalities.date_of_death <=' => $maxDate
            ];

            $data = $this
                ->select('
                    livestocks.livestock_tag_id as livestockTagId,
                    livestock_types.livestock_type_name as livestockType,
                    COALESCE(NULLIF(livestock_breeds.livestock_breed_name, ""), "Unknown") as livestockBreedName,
                    livestock_age_class.livestock_age_classification as livestockAgeClassification,
                    CASE
                        WHEN livestocks.age_years > 0 THEN CONCAT(livestocks.age_years, " years")
                        WHEN livestocks.age_months > 0 THEN CONCAT(livestocks.age_months, " months")
                        WHEN livestocks.age_weeks > 0 THEN CONCAT(livestocks.age_weeks, " weeks")
                        WHEN livestocks.age_days > 0 THEN CONCAT(livestocks.age_days, " days")
                        ELSE "Unknown Age"
                    END as age,
                    user_accounts.user_id as farmerUserId,
                    CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName,
                    CONCAT_WS(", ", user_accounts.sitio, user_accounts.barangay, user_accounts.city, user_accounts.province) as fullAddress,
                    livestock_mortalities.cause_of_death as causeOfDeath,
                    livestock_mortalities.mortality_remarks as remarks,
                    livestock_mortalities.date_of_death as dateOfDeath
                ')
                ->join('livestocks', 'livestocks.id = livestock_mortalities.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('livestock_breeds', 'livestock_breeds.id = livestocks.livestock_breed_id')
                ->join('livestock_age_class', 'livestock_age_class.id = livestocks.livestock_age_class_id')
                ->join('user_accounts', 'user_accounts.id = livestock_mortalities.farmer_id')
                ->where($whereClause)
                ->orderBy('livestock_mortalities.date_of_death', 'ASC')
                ->orderBy('farmerUserId', 'ASC')
                ->orderBy('farmerName', 'ASC')
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage());
        }
    }
}

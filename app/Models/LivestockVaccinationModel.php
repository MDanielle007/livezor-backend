<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockVaccinationModel extends Model
{
    protected $table = 'livestock_vaccinations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['vaccine_administrator_id', 'livestock_id', 'vaccination_name', 'vaccination_description', 'vaccination_remarks', 'vaccination_date', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllLivestockVaccinations()
    {
        $whereClause = [
            'livestock_vaccinations.record_status' => 'Accessible',
            'livestocks.category' => 'Livestock'
        ];

        $livestockVaccinations = $this->select(
            'livestock_vaccinations.id,
            livestock_vaccinations.vaccine_administrator_id as vaccineAdministratorId,
            CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as vaccineAdministratorName,
            livestock_vaccinations.livestock_id as livestockId,
            livestocks.livestock_tag_id as livestockTagId,
            livestock_types.livestock_type_name as livestockType,
            livestock_vaccinations.vaccination_name as vaccinationName,
            livestock_vaccinations.vaccination_description as vaccinationDescription,
            livestock_vaccinations.vaccination_remarks as remarks,
            livestock_vaccinations.vaccination_date as vaccinationDate'
        )->join('livestocks', 'livestocks.id = livestock_vaccinations.livestock_id')
            ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
            ->join('user_accounts', 'user_accounts.id = livestock_vaccinations.vaccine_administrator_id')
            ->where($whereClause)
            ->orderBy('livestock_vaccinations.vaccination_date', 'DESC')
            ->orderBy('livestocks.livestock_tag_id', 'ASC')
            ->findAll();
        return $livestockVaccinations;
    }

    public function getAllPoultryVaccinations()
    {
        $whereClause = [
            'livestock_vaccinations.record_status' => 'Accessible',
            'livestocks.category' => 'Poultry'
        ];

        $livestockVaccinations = $this->select(
            'livestock_vaccinations.id,
            livestock_vaccinations.vaccine_administrator_id as vaccineAdministratorId,
            CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as vaccineAdministratorName,
            livestock_vaccinations.livestock_id as livestockId,
            livestocks.livestock_tag_id as livestockTagId,
            livestock_types.livestock_type_name as livestockType,
            livestock_vaccinations.vaccination_name as vaccinationName,
            livestock_vaccinations.vaccination_description as vaccinationDescription,
            livestock_vaccinations.vaccination_remarks as remarks,
            livestock_vaccinations.vaccination_date as vaccinationDate'
        )->join('livestocks', 'livestocks.id = livestock_vaccinations.livestock_id')
            ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
            ->join('user_accounts', 'user_accounts.id = livestock_vaccinations.vaccine_administrator_id')
            ->where($whereClause)
            ->orderBy('livestock_vaccinations.vaccination_date', 'DESC')
            ->orderBy('livestocks.livestock_tag_id', 'ASC')
            ->findAll();
        return $livestockVaccinations;
    }

    public function getReportData($category, $selectClause, $minDate, $maxDate)
    {
        try {
            $whereClause = [
                'livestock_vaccinations.record_status' => 'Accessible',
                'livestocks.category' => $category,
                'livestock_vaccinations.vaccination_date >=' => $minDate,
                'livestock_vaccinations.vaccination_date <=' => $maxDate
            ];

            $data = $this
                ->select($selectClause)
                ->join('livestocks', 'livestocks.id = livestock_vaccinations.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('livestock_breeds', 'livestock_breeds.id = livestocks.livestock_breed_id')
                ->join('livestock_age_class', 'livestock_age_class.id = livestocks.livestock_age_class_id')
                ->join('user_accounts', 'user_accounts.id = livestock_vaccinations.vaccine_administrator_id')
                ->where($whereClause)
                ->orderBy('livestock_vaccinations.vaccination_date', 'DESC')
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockVaccination($id)
    {
        $whereClause = [
            'livestock_vaccinations.record_status' => 'Accessible'
        ];

        $livestockVaccination = $this->select(
            'livestock_vaccinations.id,
            livestock_vaccinations.vaccine_administrator_id as vaccineAdministratorId,
            CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as vaccineAdministratorName,
            livestock_vaccinations.livestock_id as livestockId,
            livestocks.livestock_tag_id as livestockTagId,
            livestock_types.livestock_type_name as livestockType,
            livestock_vaccinations.vaccination_name as vaccinationName,
            livestock_vaccinations.vaccination_description as vaccinationDescription,
            livestock_vaccinations.vaccination_remarks as remarks,
            livestock_vaccinations.vaccination_date as vaccinationDate'
        )->join('livestocks', 'livestocks.id = livestock_vaccinations.livestock_id')
            ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
            ->join('user_accounts', 'user_accounts.id = livestock_vaccinations.vaccine_administrator_id')
            ->where($whereClause)
            ->orderBy('livestocks.livestock_tag_id', 'ASC')
            ->orderBy('livestock_vaccinations.created_at', 'DESC')
            ->find($id);
        return $livestockVaccination;
    }

    public function getLivestockVaccinationByLivestockId($id)
    {
        try {
            $whereClause = [
                'livestock_vaccinations.record_status' => 'Accessible',
                'livestocks.id' => $id
            ];

            $livestockVaccination = $this->select(
                'livestock_vaccinations.id,
                livestock_vaccinations.vaccine_administrator_id as vaccineAdministratorId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as vaccineAdministratorName,
                livestocks.livestock_tag_id as livestockTagId,
                livestock_types.livestock_type_name as livestockType,
                livestock_vaccinations.vaccination_name as vaccinationName,
                livestock_vaccinations.vaccination_description as vaccinationDescription,
                livestock_vaccinations.vaccination_remarks as remarks,
                livestock_vaccinations.vaccination_date as vaccinationDate'
            )->join('livestocks', 'livestocks.id = livestock_vaccinations.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('user_accounts', 'user_accounts.id = livestock_vaccinations.vaccine_administrator_id')
                ->where($whereClause)
                ->orderBy('livestock_vaccinations.created_at', 'DESC')
                ->findAll();
            return $livestockVaccination;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function getAllFarmerLivestockVaccinations($userId)
    {
        $whereClause = [
            'vaccine_administrator_id' => $userId,
            'record_status' => 'Accessible'
        ];

        $livestockVaccinations = $this->select(
            'id,
            vaccine_administrator_id as vaccineAdministratorId,
            livestock_id as livestockId,
            vaccination_name as vaccinationName,
            vaccination_description as vaccinationDescription,
            vaccination_remarks as remarks,
            vaccination_date as vaccinationDate'
        )->where($whereClause)->findAll();
        return $livestockVaccinations;
    }

    public function getAllFarmerCompleteLivestockVaccinations($userId)
    {
        $whereClause = [
            'livestock_vaccinations.vaccine_administrator_id' => $userId,
            'livestock_vaccinations.record_status' => 'Accessible'
        ];

        $livestockVaccinations = $this->select(
            'livestock_vaccinations.id,
            livestock_vaccinations.vaccine_administrator_id as vaccineAdministratorId,
            livestock_vaccinations.livestock_id as livestockId,
            livestocks.livestock_tag_id as livestockTagId,
            livestock_types.livestock_type_name as livestockType,
            livestock_vaccinations.vaccination_name as vaccinationName,
            livestock_vaccinations.vaccination_description as vaccinationDescription,
            livestock_vaccinations.vaccination_remarks as remarks,
            livestock_vaccinations.vaccination_date as vaccinationDate'
        )->join('livestocks', 'livestocks.id = livestock_vaccinations.livestock_id')
            ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
            ->where($whereClause)
            ->orderBy('livestock_vaccinations.created_at', 'DESC')
            ->orderBy('livestocks.livestock_tag_id', 'ASC')
            ->findAll();

        return $livestockVaccinations;
    }

    public function insertLivestockVaccination($data)
    {
        try {
            $bind = [
                'vaccine_administrator_id' => $data->vaccineAdministratorId,
                'livestock_id' => $data->livestockId,
                'vaccination_name' => $data->vaccinationName,
                'vaccination_description' => $data->vaccinationDescription,
                'vaccination_remarks' => $data->remarks,
                'vaccination_date' => $data->vaccinationDate
            ];

            $result = $this->insert($bind);

            return $result;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function updateLivestockVaccination($id, $data)
    {
        $bind = [
            'vaccine_administrator_id' => $data->vaccineAdministratorId,
            'livestock_id' => $data->livestockId,
            'vaccination_name' => $data->vaccinationName,
            'vaccination_description' => $data->vaccinationDescription,
            'vaccination_remarks' => $data->remarks,
            'vaccination_date' => $data->vaccinationDate
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function updateLivestockVaccinationRecordStatus($id, $status)
    {
        $bind = [
            'record_status' => $status,
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function deleteLivestockVaccination($id)
    {
        $result = $this->delete($id);
        return $result;
    }

    public function getOverallLivestockVaccinationCount()
    {
        try {
            $livestockVaccinationCount = $this->where(['record_status' => 'Accessible'])->countAllResults();

            return $livestockVaccinationCount;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerOverallLivestockVaccinationCount($userId)
    {
        try {
            $livestockVaccinationCount = $this->where(['vaccine_administrator_id' => $userId, 'record_status' => 'Accessible'])->countAllResults();

            return $livestockVaccinationCount;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockVaccinationCountInCurrentMonth()
    {
        try {
            $whereClause = [
                'livestock_vaccinations.record_status' => 'Accessible',
                'MONTH(livestock_vaccinations.vaccination_date)' => date('m'),
                'YEAR(livestock_vaccinations.vaccination_date)' => date('Y'),
                'livestocks.livestock_health_status' => 'Alive'
            ];

            $livestockVaccinationCountInCurrentMonth = $this->join('livestocks', 'livestocks.id = livestock_vaccinations.livestock_id')->where($whereClause)->countAllResults();

            return $livestockVaccinationCountInCurrentMonth;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getTopVaccines()
    {
        try {
            $whereClause = [
                'record_status' => 'Accessible',
                'YEAR(vaccination_date)' => date('Y')
            ];

            $this->select('vaccination_name as name, COUNT(*) as value')
                ->where($whereClause)
                ->groupBy('vaccination_name')
                ->orderBy('value', 'DESC')
                ->orderBy('vaccination_name', 'ASC')
                ->limit(10);

            return $this->get()->getResult();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getVaccinationCountByMonth()
    {
        try {
            // Get the current year and month
            $currentYear = date('Y');
            $currentMonth = date('m');

            // Build the query
            $vaccinationCounts = [];
            for ($month = 1; $month <= $currentMonth; $month++) {
                $count = $this->select('COUNT(*) AS count')
                    ->join('livestocks', 'livestocks.id = livestock_vaccinations.livestock_id')
                    ->where('YEAR(livestock_vaccinations.vaccination_date)', $currentYear)
                    ->where('MONTH(livestock_vaccinations.vaccination_date)', $month)
                    ->where('livestocks.category', 'Livestock')
                    ->countAllResults();
                $vaccinationCounts[] = [
                    'month' => $month,
                    'count' => $count
                ];
            }

            return $vaccinationCounts;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getVaccinationAllCountByMonth()
    {
        try {
            // Get the oldest record date
            $oldestRecord = $this->select('MIN(vaccination_date) AS oldest_date')
                ->get()->getRow()->oldest_date;

            if (!$oldestRecord) {
                // If there's no record, return an empty array
                return [];
            }

            // Convert the oldest date to a DateTime object
            $oldestDate = new \DateTime($oldestRecord);
            $currentDate = new \DateTime();

            // Initialize an array to hold the vaccination counts
            $vaccinationCounts = [];

            // Loop through each month from the oldest to the current
            while ($oldestDate <= $currentDate) {
                $year = (int) $oldestDate->format('Y');
                $month = (int) $oldestDate->format('m');

                $count = (int) $this->select('COUNT(*) AS count')
                    ->where('YEAR(vaccination_date)', $year)
                    ->where('MONTH(vaccination_date)', $month)
                    ->countAllResults();

                $vaccinationCounts[] = [
                    'year' => (int) $year,
                    'month' => (int) $month,
                    'count' => (int) $count
                ];

                // Move to the next month
                $oldestDate->modify('+1 month');
            }

            return $vaccinationCounts;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockVaccinationCountByMonthTimeSeries()
    {
        try {
            // Get the earliest and latest dates
            $earliestDate = $this->select('MIN(vaccination_date) as earliest_date')->first();
            $latestDate = $this->select('MAX(vaccination_date) as latest_date')->first();

            if (!$earliestDate['earliest_date'] || !$latestDate['latest_date']) {
                return []; // No records found
            }

            $startDate = new \DateTime($earliestDate['earliest_date']);
            $endDate = new \DateTime($latestDate['latest_date']);

            $endDate->modify('first day of next month');

            // Get the monthly counts from the database
            $dbResults = $this->select('YEAR(livestock_vaccinations.vaccination_date) as year, MONTH(livestock_vaccinations.vaccination_date) as month, COUNT(*) as count')
                ->join('livestocks', 'livestocks.id = livestock_vaccinations.livestock_id')
                ->groupBy('YEAR(livestock_vaccinations.vaccination_date), MONTH(vaccination_date)')
                ->orderBy('YEAR(livestock_vaccinations.vaccination_date)', 'ASC')
                ->orderBy('MONTH(livestock_vaccinations.vaccination_date)', 'ASC')
                ->where(['livestocks.category' => 'Livestock'])
                ->findAll();

            // Create a complete list of months between start and end dates
            $completeResults = [];
            $interval = new \DateInterval('P1M');
            $period = new \DatePeriod($startDate, $interval, $endDate);


            foreach ($period as $dt) {
                $year = $dt->format('Y');
                $month = $dt->format('n');
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
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    public function getLivestockVaccinationCountByMonthTimeSeriesTrial()
    {
        try {
            // Get the earliest and latest dates
            $earliestDate = $this->select('MIN(vaccination_date) as earliest_date')->first();
            $latestDate = $this->select('MAX(vaccination_date) as latest_date')->first();

            if (!$earliestDate['earliest_date'] || !$latestDate['latest_date']) {
                return []; // No records found
            }

            $startDate = new \DateTime($earliestDate['earliest_date']);
            $endDate = new \DateTime($latestDate['latest_date']);

            // Get the monthly counts from the database
            $dbResults = $this->select('YEAR(vaccination_date) as year, MONTH(vaccination_date) as month, COUNT(*) as value')
                ->groupBy('YEAR(vaccination_date), MONTH(vaccination_date)')
                ->orderBy('YEAR(vaccination_date)', 'ASC')
                ->orderBy('MONTH(vaccination_date)', 'ASC')
                ->findAll();

            // Create a complete list of months between start and end dates
            $completeResults = [];
            $interval = new \DateInterval('P1M');
            $period = new \DatePeriod($startDate, $interval, $endDate);

            foreach ($period as $dt) {
                $year = $dt->format('Y');
                $month = $dt->format('n');
                $completeResults["$year-$month"] = [
                    'year' => $year,
                    'month' => $month,
                    'value' => 0
                ];
            }

            // Merge the database results with the complete list
            foreach ($dbResults as $result) {
                $key = "{$result['year']}-{$result['month']}";
                $completeResults[$key]['value'] = $result['value'];
            }

            // Re-index array to be sequential
            return array_values($completeResults);
        } catch (\Throwable $th) {
            // Log the exception or handle it as needed
            log_message('error', $th->getMessage());
            return [];
        }
    }

    public function getPoultryVaccinationCountByMonth()
    {
        // Get the current year and month
        $currentYear = date('Y');
        $currentMonth = date('m');

        // Build the query
        $vaccinationCounts = [];
        for ($month = 1; $month <= $currentMonth; $month++) {
            $count = $this->select('COUNT(*) AS count')
                ->join('livestocks', 'livestocks.id = livestock_vaccinations.livestock_id')
                ->where('YEAR(livestock_vaccinations.vaccination_date)', $currentYear)
                ->where('MONTH(livestock_vaccinations.vaccination_date)', $month)
                ->where('livestocks.category', 'Poultry')
                ->countAllResults();
            $vaccinationCounts[] = [
                'month' => $month,
                'count' => $count
            ];
        }

        return $vaccinationCounts;
    }

    public function getVaccinationCountLast4Months()
    {
        try {
            $currentDate = new \DateTime();

            $data = [];

            for ($i = 0; $i < 4; $i++) {
                $currentDate->modify('-1 month');

                $month = $currentDate->format('n'); // Numeric month
                $year = $currentDate->format('Y'); // Year

                $count = $this->selectCount('id')
                ->where('MONTH(vaccination_date)', $month)
                ->where('YEAR(vaccination_date)', $year)
                ->countAllResults();

                $data[] = [
                    'month' => $currentDate->format('F'),
                    'vaccinationCount' => $count ?? 0,
                ];
            }

            return $data;
        } catch (\Throwable $th) {
            // Handle exceptions
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    public function getVaccinationCountWholeYear()
    {
        try {
            $currentYear = date('Y');

            $vaccinationCounts = [];
            for ($month = 1; $month <= 12; $month++) {
                $count = $this->selectCount('*')
                    ->where('MONTH(vaccination_date)', $month)
                    ->where('YEAR(vaccination_date)', $currentYear)
                    ->countAllResults();

                $monthName = date("F", mktime(0, 0, 0, $month, 1));

                $vaccinationCounts[] = [
                    'month' => $monthName,
                    'count' => $count,
                ];
            }

            return $vaccinationCounts;
        } catch (\Throwable $th) {
            // Handle exceptions
            return [];
        }
    }

    public function getVaccinationCountSelectedYear()
    {
        try {
            $currentYear = date('Y');

            $vaccinationCounts = [];
            for ($month = 1; $month <= 12; $month++) {
                $count = $this->selectCount('*')
                    ->where('MONTH(vaccination_date)', $month)
                    ->where('YEAR(vaccination_date)', $currentYear)
                    ->countAllResults();

                $monthName = date("F", mktime(0, 0, 0, $month, 1));

                $vaccinationCounts[] = [
                    'month' => $monthName,
                    'count' => $count,
                ];
            }

            return $vaccinationCounts;
        } catch (\Throwable $th) {
            // Handle exceptions
            return [];
        }
    }

    public function getVaccinationsForReport($category, $minDate, $maxDate)
    {
        try {
            $whereClause = [
                'livestock_vaccinations.record_status' => 'Accessible',
                'livestocks.category' => $category,
                'livestock_vaccinations.vaccination_date >=' => $minDate,
                'livestock_vaccinations.vaccination_date <=' => $maxDate
            ];

            $data = $this
                ->select('
                    livestocks.livestock_tag_id as livestockTagId,
                    livestock_types.livestock_type_name as livestockType,
                    COALESCE(NULLIF(livestock_breeds.livestock_breed_name, ""), "Unknown") as livestockBreedName,
                    livestock_age_class.livestock_age_classification as livestockAgeClassification,
                    user_accounts.user_id as vaccineAdminUserId,
                    CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as vaccineAdministratorName,
                    CONCAT_WS(", ", user_accounts.sitio, user_accounts.barangay, user_accounts.city, user_accounts.province) as fullAddress,
                    livestock_vaccinations.vaccination_name as vaccinationName,
                    livestock_vaccinations.vaccination_description as vaccinationDescription,
                    livestock_vaccinations.vaccination_remarks as remarks,
                    livestock_vaccinations.vaccination_date as vaccinationDate
                ')
                ->join('livestocks', 'livestocks.id = livestock_vaccinations.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('livestock_breeds', 'livestock_breeds.id = livestocks.livestock_breed_id')
                ->join('livestock_age_class', 'livestock_age_class.id = livestocks.livestock_age_class_id')
                ->join('user_accounts', 'user_accounts.id = livestock_vaccinations.vaccine_administrator_id')
                ->where($whereClause)
                ->orderBy('livestock_vaccinations.vaccination_date', 'ASC')
                ->orderBy('vaccineAdminUserId', 'ASC')
                ->orderBy('vaccineAdministratorName', 'ASC')
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage());
        }
    }
}

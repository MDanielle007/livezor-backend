<?php

namespace App\Models;

use CodeIgniter\Model;

class AnimalParasiteControlModel extends Model
{
    protected $table = 'animal_parasite_controls';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['farmer_id', 'drug_name', 'livestock_id', 'administrator_name', 'parasite_name', 'dosage', 'administration_method', 'remarks', 'next_application_date', 'application_date', 'application_location', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllAnimalParasiteControls($category = 'All')
    {
        try {
            $whereClause = [
                'animal_parasite_controls.record_status' => 'Accessible'
            ];

            if ($category !== 'All' && $category !== null) {
                $whereClause['livestocks.category'] = $category;
            }

            $data = $this->select('
                animal_parasite_controls.id,
                user_accounts.id as farmerId,
                user_accounts.user_id as farmerUserId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName,
                animal_parasite_controls.livestock_id as livestockId,
                livestocks.livestock_tag_id as livestockTagId,
                livestock_types.livestock_type_name as livestockType,
                animal_parasite_controls.drug_name as drugName,
                animal_parasite_controls.administrator_name as administratorName,
                animal_parasite_controls.parasite_name as parasiteName,
                animal_parasite_controls.dosage as dosage,
                animal_parasite_controls.administration_method as administrationMethod,
                animal_parasite_controls.remarks as remarks,
                animal_parasite_controls.next_application_date as nextApplicationDate,
                animal_parasite_controls.application_date as applicationDate,
                animal_parasite_controls.application_location as applicationLocation
            ')
                ->join('livestocks', 'animal_parasite_controls.livestock_id = livestocks.id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('user_accounts', 'user_accounts.id = animal_parasite_controls.farmer_id')
                ->where($whereClause)
                ->orderBy('animal_parasite_controls.application_date', 'DESC')
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function getAllAnimalParasiteControlByAnimalId($id)
    {
        try {
            $whereClause = [
                'animal_parasite_controls.record_status' => 'Accessible',
                'animal_parasite_controls.livestock_id' => $id
            ];

            $data = $this->select('
                animal_parasite_controls.id,
                user_accounts.id as farmerId,
                user_accounts.user_id as farmerUserId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName,
                livestocks.id as livestockId,
                livestocks.livestock_tag_id as livestockTagId,
                livestock_types.livestock_type_name as livestockType,
                animal_parasite_controls.drug_name as drugName,
                animal_parasite_controls.administrator_name as administratorName,
                animal_parasite_controls.parasite_name as parasiteName,
                animal_parasite_controls.dosage as dosage,
                animal_parasite_controls.administration_method as administrationMethod,
                animal_parasite_controls.remarks as remarks,
                animal_parasite_controls.next_application_date as nextApplicationDate,
                animal_parasite_controls.application_date as applicationDate,
                animal_parasite_controls.application_location as applicationLocation
            ')
                ->join('livestocks', 'animal_parasite_controls.livestock_id = livestocks.id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('user_accounts', 'user_accounts.id = animal_parasite_controls.farmer_id')
                ->where($whereClause)
                ->orderBy('animal_parasite_controls.application_date', 'DESC')
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function getAllAnimalParasiteControlByUserId($id)
    {
        try {
            $whereClause = [
                'animal_parasite_controls.record_status' => 'Accessible',
                'animal_parasite_controls.farmer_id' => $id
            ];

            $data = $this->select('
                animal_parasite_controls.id,
                user_accounts.id as farmerId,
                user_accounts.user_id as farmerUserId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName,
                animal_parasite_controls.livestock_id as livestockId,
                livestocks.livestock_tag_id as livestockTagId,
                livestock_types.livestock_type_name as livestockType,
                animal_parasite_controls.drug_name as drugName,
                animal_parasite_controls.administrator_name as administratorName,
                animal_parasite_controls.parasite_name as parasiteName,
                animal_parasite_controls.dosage as dosage,
                animal_parasite_controls.administration_method as administrationMethod,
                animal_parasite_controls.remarks as remarks,
                animal_parasite_controls.next_application_date as nextApplicationDate,
                animal_parasite_controls.application_date as applicationDate,
                animal_parasite_controls.application_location as applicationLocation
            ')
                ->join('livestocks', 'animal_parasite_controls.livestock_id = livestocks.id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('user_accounts', 'user_accounts.id = animal_parasite_controls.farmer_id')
                ->where($whereClause)
                ->orderBy('animal_parasite_controls.application_date', 'DESC')
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function insertAnimalParasiteControl($data)
    {
        try {
            //code...
            $bind = [
                'farmer_id' => $data->farmerId,
                'livestock_id' => $data->livestockId,
                'drug_name' => $data->drugName,
                'administrator_name' => $data->administratorName,
                'parasite_name' => $data->parasiteName,
                'dosage' => $data->dosage,
                'administration_method' => $data->administrationMethod,
                'remarks' => $data->remarks,
                'next_application_date' => $data->nextApplicationDate,
                'application_date' => $data->applicationDate,
                'application_location' => $data->applicationLocation
            ];

            $result = $this->insert($bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function updateAnimalParasiteControl($id, $data)
    {
        try {
            //code...
            $bind = [
                'farmer_id' => $data->farmerId,
                'livestock_id' => $data->livestockId,
                'drug_name' => $data->drugName,
                'administrator_name' => $data->administratorName,
                'parasite_name' => $data->parasiteName,
                'dosage' => $data->dosage,
                'administration_method' => $data->administrationMethod,
                'remarks' => $data->remarks,
                'next_application_date' => $data->nextApplicationDate,
                'application_date' => $data->applicationDate,
                'application_location' => $data->applicationLocation
            ];

            $result = $this->update($id, $bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function deleteAnimalParasiteControl($id)
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

    public function getOverallAnimalParasiteControlCount()
    {
        try {
            //code...

            $count = $this->where(['record_status' => 'Accessible'])->countAllResults();

            return $count;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function getOverallAnimalParasiteControlCountByUserId($userId)
    {
        try {
            //code...

            $count = $this->where(['record_status' => 'Accessible', 'farmer_id' => $userId])->countAllResults();

            return $count;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function getParasiteControlCountLast4Months()
    {
        try {
            $currentDate = new \DateTime();

            $data = [];

            for ($i = 0; $i < 4; $i++) {
                $currentDate->modify('-1 month');

                $month = $currentDate->format('n'); // Numeric month
                $year = $currentDate->format('Y'); // Year

                $count = $this->selectCount('id')
                    ->where('MONTH(application_date)', $month)
                    ->where('YEAR(application_date)', $year)
                    ->countAllResults();

                $data[] = [
                    'month' => $currentDate->format('F'),
                    'parasiteControlCount' => $count ?? 0,
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

    public function getTopAnimalTypeParasiteControlCount()
    {
        try {
            $whereClause = [
                'animal_parasite_controls.record_status' => 'Accessible'
            ];

            $data = $this->select('
                livestock_types.livestock_type_name as livestockType,
                COUNT(*) as count
            ')
                ->join('livestocks', 'livestocks.id = animal_parasite_controls.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->where($whereClause)
                ->groupBy('livestock_types.livestock_type_name')
                ->orderBy('count', 'DESC')
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    public function getAdministrationMethodsCount()
    {
        try {
            $whereClause = [
                'animal_parasite_controls.record_status' => 'Accessible'
            ];

            $livestockDewormings = $this->select('
                animal_parasite_controls.administration_method as administrationMethod,
                COUNT(*) as count
            ')
                ->join('livestocks', 'livestocks.id = animal_parasite_controls.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->where($whereClause)
                ->groupBy('animal_parasite_controls.administration_method')
                ->orderBy('count', 'DESC')
                ->findAll();

            return $livestockDewormings;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    public function getParasiteControlCountByMonth()
    {
        try {
            // Get the current year and month
            $currentYear = date('Y');
            $currentMonth = date('m');

            // Build the query
            $data = [];
            for ($month = 1; $month <= $currentMonth; $month++) {
                $count = $this->select('COUNT(*) AS count')
                    ->join('livestocks', 'livestocks.id = animal_parasite_controls.livestock_id')
                    ->where('YEAR(animal_parasite_controls.application_date)', $currentYear)
                    ->where('MONTH(animal_parasite_controls.application_date)', $month)
                    ->where('livestocks.category', 'Livestock')
                    ->countAllResults();
                $data[] = [
                    'month' => $month,
                    'count' => $count
                ];
            }

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    public function getLivestockDewormingsCountByMonthTimeSeries()
    {
        try {
            // Get the earliest and latest dates
            $earliestDate = $this->select('MIN(application_date) as earliest_date')->first();
            $latestDate = $this->select('MAX(application_date) as latest_date')->first();

            if (!$earliestDate['earliest_date'] || !$latestDate['latest_date']) {
                return []; // No records found
            }

            $startDate = new \DateTime($earliestDate['earliest_date']);
            $endDate = new \DateTime($latestDate['latest_date']);
            // Include the end month in the period
            $endDate->modify('first day of next month');

            // Get the monthly counts from the database
            $dbResults = $this->select('YEAR(animal_parasite_controls.application_date) as year, MONTH(animal_parasite_controls.application_date) as month, COUNT(*) as count')
                ->join('livestocks', 'livestocks.id = animal_parasite_controls.livestock_id')
                ->where(['livestocks.category' => 'Livestock'])
                ->groupBy('YEAR(animal_parasite_controls.application_date), MONTH(application_date)')
                ->orderBy('YEAR(animal_parasite_controls.application_date)', 'ASC')
                ->orderBy('MONTH(animal_parasite_controls.application_date)', 'ASC')
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
            log_message('error', $th->getMessage());
            return [];
        }
    }

    public function getParasiteControlForReport($minDate, $maxDate, $category)
    {
        try {
            $whereClause = [
                'animal_parasite_controls.record_status' => 'Accessible',
                'livestocks.category' => $category,
                'animal_parasite_controls.application_date >=' => $minDate,
                'animal_parasite_controls.application_date <=' => $maxDate
            ];

            $data = $this
                ->select('
                    livestocks.livestock_tag_id as livestockTagId,
                    livestock_types.livestock_type_name as livestockType,
                    COALESCE(NULLIF(livestock_breeds.livestock_breed_name, ""), "Unknown") as livestockBreedName,
                    livestock_age_class.livestock_age_classification as livestockAgeClassification,
                    user_accounts.user_id as farmerUserId,
                    CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName,
                    CONCAT_WS(", ", user_accounts.sitio, user_accounts.barangay, user_accounts.city, user_accounts.province) as fullAddress,
                    animal_parasite_controls.drug_name as drugName,
                    animal_parasite_controls.parasite_name as parasiteName,
                    animal_parasite_controls.dosage,
                    animal_parasite_controls.administration_method as administrationMethod,
                    animal_parasite_controls.remarks as remarks,
                    animal_parasite_controls.next_application_date as nextApplicationDate,
                    animal_parasite_controls.application_date as applicationDate,
                    animal_parasite_controls.application_location as applicationLocation,
                ')
                ->join('livestocks', 'livestocks.id = animal_parasite_controls.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('livestock_breeds', 'livestock_breeds.id = livestocks.livestock_breed_id')
                ->join('livestock_age_class', 'livestock_age_class.id = livestocks.livestock_age_class_id')
                ->join('user_accounts', 'user_accounts.id = animal_parasite_controls.farmer_id')
                ->where($whereClause)
                ->orderBy('animal_parasite_controls.application_date', 'ASC')
                ->orderBy('farmerUserId', 'ASC')
                ->orderBy('farmerName', 'ASC')
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }
}

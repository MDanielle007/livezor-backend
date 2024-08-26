<?php

namespace App\Models;

use CodeIgniter\Model;

class AnimalSamplesModel extends Model
{
    protected $table = 'animal_samples';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['user_id', 'animal_id', 'sample_type', 'sample_description', 'animal_observation', 'findings', 'sample_date', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllAnimalSample($category = 'All')
    {
        try {
            $whereClause = [
                'animal_samples.record_status' => 'Accessible'
            ];

            if ($category !== 'All' && $category !== null) {
                $whereClause['livestocks.category'] = $category;
            }

            $livestockFecalSamples = $this->select('
                animal_samples.id,
                animal_samples.user_id as userId,
                user_accounts.user_id as userAccountId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as userName,
                animal_samples.animal_id as animalId,
                livestocks.livestock_tag_id as animalTagId,
                livestock_types.livestock_type_name as animalType,
                animal_samples.sample_type as sampleType,
                animal_samples.sample_description as sampleDescription,
                animal_samples.animal_observation as animalObservation,
                animal_samples.findings,
                animal_samples.sample_date as sampleDate
            ')
                ->join('livestocks', 'livestocks.id = animal_samples.animal_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('user_accounts', 'user_accounts.id = animal_samples.user_id')
                ->where($whereClause)
                ->orderBy('animal_samples.sample_date', 'DESC')
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->findAll();

            return $livestockFecalSamples;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    public function getAllAnimalSampleByUser($userId, $category = 'All')
    {
        try {
            $whereClause = [
                'animal_samples.record_status' => 'Accessible',
                'animal_samples.user_id' => $userId
            ];

            if ($category !== 'All') {
                $whereClause['livestocks.category'] = $category;
            }

            $livestockFecalSamples = $this->select('
                animal_samples.id,
                animal_samples.user_id as userId,
                user_accounts.user_id as userAccountId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as userName,
                animal_samples.animal_id as animalId,
                livestocks.livestock_tag_id as animalTagId,
                livestock_types.livestock_type_name as animalType,
                animal_samples.sample_type as sampleType,
                animal_samples.sample_description as sampleDescription,
                animal_samples.animal_observation as animalObservation,
                animal_samples.findings,
                animal_samples.sample_date as sampleDate
            ')
                ->join('livestocks', 'livestocks.id = animal_samples.animal_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('user_accounts', 'user_accounts.id = animal_samples.user_id')
                ->where($whereClause)
                ->orderBy('animal_samples.sample_date', 'DESC')
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->findAll();

            return $livestockFecalSamples;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    public function getAllAnimalSampleByAnimalId($id)
    {
        try {
            $whereClause = [
                'animal_samples.record_status' => 'Accessible',
                'animal_samples.animal_id' => $id
            ];

            $livestockFecalSamples = $this->select('
                animal_samples.id,
                animal_samples.user_id as userId,
                user_accounts.user_id as userAccountId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as userName,
                animal_samples.animal_id as animalId,
                livestocks.livestock_tag_id as animalTagId,
                livestock_types.livestock_type_name as animalType,
                animal_samples.sample_type as sampleType,
                animal_samples.sample_description as sampleDescription,
                animal_samples.animal_observation as animalObservation,
                animal_samples.findings,
                animal_samples.sample_date as sampleDate
            ')
                ->join('livestocks', 'livestocks.id = animal_samples.animal_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('user_accounts', 'user_accounts.id = animal_samples.user_id')
                ->where($whereClause)
                ->orderBy('animal_samples.sample_date', 'DESC')
                ->findAll();

            return $livestockFecalSamples;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    public function insertAnimalSample($data)
    {
        try {
            //code...
            $bind = [
                'user_id' => $data->userId,
                'animal_id' => $data->animalId,
                'sample_type' => $data->sampleType,
                'sample_description' => $data->sampleDescription,
                'animal_observation' => $data->animalObservation,
                'findings' => $data->findings,
                'sample_date' => $data->sampleDate
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

    public function updateAnimalSample($id, $data)
    {
        try {
            //code...
            $bind = [
                'user_id' => $data->userId,
                'animal_id' => $data->animalId,
                'sample_type' => $data->sampleType,
                'sample_description' => $data->sampleDescription,
                'animal_observation' => $data->animalObservation,
                'findings' => $data->findings,
                'sample_date' => $data->sampleDate
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

    public function deleteAnimalSample($id)
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

    public function getOverallAnimalSampleCount()
    {
        try {
            $data = $this
                ->where(['record_status' => 'Accessible'])
                ->countAllResults();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function getUserOverallAnimalSampleCount($userId)
    {
        try {
            $data = $this
                ->where(['record_status' => 'Accessible', 'user_id' => $userId])
                ->countAllResults();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function getTopAnimalObservations()
    {
        try {
            $whereClause = [
                'record_status' => 'Accessible',
                'YEAR(sample_date)' => date('Y'),
                'animal_observation != ' => ''
            ];

            $data = $this->select('animal_observation as name, COUNT(*) as value')
                ->where($whereClause)
                ->groupBy('animal_observation')
                ->orderBy('value', 'DESC')
                ->orderBy('animal_observation', 'ASC')
                ->limit(10)
                ->get()->getResult();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    public function getTopAnimalSampleFindings()
    {
        try {
            $whereClause = [
                'record_status' => 'Accessible',
                'YEAR(sample_date)' => date('Y'),
                'findings != ' => ''
            ];

            $data = $this->select('findings as name, COUNT(*) as value')
                ->where($whereClause)
                ->groupBy('findings')
                ->orderBy('value', 'DESC')
                ->orderBy('findings', 'ASC')
                ->limit(10)
                ->get()->getResult();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    public function getTopAnimalSampleType()
    {
        try {
            $whereClause = [
                'record_status' => 'Accessible',
                'YEAR(sample_date)' => date('Y')
            ];

            $data = $this->select('sample_type as name, COUNT(*) as value')
                ->where($whereClause)
                ->groupBy('sample_type')
                ->orderBy('value', 'DESC')
                ->orderBy('sample_type', 'ASC')
                ->limit(10)
                ->get()->getResult();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    public function getSampleCountLast4Months()
    {
        try {
            $currentDate = new \DateTime();

            $data = [];

            for ($i = 0; $i < 4; $i++) {
                $currentDate->modify('-1 month');

                $month = $currentDate->format('n'); // Numeric month
                $year = $currentDate->format('Y'); // Year

                $count = $this->selectCount('id')
                    ->where('MONTH(sample_date)', $month)
                    ->where('YEAR(sample_date)', $year)
                    ->countAllResults();

                $data[] = [
                    'month' => $currentDate->format('F'),
                    'sampleCount' => $count ?? 0,
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

    public function getAnimalSampleCountByMonth()
    {
        try {
            $currentYear = date('Y');
            $currentMonth = date('m');

            // Build the query
            $data = [];
            for ($month = 1; $month <= $currentMonth; $month++) {
                $count = $this->select('COUNT(*) AS count')
                    ->where('YEAR(sample_date)', $currentYear)
                    ->where('MONTH(sample_date)', $month)
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

    public function getAnimalSamplesForReport($minDate, $maxDate, $category = 'All')
    {
        try {
            $whereClause = [
                'animal_samples.record_status' => 'Accessible',
                'animal_samples.fecal_sample_date >=' => $minDate,
                'animal_samples.fecal_sample_date <=' => $maxDate
            ];

            if ($category !== 'All') {
                $whereClause['livestocks.category'] = $category;
            }

            $data = $this
                ->select('
                    livestocks.livestock_tag_id as animalTagId,
                    livestock_types.livestock_type_name as animalType,
                    livestock_age_class.livestock_age_classification as animalAgeClassification,
                    user_accounts.user_id as farmerUserId,
                    CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName,
                    CONCAT_WS(", ", user_accounts.sitio, user_accounts.barangay, user_accounts.city, user_accounts.province) as fullAddress,
                    animal_samples.sample_type as sampleType,
                    animal_samples.sample_description as sampleDescription,
                    animal_samples.animal_observation as animalObservation,
                    animal_samples.findings,
                    animal_samples.sample_date as sampleDate
                ')
                ->join('livestocks', 'livestocks.id = animal_samples.animal_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('livestock_breeds', 'livestock_breeds.id = livestocks.livestock_breed_id')
                ->join('livestock_age_class', 'livestock_age_class.id = livestocks.livestock_age_class_id')
                ->join('user_accounts', 'user_accounts.id = animal_samples.user_id')
                ->where($whereClause)
                ->orderBy('animal_samples.sample_date', 'ASC')
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

<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockFecalSampleModel extends Model
{
    protected $table = 'livestock_fecal_samples';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['user_id', 'livestock_id', 'livestock_observation', 'findings', 'fecal_sample_date', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllLivestockFecalSample()
    {
        try {
            $whereClause = [
                'livestocks.category' => 'Livestock',
                'livestock_fecal_samples.record_status' => 'Accessible'
            ];

            $livestockFecalSamples = $this->select('
                livestock_fecal_samples.id,
                livestock_fecal_samples.user_id as userId,
                user_accounts.user_id as userAccountId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as userName,
                livestock_fecal_samples.livestock_observation as livestockObservation,
                livestock_fecal_samples.livestock_id as livestockId,
                livestocks.livestock_tag_id as livestockTagId,
                livestock_types.livestock_type_name as livestockType,
                livestock_fecal_samples.findings,
                livestock_fecal_samples.fecal_sample_date as sampleDate
            ')
                ->join('livestocks', 'livestocks.id = livestock_fecal_samples.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('user_accounts', 'user_accounts.id = livestock_fecal_samples.user_id')
                ->where($whereClause)
                ->orderBy('livestock_fecal_samples.fecal_sample_date', 'DESC')
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->findAll();

            return $livestockFecalSamples;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getReportData($selectClause, $minDate, $maxDate)
    {
        try {
            $whereClause = [
                'livestocks.category' => 'Livestock',
                'livestock_fecal_samples.record_status' => 'Accessible',
                'livestock_fecal_samples.fecal_sample_date >=' => $minDate,
                'livestock_fecal_samples.fecal_sample_date <=' => $maxDate
            ];

            $livestockFecalSamples = $this->select($selectClause)
                ->join('livestocks', 'livestocks.id = livestock_fecal_samples.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('livestock_breeds', 'livestock_breeds.id = livestocks.livestock_breed_id')
                ->join('livestock_age_class', 'livestock_age_class.id = livestocks.livestock_age_class_id')
                ->join('user_accounts', 'user_accounts.id = livestock_fecal_samples.user_id')
                ->where($whereClause)
                ->orderBy('livestock_fecal_samples.fecal_sample_date', 'DESC')
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->findAll();

            return $livestockFecalSamples;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllFarmerLivestockFecalSamples($userId)
    {
        try {
            $whereClause = [
                'livestocks.category' => 'Livestock',
                'livestock_fecal_samples.record_status' => 'Accessible',
                'livestock_fecal_samples.user_id' => $userId
            ];

            $livestockFecalSamples = $this->select('
                livestock_fecal_samples.id,
                livestock_fecal_samples.user_id as userId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as userName,
                livestock_fecal_samples.livestock_observation as livestockObservation,
                livestock_fecal_samples.livestock_id as livestockId,
                livestocks.livestock_tag_id as livestockTagId,
                livestock_types.livestock_type_name as livestockType,
                livestock_fecal_samples.findings,
                livestock_fecal_samples.fecal_sample_date as sampleDate
            ')
                ->join('livestocks', 'livestocks.id = livestock_fecal_samples.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('user_accounts', 'user_accounts.id = livestock_fecal_samples.user_id')
                ->where($whereClause)
                ->orderBy('livestock_fecal_samples.fecal_sample_date', 'DESC')
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->findAll();

            return $livestockFecalSamples;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockFecalSample($id)
    {
        try {
            $whereClause = [
                'livestocks.category' => 'Livestock',
                'livestock_fecal_samples.record_status' => 'Accessible'
            ];

            $livestockFecalSample = $this->select('
                livestock_fecal_samples.id,
                livestock_fecal_samples.user_id as userId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as userName,
                livestock_fecal_samples.livestock_id as livestockId,
                livestocks.livestock_tag_id as livestockTagId,
                livestock_types.livestock_type_name as livestockType,
                livestock_fecal_samples.findings,
                livestock_fecal_samples.fecal_sample_date as sampleDate
            ')
                ->join('livestocks', 'livestocks.id = livestock_fecal_samples.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('user_accounts', 'user_accounts.id = livestock_fecal_samples.user_id')
                ->where($whereClause)
                ->find($id);

            return $livestockFecalSample;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockFecalSampleByLivestockId($id)
    {
        try {
            $whereClause = [
                'livestocks.category' => 'Livestock',
                'livestock_fecal_samples.record_status' => 'Accessible',
                'livestocks.id' => $id

            ];

            $livestockFecalSample = $this->select('
                livestock_fecal_samples.id,
                livestock_fecal_samples.user_id as userId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as userName,
                livestock_fecal_samples.livestock_id as livestockId,
                livestocks.livestock_tag_id as livestockTagId,
                livestock_types.livestock_type_name as livestockType,
                livestock_fecal_samples.findings,
                livestock_fecal_samples.fecal_sample_date as sampleDate
            ')
                ->join('livestocks', 'livestocks.id = livestock_fecal_samples.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('user_accounts', 'user_accounts.id = livestock_fecal_samples.user_id')
                ->where($whereClause)
                ->findAll();

            return $livestockFecalSample;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertLivestockFecalSample($data)
    {
        try {
            $bind = [
                'user_id' => $data->userId,
                'livestock_id' => $data->livestockId,
                'livestock_observation' => $data->livestockObservation,
                'findings' => $data->findings,
                'fecal_sample_date' => $data->fecalSampleDate,
            ];

            $result = $this->insert($bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function updateLivestockFecalSample($id, $data)
    {
        try {
            $bind = [
                'user_id' => $data->userId,
                'livestock_id' => $data->livestockId,
                'livestock_observation' => $data->livestockObservation,
                'findings' => $data->findings,
                'fecal_sample_date' => $data->fecalSampleDate,
            ];

            $result = $this->update($id, $bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function updateLivestockFecalSampleRecordStatus($id, $status)
    {
        try {
            $bind = [
                'record_status' => $status
            ];

            $result = $this->update($id, $bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteLivestockFecalSample($id)
    {
        try {
            $result = $this->delete($id);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getOverallLivestockFecalSampleCount()
    {
        try {
            $livestockFecalSampleCount = $this
                ->where(['record_status' => 'Accessible'])
                ->countAllResults();

            return $livestockFecalSampleCount;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerOverallLivestockFecalSampleCount($userId)
    {
        try {
            $livestockFecalSampleCount = $this
                ->where(['record_status' => 'Accessible', 'user_id' => $userId])
                ->countAllResults();

            return $livestockFecalSampleCount;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getRecentLivestockFecalSample()
    {
        $livestockFecalSample = $this->select('
            livestock_fecal_samples.livestock_observation as livestockObservation,
            livestocks.livestock_tag_id as livestockTagId,
            livestock_types.livestock_type_name as livestockType,
            livestock_fecal_samples.findings,
            livestock_fecal_samples.fecal_sample_date as sampleDate
        ')
            ->join('livestocks', 'livestocks.id = livestock_fecal_samples.livestock_id')
            ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
            ->orderBy('livestock_fecal_samples.created_at', 'DESC')
            ->first();

        return $livestockFecalSample;
    }

    public function getTopLivestockObservations()
    {
        try {
            $whereClause = [
                'record_status' => 'Accessible',
                'YEAR(fecal_sample_date)' => date('Y')
            ];

            $livestockObservations = $this->select('livestock_observation as name, COUNT(*) as value')
                ->where($whereClause)
                ->groupBy('livestock_observation')
                ->orderBy('value', 'DESC')
                ->orderBy('livestock_observation', 'ASC')
                ->limit(10)
                ->get()->getResult();

            return $livestockObservations;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getTopFecalSampleFindings()
    {
        try {
            $whereClause = [
                'record_status' => 'Accessible',
                'YEAR(fecal_sample_date)' => date('Y')
            ];

            $findings = $this->select('findings as name, COUNT(*) as value')
                ->where($whereClause)
                ->groupBy('findings')
                ->orderBy('value', 'DESC')
                ->orderBy('findings', 'ASC')
                ->limit(10)
                ->get()->getResult();

            return $findings;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFecalCountLast4Months()
    {
        try {
            $currentDate = new \DateTime();

            $data = [];

            for ($i = 0; $i < 4; $i++) {
                $currentDate->modify('-1 month');

                $month = $currentDate->format('n'); // Numeric month
                $year = $currentDate->format('Y'); // Year

                $count = $this->selectCount('id')
                ->where('MONTH(fecal_sample_date)', $month)
                ->where('YEAR(fecal_sample_date)', $year)
                ->countAllResults();

                $data[] = [
                    'month' => $currentDate->format('F'),
                    'fecalSampleCount' => $count ?? 0,
                ];
            }

            return $data;
        } catch (\Throwable $th) {
            // Handle exceptions
            return [];
        }
    }

    public function getFecalSampleCountByMonth()
    {
        $currentYear = date('Y');
        $currentMonth = date('m');

        // Build the query
        $fecalSampleCounts = [];
        for ($month = 1; $month <= $currentMonth; $month++) {
            $count = $this->select('COUNT(*) AS count')
                ->where('YEAR(fecal_sample_date)', $currentYear)
                ->where('MONTH(fecal_sample_date)', $month)
                ->countAllResults();
            $fecalSampleCounts[] = [
                'month' => $month,
                'count' => $count
            ];
        }

        return $fecalSampleCounts;
    }

    public function getFecalSamplesForReport( $minDate, $maxDate)
    {
        try {
            $whereClause = [
                'livestock_fecal_samples.record_status' => 'Accessible',
                'livestocks.category' => 'Livestock',
                'livestock_fecal_samples.fecal_sample_date >=' => $minDate,
                'livestock_fecal_samples.fecal_sample_date <=' => $maxDate
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
                    livestock_fecal_samples.livestock_observation as livestockObservation,
                    livestock_fecal_samples.findings,
                    livestock_fecal_samples.fecal_sample_date as sampleDate
                ')
                ->join('livestocks', 'livestocks.id = livestock_fecal_samples.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('livestock_breeds', 'livestock_breeds.id = livestocks.livestock_breed_id')
                ->join('livestock_age_class', 'livestock_age_class.id = livestocks.livestock_age_class_id')
                ->join('user_accounts', 'user_accounts.id = livestock_fecal_samples.user_id')
                ->where($whereClause)
                ->orderBy('livestock_fecal_samples.fecal_sample_date', 'ASC')
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

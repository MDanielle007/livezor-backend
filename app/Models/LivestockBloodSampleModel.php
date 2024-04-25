<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockBloodSampleModel extends Model
{
    protected $table            = 'livestock_blood_samples';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'livestock_id', 'livestock_observation', 'findings', 'blood_sample_date', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
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

    public function getAllLivestockBloodSamples(){
        try {
            $whereClause = [
                'livestocks.category' => 'Livestock',
                'livestock_blood_samples.record_status' => 'Accessible'
            ];

            $livestockBloodSamples = $this->select('
                livestock_blood_samples.id,
                livestock_blood_samples.user_id as userId,
                user_accounts.user_id as userAccountId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as userName,
                livestock_blood_samples.livestock_observation as livestockObservation,
                livestock_blood_samples.livestock_id as livestockId,
                livestocks.livestock_tag_id as livestockTagId,
                livestock_types.livestock_type_name as livestockType,
                livestock_blood_samples.findings,
                livestock_blood_samples.blood_sample_date as sampleDate
            ')
                ->join('livestocks', 'livestocks.id = livestock_blood_samples.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('user_accounts', 'user_accounts.id = livestock_blood_samples.user_id')
                ->where($whereClause)
                ->orderBy('livestock_blood_samples.blood_sample_date', 'DESC')
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->findAll();

            return $livestockBloodSamples;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllFarmerLivestockBloodSamples($userId)
    {
        try {
            $whereClause = [
                'livestocks.category' => 'Livestock',
                'livestock_blood_samples.record_status' => 'Accessible',
                'livestock_blood_samples.user_id' => $userId
            ];

            $livestockBloodSamples = $this->select('
                livestock_blood_samples.id,
                livestock_blood_samples.user_id as userId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as userName,
                livestock_blood_samples.livestock_observation as livestockObservation,
                livestock_blood_samples.livestock_id as livestockId,
                livestocks.livestock_tag_id as livestockTagId,
                livestock_types.livestock_type_name as livestockType,
                livestock_blood_samples.findings,
                livestock_blood_samples.blood_sample_date as sampleDate
            ')
                ->join('livestocks', 'livestocks.id = livestock_blood_samples.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('user_accounts', 'user_accounts.id = livestock_blood_samples.user_id')
                ->where($whereClause)
                ->orderBy('livestock_blood_samples.blood_sample_date', 'DESC')
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->findAll();

            return $livestockBloodSamples;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockBloodSample($id)
    {
        try {
            $whereClause = [
                'livestocks.category' => 'Livestock',
                'livestock_blood_samples.record_status' => 'Accessible'
            ];

            $livestockBloodSample = $this->select('
                livestock_blood_samples.id,
                livestock_blood_samples.user_id as userId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as userName,
                livestock_blood_samples.livestock_id as livestockId,
                livestocks.livestock_tag_id as livestockTagId,
                livestock_types.livestock_type_name as livestockType,
                livestock_blood_samples.findings,
                livestock_blood_samples.blood_sample_date as sampleDate
            ')
                ->join('livestocks', 'livestocks.id = livestock_blood_samples.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('user_accounts', 'user_accounts.id = livestock_blood_samples.user_id')
                ->where($whereClause)
                ->find($id);

            return $livestockBloodSample;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertLivestockBloodSample($data)
    {
        try {
            $bind = [
                'user_id' => $data->userId,
                'livestock_id' => $data->livestockId,
                'livestock_observation' => $data->livestockObservation,
                'findings' => $data->findings,
                'blood_sample_date' => $data->bloodSampleDate,
            ];

            $result = $this->insert($bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function updateLivestockBloodSample($id,$data)
    {
        try {
            $bind = [
                'user_id' => $data->userId,
                'livestock_id' => $data->livestockId,
                'livestock_observation' => $data->livestockObservation,
                'findings' => $data->findings,
                'blood_sample_date' => $data->bloodSampleDate,
            ];

            $result = $this->update($id,$bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function updateLivestockBloodSampleRecordStatus($id, $status)
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

    public function deleteLivestockBloodSample($id)
    {
        try {
            $result = $this->delete($id);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    } 
    
    public function getOverallLivestockBloodSampleCount()
    {
        try {
            $livestockBloodSampleCount = $this
                ->where(['record_status' => 'Accessible'])
                ->countAllResults();

            return $livestockBloodSampleCount;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerOverallLivestockBloodSampleCount($userId)
    {
        try {
            $livestockBloodSampleCount = $this
                ->where(['record_status' => 'Accessible', 'user_id' => $userId])
                ->countAllResults();

            return $livestockBloodSampleCount;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getRecentLivestockBloodSample()
    {
        $livestockBloodSample = $this->select('
            livestock_blood_samples.livestock_observation as livestockObservation,
            livestocks.livestock_tag_id as livestockTagId,
            livestock_types.livestock_type_name as livestockType,
            livestock_blood_samples.findings,
            livestock_blood_samples.blood_sample_date as sampleDate
        ')
        ->join('livestocks', 'livestocks.id = livestock_blood_samples.livestock_id')
        ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
        ->orderBy('livestock_blood_samples.created_at', 'DESC')
        ->first();

        return $livestockBloodSample;
    }

    public function getTopLivestockObservations()
    {
        try {
            $whereClause = [
                'record_status' => 'Accessible',
                'YEAR(blood_sample_date)' => date('Y')
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

    public function getTopBloodSampleFindings()
    {
        try {
            $whereClause = [
                'record_status' => 'Accessible',
                'YEAR(blood_sample_date)' => date('Y')
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

    public function getBloodCountLast4Months()
    {
        try {
            $currentYear = date('Y');

            $months = [];
            for ($i = 3; $i >= 0; $i--) {
                $month = date('F', strtotime("-$i months"));
                $months[] = $month;
            }

            $bloodSampleCounts = [];
            foreach ($months as $month) {
                $count = $this->select('COUNT(*) as bloodSampleCount')
                    ->where('MONTH(blood_sample_date)', date('m', strtotime($month)))
                    ->where('YEAR(blood_sample_date)', $currentYear)
                    ->get()
                    ->getRowArray();

                $bloodSampleCounts[] = [
                    'month' => $month,
                    'bloodSampleCount' => $count['bloodSampleCount'] ?? 0,
                ];
            }

            return $bloodSampleCounts;
        } catch (\Throwable $th) {
            // Handle exceptions
            return [];
        }
    }

    public function getBloodSampleCountByMonth()
    {
        // Get the current year
        $currentYear = date('Y');

        // Build the query
        $this->select('MONTH(livestock_blood_samples.blood_sample_date) AS month, COUNT(*) AS count')
            ->join('livestocks', 'livestocks.id = livestock_blood_samples.livestock_id')
            ->where("YEAR(livestock_blood_samples.blood_sample_date)", $currentYear)
            ->where('livestocks.category','Livestock')
            ->groupBy('MONTH(livestock_blood_samples.blood_sample_date)')
            ->orderBy('MONTH(livestock_blood_samples.blood_sample_date)');

        // Execute the query and return the result
        return $this->get()->getResult();
    }
}

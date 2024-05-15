<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockDewormingModel extends Model
{
    protected $table = 'livestock_dewormings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['dewormer_id', 'livestock_id', 'dosage', 'administration_method', 'deworming_remarks', 'next_deworming_date', 'deworming_date', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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


    public function getAllLivestockDewormings()
    {
        try {
            $whereClause = [
                'livestock_dewormings.record_status' => 'Accessible'
            ];

            $livestockDewormings = $this->select('
                livestock_dewormings.id,
                livestock_dewormings.dewormer_id as dewormerId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as dewormerName,
                livestock_dewormings.livestock_id as livestockId,
                livestocks.livestock_tag_id as livestockTagId,
                livestock_types.livestock_type_name as livestockType,
                livestock_dewormings.dosage as dosage,
                livestock_dewormings.administration_method as administrationMethod,
                livestock_dewormings.deworming_remarks as remarks,
                livestock_dewormings.next_deworming_date as nextDewormingDate,
                livestock_dewormings.deworming_date as dewormingDate
            ')
                ->join('livestocks', 'livestocks.id = livestock_dewormings.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('user_accounts', 'user_accounts.id = livestock_dewormings.dewormer_id')
                ->where($whereClause)
                ->orderBy('livestock_dewormings.deworming_date', 'DESC')
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->findAll();

            return $livestockDewormings;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getReportData($selectClause, $minDate, $maxDate)
    {
        try {
            $whereClause = [
                'livestock_dewormings.record_status' => 'Accessible',
                'livestock_dewormings.deworming_date >=' => $minDate,
                'livestock_dewormings.deworming_date <=' => $maxDate
            ];

            $data = $this->select($selectClause)
                ->join('livestocks', 'livestocks.id = livestock_dewormings.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('livestock_breeds', 'livestock_breeds.id = livestocks.livestock_breed_id')
                ->join('livestock_age_class', 'livestock_age_class.id = livestocks.livestock_age_class_id')
                ->join('user_accounts', 'user_accounts.id = livestock_dewormings.dewormer_id')
                ->where($whereClause)
                ->orderBy('livestock_dewormings.deworming_date', 'DESC')
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getLivestockDeworming($id)
    {
        try {
            $whereClause = [
                'livestock_dewormings.record_status' => 'Accessible'
            ];

            $livestockDewormings = $this->select('
                livestock_dewormings.id,
                livestock_dewormings.dewormer_id as dewormerId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as dewormerName,
                livestock_dewormings.livestock_id as livestockId,
                livestocks.livestock_tag_id as livestockTagId,
                livestock_types.livestock_type_name as livestockType,
                livestock_dewormings.dosage as dosage,
                livestock_dewormings.administration_method as administrationMethod,
                livestock_dewormings.deworming_remarks as remarks,
                livestock_dewormings.next_deworming_date as nextDewormingDate,
                livestock_dewormings.deworming_date as dewormingDate
            ')
                ->join('livestocks', 'livestocks.id = livestock_dewormings.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('user_accounts', 'user_accounts.id = livestock_dewormings.dewormer_id')
                ->where($whereClause)
                ->orderBy('livestock_dewormings.created_at', 'DESC')
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->find($id);

            return $livestockDewormings;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllFarmerLivestockDewormings($userId)
    {
        try {
            $whereClause = [
                'livestock_dewormings.record_status' => 'Accessible',
                'livestock_dewormings.dewormer_id' => $userId
            ];

            $livestockDewormings = $this->select('
                livestock_dewormings.id,
                livestock_dewormings.dewormer_id as dewormerId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as dewormerName,
                livestock_dewormings.livestock_id as livestockId,
                livestocks.livestock_tag_id as livestockTagId,
                livestock_types.livestock_type_name as livestockType,
                livestock_dewormings.dosage as dosage,
                livestock_dewormings.administration_method as administrationMethod,
                livestock_dewormings.deworming_remarks as remarks,
                livestock_dewormings.next_deworming_date as nextDewormingDate,
                livestock_dewormings.deworming_date as dewormingDate
            ')
                ->join('livestocks', 'livestocks.id = livestock_dewormings.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('user_accounts', 'user_accounts.id = livestock_dewormings.dewormer_id')
                ->where($whereClause)
                ->orderBy('livestock_dewormings.created_at', 'DESC')
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->findAll();

            return $livestockDewormings;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function insertLivestockDeworming($data)
    {
        try {
            $bind = [
                'dewormer_id' => $data->dewormerId,
                'livestock_id' => $data->livestockId,
                'dosage' => $data->dosage,
                'administration_method' => $data->administrationMethod,
                'deworming_remarks' => $data->remarks,
                'next_deworming_date' => $data->nextDewormingDate,
                'deworming_date' => $data->dewormingDate
            ];

            $result = $this->insert($bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateLivestockDeworming($id, $data)
    {
        try {
            $bind = [
                'dewormer_id' => $data->dewormerId,
                'livestock_id' => $data->livestockId,
                'dosage' => $data->dosage,
                'administration_method' => $data->administrationMethod,
                'deworming_remarks' => $data->remarks,
                'next_deworming_date' => $data->nextDewormingDate,
                'deworming_date' => $data->dewormingDate
            ];

            $result = $this->update($id, $bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateLivestockDewormingRecordStatus($id, $status)
    {
        try {
            $bind = [
                'record_status' => $status,
            ];

            $result = $this->update($id, $bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteLivestockDewormingRecord($id)
    {
        try {
            $result = $this->delete($id);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getOverallLivestockDewormingCount()
    {
        try {
            $livestockDewormingCount = $this->where(['record_status' => 'Accessible'])->countAllResults();

            return $livestockDewormingCount;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerOverallLivestockDewormingCount($userId)
    {
        try {
            $livestockDewormingCount = $this->where(['record_status' => 'Accessible', 'dewormer_id' => $userId])->countAllResults();

            return $livestockDewormingCount;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getDewormingCountLast4Months()
    {
        try {
            $currentMonth = date('F');
            $currentYear = date('Y');

            $months = [];
            for ($i = 3; $i >= 0; $i--) {
                $month = date('F', strtotime("-$i months"));
                $months[] = $month;
            }

            $dewormingCounts = [];
            foreach ($months as $month) {
                $count = $this->select('COUNT(*) as dewormingCount')
                    ->where('MONTH(deworming_date)', date('m', strtotime($month)))
                    ->where('YEAR(deworming_date)', $currentYear)
                    ->get()
                    ->getRowArray();

                $dewormingCounts[] = [
                    'month' => $month,
                    'dewormingCount' => $count['dewormingCount'] ?? 0,
                ];
            }

            return $dewormingCounts;
        } catch (\Throwable $th) {
            // Handle exceptions
            return [];
        }
    }

    public function getTopLivestockTypeDewormedCount()
    {
        try {
            $whereClause = [
                'livestock_dewormings.record_status' => 'Accessible'
            ];

            $livestockDewormings = $this->select('
                livestock_types.livestock_type_name as livestockType,
                COUNT(*) as dewormingCount
            ')
                ->join('livestocks', 'livestocks.id = livestock_dewormings.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->where($whereClause)
                ->groupBy('livestock_types.livestock_type_name')
                ->orderBy('dewormingCount', 'DESC')
                ->findAll();

            return $livestockDewormings;
        } catch (\Throwable $th) {
        }
    }

    public function getAdministrationMethodsCount()
    {
        try {
            $whereClause = [
                'livestock_dewormings.record_status' => 'Accessible'
            ];

            $livestockDewormings = $this->select('
                livestock_dewormings.administration_method as administrationMethod,
                COUNT(*) as dewormingCount
            ')
                ->join('livestocks', 'livestocks.id = livestock_dewormings.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->where($whereClause)
                ->groupBy('livestock_dewormings.administration_method')
                ->orderBy('dewormingCount', 'DESC')
                ->findAll();

            return $livestockDewormings;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getDewormingCountByMonth()
    {
        try {
            // Get the current year
            $currentYear = date('Y');

            // Build the query
            $this->select('MONTH(deworming_date) AS month, COUNT(*) AS count')
                ->where("YEAR(deworming_date)", $currentYear)
                ->groupBy('MONTH(deworming_date)')
                ->orderBy('MONTH(deworming_date)');

            // Execute the query and return the result
            return $this->get()->getResult();
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }


}

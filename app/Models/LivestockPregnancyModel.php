<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockPregnancyModel extends Model
{
    protected $table = 'livestock_pregnancies';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['breeding_id', 'livestock_id', 'outcome', 'pregnancy_start_date', 'expected_delivery_date', 'actual_delivery_date', 'pregnancy_notes', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllLivestockPregnancies()
    {
        try {
            $livestockPregnancies = $this->select('
                livestock_pregnancies.id,
                livestock_pregnancies.breeding_id as breedingId,
                livestock_pregnancies.livestock_id as livestockId,
                livestocks.livestock_tag_id as livestockTagId,
                livestocks.livestock_type_id as livestockTypeId,
                livestock_types.livestock_type_name as livestockTypeName,
                livestock_pregnancies.outcome as outcome,
                livestock_pregnancies.pregnancy_start_date as pregnancyStartDate,
                livestock_pregnancies.expected_delivery_date as expectedDeliveryDate,
                livestock_pregnancies.actual_delivery_date as actualDeliveryDate,
                livestock_breedings.farmer_id as farmerId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName,
                user_accounts.user_id as farmerUserId
            ')->join('livestock_breedings', 'livestock_breedings.id = livestock_pregnancies.breeding_id')
                ->join('user_accounts', 'user_accounts.id = livestock_breedings.farmer_id')
                ->join('livestocks', 'livestocks.id = livestock_pregnancies.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->findAll();
            return $livestockPregnancies;
        } catch (\Throwable $th) {
            //throw $th;

            return $th->getMessage();
        }
    }

    public function getAllLivestockPregnanciesByLivestockId($id)
    {
        try {
            $whereClause = [
                'livestocks.id' => $id,
                'livestock_pregnancies.record_status' => 'Accessible',  
            ];

            $livestockPregnancies = $this->select('
                livestock_pregnancies.id,
                livestocks.livestock_tag_id as livestockTagId,
                livestock_types.livestock_type_name as livestockType,
                livestock_pregnancies.outcome as outcome,
                livestock_pregnancies.pregnancy_start_date as pregnancyStartDate,
                livestock_pregnancies.expected_delivery_date as expectedDeliveryDate,
                livestock_pregnancies.actual_delivery_date as actualDeliveryDate,   
            ')->join('livestock_breedings', 'livestock_breedings.id = livestock_pregnancies.breeding_id')
                ->join('user_accounts', 'user_accounts.id = livestock_breedings.farmer_id')
                ->join('livestocks', 'livestocks.id = livestock_pregnancies.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->where($whereClause)
                ->findAll();
            return $livestockPregnancies;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function getReportData($selectClause, $minDate, $maxDate)
    {
        try {
            $whereClause = [
                'livestock_pregnancies.record_status' => 'Accessible',
                'livestock_pregnancies.pregnancy_start_date >=' => $minDate,
                'livestock_pregnancies.pregnancy_start_date <=' => $maxDate
            ];

            $livestockPregnancies = $this->select($selectClause)
                ->join('livestock_breedings', 'livestock_breedings.id = livestock_pregnancies.breeding_id')
                ->join('user_accounts', 'user_accounts.id = livestock_breedings.farmer_id')
                ->join('livestocks', 'livestocks.id = livestock_pregnancies.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->where($whereClause)
                ->findAll();
            return $livestockPregnancies;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    public function getLivestockPregnancy($id)
    {
        try {
            $livestockPregnancy = $this->select('
                id,
                breeding_id as breedingId,
                livestock_id as livestockId,
                outcome as outcome,
                pregnancy_start_date as pregnancyStartDate,
                expected_delivery_date as expectedDeliveryDate,
                actual_delivery_date as actualDeliveryDate
            ')->find($id);
            return $livestockPregnancy;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllFarmerLivestockPregnancies($userId)
    {
        try {
            $whereClause = [
                'livestock_breedings.farmer_id' => $userId,
                'livestock_pregnancies.record_status' => 'Accessible',
                'livestock_pregnancies.outcome' => 'Pending',
            ];

            $livestockPregnancies = $this->select(
                'livestock_pregnancies.id,
                livestock_pregnancies.breeding_id as breedingId,
                livestock_pregnancies.livestock_id as livestockId,
                livestocks.livestock_tag_id as livestockTagId,
                livestocks.livestock_type_id as livestockTypeId,
                livestock_types.livestock_type_name as livestockTypeName,
                livestock_pregnancies.outcome as outcome,
                livestock_pregnancies.pregnancy_start_date as pregnancyStartDate,
                livestock_pregnancies.expected_delivery_date as expectedDeliveryDate,
                livestock_pregnancies.actual_delivery_date as actualDeliveryDate,'
            )
                ->join('livestock_breedings', 'livestock_breedings.id = livestock_pregnancies.breeding_id')
                ->join('livestocks', 'livestocks.id = livestock_pregnancies.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->where($whereClause)
                ->findAll();

            return $livestockPregnancies;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function insertLivestockPregnancyByBreeding($data)
    {
        try {
            $bind = [
                'breeding_id' => $data->breedingId,
                'livestock_id' => $data->livestockId,
                'pregnancy_start_date' => $data->pregnancyStartDate,
                'expected_delivery_date' => $data->expectedDeliveryDate,
            ];

            if(isset($data->actualDeliveryDate)){
                $bind['actual_delivery_date'] = $data->actualDeliveryDate;
            }

            if (isset($data->pregnancyNotes)) {
                $bind['pregnancy_notes'] = $data->pregnancyNotes;
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

    public function insertLivestockPregnancy($data)
    {
        try {
            $bind = [
                'breeding_id' => $data->breedingId,
                'livestock_id' => $data->livestockId,
                'outcome' => $data->outcome,
                'pregnancy_start_date' => $data->pregnancyStartDate,
                'expected_delivery_date' => $data->expectedDeliveryDate,
                'actual_delivery_date' => $data->actualDeliveryDate,
            ];

            if (isset($data->pregnancyNotes)) {
                $bind['pregnancy_notes'] = $data->pregnancyNotes;
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

    public function updateLivestockPregnancy($id, $data)
    {
        try {
            $bind = [
                'breeding_id' => $data->breedingId,
                'livestock_id' => $data->livestockId,
                'outcome' => $data->outcome,
                'pregnancy_start_date' => $data->pregnancyStartDate,
                'expected_delivery_date' => $data->expectedDeliveryDate,
                'actual_delivery_date' => $data->actualDeliveryDate,
                'pregnancy_notes' => $data->pregnancyNotes,
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

    public function updateLivestockPregnancyOutcome($id, $data)
    {
        try {
            $bind = [
                'outcome' => $data->outcome,
            ];

            $result = $this->update($id, $bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateLivestockPregnancyOutcomeSuccessful($id, $data)
    {
        try {
            $bind = [
                'outcome' => $data->outcome,
                'actual_delivery_date' => $data->actualDeliveryDate,
            ];

            $result = $this->update($id, $bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateLivestockPregnancyRecordStatus($id, $status)
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

    public function deleteLivestockPregnancy($id)
    {
        try {
            $result = $this->delete($id);
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerPregnantLivestockCount($userId)
    {
        try {
            $whereClause = [
                'livestock_breedings.farmer_id' => $userId,
                'livestock_pregnancies.record_status' => 'Accessible',
                'livestock_pregnancies.outcome' => 'Pending',
            ];

            $livestockCount = $this
                ->join('livestock_breedings', 'livestock_breedings.id = livestock_pregnancies.breeding_id')
                ->join('livestocks', 'livestocks.id = livestock_pregnancies.livestock_id')
                ->where($whereClause)->countAllResults();

            return $livestockCount;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getPregnanciesForReport($minDate, $maxDate){
        try {
            $whereClause = [
                'livestock_pregnancies.record_status' => 'Accessible',
                'livestock_pregnancies.pregnancy_start_date >=' => $minDate,
                'livestock_pregnancies.pregnancy_start_date <=' => $maxDate
            ];


            $data = $this
                ->select('
                    user_accounts.user_id as farmerUserId,
                    CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName,
                    CONCAT_WS(", ", user_accounts.sitio, user_accounts.barangay, user_accounts.city, user_accounts.province) as fullAddress,
                    livestock_breedings.male_livestock_tag_id as maleLivestockTagId,
                    livestock_breedings.female_livestock_tag_id as femaleLivestockTagId,
                    livestock_breedings.breeding_result as breedingResult,
                    livestock_types.livestock_type_name as livestockTypeName,
                    livestock_pregnancies.outcome,
                    livestock_pregnancies.pregnancy_start_date as pregnancyStartDate,
                    livestock_pregnancies.expected_delivery_date as expectedDeliveryDate,
                    livestock_pregnancies.actual_delivery_date as actualDeliveryDate
                ')
                ->join('livestock_breedings', 'livestock_breedings.id = livestock_pregnancies.breeding_id')
                ->join('user_accounts', 'user_accounts.id = livestock_breedings.farmer_id')
                ->join('livestocks', 'livestocks.id = livestock_pregnancies.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->where($whereClause)
                ->orderBy('livestock_pregnancies.pregnancy_start_date', 'ASC')
                ->orderBy('livestock_pregnancies.expected_delivery_date', 'ASC')
                ->orderBy('livestock_pregnancies.actual_delivery_date', 'ASC')
                ->orderBy('farmerUserId', 'ASC')
                ->orderBy('farmerName', 'ASC')
                ->orderBy('livestock_breedings.male_livestock_tag_id', 'ASC')
                ->orderBy('livestock_breedings.female_livestock_tag_id', 'ASC')
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage());

        }
    }
}

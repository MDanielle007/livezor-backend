<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockModel extends Model
{
    protected $table = 'livestocks';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['livestock_tag_id', 'livestock_type_id', 'livestock_breed_id', 'livestock_age_class_id', 'age_days', 'age_weeks', 'age_months', 'age_years', 'sex', 'breeding_eligibility', 'date_of_birth', 'livestock_health_status', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllLivestock()
    {
        try {
            $livestocks = $this->select(
                'id,
                livestock_tag_id as livestockTagId,
                livestock_type_id as livestockTypeId,
                livestock_breed_id as livestockBreedId,
                livestock_age_class_id as livestockAgeClassId,
                age_days as ageDays,
                age_weeks as ageWeeks,
                age_months as ageMonths,
                age_years as ageYears,
                sex as sex,
                breeding_eligibility as breedingEligibility,
                date_of_birth as dateOfBirth,
                livestock_health_status as livestockHealthStatus,
                record_status as recordStatus'
            )->findAll();

            return $livestocks;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockById($id)
    {
        try {
            $livestock = $this->select(
                'id,
                livestock_tag_id as livestockTagId,
                livestock_type_id as livestockTypeId,
                livestock_breed_id as livestockBreedId,
                livestock_age_class_id as livestockAgeClassId,
                age_days as ageDays,
                age_weeks as ageWeeks,
                age_months as ageMonths,
                age_years as ageYears,
                sex as sex,
                breeding_eligibility as breedingEligibility,
                date_of_birth as dateOfBirth,
                livestock_health_status as livestockHealthStatus,
                record_status as recordStatus'
            )->find($id);

            return $livestock;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockPrimaryData($id)
    {
        try {
            $livestock = $this->select('
                livestock_tag_id as livestockTagId,
                livestock_type_id as livestockTypeId,
                livestock_age_class_id as livestockAgeClassId,'
            )->find($id);

            return $livestock;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertLivestock($data)
    {
        try {
            $bind = [
                'livestock_type_id' => $data->livestockTypeId,
                'livestock_age_class_id' => $data->livestockAgeClassId,
                'sex' => $data->sex,
                'date_of_birth' => $data->dateOfBirth,
                'age_days' => $data->ageDays,
                'age_weeks' => $data->ageWeeks,
                'age_months' => $data->ageMonths,
                'age_years' => $data->ageYears,
                'breeding_eligibility' => $data->breedingEligibility,
            ];

            if (isset ($data->livestockTagId)) {
                $bind['livestock_tag_id'] = $data->livestockTagId;
            }

            if (isset ($data->livestockBreedId)) {
                $bind['livestock_breed_id'] = $data->livestockBreedId;
            }

            $result = $this->insert($bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function updateLivestock($id, $data)
    {
        try {
            $bind = [
                'livestock_tag_id' => $data->livestockTagId,
                'livestock_type_id' => $data->livestockTypeId,
                'livestock_age_class_id' => $data->livestockAgeClassId,
                'sex' => $data->sex,
                'breeding_eligibility' => $data->breedingEligibility,
                'date_of_birth' => $data->dateOfBirth,
                'livestock_health_status' => $data->livestockHealthStatus,
            ];

            if (isset ($data->livestockBreedId)) {
                $bind['livestock_breed_id'] = $data->livestockBreedId;
            }

            $result = $this->update($id, $bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function deleteLivestock($id)
    {
        $result = $this->delete($id);
        return $result;
    }

    public function updateLivestockHealthStatus($id, $data)
    {
        try {
            $bind = [
                'livestock_health_status' => $data->livestockHealthStatus,
            ];

            $result = $this->update($id, $bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function updateLivestockRecordStatus($id, $status)
    {
        try {
            $bind = [
                'record_status' => $status,
            ];

            $result = $this->update($id, $bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getFarmerAllLivestock($userId)
    {
        try {
            $livestock = [];

            $whereClause = [
                'farmer_livestocks.farmer_id' => $userId,
                'livestocks.livestock_health_status' => 'Alive',
                'livestocks.record_status' => 'Accessible',
                'farmer_livestocks.ownership_status' => 'Owned'
            ];

            $livestock = $this->select(
                'livestocks.id as id, 
                        livestocks.livestock_tag_id as livestockTagId, 
                        livestocks.livestock_type_id as livestockTypeId, 
                        livestock_types.livestock_type_name as livestockType,
                        livestocks.livestock_breed_id as livestockBreedId, 
                        livestock_breeds.livestock_breed_name as livestockBreed,
                        livestocks.livestock_age_class_id as livestockAgeClassId,
                        livestock_age_class.livestock_age_classification as livestockAgeClassification,
                        livestocks.age_days as ageDays,
                        livestocks.age_weeks as ageWeeks,
                        livestocks.age_months as ageMonths,
                        livestocks.age_years as ageYears,
                        livestocks.sex as sex,
                        livestocks.breeding_eligibility as breedingEligibility,
                        livestocks.date_of_birth as dateOfBirth, 
                        livestocks.livestock_health_status as livestockHealthStatus, 
                        farmer_livestocks.acquired_date as acquiredDate, 
                        CASE
                            WHEN livestocks.age_days > 0 THEN CONCAT(livestocks.age_days, " days")
                            WHEN livestocks.age_weeks > 0 THEN CONCAT(livestocks.age_weeks, " weeks")
                            WHEN livestocks.age_months > 0 THEN CONCAT(livestocks.age_months, " months")
                            WHEN livestocks.age_years > 0 THEN CONCAT(livestocks.age_years, " years")
                            ELSE "Unknown Age"
                        END as age'
            )
                ->join('farmer_livestocks', 'livestocks.id = farmer_livestocks.livestock_id')
                ->join('livestock_types','livestock_types.id = livestocks.livestock_type_id')
                ->join('livestock_breeds','livestock_breeds.id = livestocks.livestock_breed_id')
                ->join('livestock_age_class','livestock_age_class.id = livestocks.livestock_age_class_id')
                ->where($whereClause)
                ->findAll();

            return $livestock;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getFarmerEachLivestockTypeCountData($id)
    {
        try {
            $whereClause = [
                'livestocks.record_status' => 'Accessible',
                'farmer_livestocks.farmer_id' => $id
            ];

            $mappingData = $this->select('lt.livestock_type_name AS livestockType, COUNT(*) AS livestockCount')
                ->join('livestock_types lt', 'lt.id = livestocks.livestock_type_id')
                ->join('farmer_livestocks', 'farmer_livestocks.livestock_id = livestocks.id')
                ->groupBy('lt.livestock_type_name')
                ->where($whereClause)
                ->findAll();

            return $mappingData;


        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getFarmerLivestockIdByTag($livestockTagId, $userId)
    {
        $whereClause = [
            'livestocks.livestock_tag_id' => $livestockTagId,
            'farmer_livestocks.farmer_id' => $userId
        ];

        $livestockId = $this->select('livestocks.id')
            ->join('farmer_livestocks', 'farmer_livestocks.livestock_id = livestocks.id')
            ->where($whereClause)->find();

        return $livestockId[0]['id'];
    }

    public function getAllLivestockTypeAgeClassCount()
    {
        try {
            //code...

            $whereClause = [
                'livestocks.livestock_health_status' => 'Alive',
                'livestocks.record_status' => 'Accessible',
            ];

            $data = $this->select('
                livestock_types.livestock_type_name as livestockType,
                livestock_age_class.livestock_age_classification as livestockAgeClass,
                COUNT(livestocks.id) as livestockCount,
            ')->join('farmer_livestocks', 'farmer_livestocks.livestock_id = livestocks.id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('livestock_age_class', 'livestock_age_class.id = livestocks.livestock_age_class_id')
                ->where($whereClause)
                ->groupBy('
                livestock_types.livestock_type_name,
                livestock_age_class.livestock_age_classification
            ')
                ->orderBy('
                livestock_types.livestock_type_name,
                livestock_age_class.livestock_age_classification
            ')->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getFarmerLivestockTypeAgeClassCount($userId)
    {
        try {
            $whereClause = [
                'livestocks.livestock_health_status' => 'Alive',
                'livestocks.record_status' => 'Accessible',
                'farmer_livestocks.farmer_id' => $userId
            ];

            $data = $this->select('
                livestock_types.livestock_type_name as livestockType,
                livestock_age_class.livestock_age_classification as livestockAgeClass,
                COUNT(livestocks.id) as livestockCount
            ')->join('farmer_livestocks', 'farmer_livestocks.livestock_id = livestocks.id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('livestock_age_class', 'livestock_age_class.id = livestocks.livestock_age_class_id')
                ->where($whereClause)
                ->groupBy('
                livestock_types.livestock_type_name,
                livestock_age_class.livestock_age_classification
            ')
                ->orderBy('
                livestock_types.livestock_type_name,
                livestock_age_class.livestock_age_classification
            ')->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getAllLivestockTypeCount()
    {
        try {
            $whereClause = [
                'livestocks.livestock_health_status' => 'Alive',
                'livestocks.record_status' => 'Accessible',
            ];

            $data = $this->select('
                livestock_types.livestock_type_name as livestockType,
                COUNT(livestocks.id) as livestockCount
            ')->join('farmer_livestocks', 'farmer_livestocks.livestock_id = livestocks.id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->where($whereClause)
                ->groupBy('
                livestock_types.livestock_type_name,
            ')
                ->orderBy('
                livestock_types.livestock_type_name,
            ')->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getFarmerLivestockTypeCount($userId)
    {
        try {
            $whereClause = [
                'livestocks.livestock_health_status' => 'Alive',
                'livestocks.record_status' => 'Accessible',
                'farmer_livestocks.farmer_id' => $userId
            ];

            $data = $this->select('
                livestock_types.livestock_type_name as livestockType,
                COUNT(livestocks.id) as livestockCount
            ')->join('farmer_livestocks', 'farmer_livestocks.livestock_id = livestocks.id')
            ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
            ->where($whereClause)
            ->groupBy('
                livestock_types.livestock_type_name
            ')
            ->orderBy('
                livestock_types.livestock_type_name
            ')->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getAllLivestockCount()
    {
        try {
            $whereClause = [
                'livestocks.livestock_health_status' => 'Alive',
                'livestocks.record_status' => 'Accessible',
            ];

            $data = $this->where($whereClause)->countAllResults();

            return $data ?: 0;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getFarmerLivestockCount($userId)
    {
        try {
            $whereClause = [
                'livestocks.livestock_health_status' => 'Alive',
                'livestocks.record_status' => 'Accessible',
                'farmer_livestocks.farmer_id' => $userId
            ];

            $data = $this->join('farmer_livestocks', 'farmer_livestocks.livestock_id = livestocks.id')
                ->where($whereClause)->countAllResults();

            return $data;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockModel extends Model
{
    protected $table = 'livestocks';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['livestock_tag_id', 'livestock_type_id', 'livestock_breed_id', 'livestock_age_class_id', 'category', 'age_days', 'age_weeks', 'age_months', 'age_years', 'sex', 'breeding_eligibility', 'date_of_birth', 'livestock_health_status', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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
            $whereClause = [
                'livestocks.livestock_health_status' => 'Alive',
                'livestocks.record_status' => 'Accessible',
                'livestocks.category' => 'Livestock'
            ];

            $livestocks = $this->select(
                'livestocks.id,
                COALESCE(NULLIF(livestocks.livestock_tag_id, ""), "Untagged") as livestockTagId,
                livestocks.livestock_type_id as livestockTypeId,
                livestock_types.livestock_type_name as livestockTypeName,
                livestocks.livestock_breed_id as livestockBreedId,
                COALESCE(NULLIF(livestock_breeds.livestock_breed_name, ""), "Unknown") as livestockBreedName,
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
                livestocks.record_status as recordStatus,
                CASE
                    WHEN livestocks.age_years > 0 THEN CONCAT(livestocks.age_years, " years")
                    WHEN livestocks.age_months > 0 THEN CONCAT(livestocks.age_months, " months")
                    WHEN livestocks.age_weeks > 0 THEN CONCAT(livestocks.age_weeks, " weeks")
                    WHEN livestocks.age_days > 0 THEN CONCAT(livestocks.age_days, " days")
                    ELSE "Unknown Age"
                END as age,
                user_accounts.id as farmerId,
                user_accounts.user_id as farmerUserId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName
                '
            )
                ->join('farmer_livestocks', 'livestocks.id = farmer_livestocks.livestock_id')
                ->join('user_accounts', 'user_accounts.id = farmer_livestocks.farmer_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('livestock_breeds', 'livestock_breeds.id = livestocks.livestock_breed_id')
                ->join('livestock_age_class', 'livestock_age_class.id = livestocks.livestock_age_class_id')
                ->where($whereClause)
                ->orderBy('user_accounts.first_name', 'ASC')
                ->orderBy('user_accounts.last_name', 'ASC')
                ->orderBy('livestock_types.livestock_type_name', 'ASC')
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->orderBy('livestock_age_class.id', 'ASC')
                ->orderBy('livestocks.date_of_birth', 'DESC')
                ->orderBy('livestocks.created_at', 'DESC')
                ->findAll();

            return $livestocks;
        } catch (\Throwable $th) {
            // Handle exceptions
            return $th->getMessage();
        }
    }

    public function getReportData($category, $selectClause, $minDate, $maxDate)
    {
        try {
            $whereClause = [
                'livestocks.record_status' => 'Accessible',
                'livestocks.category' => $category,
                'livestocks.date_of_birth >=' => $minDate,
                'livestocks.date_of_birth <=' => $maxDate
            ];

            $data = $this
                ->select($selectClause)
                ->join('farmer_livestocks', 'livestocks.id = farmer_livestocks.livestock_id')
                ->join('user_accounts', 'user_accounts.id = farmer_livestocks.farmer_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('livestock_breeds', 'livestock_breeds.id = livestocks.livestock_breed_id')
                ->join('livestock_age_class', 'livestock_age_class.id = livestocks.livestock_age_class_id')
                ->where($whereClause)
                ->orderBy('user_accounts.first_name', 'ASC')
                ->orderBy('user_accounts.last_name', 'ASC')
                ->orderBy('livestock_types.livestock_type_name', 'ASC')
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->orderBy('livestock_age_class.id', 'ASC')
                ->orderBy('livestocks.date_of_birth', 'DESC')
                ->orderBy('livestocks.created_at', 'DESC')
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getAllPoultries()
    {
        try {
            $whereClause = [
                'livestocks.livestock_health_status' => 'Alive',
                'livestocks.record_status' => 'Accessible',
                'livestocks.category' => 'Poultry'
            ];

            $poultries = $this->select(
                'livestocks.id,
                COALESCE(NULLIF(livestocks.livestock_tag_id, ""), "Untagged") as livestockTagId,
                livestocks.livestock_type_id as livestockTypeId,
                livestock_types.livestock_type_name as livestockTypeName,
                livestocks.livestock_breed_id as livestockBreedId,
                COALESCE(NULLIF(livestocks.livestock_breed_id, ""), "Unknown") as livestockBreedName,
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
                livestocks.record_status as recordStatus,
                CASE
                    WHEN livestocks.age_days > 0 THEN CONCAT(livestocks.age_days, " days")
                    WHEN livestocks.age_weeks > 0 THEN CONCAT(livestocks.age_weeks, " weeks")
                    WHEN livestocks.age_months > 0 THEN CONCAT(livestocks.age_months, " months")
                    WHEN livestocks.age_years > 0 THEN CONCAT(livestocks.age_years, " years")
                    ELSE "Unknown Age"
                END as age,
                user_accounts.id as farmerId,
                user_accounts.user_id as farmerUserId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName
                '
            )
                ->join('farmer_livestocks', 'livestocks.id = farmer_livestocks.livestock_id')
                ->join('user_accounts', 'user_accounts.id = farmer_livestocks.farmer_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('livestock_age_class', 'livestock_age_class.id = livestocks.livestock_age_class_id')
                ->where($whereClause)
                ->orderBy('user_accounts.first_name', 'ASC')
                ->orderBy('user_accounts.last_name', 'ASC')
                ->orderBy('livestock_types.livestock_type_name', 'ASC')
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->orderBy('livestock_age_class.id', 'ASC')
                ->orderBy('livestocks.date_of_birth', 'DESC')
                ->orderBy('livestocks.created_at', 'DESC')
                ->findAll();

            return $poultries;
        } catch (\Throwable $th) {
            // Handle exceptions
            return $th->getMessage();
        }
    }


    public function getLivestockById($id)
    {
        try {
            $whereClause = [
                'livestocks.record_status' => 'Accessible',
                'livestock.category' => 'Livestock'
            ];

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
            )->where($whereClause)->find($id);

            return $livestock;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getPoultryById($id)
    {
        try {
            $whereClause = [
                'livestocks.record_status' => 'Accessible',
                'livestock.category' => 'Poultry'
            ];

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
            )->where($whereClause)->find($id);

            return $livestock;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockPrimaryData($id)
    {
        try {
            $whereClause = [
                'livestock_health_status' => 'Alive',
                'record_status' => 'Accessible',
                'category' => 'Livestock'
            ];


            $livestock = $this->select('
                livestock_tag_id as livestockTagId,
                livestock_type_id as livestockTypeId,
                livestock_age_class_id as livestockAgeClassId,'
            )->where($whereClause)->find($id);

            return $livestock;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getPoultryPrimaryData($id)
    {
        try {
            $whereClause = [
                'livestock_health_status' => 'Alive',
                'record_status' => 'Accessible',
                'category' => 'Poultry'
            ];


            $livestock = $this->select('
                livestock_tag_id as livestockTagId,
                livestock_type_id as livestockTypeId,
                livestock_age_class_id as livestockAgeClassId,'
            )->where($whereClause)->find($id);

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
                'breeding_eligibility' => $data->breedingEligibility,
                'category' => $data->category
            ];

            if (isset($data->livestockTagId)) {
                $bind['livestock_tag_id'] = $data->livestockTagId;
            }

            if (isset($data->livestockBreedId)) {
                $bind['livestock_breed_id'] = $data->livestockBreedId;
            }

            if (isset($data->livestockHealthStatus)) {
                $bind['livestock_health_status'] = $data->livestockHealthStatus;
            }

            if (isset($data->origin)) {
                $bind['origin'] = $data->origin;
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
                'category' => $data->category
            ];

            if (isset($data->livestockBreedId)) {
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
            $whereClause = [
                'farmer_livestocks.farmer_id' => $userId,
                'livestocks.livestock_health_status' => 'Alive',
                'livestocks.record_status' => 'Accessible',
                'farmer_livestocks.ownership_status' => 'Owned',
                'livestocks.category' => 'Livestock'
            ];

            $livestocks = $this->select(
                'livestocks.id,
            COALESCE(NULLIF(livestocks.livestock_tag_id, ""), "Untagged") as livestockTagId,
            livestocks.livestock_type_id as livestockTypeId,
            livestock_types.livestock_type_name as livestockTypeName,
            COALESCE(NULLIF(livestocks.livestock_breed_id, ""), "Unknown") as livestockBreedId,
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
            livestocks.record_status as recordStatus,
            CASE
                WHEN livestocks.age_days > 0 THEN CONCAT(livestocks.age_days, " days")
                WHEN livestocks.age_weeks > 0 THEN CONCAT(livestocks.age_weeks, " weeks")
                WHEN livestocks.age_months > 0 THEN CONCAT(livestocks.age_months, " months")
                WHEN livestocks.age_years > 0 THEN CONCAT(livestocks.age_years, " years")
                ELSE "Unknown Age"
            END as age'
            )
                ->join('farmer_livestocks', 'livestocks.id = farmer_livestocks.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('livestock_age_class', 'livestock_age_class.id = livestocks.livestock_age_class_id')
                ->where($whereClause)
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->findAll();

            return $livestocks;
        } catch (\Throwable $th) {
            // Handle exceptions
            return [];
        }
    }

    public function getFarmerAllPoultries($userId)
    {
        try {
            $whereClause = [
                'farmer_livestocks.farmer_id' => $userId,
                'livestocks.livestock_health_status' => 'Alive',
                'livestocks.record_status' => 'Accessible',
                'farmer_livestocks.ownership_status' => 'Owned',
                'livestocks.category' => 'Poultry'
            ];

            $livestocks = $this->select(
                'livestocks.id,
            COALESCE(NULLIF(livestocks.livestock_tag_id, ""), "Untagged") as livestockTagId,
            livestocks.livestock_type_id as livestockTypeId,
            livestock_types.livestock_type_name as livestockTypeName,
            COALESCE(NULLIF(livestocks.livestock_breed_id, ""), "Unknown") as livestockBreedName,
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
            livestocks.record_status as recordStatus,
            CASE
                WHEN livestocks.age_days > 0 THEN CONCAT(livestocks.age_days, " days")
                WHEN livestocks.age_weeks > 0 THEN CONCAT(livestocks.age_weeks, " weeks")
                WHEN livestocks.age_months > 0 THEN CONCAT(livestocks.age_months, " months")
                WHEN livestocks.age_years > 0 THEN CONCAT(livestocks.age_years, " years")
                ELSE "Unknown Age"
            END as age'
            )
                ->join('farmer_livestocks', 'livestocks.id = farmer_livestocks.livestock_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('livestock_age_class', 'livestock_age_class.id = livestocks.livestock_age_class_id')
                ->where($whereClause)
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->findAll();

            return $livestocks;
        } catch (\Throwable $th) {
            // Handle exceptions
            return [];
        }
    }


    public function getFarmerEachLivestockTypeCountData($id)
    {
        try {
            $whereClause = [
                'livestocks.record_status' => 'Accessible',
                'farmer_livestocks.farmer_id' => $id,
                'livestocks.category' => 'Livestock'
            ];

            $mappingData = $this->select('
                lt.livestock_type_name AS livestockType, 
                COUNT(*) AS livestockCount
            ')->join('livestock_types lt', 'lt.id = livestocks.livestock_type_id')
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

    public function getFarmerEachSpecificLivestockTypeCountData($id, $livestockTypeId)
    {
        try {
            $whereClause = [
                'livestocks.record_status' => 'Accessible',
                'farmer_livestocks.farmer_id' => $id,
                'livestocks.category' => 'Livestock',
                'livestocks.livestock_type_id' => $livestockTypeId
            ];

            $mappingData = $this->select('
                lt.livestock_type_name AS livestockType, 
                COUNT(*) AS livestockCount
            ')->join('livestock_types lt', 'lt.id = livestocks.livestock_type_id')
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

    public function getFarmerEachPoultryTypeCountData($id)
    {
        try {
            $whereClause = [
                'livestocks.record_status' => 'Accessible',
                'farmer_livestocks.farmer_id' => $id,
                'livestocks.category' => 'Poultry'
            ];

            $mappingData = $this->select('
                lt.livestock_type_name AS livestockType, 
                COUNT(*) AS livestockCount
            ')->join('livestock_types lt', 'lt.id = livestocks.livestock_type_id')
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

    public function getFarmerLivestockTypeCountDataByCity()
    {
        try {
            $whereClause = [
                'livestocks.record_status' => 'Accessible',
                'livestocks.category' => 'Livestock'
            ];

            $mappingData = $this->select('user_accounts.city ,lt.livestock_type_name AS livestockType, COUNT(*) AS livestockCount')
                ->join('livestock_types lt', 'lt.id = livestocks.livestock_type_id')
                ->join('farmer_livestocks', 'farmer_livestocks.livestock_id = livestocks.id')
                ->join('user_accounts', 'user_accounts.id = farmer_livestocks.farmer_id')
                ->groupBy('user_accounts.city')
                ->groupBy('lt.livestock_type_name')
                ->where($whereClause)
                ->findAll();

            return $mappingData;


        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getFarmerPoultryTypeCountDataByCity()
    {
        try {
            $whereClause = [
                'livestocks.record_status' => 'Accessible',
                'livestocks.category' => 'Poultry'
            ];

            $mappingData = $this->select('user_accounts.city ,lt.livestock_type_name AS livestockType, COUNT(*) AS livestockCount')
                ->join('livestock_types lt', 'lt.id = livestocks.livestock_type_id')
                ->join('farmer_livestocks', 'farmer_livestocks.livestock_id = livestocks.id')
                ->join('user_accounts', 'user_accounts.id = farmer_livestocks.farmer_id')
                ->groupBy('user_accounts.city')
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
            'farmer_livestocks.farmer_id' => $userId,
            'livestocks.category' => 'Livestock'
        ];

        $livestockId = $this->select('livestocks.id')
            ->join('farmer_livestocks', 'farmer_livestocks.livestock_id = livestocks.id')
            ->where($whereClause)
            ->orderBy('livestocks.livestock_tag_id', 'ASC')
            ->find();

        return $livestockId[0]['id'];
    }

    public function getFarmerPoultryIdByTag($livestockTagId, $userId)
    {
        $whereClause = [
            'livestocks.livestock_tag_id' => $livestockTagId,
            'farmer_livestocks.farmer_id' => $userId,
            'livestocks.category' => 'Poultry'
        ];

        $livestockId = $this->select('livestocks.id')
            ->join('farmer_livestocks', 'farmer_livestocks.livestock_id = livestocks.id')
            ->where($whereClause)
            ->orderBy('livestocks.livestock_tag_id', 'ASC')
            ->find();

        return $livestockId[0]['id'];
    }

    public function getLivestockTagIdById($id)
    {
        try {
            $livestockId = $this->select('livestock_tag_id as livestockTagId')
                ->find($id);

            return $livestockId['livestockTagId'];
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getLivestockByVaccination($id)
    {
        try {
            $whereClause = [
                'livestock_vaccinations.id' => $id,
                'livestocks.category' => 'Livestock'
            ];

            $livestock = $this->select('livestocks.id, livestocks.livestock_tag_id')
                ->join('livestock_vaccinations', 'livestocks.id = livestock_vaccinations.livestock_id')
                ->where($whereClause) // Use the parameter $id here
                ->get()
                ->result();

            // Check if any result is found
            if (!empty($livestock)) {
                return $livestock[0]; // Access the id property of the first object
            } else {
                return null; // Or you can return an appropriate value if no result is found
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getPoultryByVaccination($id)
    {
        try {
            $whereClause = [
                'livestock_vaccinations.id' => $id,
                'livestocks.category' => 'Poultry'
            ];

            $livestock = $this->select('livestocks.id, livestocks.livestock_tag_id')
                ->join('livestock_vaccinations', 'livestocks.id = livestock_vaccinations.livestock_id')
                ->where($whereClause) // Use the parameter $id here
                ->get()
                ->result();

            // Check if any result is found
            if (!empty($livestock)) {
                return $livestock[0]; // Access the id property of the first object
            } else {
                return null; // Or you can return an appropriate value if no result is found
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getLivestockByDeworming($id)
    {
        try {
            $whereClause = [
                'livestock_dewormings.id' => $id,
                'livestocks.category' => 'Livestock'
            ];

            $livestock = $this->select('livestocks.id, livestocks.livestock_tag_id')
                ->join('livestock_dewormings', 'livestocks.id = livestock_dewormings.livestock_id')
                ->where($whereClause) // Use the parameter $id here
                ->get()
                ->result();

            // Check if any result is found
            if (!empty($livestock)) {
                return $livestock[0]; // Access the id property of the first object
            } else {
                return null; // Or you can return an appropriate value if no result is found
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getPoultryByDeworming($id)
    {
        try {
            $whereClause = [
                'livestock_dewormings.id' => $id,
                'livestocks.category' => 'Poultry'
            ];

            $livestock = $this->select('livestocks.id, livestocks.livestock_tag_id')
                ->join('livestock_dewormings', 'livestocks.id = livestock_dewormings.livestock_id')
                ->where($whereClause) // Use the parameter $id here
                ->get()
                ->result();

            // Check if any result is found
            if (!empty($livestock)) {
                return $livestock[0]; // Access the id property of the first object
            } else {
                return null; // Or you can return an appropriate value if no result is found
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getLivestockByMortality($id)
    {
        try {
            $whereClause = [
                'livestock_mortalities.id' => $id,
                'livestocks.category' => 'Livestock'
            ];

            $livestock = $this->select('livestocks.id, livestocks.livestock_tag_id')
                ->join('livestock_mortalities', 'livestocks.id = livestock_mortalities.livestock_id')
                ->where($whereClause) // Use the parameter $id here
                ->get()
                ->result();

            // Check if any result is found
            if (!empty($livestock)) {
                return $livestock[0]; // Access the id property of the first object
            } else {
                return null; // Or you can return an appropriate value if no result is found
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getPoultryByMortality($id)
    {
        try {
            $whereClause = [
                'livestock_mortalities.id' => $id,
                'livestocks.category' => 'Poultry'
            ];

            $livestock = $this->select('livestocks.id, livestocks.livestock_tag_id')
                ->join('livestock_mortalities', 'livestocks.id = livestock_mortalities.livestock_id')
                ->where($whereClause) // Use the parameter $id here
                ->get()
                ->result();

            // Check if any result is found
            if (!empty($livestock)) {
                return $livestock[0]; // Access the id property of the first object
            } else {
                return null; // Or you can return an appropriate value if no result is found
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getAllLivestockTypeAgeClassCount()
    {
        try {
            //code...

            $whereClause = [
                'livestocks.livestock_health_status' => 'Alive',
                'livestocks.record_status' => 'Accessible',
                'livestocks.category' => 'Livestock'
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

    public function getAllPoultryTypeAgeClassCount()
    {
        try {
            //code...

            $whereClause = [
                'livestocks.livestock_health_status' => 'Alive',
                'livestocks.record_status' => 'Accessible',
                'livestocks.category' => 'Poultry'
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
                'farmer_livestocks.farmer_id' => $userId,
                'livestocks.category' => 'Livestock'
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

    public function getFarmerPoultryTypeAgeClassCount($userId)
    {
        try {
            $whereClause = [
                'livestocks.livestock_health_status' => 'Alive',
                'livestocks.record_status' => 'Accessible',
                'farmer_livestocks.farmer_id' => $userId,
                'livestocks.category' => 'Poultry'
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
                'livestocks.category' => 'Livestock'
            ];

            $data = $this->select('
                livestock_types.livestock_type_name as livestockType,
                COUNT(livestocks.id) as livestockCount
            ')
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

    public function getAllPoultryTypeCount()
    {
        try {
            $whereClause = [
                'livestocks.livestock_health_status' => 'Alive',
                'livestocks.record_status' => 'Accessible',
                'livestocks.category' => 'Poultry'
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

    public function getFarmerLivestockTypeCount($userId)
    {
        try {
            $whereClause = [
                'livestocks.livestock_health_status' => 'Alive',
                'livestocks.record_status' => 'Accessible',
                'farmer_livestocks.farmer_id' => $userId,
                'livestocks.category' => 'Livestock'
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

    public function getFarmerPoultryTypeCount($userId)
    {
        try {
            $whereClause = [
                'livestocks.livestock_health_status' => 'Alive',
                'livestocks.record_status' => 'Accessible',
                'farmer_livestocks.farmer_id' => $userId,
                'livestocks.category' => 'Poultry'
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
                'livestocks.category' => 'Livestock'
            ];

            $data = $this->where($whereClause)->countAllResults();

            return $data ?: 0;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getAllPoultryCount()
    {
        try {
            $whereClause = [
                'livestocks.livestock_health_status' => 'Alive',
                'livestocks.record_status' => 'Accessible',
                'livestocks.category' => 'Poultry'
            ];

            $data = $this->where($whereClause)->countAllResults();

            return $data ?: 0;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getOverallLivestockCount()
    {
        try {
            $whereClause = [
                'livestocks.record_status' => 'Accessible',
                'livestocks.category' => 'Livestock'
            ];

            $data = $this->where($whereClause)->countAllResults();

            return $data ?: 0;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getOverallPoultryCount()
    {
        try {
            $whereClause = [
                'livestocks.record_status' => 'Accessible',
                'livestocks.category' => 'Poultry'
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
                'farmer_livestocks.farmer_id' => $userId,
                'livestocks.category' => 'Livestock'
            ];

            $data = $this->join('farmer_livestocks', 'farmer_livestocks.livestock_id = livestocks.id')
                ->where($whereClause)->countAllResults();

            return $data;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getFarmerPoultryCount($userId)
    {
        try {
            $whereClause = [
                'livestocks.livestock_health_status' => 'Alive',
                'livestocks.record_status' => 'Accessible',
                'farmer_livestocks.farmer_id' => $userId,
                'livestocks.category' => 'Poultry'
            ];

            $data = $this->join('farmer_livestocks', 'farmer_livestocks.livestock_id = livestocks.id')
                ->where($whereClause)->countAllResults();

            return $data;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getAllFarmerLivestockTagIDs($userId)
    {
        try {
            $whereClause = [
                'livestocks.livestock_health_status' => 'Alive',
                'livestocks.record_status' => 'Accessible',
                'farmer_livestocks.farmer_id' => $userId,
                'livestocks.livestock_tag_id IS NOT NULL' => null, // Condition for not null
                'livestocks.category' => 'Livestock'
            ];

            $data = $this->select('
                livestocks.id,
                livestocks.livestock_tag_id as livestockTagId
            ')
                ->join('farmer_livestocks', 'farmer_livestocks.livestock_id = livestocks.id')
                ->where($whereClause)
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getAllFarmerPoultryTagIDs($userId)
    {
        try {
            $whereClause = [
                'livestocks.livestock_health_status' => 'Alive',
                'livestocks.record_status' => 'Accessible',
                'farmer_livestocks.farmer_id' => $userId,
                'livestocks.livestock_tag_id IS NOT NULL' => null, // Condition for not null
                'livestocks.category' => 'Poultry'
            ];

            $data = $this->select('
                livestocks.id,
                livestocks.livestock_tag_id as livestockTagId
            ')
                ->join('farmer_livestocks', 'farmer_livestocks.livestock_id = livestocks.id')
                ->where($whereClause)
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerDistinctLivestockType($userId)
    {
        try {
            $whereClause = [
                'livestocks.livestock_health_status' => 'Alive',
                'livestocks.record_status' => 'Accessible',
                'farmer_livestocks.farmer_id' => $userId,
                'livestocks.category' => 'Livestock'
            ];

            $data = $this->
                distinct()
                ->select('
                livestock_types.id as id,
                livestock_types.livestock_type_name as livestockTypeName
            ')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('farmer_livestocks', 'farmer_livestocks.livestock_id = livestocks.id')
                ->where($whereClause)->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerDistinctPoultryType($userId)
    {
        try {
            $whereClause = [
                'livestocks.livestock_health_status' => 'Alive',
                'livestocks.record_status' => 'Accessible',
                'farmer_livestocks.farmer_id' => $userId,
                'livestocks.category' => 'Poultry'
            ];

            $data = $this->
                distinct()
                ->select('
                livestock_types.id as id,
                livestock_types.livestock_type_name as livestockTypeName
            ')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('farmer_livestocks', 'farmer_livestocks.livestock_id = livestocks.id')
                ->where($whereClause)->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllFarmerLivestocksBySexAndType($userId)
    {
        try {
            $whereClause = [
                'livestocks.livestock_health_status' => 'Alive',
                'livestocks.breeding_eligibility' => 'Age-Suited',
                'livestocks.record_status' => 'Accessible',
                'farmer_livestocks.farmer_id' => $userId,
                'livestocks.category' => 'Livestock'
            ];

            $data = $this->select('
                livestocks.id,
                livestocks.livestock_tag_id as livestockTagId,
                livestocks.livestock_type_id as livestockTypeId,
                livestocks.sex,
            ')
                ->join('farmer_livestocks', 'farmer_livestocks.livestock_id = livestocks.id')
                ->where($whereClause)
                ->orderBy('livestocks.livestock_tag_id', 'ASC')
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllBreedingEligibleLivestocks()
    {
        try {
            $whereClause = [
                'livestock_health_status' => 'Alive',
                'breeding_eligibility' => 'Age-Suited',
                'record_status' => 'Accessible',
                'category' => 'Livestock'
            ];

            $data = $this
                ->where($whereClause)
                ->countAllResults();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockCountByMonthAndType($livestockTypes)
    {
        try {
            // Initialize an array to hold the final results
            $results = [];

            foreach ($livestockTypes as $type) {
                $livestockTypeName = $type['livestockTypeName'];

                for ($month = 1; $month <= 12; $month++) {
                    // Reset the query builder before each count
                    $this->builder()->resetQuery();
                    $count = $this
                        ->where('YEAR(livestocks.date_of_birth)', date('Y'))
                        ->where('livestocks.category', 'Livestock')
                        ->where('livestock_types.livestock_type_name', $livestockTypeName)
                        ->where('MONTH(livestocks.date_of_birth)', $month)
                        ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                        ->countAllResults(false); // Count the number of results

                    // Add the result to the array
                    $results[] = [
                        'month' => (string) $month,
                        'livestock_type_name' => $livestockTypeName,
                        'livestock_count' => (string) $count,
                    ];
                }
            }

            return $results;
        } catch (\Throwable $th) {
            // Log the error or handle it as needed
            log_message('error', $th->getMessage());
            return [];
        }
    }

    public function getPoultryCountByMonthAndType()
    {
        try {
            $whereClause = [
                'YEAR(livestocks.date_of_birth)' => date('Y'),
                'livestocks.category' => 'Poultry'
            ];

            // Build the query
            $this->select('MONTH(livestocks.date_of_birth) AS month, livestock_types.livestock_type_name, COUNT(*) AS livestock_count')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->where($whereClause)
                ->groupBy(['MONTH(livestocks.date_of_birth)', 'livestock_types.livestock_type_name'])
                ->orderBy('MONTH(livestocks.date_of_birth)');

            // Execute the query and return the result
            return $this->get()->getResult();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockHealthStatusesCount()
    {
        try {
            $whereClause = [
                'livestocks.record_status' => 'Accessible',
                'livestocks.category' => 'Livestock'
            ];

            $data = $this->select('
                SUM(CASE WHEN livestocks.livestock_health_status = "Alive" THEN 1 ELSE 0 END) as Alive,
                SUM(CASE WHEN livestocks.livestock_health_status = "Sick" THEN 1 ELSE 0 END) as Sick,
                SUM(CASE WHEN livestocks.livestock_health_status = "Dead" THEN 1 ELSE 0 END) as Dead
            ')
                ->where($whereClause)
                ->get()
                ->getRowArray();

            return $data;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getPoultryHealthStatusesCount()
    {
        try {
            $whereClause = [
                'livestocks.record_status' => 'Accessible',
                'livestocks.category' => 'Poultry'
            ];

            $data = $this->select('
                SUM(CASE WHEN livestocks.livestock_health_status = "Alive" THEN 1 ELSE 0 END) as Alive,
                SUM(CASE WHEN livestocks.livestock_health_status = "Sick" THEN 1 ELSE 0 END) as Sick,
                SUM(CASE WHEN livestocks.livestock_health_status = "Dead" THEN 1 ELSE 0 END) as Dead
            ')
                ->where($whereClause)
                ->get()
                ->getRowArray();

            return $data;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }


    public function getAllLivestockTypeCountByCity($city)
    {
        try {
            $whereClause = [
                'livestocks.record_status' => 'Accessible',
                'livestocks.livestock_health_status' => 'Alive',
                'user_accounts.city' => $city,
                'livestocks.category' => 'Livestock'
            ];

            $livestock = $this->select('
                ROW_NUMBER() OVER () AS id,
                livestock_types.livestock_type_name as livestockType, 
                COUNT(*) as livestockCount
            ')
                ->join('farmer_livestocks', 'livestocks.id = farmer_livestocks.livestock_id')
                ->join('user_accounts', 'user_accounts.id = farmer_livestocks.farmer_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('livestock_age_class', 'livestock_age_class.id = livestocks.livestock_age_class_id')
                ->where($whereClause)
                ->groupBy('livestock_types.livestock_type_name')
                ->orderBy('livestock_types.livestock_type_name')
                ->orderBy('livestockCount')
                ->get()->getResultArray();
            return $livestock;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getAllPoultryTypeCountByCity($city)
    {
        try {
            $whereClause = [
                'livestocks.record_status' => 'Accessible',
                'livestocks.livestock_health_status' => 'Alive',
                'user_accounts.city' => $city,
                'livestocks.category' => 'Poultry'
            ];

            $livestock = $this->select('
                ROW_NUMBER() OVER () AS id,
                livestock_types.livestock_type_name as livestockType, 
                COUNT(*) as livestockCount
            ')
                ->join('farmer_livestocks', 'livestocks.id = farmer_livestocks.livestock_id')
                ->join('user_accounts', 'user_accounts.id = farmer_livestocks.farmer_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('livestock_age_class', 'livestock_age_class.id = livestocks.livestock_age_class_id')
                ->where($whereClause)
                ->groupBy('livestock_types.livestock_type_name')
                ->orderBy('livestock_types.livestock_type_name')
                ->orderBy('livestockCount')
                ->get()->getResultArray();
            return $livestock;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getLivestockTypeCountByCity($city, $livestockTypeId)
    {
        try {
            $whereClause = [
                'livestocks.record_status' => 'Accessible',
                'livestocks.livestock_health_status' => 'Alive',
                'user_accounts.city' => $city,
                'livestock_types.id' => $livestockTypeId,
                'livestocks.category' => 'Livestock'
            ];

            $livestock = $this->select('
                ROW_NUMBER() OVER () AS id,
                livestock_types.livestock_type_name as livestockType, 
                COUNT(*) as livestockCount
            ')
                ->join('farmer_livestocks', 'livestocks.id = farmer_livestocks.livestock_id')
                ->join('user_accounts', 'user_accounts.id = farmer_livestocks.farmer_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('livestock_age_class', 'livestock_age_class.id = livestocks.livestock_age_class_id')
                ->where($whereClause)
                ->groupBy('livestock_types.livestock_type_name')
                ->orderBy('livestock_types.livestock_type_name')
                ->orderBy('livestockCount')
                ->get()->getResultArray();
            return $livestock;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getPoultryTypeCountByCity($city, $livestockTypeId)
    {
        try {
            $whereClause = [
                'livestocks.record_status' => 'Accessible',
                'livestocks.livestock_health_status' => 'Alive',
                'user_accounts.city' => $city,
                'livestock_types.id' => $livestockTypeId,
                'livestocks.category' => 'Poultry'
            ];

            $livestock = $this->select('
                ROW_NUMBER() OVER () AS id,
                livestock_types.livestock_type_name as livestockType, 
                COUNT(*) as livestockCount
            ')
                ->join('farmer_livestocks', 'livestocks.id = farmer_livestocks.livestock_id')
                ->join('user_accounts', 'user_accounts.id = farmer_livestocks.farmer_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('livestock_age_class', 'livestock_age_class.id = livestocks.livestock_age_class_id')
                ->where($whereClause)
                ->groupBy('livestock_types.livestock_type_name')
                ->orderBy('livestock_types.livestock_type_name')
                ->orderBy('livestockCount')
                ->get()->getResultArray();
            return $livestock;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getAllLivestockCountCity($city)
    {
        try {
            $whereClause = [
                'livestocks.record_status' => 'Accessible',
                'user_accounts.city' => $city,
                'livestocks.category' => 'Livestock'
            ];

            $livestock = $this->select('
                user_accounts.city,
                livestock_types.livestock_type_name as livestockType
            ')
                ->join('farmer_livestocks', 'livestocks.id = farmer_livestocks.livestock_id')
                ->join('user_accounts', 'user_accounts.id = farmer_livestocks.farmer_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->where($whereClause)
                ->findAll();
            return $livestock;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getAllPoultryCountCity($city)
    {
        try {
            $whereClause = [
                'livestocks.record_status' => 'Accessible',
                'user_accounts.city' => $city,
                'livestocks.category' => 'Poultry'
            ];

            $livestock = $this->select('
                user_accounts.city,
                livestock_types.livestock_type_name as livestockType
            ')
                ->join('farmer_livestocks', 'livestocks.id = farmer_livestocks.livestock_id')
                ->join('user_accounts', 'user_accounts.id = farmer_livestocks.farmer_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->where($whereClause)
                ->findAll();
            return $livestock;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getLivestockCountByCity($city)
    {
        try {
            $whereClause = [
                'livestocks.record_status' => 'Accessible',
                'livestocks.livestock_health_status' => 'Alive',
                'user_accounts.city' => $city,
                'livestocks.category' => 'Livestock'
            ];

            $livestock = $this->join('farmer_livestocks', 'livestocks.id = farmer_livestocks.livestock_id')
                ->join('user_accounts', 'user_accounts.id = farmer_livestocks.farmer_id')
                ->where($whereClause)->countAllResults();
            return $livestock;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getPoultryCountByCity($city)
    {
        try {
            $whereClause = [
                'livestocks.record_status' => 'Accessible',
                'livestocks.livestock_health_status' => 'Alive',
                'user_accounts.city' => $city,
                'livestocks.category' => 'Poultry'
            ];

            $livestock = $this->join('farmer_livestocks', 'livestocks.id = farmer_livestocks.livestock_id')
                ->join('user_accounts', 'user_accounts.id = farmer_livestocks.farmer_id')
                ->where($whereClause)->countAllResults();
            return $livestock;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getLivestockProductionCountWholeYear()
    {
        try {
            $currentYear = date('Y');

            $livestockCounts = [];
            for ($month = 1; $month <= 12; $month++) {
                $count = $this
                    ->where('MONTH(date_of_birth)', $month)
                    ->where('YEAR(date_of_birth)', $currentYear)
                    ->countAllResults();

                $monthName = date("F", mktime(0, 0, 0, $month, 1));

                $livestockCounts[] = [
                    'month' => $monthName,
                    'count' => $count,
                ];
            }

            return $livestockCounts;
        } catch (\Throwable $th) {
            // Handle exceptions
            return $th->getMessage();
        }
    }

    public function getLivestockTypeCountWholeYear()
    {
        try {
            $currentYear = date('Y');

            $livestockCounts = [];
            for ($month = 1; $month <= 12; $month++) {
                $counts = $this
                    ->select('livestock_types.livestock_type_name as livestockType, COUNT(*) as count')
                    ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                    ->where('MONTH(date_of_birth)', $month)
                    ->where('YEAR(date_of_birth)', $currentYear)
                    ->groupBy('livestock_types.livestock_type_name')
                    ->findAll();

                $monthName = date("F", mktime(0, 0, 0, $month, 1));

                $livestockCounts[$monthName] = $counts;
            }

            return $livestockCounts;
        } catch (\Throwable $th) {
            // Handle exceptions
            return $th->getMessage();
        }
    }

    public function getLivestockProductionCount($livestockType, $month, $year)
    {
        try {
            $whereClause = [
                'MONTH(date_of_birth)' => $month,
                'YEAR(date_of_birth)' => $year,
                'livestock_type_id' => $livestockType
            ];

            $count = $this->db->table('livestocks')
                ->where($whereClause)
                ->countAllResults();

            return $count;
        } catch (\Throwable $th) {
            // Handle exceptions
            return $th->getMessage();
        }
    }

    public function getProductionCountByMonthAndType($livestockTypes)
    {
        $productionData = [];
        $year = date('Y');

        foreach ($livestockTypes as $type) {
            $productionDataItem = [
                'livestockType' => $type['livestockTypeName']
            ];

            $totalCount = 0;


            for ($month = 1; $month <= 12; $month++) {
                $count = $this->selectCount('id')
                    ->where('MONTH(date_of_birth)', $month)
                    ->where('YEAR(date_of_birth)', $year)
                    ->where('livestock_type_id', $type['id'])
                    ->countAllResults();
                $monthName = date('M', mktime(0, 0, 0, $month, 1));
                $productionDataItem[$monthName] = $count;

                $totalCount += $count;
            }
            $productionDataItem['id'] = $type['id'];
            $productionDataItem['totalYearProduction'] = $totalCount;
            $productionData[] = $productionDataItem;
        }

        return $productionData;
    }

    public function getProductionCountByMonthYearAndType($livestockTypes, $year)
    {
        $productionData = [];

        foreach ($livestockTypes as $type) {
            $productionDataItem = [
                'livestockType' => $type['livestockTypeName']
            ];

            $totalCount = 0;


            for ($month = 1; $month <= 12; $month++) {
                $count = $this->selectCount('id')
                    ->where('MONTH(date_of_birth)', $month)
                    ->where('YEAR(date_of_birth)', $year)
                    ->where('livestock_type_id', $type['id'])
                    ->countAllResults();
                $monthName = date('M', mktime(0, 0, 0, $month, 1));
                $productionDataItem[$monthName] = $count;

                $totalCount += $count;
            }
            $productionDataItem['id'] = $type['id'];
            $productionDataItem['totalYearProduction'] = $totalCount;
            $productionData[] = $productionDataItem;
        }

        return $productionData;
    }

    public function getLivestockForReport($category, $minDate, $maxDate)
    {
        try {
            $whereClause = [
                'livestocks.record_status' => 'Accessible',
                'livestocks.category' => $category,
                'livestocks.date_of_birth >=' => $minDate,
                'livestocks.date_of_birth <=' => $maxDate
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
                    CASE 
                        WHEN livestocks.age_years > 0 THEN CONCAT(livestocks.age_years, " years") 
                        WHEN livestocks.age_months > 0 THEN CONCAT(livestocks.age_months, " months") 
                        WHEN livestocks.age_weeks > 0 THEN CONCAT(livestocks.age_weeks, " weeks") 
                        WHEN livestocks.age_days > 0 THEN CONCAT(livestocks.age_days, " days")
                        ELSE "Unknown Age" 
                    END as age,
                    livestocks.sex,
                    livestocks.breeding_eligibility as breedingEligibility,
                    livestocks.date_of_birth as dateOfBirth,
                    livestocks.livestock_health_status as livestockHealthStatus,
                    livestocks.origin
                ')
                ->join('farmer_livestocks', 'livestocks.id = farmer_livestocks.livestock_id')
                ->join('user_accounts', 'user_accounts.id = farmer_livestocks.farmer_id')
                ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
                ->join('livestock_breeds', 'livestock_breeds.id = livestocks.livestock_breed_id')
                ->join('livestock_age_class', 'livestock_age_class.id = livestocks.livestock_age_class_id')
                ->where($whereClause)
                ->orderBy('livestocks.date_of_birth', 'ASC')
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

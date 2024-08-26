<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockAgeClassModel extends Model
{
    protected $table = 'livestock_age_class';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['livestock_age_classification', 'age_class_range',  'age_min_days', 'age_max_days', 'is_offspring', 'sex', 'breeding_eligibility', 'livestock_type_id', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAnimalAgeClasses($category)
    {
        $livestockAgeClasses = $this->select('
            livestock_age_class.id,
            livestock_age_class.livestock_age_classification as livestockAgeClassification,
            livestock_age_class.age_class_range as ageClassRange,
            livestock_age_class.age_min_days as ageMinDays,
            livestock_age_class.age_max_days as ageMaxDays,
            livestock_age_class.is_offspring as isOffspring,
            livestock_age_class.sex,
            livestock_age_class.breeding_eligibility as breedingEligibility,
            livestock_age_class.livestock_type_id as livestockTypeId,
            livestock_types.livestock_type_name as livestockTypeName,
        ')
        ->join('livestock_types', 'livestock_types.id = livestock_age_class.livestock_type_id')
        ->where('livestock_types.category',$category)
        ->findAll();

        return $livestockAgeClasses;
    }

    public function getLivestockAgeClasses()
    {
        $livestockAgeClasses = $this->select('
            livestock_age_class.id,
            livestock_age_class.livestock_age_classification as livestockAgeClassification,
            livestock_age_class.age_class_range as ageClassRange,
            livestock_age_class.age_min_days as ageMinDays,
            livestock_age_class.age_max_days as ageMaxDays,
            livestock_age_class.is_offspring as isOffspring,
            livestock_age_class.sex,
            livestock_age_class.breeding_eligibility as breedingEligibility,
            livestock_age_class.livestock_type_id as livestockTypeId,
            livestock_types.livestock_type_name as livestockTypeName,
        ')
        ->join('livestock_types', 'livestock_types.id = livestock_age_class.livestock_type_id')
        ->where('livestock_types.category','Livestock')
        ->findAll();

        return $livestockAgeClasses;
    }

    public function getPoultryAgeClasses()
    {
        $livestockAgeClasses = $this->select('
            livestock_age_class.id,
            livestock_age_class.livestock_age_classification as livestockAgeClassification,
            livestock_age_class.age_class_range as ageClassRange,
            livestock_age_class.age_min_days as ageMinDays,
            livestock_age_class.age_max_days as ageMaxDays,
            livestock_age_class.is_offspring as isOffspring,
            livestock_age_class.sex,
            livestock_age_class.breeding_eligibility as breedingEligibility,
            livestock_age_class.livestock_type_id as livestockTypeId,
            livestock_types.livestock_type_name as livestockTypeName,
        ')
        ->join('livestock_types', 'livestock_types.id = livestock_age_class.livestock_type_id')
        ->where('livestock_types.category','Poultry')
        ->findAll();

        return $livestockAgeClasses;
    }

    public function getLivestockAgeClassBase(){
        $data = $this->select(
            'id,
            livestock_age_classification as ageClassName,
            age_min_days as ageMin,
            age_max_days as ageMax,
            livestock_type_id as typeId,
            sex,
            breeding_eligibility as breedingEligibility
        ')
        ->findAll();

        return $data;
    }

    public function getLivestockAgeClass($id)
    {
        $livestockAgeClass = $this->select(
            'id,
            livestock_age_classification as livestockAgeClassification,
            age_class_range as ageClassRange,
            livestock_type_id as livestockTypeId,'
        )->find($id);

        return $livestockAgeClass;
    }

    public function insertLivestockAgeClass($data)
    {
        $bind = [
            'livestock_age_classification' => $data->livestockAgeClassification,
            'age_class_range' => $data->ageClassRange,
            'age_min_days' => $data->ageMinDays,
            'age_max_days' => $data->ageMaxDays,
            'is_offspring' => $data->isOffspring,
            'sex' => $data->sex,
            'breeding_eligibility' => $data->breedingEligibility,
            'livestock_type_id' => $data->livestockTypeId,
        ];
        $result = $this->insert($bind);
        return $result;
    }

    public function updateLivestockAgeClass($id, $data)
    {
        $bind = [
            'livestock_age_classification' => $data->livestockAgeClassification,
            'age_class_range' => $data->ageClassRange,
            'age_min_days' => $data->ageMinDays,
            'age_max_days' => $data->ageMaxDays,
            'is_offspring' => $data->isOffspring,
            'sex' => $data->sex,
            'breeding_eligibility' => $data->breedingEligibility,
            'livestock_type_id' => $data->livestockTypeId,
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function deleteLivestockAgeClass($id)
    {
        $result = $this->delete($id);

        return $result;
    }

    public function getLivestockTypeOffspring($typeId)
    {
        $ageClass = $this->where('livestock_type_id', $typeId)->first();
        return $ageClass;
    }

    public function getLivestockAgeClassIdAndName()
    {
        try {
            $livestockAgeClasses = $this->select(
                'id,
                livestock_age_classification as livestockAgeClassification,
                livestock_type_id as livestockTypeId'
            )->findAll();

            return $livestockAgeClasses;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockAgeClassIdAndNameById($livestockTypeId)
    {
        try {
            $livestockAgeClasses = $this->select(
                'id,
                livestock_age_classification as livestockAgeClassification,
                livestock_type_id as livestockTypeId'
            )->where('livestock_type_id', $livestockTypeId)->findAll();
            return $livestockAgeClasses;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockAgeClassIdByName($livestockAgeClass, $livestockTypeId)
    {
        try {
            $nameLower = strtolower($livestockAgeClass);

            // Capitalize the first character
            $capitalizedname = ucfirst($nameLower);

            $result = $this->select('id')
                ->where('livestock_age_classification', $capitalizedname)
                ->where('livestock_type_id', $livestockTypeId)
                ->first();

            if ($result) {
                return $result['id'];
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

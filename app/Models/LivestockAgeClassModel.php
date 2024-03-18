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
    protected $allowedFields = ['livestock_age_classification', 'age_class_range', 'is_offspring', 'livestock_type_id', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getLivestockAgeClasses()
    {
        $livestockAgeClasses = $this->select(
            'id,
            livestock_age_classification as livestockAgeClassification,
            age_class_range as ageClassRange,
            livestock_type_id as livestockTypeId,'
        )->findAll();

        return $livestockAgeClasses;
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
}

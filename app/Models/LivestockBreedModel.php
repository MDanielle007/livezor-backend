<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockBreedModel extends Model
{
    protected $table = 'livestock_breeds';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['livestock_breed_name', 'livestock_breed_description', 'livestock_type_id', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getLivestockBreeds()
    {
        $livestockBreeds = $this->select(
            'id,
            livestock_breed_name as livestockBreedName,
            livestock_breed_description as livestockBreedDescription,
            livestock_type_id as livestockTypeId'
        )->findAll();

        return $livestockBreeds;
    }

    public function getLivestockBreed($id)
    {
        $livestockBreed = $this->select(
            'id,
            livestock_breed_name as livestockBreedName,
            livestock_breed_description as livestockBreedDescription,
            livestock_type_id as livestockTypeId'
        )->find($id);

        return $livestockBreed;
    }

    public function insertLivestockBreed($data)
    {
        $bind = [
            'livestock_breed_name' => $data->livestockBreedName,
            'livestock_breed_description' => $data->livestockBreedDescription,
            'livestock_type_id' => $data->livestockTypeId,
        ];
        $result = $this->insert($bind);
        return $result;
    }

    public function updateLivestockBreed($id, $data)
    {
        $bind = [
            'livestock_breed_name' => $data->livestockBreedName,
            'livestock_breed_description' => $data->livestockBreedDescription,
            'livestock_type_id' => $data->livestockTypeId,
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function deleteLivestockBreed($id)
    {
        $result = $this->delete($id);

        return $result;
    }
}

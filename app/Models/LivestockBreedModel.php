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
        try {
            $livestockBreeds = $this->select(
                'livestock_breeds.id,
                livestock_breeds.livestock_breed_name as livestockBreedName,
                livestock_breeds.livestock_breed_description as livestockBreedDescription,
                livestock_breeds.livestock_type_id as livestockTypeId,
                livestock_types.livestock_type_name as livestockTypeName'
            )->join('livestock_types', 'livestock_types.id = livestock_breeds.livestock_type_id')
                ->where('livestock_types.category', 'Livestock')
                ->findAll();

            return $livestockBreeds;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getPoultryBreeds()
    {
        try {
            $livestockBreeds = $this->select(
                'livestock_breeds.id,
                livestock_breeds.livestock_breed_name as livestockBreedName,
                livestock_breeds.livestock_breed_description as livestockBreedDescription,
                livestock_breeds.livestock_type_id as livestockTypeId,
                livestock_types.livestock_type_name as livestockTypeName'
            )->join('livestock_types', 'livestock_types.id = livestock_breeds.livestock_type_id')
                ->where('livestock_types.category', 'Poultry')
                ->findAll();

            return $livestockBreeds;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockBreed($id)
    {
        try {
            $livestockBreed = $this->select(
                'id,
                livestock_breed_name as livestockBreedName,
                livestock_breed_description as livestockBreedDescription,
                livestock_type_id as livestockTypeId'
            )->find($id);

            return $livestockBreed;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertLivestockBreed($data)
    {
        try {
            $bind = [
                'livestock_breed_name' => $data->livestockBreedName,
                'livestock_breed_description' => $data->livestockBreedDescription,
                'livestock_type_id' => $data->livestockTypeId,
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

    public function updateLivestockBreed($id, $data)
    {
        try {
            $bind = [
                'livestock_breed_name' => $data->livestockBreedName,
                'livestock_breed_description' => $data->livestockBreedDescription,
                'livestock_type_id' => $data->livestockTypeId,
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

    public function deleteLivestockBreed($id)
    {
        try {
            $result = $this->delete($id);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function getLivestockBreedIdAndName()
    {
        try {
            $livestockBreeds = $this->select(
                'id,
                livestock_breed_name as livestockBreedName,
                livestock_type_id as livestockTypeId'
            )->findAll();

            return $livestockBreeds;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockBreedIdAndNameById($livestockTypeId)
    {
        try {
            $livestockBreeds = $this->select(
                'id,
                livestock_breed_name as livestockBreedName,
                livestock_type_id as livestockTypeId'
            )->where('livestock_type_id', $livestockTypeId)->findAll();
            return $livestockBreeds;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockBreedIdByName($livestockBreed, $livestockTypeId)
    {
        try {
            $nameLower = strtolower($livestockBreed);

            // Capitalize the first character
            $capitalizedname = ucfirst($nameLower);

            $result = $this->select('id')
                ->where('livestock_breed_name', $capitalizedname)
                ->where('livestock_type_id', $livestockTypeId)
                ->first();

            if ($result) {
                return $result['id'];
            } else {
                $bind = [
                    'livestock_breed_name' => $livestockBreed,
                    'livestock_breed_description' => '',
                    'livestock_type_id' => $livestockTypeId,
                ];
                $result = $this->insert($bind);
                return $result;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

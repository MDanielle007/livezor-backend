<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockBreedingsModel extends Model
{
    protected $table            = 'livestock_breedings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['farmer_id', 'livestock_type_id', 'male_livestock_tag_id', 'female_livestock_tag_id', 'breed_result', 'breed_additional_notes', 'breed_date', 'record_status'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
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

    public function getAllLivestockBreedings()
    {
        $livestockBreedings = $this->findAll();
        return $livestockBreedings;
    }

    public function getLivestockBreeding($id)
    {
        $livestockBreeding = $this->find($id);
        return $livestockBreeding;
    }

    public function getAllFarmerLivestockBreedings($userId)
    {
        $whereClause = [
            'farmer_id' => $userId,
            'record_status' => 'Accessible'
        ];

        $livestockBreedings = $this->where($whereClause)->findAll();
        return $livestockBreedings;
    }

    public function insertLivestockBreeding($data)
    {
        $bind = [
            'farmer_id' => $data->farmerId,
            'livestock_type_id' => $data->livestockTypeId,
            'male_livestock_tag_id' => $data->maleLivestockTagId,
            'female_livestock_tag_id' => $data->femaleLivestockTagId,
            'breed_result' => $data->breedResult,
            'breed_additional_notes' => $data->breedAdditionalNotes,
            'breed_date' => $data->breedDate,
        ];

        $result = $this->insert($bind);

        return $result;
    }

    public function updateLivestockBreeding($id, $data)
    {
        $bind = [
            'farmer_id' => $data->farmerId,
            'livestock_type_id' => $data->livestockTypeId,
            'male_livestock_tag_id' => $data->maleLivestockTagId,
            'female_livestock_tag_id' => $data->femaleLivestockTagId,
            'breed_result' => $data->breedResult,
            'breed_additional_notes' => $data->breedAdditionalNotes,
            'breed_date' => $data->breedDate,
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function updateLivestockBreedingRecordStatus($id, $status){
        $bind = [
            'record_status' => $status,
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function deleteLivestockBreeding($id){
        $result = $this->delete($id);
        return $result;
    }
}

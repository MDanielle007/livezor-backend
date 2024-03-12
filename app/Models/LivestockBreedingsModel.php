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
    protected $allowedFields    = ['farmer_id', 'livestock_type_id', 'male_livestock_tag_id', 'female_livestock_tag_id', 'breed_result', 'breed_additional_notes', 'breed_date', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllLivestockBreedings()
    {
        $livestockBreedings = $this->select(
            'id,
            farmer_id as farmerId,
            livestock_type_id as livestockTypeId,
            male_livestock_tag_id as maleLivestockTagId,
            female_livestock_tag_id as femaleLivestockTagId,
            breed_result as breedResult,
            breed_additional_notes as breedAdditionalNotes,
            breed_date as breedDate,
            record_status as recordStatus'
        )->findAll();
        return $livestockBreedings;
    }

    public function getLivestockBreeding($id)
    {
        $livestockBreeding = $this->select(
            'id,
            farmer_id as farmerId,
            livestock_type_id as livestockTypeId,
            male_livestock_tag_id as maleLivestockTagId,
            female_livestock_tag_id as femaleLivestockTagId,
            breed_result as breedResult,
            breed_additional_notes as breedAdditionalNotes,
            breed_date as breedDate,
            record_status as recordStatus'
        )->find($id);
        return $livestockBreeding;
    }

    public function getAllFarmerLivestockBreedings($userId)
    {
        $whereClause = [
            'farmer_id' => $userId,
            'record_status' => 'Accessible'
        ];

        $livestockBreedings = $this->select(
            'id,
            farmer_id as farmerId,
            livestock_type_id as livestockTypeId,
            male_livestock_tag_id as maleLivestockTagId,
            female_livestock_tag_id as femaleLivestockTagId,
            breed_result as breedResult,
            breed_additional_notes as breedAdditionalNotes,
            breed_date as breedDate,
            record_status as recordStatus'
        )->where($whereClause)->findAll();
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

    public function getAllBreedingParentOffspringData(){
        try {

            $livestockBreedings = $this->select(
                'livestock_breedings.id,
                livestock_breedings.male_livestock_tag_id as parentMaleLivestockTagId,
                livestock_breedings.female_livestock_tag_id as parentFemaleLivestockTagId,
                livestocks.livestock_type_id as livestockTypeId,
                livestocks.livestock_tag_id as offspringLivestockTagId,
                livestock_pregnancies.outcome,
                livestock_pregnancies.pregnancy_start_date as pregnancyStartDate,
                livestock_pregnancies.actual_delivery_date as actualDeliveryDate,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName,
                user_accounts.user_id as farmerUserId'
            )
            ->join('livestock_pregnancies','livestock_pregnancies.breeding_id = livestock_breedings.id')
            ->join('livestock_offspring','livestock_offspring.pregnancy_id = livestock_pregnancies.id')
            ->join('livestocks','livestocks.id = livestock_offspring.livestock_id')
            ->join('user_accounts','user_accounts.id = livestock_breedings.farmer_id')
            ->findAll();

            return $livestockBreedings;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }
}

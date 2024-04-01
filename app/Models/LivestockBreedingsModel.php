<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockBreedingsModel extends Model
{
    protected $table = 'livestock_breedings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['farmer_id', 'livestock_type_id', 'male_livestock_tag_id', 'female_livestock_tag_id', 'breeding_result', 'breeding_remarks', 'breed_date', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllLivestockBreedings()
    {
        try {
            $livestockBreedings = $this->select(
                'id,
                farmer_id as farmerId,
                livestock_type_id as livestockTypeId,
                male_livestock_tag_id as maleLivestockTagId,
                female_livestock_tag_id as femaleLivestockTagId,
                breeding_result as breedResult,
                breeding_remarks as remarks,
                breed_date as breedDate,
                record_status as recordStatus'
            )->findAll();
            return $livestockBreedings;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockBreeding($id)
    {
        try {
            $livestockBreeding = $this->select(
                'id,
                farmer_id as farmerId,
                livestock_type_id as livestockTypeId,
                male_livestock_tag_id as maleLivestockTagId,
                female_livestock_tag_id as femaleLivestockTagId,
                breeding_result as breedResult,
                breeding_remarks as remarks,
                breed_date as breedDate,
                record_status as recordStatus'
            )->find($id);
            return $livestockBreeding;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllFarmerLivestockBreedings($userId)
    {
        try {
            $whereClause = [
                'livestock_breedings.farmer_id' => $userId,
                'livestock_breedings.record_status' => 'Accessible'
            ];

            $livestockBreedings = $this->select(
                'livestock_breedings.id,
                livestock_breedings.livestock_type_id as livestockTypeId,
                livestock_types.livestock_type_name as livestockTypeName,
                livestock_breedings.male_livestock_tag_id as maleLivestockTagId,
                livestock_breedings.female_livestock_tag_id as femaleLivestockTagId,
                livestock_breedings.breeding_result as breedResult,
                livestock_breedings.breeding_remarks as remarks,
                livestock_breedings.breed_date as breedDate'
            )
            ->join('livestock_types', 'livestock_types.id = livestock_breedings.livestock_type_id')
            ->where($whereClause)->findAll();
            return $livestockBreedings;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function insertLivestockBreeding($data)
    {
        try {
            $bind = [
                'farmer_id' => $data->farmerId,
                'livestock_type_id' => $data->livestockTypeId,
                'male_livestock_tag_id' => $data->maleLivestockTagId,
                'female_livestock_tag_id' => $data->femaleLivestockTagId,
                'breeding_result' => $data->breedResult,
                'breeding_remarks' => $data->remarks,
                'breed_date' => $data->breedDate,
            ];

            $result = $this->insert($bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function updateLivestockBreeding($id, $data)
    {
        try {
            $bind = [
                'farmer_id' => $data->farmerId,
                'livestock_type_id' => $data->livestockTypeId,
                'male_livestock_tag_id' => $data->maleLivestockTagId,
                'female_livestock_tag_id' => $data->femaleLivestockTagId,
                'breeding_result' => $data->breedResult,
                'breeding_remarks' => $data->remarks,
                'breed_date' => $data->breedDate,
            ];

            $result = $this->update($id, $bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateLivestockBreedingRecordStatus($id, $status)
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

    public function deleteLivestockBreeding($id)
    {
        try {
            $result = $this->delete($id);
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllBreedingParentOffspringData()
    {
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
                ->join('livestock_pregnancies', 'livestock_pregnancies.breeding_id = livestock_breedings.id')
                ->join('livestock_offspring', 'livestock_offspring.pregnancy_id = livestock_pregnancies.id')
                ->join('livestocks', 'livestocks.id = livestock_offspring.livestock_id')
                ->join('user_accounts', 'user_accounts.id = livestock_breedings.farmer_id')
                ->findAll();

            return $livestockBreedings;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }
}

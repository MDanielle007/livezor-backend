<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockOffspringModel extends Model
{
    protected $table            = 'livestock_offspring';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['pregnancy_id', 'livestock_id', 'birth_date', 'sex', 'offspring_notes', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllLivestockOffspring(){
        $livestockOffsprings = $this->findAll();

        return $livestockOffsprings;
    }

    public function getAllCompleteLivestockOffspring(){
        try {
            $livestockOffsprings = $this->select(
                'livestock_offspring.id,
                livestock_offspring.livestock_id as livestockId,
                livestock_breedings.livestock_type_id as livestockTypeId,
                livestock_offspring.birth_date as birthDate,
                livestock_offspring.sex,
                livestock_offspring.offspring_notes as offspringNotes,
                livestock_breedings.male_livestock_tag_id as parentMaleLivestockTagId,
                livestock_breedings.female_livestock_tag_id as parentFemaleLivestockTagId,
                user_accounts.id as farmerId,
                user_accounts.user_id as farmerUserId,
                CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName'
            )->join('livestock_pregnancies','livestock_pregnancies.id = livestock_offspring.pregnancy_id')
            ->join('livestock_breedings','livestock_breedings.id = livestock_pregnancies.breeding_id')
            ->join('user_accounts','user_accounts.id = livestock_breedings.farmer_id')
            ->findAll();
    
            return $livestockOffsprings;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getLivestockOffspring($id){
        $livestockOffspring = $this->find($id);

        return $livestockOffspring;
    }

    public function getAllFarmerLivestockOffspringRecords($userId){
        try {
            $whereClause = [
                'livestock_breedings.farmer_id' => $userId,
                'record_status' => 'Accessible'
            ];

            $livestockoffsprings = $this->join('livestock_pregnancies','livestock_pregnancies.id = livestock_offspring.pregnancy_id')
            ->join('livestock_breedings','livestock_breedings.id = livestock_pregnancies.breeding_id')
            ->where($whereClause)->findAll();

            return $livestockoffsprings;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertLivestockOffspring($data){
        $bind = [
            'pregnancy_id' => $data->pregnancyId,
            'livestock_id' => $data->livestockId,
            'birth_date' => $data->birthDate,
            'sex' => $data->sex,
        ];

        if(isset($data->offspringNotes)){
            $bind['offspring_notes'] = $data->offspringNotes;
        }
        $result = $this->insert($bind);

        return $result;
    }

    public function updateLivestockOffspring($id, $data){
        $bind = [
            'pregnancy_id' => $data->pregnancyId,
            'livestock_id' => $data->livestockId,
            'birth_date' => $data->birthdate,
            'sex' => $data->sex,
        ];

        if(isset($data->offspringNotes)){
            $bind['offspring_notes'] = $data->offspringNotes;
        }

        $result = $this->update($id,$bind);

        return $result;
    }

    public function updateLivestockOffspringRecordStatus($id,$status){
        $bind = [
          'record_status' => $status
        ];

        $result = $this->update($id,$bind);

        return $result;
    }

    public function deleteLivestockOffspringRecord($id){
        $result = $this->delete($id);

        return $result;
    }
}

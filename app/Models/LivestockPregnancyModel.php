<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockPregnancyModel extends Model
{
    protected $table            = 'livestock_pregnancies';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['breeding_id', 'livestock_id', 'outcome', 'pregnancy_start_date', 'expected_delivery_date', 'actual_delivery_date', 'pregnancy_notes', 'record_status'];

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

    public function getAllLivestockPregnancies(){
        $livestockPregnancies = $this->findAll();
        return $livestockPregnancies;
    }

    public function getLivestockPregnancy($id){
        $livestockPregnancy = $this->find($id);
        return $livestockPregnancy;
    }

    public function getAllFarmerLivestockPregnancies($userId){
        $whereClause = [
            'livestock_breedings.farmer_id' => $userId,
            'livestock_pregnancies.record_status' => 'Accessible',
            'livestock_pregnancies.outcome' => 'Pending',
        ];

        $livestockPregnancies = $this->select(
            'livestock_pregnancies.id,
            livestock_pregnancies.breeding_id as breedingId,
            livestock_pregnancies.livestock_id as livestockId,
            livestocks.livestock_tag_id as livestockTagId,
            livestocks.livestock_type_id as livestockTypeId,
            livestock_pregnancies.outcome as outcome,
            livestock_pregnancies.pregnancy_start_date as pregnancyStartDate,
            livestock_pregnancies.expected_delivery_date as expectedDeliveryDate,
            livestock_pregnancies.actual_delivery_date as actualDeliveryDate,'
        )->join('livestock_breedings','livestock_breedings.id = livestock_pregnancies.breeding_id')
        ->join('livestocks','livestocks.id = livestock_pregnancies.livestock_id')
        ->where($whereClause)->findAll();

        return $livestockPregnancies;
    }

    public function insertLivestockPregnancyByBreeding($data){        
        $bind = [
            'breeding_id' => $data->breedingId,
            'livestock_id' => $data->femaleLivestockId,
            'pregnancy_start_date' => $data->pregnancyStartDate,
        ];

        if(isset($data->pregnancyNotes)){
            $bind['pregnancy_notes'] = $data->pregnancyNotes;
        }

        $result = $this->insert($bind);

        return $result;
    }

    public function insertLivestockPregnancy($data){
        $bind = [
            'breeding_id' => $data->breedingId,
            'livestock_id' => $data->femaleLivestockId,
            'outcome' => $data->outcome,
            'pregnancy_start_date' => $data->pregnancyStartDate,
            'expected_delivery_date' => $data->expectedDeliveryDate,
            'actual_delivery_date' => $data->actualDeliveryDate,
        ];

        if(isset($data->pregnancyNotes)){
            $bind['pregnancy_notes'] = $data->pregnancyNotes;
        }

        $result = $this->insert($bind);

        return $result;
    }

    public function updateLivestockPregnancy($id, $data){
        $bind = [
            'breeding_id' => $data->breedingId,
            'livestock_id' => $data->femaleLivestockId,
            'outcome' => $data->outcome,
            'pregnancy_start_date' => $data->pregnancyStartDate,
            'expected_delivery_date' => $data->expectedDeliveryDate,
            'actual_delivery_date' => $data->actualDeliveryDate,
            'pregnancy_notes' => $data->pregnancyNotes,
        ];

        $result = $this->update($id,$bind);

        return $result;
    }

    public function updateLivestockPregnancyOutcome($id, $data){
        $bind = [
            'outcome' => $data->outcome,
        ];

        $result = $this->update($id,$bind);

        return $result;
    }

    public function updateLivestockPregnancyOutcomeSuccessful($id, $data){
        $bind = [
            'outcome' => $data->outcome,
            'actual_delivery_date' => $data->actualDeliveryDate,
        ];

        $result = $this->update($id,$bind);

        return $result;
    }

    public function updateLivestockPregnancyRecordStatus($id, $status){
        $bind = [
          'record_status' => $status
        ];

        $result = $this->update($id,$bind);

        return $result;
    }

    public function deleteLivestockPregnancy($id){
        $result = $this->delete($id);
        return $result;
    }
}

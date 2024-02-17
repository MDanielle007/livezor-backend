<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockMortalityModel extends Model
{
    protected $table            = 'livestock_mortalities';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['livestock_id', 'farmer_id', 'cause_of_death', 'additional_death_notes', 'date_of_death', 'record_status'];

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

    public function getAllLivestockMortalities()
    {
        $livestockMortalities = $this->findAll();
        return $livestockMortalities;
    }

    public function getLivestockMortality($id)
    {
        $livestockMortality = $this->find($id);
        return $livestockMortality;
    }

    public function getAllFarmerLivestockMortalities($userId)
    {
        $whereClause = [
            'farmer_id' => $userId,
            'record_status' => 'Accessible'
        ];

        $livestockMortalities = $this->where($whereClause)->findAll();
        return $livestockMortalities;
    }

    public function insertLivestockMortality($data)
    {
        $bind = [
            'farmer_id' => $data->farmerId,
            'livestock_id' => $data->livestockId,
            'cause_of_death' => $data->causeOfDeath,
            'additional_death_notes' => $data->additionalDeathNotes,
            'date_of_death' => $data->dateOfDeath,
        ];

        $result = $this->insert($bind);

        return $result;
    }

    public function updateLivestockMortality($id, $data)
    {
        $bind = [
            'farmer_id' => $data->farmerId,
            'livestock_id' => $data->livestockId,
            'cause_of_death' => $data->causeOfDeath,
            'additional_death_notes' => $data->additionalDeathNotes,
            'date_of_death' => $data->dateOfDeath,
        ];
        $result = $this->update($id, $bind);

        return $result;
    }

    public function updateLivestockMortalityRecordStatus($id, $status){
        $bind = [
            'record_status' => $status,
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function deleteLivestockMortality($id){
        $result = $this->delete($id);
        return $result;
    }
}

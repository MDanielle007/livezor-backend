<?php

namespace App\Models;

use CodeIgniter\Model;

class FarmerLivestockModel extends Model
{
    protected $table            = 'farmer_livestocks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['farmer_id', 'livestock_id', 'acquired_date', 'ownership_status', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function associateFarmerLivestock($data){
        $bind = [
            'farmer_id' => $data->farmerId,
            'livestock_id' => $data->livestockId,
            'acquired_date' => $data->acquiredDate,
        ];

        $result = $this->insert($bind);

        return $result;
    }

    public function updateFarmerLivestock($id, $data){
        $bind = [
            'farmer_id' => $data->farmerId,
            'livestock_id' => $data->livestockId,
            'acquired_date' => $data->acquiredDate,
            'ownership_status' => $data->ownershipStatus,
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function deleteFarmerLivestock($id){
        $result = $this->delete($id);

        return $result;
    }
}

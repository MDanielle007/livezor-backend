<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockTypeModel extends Model
{
    protected $table            = 'livestock_types';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['livestock_type_name', 'livestock_type_uses'];

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

    public function getLivestockTypes(){
        $livestockTypes = $this->findAll();

        return $livestockTypes;
    }

    public function getLivestockType($id){
        $livestockType = $this->find($id);

        return $livestockType;
    }

    public function insertLivestockType($data){
        $bind = [
            'livestock_type_name' => $data->livestockTypeName,
            'livestock_type_uses' => $data->livestockTypeUses
        ];
        $result = $this->insert($bind);
        return $result;
    }

    public function updateLivestockType($id,$data){
        $bind = [
            'livestock_type_name' => $data->livestockTypeName,
            'livestock_type_uses' => $data->livestockTypeUses
        ];

        $result = $this->update($id,$bind);

        return $result;
    }

    public function deleteLivestockType($id){
        $result = $this->delete($id);

        return $result;
    }
}

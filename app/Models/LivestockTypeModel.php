<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockTypeModel extends Model
{
    protected $table = 'livestock_types';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['livestock_type_name', 'livestock_type_uses', 'created_at', 'updated_at', 'deleted_at'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'livestock_type_name' => [
            'label' => 'Livestock Type Name',
            'rules' => 'required|max_length[50]' // Required and maximum length of 50 characters
        ],
        'livestock_type_uses' => [
            'label' => 'Livestock Type Uses',
            'rules' => 'permit_empty|max_length[65535]' // Allow empty value and maximum length of TEXT field
        ]
    ];
    protected $validationMessages = [
        'livestock_type_name' => [
            'required' => 'The Livestock Type Name field is required.',
            'max_length' => 'The Livestock Type Name field cannot exceed 50 characters in length.'
        ],
        'livestock_type_uses' => [
            'max_length' => 'The Livestock Type Uses field cannot exceed 65535 characters in length.'
        ]
    ];
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

    public function getLivestockTypes()
    {
        try {
            $livestockTypes = $this->select(
                'id,
                livestock_type_name as livestockTypeName,
                livestock_type_uses as livestockTypeUses'
            )->findAll();
    
            return $livestockTypes;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockType($id)
    {
        try {
            $livestockType = $this->select(
                'id,
                livestock_type_name as livestockTypeName,
                livestock_type_uses as livestockTypeUses'
            )->find($id);
    
            return $livestockType;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockTypeName($id)
    {
        try {
            // Assuming 'livestockTypeName' is the column name in your database
            $livestockType = $this->find($id);
    
            if ($livestockType) {
                return $livestockType['livestock_type_name'];
            } else {
                return null; // or handle the case where the record is not found
            }
        } catch (\Throwable $th) {
            // Handle exceptions appropriately
            // For now, let's just suppress them
            return $th->getMessage();
        }
    }

    public function insertLivestockType($data)
    {
        try {
            $bind = [
                'livestock_type_name' => $data->livestockTypeName,
                'livestock_type_uses' => $data->livestockTypeUses
            ];
            $result = $this->insert($bind);
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function updateLivestockType($id, $data)
    {
        try {
            $bind = [
                'livestock_type_name' => $data->livestockTypeName,
                'livestock_type_uses' => $data->livestockTypeUses
            ];
    
            $result = $this->update($id, $bind);
    
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function deleteLivestockType($id)
    {
        try {
            $result = $this->delete($id);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getAllLivestockTypeIdName(){
        try {
            $livestockTypes = $this->select(
                'id,
                livestock_type_name as livestockTypeName'
            )->findAll();
    
            return $livestockTypes;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

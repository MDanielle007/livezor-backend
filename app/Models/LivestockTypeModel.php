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
    protected $allowedFields = ['livestock_type_name', 'livestock_type_uses', 'category', 'created_at', 'updated_at', 'deleted_at'];

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
            )
                ->where('category', 'Livestock')
                ->findAll();

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
            )
                ->where('category', 'Livestock')
                ->find($id);

            return $livestockType;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getPoultryTypes()
    {
        try {
            $livestockTypes = $this->select(
                'id,
                livestock_type_name as livestockTypeName,
                livestock_type_uses as livestockTypeUses'
            )
                ->where('category', 'Poultry')
                ->findAll();

            return $livestockTypes;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getPoultryType($id)
    {
        try {
            $livestockType = $this->select(
                'id,
                livestock_type_name as livestockTypeName,
                livestock_type_uses as livestockTypeUses'
            )
                ->where('category', 'Poultry')
                ->find($id);

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
                'livestock_type_uses' => $data->livestockTypeUses,
                'category' => $data->category
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
                'livestock_type_uses' => $data->livestockTypeUses,
                'category' => $data->category
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

    public function getAllLivestockTypeIdName()
    {
        try {
            $livestockTypes = $this->select(
                'id,
                livestock_type_name as livestockTypeName'
            )
                ->where('category', 'Livestock')
                ->findAll();

            return $livestockTypes;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllLivestockTypeName()
    {
        try {
            $livestockTypes = $this->select(
                'livestock_type_name as livestockTypeName'
            )
                ->where('category', 'Livestock')
                ->findAll();

            return $livestockTypes;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllPoultryTypeIdName()
    {
        try {
            $livestockTypes = $this->select(
                'id,
                livestock_type_name as livestockTypeName'
            )
                ->where('category', 'Poultry')
                ->findAll();

            return $livestockTypes;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockTypeIdByName($livestockTypeName, $category)
    {
        try {

            $typeNameLower = strtolower($livestockTypeName);

            // Capitalize the first character
            $capitalizedTypeName = ucfirst($typeNameLower);

            $livestockType = $this->select('id')
                ->where('livestock_type_name', $capitalizedTypeName)
                ->first();

            if ($livestockType) {
                return $livestockType['id'];
            } else {
                $bind = [
                    'livestock_type_name' => $capitalizedTypeName,
                    'livestock_type_uses' => "",
                    'category' => $category
                ];
                $result = $this->insert($bind);
                return $result;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryItemsCategoryModel extends Model
{
    protected $table            = 'inventory_items_category';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'description', 'created_at', 'updated_at', 'deleted_at'];

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


    public function getAllItemCategoriesIdName(){
        try {
            $data = $this->select('
                id,
                name
            ')->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function insertItemCategory($data){
        try {
            $bind = [
                'name' => $data->name,
            ];

            if(isset($data->description)){
                $bind['description'] = $data->description;
            }

            $this->insert($bind);
            return $this->insertID;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage(). ": ". $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function updateItemCategory($id, $data){
        try {
            $bind = [
                'name' => $data->name,
            ];

            if(isset($data->description)){
                $bind['description'] = $data->description;
            }

            $this->update($id,$bind);
            return $this->insertID;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage(). ": ". $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function deleteItemCategory($id){
        try {
            $res = $this->delete($id);
            return $res;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage(). ": ". $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return false;
        }
    }
}

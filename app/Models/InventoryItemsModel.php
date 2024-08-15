<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryItemsModel extends Model
{
    protected $table            = 'inventory_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['item_name', 'description', 'category_id', 'unit_of_measurement', 'reorder_level', 'item_image', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllInventoryItems(){
        try {
            //code...
            $data = $this->select('
                inventory_items.id as id,
                inventory_items.item_name as itemName,
                inventory_items.description as description,
                inventory_items_category.id as categoryId,
                inventory_items_category.category_name as categoryName,
                inventory_items.unit_of_measurement as unitOfMeasurement,
                inventory_items.reorder_level as reorderLevel,
            ')
            ->join('inventory_items_category', 'inventory_items_category.id = inventory_items.category_id')
            ->where('inventory_items.record_status', 'Accessible')
            ->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function getAllInventoryItemById($id){
        try {
            //code...
            $data = $this->select('
                inventory_items.id as id,
                inventory_items.item_name as itemName,
                inventory_items.description as description,
                inventory_items_category.id as categoryId,
                inventory_items_category.category_name as categoryName,
                inventory_items.unit_of_measurement as unitOfMeasurement,
                inventory_items.reorder_level as reorderLevel,
            ')
            ->join('inventory_items_category', 'inventory_items_category.id = inventory_items.category_id')
            ->where('inventory_items.record_status', 'Accessible')
            ->find($id);

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function insertInventoryItems($data){
        try {
            //code...
            $bind = [
                'item_name' => $data->itemName,
                'category_id' => $data->categoryId,
                'unit_of_measurement' => $data->unitOfMeasurement,
            ];

            if(isset($data->description)){
                $bind['description'] = $data->description;
            }

            if(isset($data->reorderLevel)){
                $bind['reorder_level'] = $data->reorderLevel;
            }

            if(isset($data->itemImage)){
                $bind['item_image'] = $data->itemImage;
            }

            $res = $this->insert($bind);
            return $res;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage(). ": ". $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function updateInventoryItems($id, $data){
        try {
            //code...
            $bind = [
                'item_name' => $data->itemName,
                'category_id' => $data->categoryId,
                'unit_of_measurement' => $data->unitOfMeasurement,
            ];

            if(isset($data->description)){
                $bind['description'] = $data->description;
            }

            if(isset($data->reorderLevel)){
                $bind['reorder_level'] = $data->reorderLevel;
            }

            if(isset($data->itemImage)){
                $bind['item_image'] = $data->itemImage;
            }

            $res = $this->update($id,$bind);
            return $res;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage(). ": ". $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function deleteInventoryItems($id){
        try {
            $res = $this->delete($id);
            return $res;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage(). ": ". $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }


}

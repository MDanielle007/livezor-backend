<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryItemsBatchModel extends Model
{
    protected $table            = 'inventory_item_batches';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['batch_code', 'inventory_item_id', 'initial_quantity', 'current_quantity', 'status', 'production_date', 'expiration_date', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllInventoryItemBatches(){
        try {
            //code...
            $data = $this->select('
                inventory_item_batches.id,
                inventory_item_batches.batch_code as batchCode,
                inventory_items.id as itemId,
                inventory_items.item_name as itemName,
                inventory_items.description,
                inventory_items_category.id as categoryId,
                inventory_items_category.name as categoryName,
                inventory_item_batches.initial_quantity as initialQuantity,
                inventory_item_batches.current_quantity as currentQuantity,
                inventory_items.unit_of_measurement as unitOfMeasurement,
                inventory_items.reorder_level as reorderLevel,
                inventory_items.item_image as itemImage,
                inventory_item_batches.production_date as productionDate,
                inventory_item_batches.expiration_date as expirationDate,
            ')
            ->join('inventory_items','inventory_items.id = inventory_item_batches.inventory_item_id')
            ->join('inventory_items_category', 'inventory_items_category.id = inventory_items.category_id')
            ->where('inventory_item_batches.record_status','Accessible')
            ->orderBy('inventory_items.item_name','ASC')
            ->orderBy('inventory_item_batches.batch_code','ASC')
            ->orderBy('inventory_item_batches.initial_quantity','ASC')
            ->orderBy('inventory_item_batches.current_quantity','ASC')
            ->orderBy('inventory_items_category.name', 'ASC')
            ->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function getAllInventoryItemBatchById($id){
        try {
            //code...
            $data = $this->select('
                inventory_item_batches.id,
                inventory_item_batches.batch_code as batchCode,
                inventory_items.id as itemId,
                inventory_items.item_name as itemName,
                inventory_items.description,
                inventory_items_category.id as categoryId,
                inventory_items_category.name as categoryName,
                inventory_item_batches.initial_quantity as initialQuantity,
                inventory_item_batches.current_quantity as currentQuantity,
                inventory_items.unit_of_measurement as unitOfMeasurement,
                inventory_items.reorder_level as reorderLevel,
                inventory_items.item_image as itemImage,
                inventory_item_batches.production_date as productionDate,
                inventory_item_batches.expiration_date as expirationDate,
            ')
            ->join('inventory_items','inventory_items.id = inventory_item_batches.inventory_item_id')
            ->join('inventory_items_category', 'inventory_items_category.id = inventory_items.category_id')
            ->where('inventory_item_batches.record_status','Accessible')
            ->orderBy('inventory_items.item_name','ASC')
            ->orderBy('inventory_item_batches.batch_code','ASC')
            ->orderBy('inventory_item_batches.initial_quantity','ASC')
            ->orderBy('inventory_item_batches.current_quantity','ASC')
            ->orderBy('inventory_items_category.name', 'ASC')
            ->find($id);

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function getAllInventoryItemBatchByItemId($id){
        try {
            $whereClause = [
                'inventory_item_batches.record_status' => 'Accessible',
                'inventory_items.id' => $id
            ];
            
            $data = $this->select('
                inventory_item_batches.id,
                inventory_item_batches.batch_code as batchCode,
                inventory_items.id as itemId,
                inventory_items.item_name as itemName,
                inventory_items.description,
                inventory_items_category.name as categoryName,
                inventory_item_batches.initial_quantity as initialQuantity,
                inventory_item_batches.current_quantity as currentQuantity,
                inventory_items.unit_of_measurement as unitOfMeasurement,
                inventory_items.reorder_level as reorderLevel,
                inventory_items.item_image as itemImage,
                inventory_item_batches.production_date as productionDate,
                inventory_item_batches.expiration_date as expirationDate,
            ')
            ->join('inventory_items','inventory_items.id = inventory_item_batches.inventory_item_id')
            ->join('inventory_items_category', 'inventory_items_category.id = inventory_items.category_id')
            ->where($whereClause)
            ->orderBy('inventory_items.item_name','ASC')
            ->orderBy('inventory_item_batches.batch_code','ASC')
            ->orderBy('inventory_item_batches.initial_quantity','ASC')
            ->orderBy('inventory_item_batches.current_quantity','ASC')
            ->orderBy('inventory_items_category.name', 'ASC')
            ->find();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function getAllInventoryItemBatchByBatchCode($code){
        try {
            $whereClause = [
                'inventory_item_batches.record_status' => 'Accessible',
                'inventory_item_batches.batch_code' => $code
            ];
            
            $data = $this->select('
                inventory_item_batches.id,
                inventory_item_batches.batch_code as batchCode,
                inventory_items.id as itemId,
                inventory_items.item_name as itemName,
                inventory_items.description,
                inventory_items_category.id as categoryId,
                inventory_items_category.name as categoryName,
                inventory_item_batches.initial_quantity as initialQuantity,
                inventory_item_batches.current_quantity as currentQuantity,
                inventory_items.unit_of_measurement as unitOfMeasurement,
                inventory_items.reorder_level as reorderLevel,
                inventory_items.item_image as itemImage,
                inventory_item_batches.production_date as productionDate,
                inventory_item_batches.expiration_date as expirationDate,
            ')
            ->join('inventory_items','inventory_items.id = inventory_item_batches.inventory_item_id')
            ->join('inventory_items_category', 'inventory_items_category.id = inventory_items.category_id')
            ->where($whereClause)
            ->orderBy('inventory_items.item_name','ASC')
            ->orderBy('inventory_item_batches.batch_code','ASC')
            ->orderBy('inventory_item_batches.initial_quantity','ASC')
            ->orderBy('inventory_item_batches.current_quantity','ASC')
            ->orderBy('inventory_items_category.name', 'ASC')
            ->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function insertInventoryItemBatch($data){
        try {
            $bind = [
                'batch_code' => $data->batchCode,
                'inventory_item_id' => $data->itemId,
                'initial_quantity' => $data->initialQuantity,
                'current_quantity' => $data->initialQuantity,
                'production_date' => $data->productionDate,
                'expiration_date' => $data->expirationDate,
            ];  

            if(isset($data->status)){
                $bind['status'] = $data->status;
            }

            $result = $this->insert($bind);
            
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function updateInventoryItemBatch($id, $data){
        try {
            $bind = [
                'batch_code' => $data->batchCode,
                'inventory_item_id' => $data->itemId,
                'initial_quantity' => $data->initialQuantity,
                'current_quantity' => $data->currentQuantity,
                'production_date' => $data->productionDate,
                'expiration_date' => $data->expirationDate,
            ];  

            if(isset($data->status)){
                $bind['status'] = $data->status;
            }

            $this->where('id', $id);
            $result = $this->update($bind);
            
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function deleteInventoryItemBatch($id){
        try {
            //code...
            $result = $this->delete($id);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }
}

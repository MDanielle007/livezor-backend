<?php

namespace App\Models;

use CodeIgniter\Model;

class FarmerUserAssociationModel extends Model
{
    protected $table            = 'farmer_user_associations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'farmer_association_id', 'farmer_id', 'position', 'join_date', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllFarmerUserAssociations(){
        try {
            $data = $this->select('
                id,
                user_id as userId,
                farmer_association_id as farmerAssociationId,
                farmer_id as farmerId,
                position,
                join_date as joinDate,
            ')
                ->where('record_status', 'Accessible')
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerUserAssociationsById($id)
    {
        try {
            $data = $this->select('
                id,
                user_id as userId,
                farmer_association_id as farmerAssociationId,
                farmer_id as farmerId,
                position,
                join_date as joinDate,
            ')
                ->where('record_status', 'Accessible')
                ->where('id', $id)
                ->first();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }   
    
    public function getFarmerUserAssociationsByUser($id)
    {
        try {
            $data = $this->select('
                farmer_user_associations.id,
                farmer_associations.farmer_association_name as farmerAssociationName,
                farmer_associations.overview,
                farmer_user_associations.farmer_id as farmerId,
                farmer_user_associations.position,
                farmer_user_associations.join_date as joinDate,
            ')
                ->join('farmer_associations','farmer_associations.id = farmer_user_associations.farmer_association_id')
                ->where('farmer_user_associations.record_status', 'Accessible')
                ->where('farmer_user_associations.user_id', $id)
                ->orderBy('farmerAssociationName')
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
        }
    }
    
    public function getFarmerUserAssociationsByUserId($id)
    {
        try {
            $data = $this->select('
                farmer_user_associations.id,
                farmer_associations.farmer_association_name as farmerAssociationName,
                farmer_associations.overview,
                farmer_user_associations.farmer_id as farmerId,
                farmer_user_associations.position,
                farmer_user_associations.join_date as joinDate,
            ')
                ->join('farmer_associations','farmer_associations.id = farmer_user_associations.farmer_association_id')
                ->join('user_accounts', 'user_accounts.id = farmer_user_associations.user_id')
                ->where('farmer_user_associations.record_status', 'Accessible')
                ->where('user_accounts.user_id', $id)
                ->orderBy('farmerAssociationName')
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
        }
       }   

    public function insertFarmerUserAssociation($data){
        try {
            $bind = [
                'user_id' => $data->userId,
                'farmer_association_id' => $data->farmerAssociationId,
            ];

            if (isset($data->farmerId)) {
                $bind['farmer_id'] = $data->farmerId;
            }

            if (isset($data->position)) {
                $bind['position'] = $data->position;
            }

            if (isset($data->joinDate)) {
                $bind['join_date'] = $data->joinDate;
            }

            $res = $this->insert($bind);

            return $res;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateFarmerUserAssociation($id,$data){
        try {
            $bind = [
                'user_id' => $data->userId,
                'farmer_association_id' => $data->farmerAssociationId,
            ];

            if (isset($data->farmerId)) {
                $bind['farmer_id'] = $data->farmerId;
            }

            if (isset($data->position)) {
                $bind['position'] = $data->position;
            }

            if (isset($data->joinDate)) {
                $bind['join_date'] = $data->joinDate;
            }

            if(isset($data->recordStatus)){
                $bind['record_status'] = $data->recordStatus;
            }

            $res = $this->update($id,$bind);

            return $res;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteFarmerUserAssociation($id){
        try {
            $res = $this->delete($id);

            return $res;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

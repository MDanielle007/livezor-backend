<?php

namespace App\Models;

use CodeIgniter\Model;

class FarmerAssociationModel extends Model
{
    protected $table            = 'farmer_associations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = ['farmer_association_name', 'overview', 'sitio', 'barangay', 'city', 'province', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllFarmerAssociationsComplete()
    {
        try {
            $data = $this->select('
                id,
                farmer_association_name as farmerAssociationName,
                overview,
                sitio,
                barangay,
                city,
                province,
            ')
                ->where('record_status', 'Accessible')
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerAssociationComplete($id)
    {
        try {
            $data = $this->select('
                id,
                farmer_association_name as farmerAssociationName,
                overview,
                sitio,
                barangay,
                city,
                province,
            ')
                ->where('record_status', 'Accessible')
                ->where('id', $id)
                ->first();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerAssociation()
    {
        try {
            $data = $this->select('
                id,
                farmer_association_name as farmerAssociationName
            ')
                ->where('record_status', 'Accessible')
                ->first();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerAssociationByName($name){
        try {
            $data = $this->select('
                id,
                farmer_association_name as farmerAssociationName
            ')
                ->where('record_status', 'Accessible')
                ->where('farmer_association_name', $name)
                ->first();

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }


    public function insertFarmerAssociation($data){
        try {
            $bind = [
                'farmer_association_name' => $data->farmerAssociationName
            ];

            if (isset($data->overview)) {
                $bind['overview'] = $data->overview;
            }

            if (isset($data->sitio)) {
                $bind['sitio'] = $data->sitio;
            }

            if (isset($data->barangay)) {
                $bind['barangay'] = $data->barangay;
            }

            if (isset($data->city)) {
                $bind['city'] = $data->city;
            }

            if (isset($data->province)) {
                $bind['province'] = $data->province;
            }

            $res = $this->insert($bind);

            return $res;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateFarmerAssociation($id,$data){
        try {
            $bind = [
                'farmer_association_name' => $data->farmerAssociationName
            ];

            if (isset($data->overview)) {
                $bind['overview'] = $data->overview;
            }

            if (isset($data->sitio)) {
                $bind['sitio'] = $data->sitio;
            }

            if (isset($data->barangay)) {
                $bind['barangay'] = $data->barangay;
            }

            if (isset($data->city)) {
                $bind['city'] = $data->city;
            }

            if (isset($data->province)) {
                $bind['province'] = $data->province;
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

    public function deleteFarmerAssociation($id){
        try {
            $res = $this->delete($id);

            return $res;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

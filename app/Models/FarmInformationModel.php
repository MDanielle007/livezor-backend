<?php

namespace App\Models;

use CodeIgniter\Model;

class FarmInformationModel extends Model
{
    protected $table            = 'farm_information';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['farmer_id', 'farm_uid', 'farm_name', 'sitio', 'barangay', 'city', 'province', 'total_area', 'total_area_unit', 'farm_type', 'latitude', 'longitude', 'date_established', 'contact_number', 'owner_type', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllFarmData(){
        try {
            //code...
            $data = $this->select([
                'id',
                'user_accounts.user_id as farmerUserId,',
                'CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName',
                'farm_information.farm_uid as farmUID',
                'farm_information.farm_name as farmName',
                'farm_information.sitio',
                'farm_information.barangay',
                'farm_information.city',
                'farm_information.province',
                'farm_information.total_area as totalArea',
                'farm_information.total_area_unit as totalAreaUnit',
                'farm_information.farm_type as farmType',
                'farm_information.latitude as latitude',
                'farm_information.longitude as longitude',
                'farm_information.date_established as dateEstablished',
                'farm_information.contact_number as contactNumber',
                'farm_information.owner_type as ownerType'
            ])
            ->join('user_accounts', 'user_accounts.id = farm_information.farmer_id')
            ->orderBy('farm_information.farm_uid', 'ASC')
            ->findAll();
            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    public function getAllFarmDataById($id){
        try {
            //code...
            $data = $this->select([
                'id',
                'user_accounts.user_id as farmerUserId,',
                'CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName',
                'farm_information.farm_uid as farmUID',
                'farm_information.farm_name as farmName',
                'farm_information.sitio',
                'farm_information.barangay',
                'farm_information.city',
                'farm_information.province',
                'farm_information.total_area as totalArea',
                'farm_information.total_area_unit as totalAreaUnit',
                'farm_information.farm_type as farmType',
                'farm_information.latitude as latitude',
                'farm_information.longitude as longitude',
                'farm_information.date_established as dateEstablished',
                'farm_information.contact_number as contactNumber',
                'farm_information.owner_type as ownerType'
            ])
            ->join('user_accounts', 'user_accounts.id = farm_information.farmer_id')
            ->find($id);

            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    public function getAllFarmDataByFarmer($farmerId){
        try {
            //code...
            $data = $this->select([
                'farm_information.id',
                'user_accounts.user_id as farmerUserId',
                'CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName',
                'farm_information.farm_uid as farmUID',
                'farm_information.farm_name as farmName',
                'farm_information.sitio',
                'farm_information.barangay',
                'farm_information.city',
                'farm_information.province',
                'farm_information.total_area as totalArea',
                'farm_information.total_area_unit as totalAreaUnit',
                'farm_information.farm_type as farmType',
                'farm_information.latitude as latitude',
                'farm_information.longitude as longitude',
                'farm_information.date_established as dateEstablished',
                'farm_information.contact_number as contactNumber',
                'farm_information.owner_type as ownerType'
            ])
            ->join('user_accounts', 'user_accounts.id = farm_information.farmer_id')
            ->where('farm_information.farmer_id', $farmerId)
            ->orderBy('farm_information.farm_uid', 'ASC')
            ->findAll();
            
            return $data;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    public function insertFarm($data){
        try {
            $bind = (object)[
                'farmer_id' => $data->farmerId,
                'farm_name' => $data->farmName,
                'farm_uid' => $data->farmUID,
                'sitio' => $data->sitio,
                'barangay' => $data->barangay,
                'city' => $data->city,
                'province' => $data->province,
                'total_area' => $data->totalArea,
                'total_area_unit' => $data->totalAreaUnit,
                'farm_type' => $data->farmType,
                'latitude' => $data->latitude,
                'longitude' => $data->longitude,
                'date_established' => $data->dateEstablished,
                'contact_number' => $data->contactNumber,
                'owner_type' => $data->ownerType,
            ];

            return $this->insert($bind);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function updateFarm($id, $data){
        try {
            $bind = (object)[
                'farm_name' => $data->farmName,
                'sitio' => $data->sitio,
                'barangay' => $data->barangay,
                'city' => $data->city,
                'province' => $data->province,
                'total_area' => $data->totalArea,
                'total_area_unit' => $data->totalAreaUnit,
                'farm_type' => $data->farmType,
                'latitude' => $data->latitude,
                'longitude' => $data->longitude,
                'date_established' => $data->dateEstablished,
                'contact_number' => $data->contactNumber,
                'owner_type' => $data->ownerType,
            ];

            return $this->update($id,$bind);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function deleteFarm($id)
    {
        $result = $this->delete($id);
        return $result;
    }

}

<?php

namespace App\Models;

use CodeIgniter\Model;

class EggDistributionModel extends Model
{
    protected $table = 'egg_distribution';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['number_of_eggs', 'poultry_type', 'poultry_breed', 'recipient_user_id', 'recipient_first_name', 'recipient_middle_name', 'recipient_last_name', 'recipient_barangay', 'recipient_city_municipality', 'recipient_province', 'recipient_contact_number', 'date_of_distribution', 'remarks', 'farmer_association', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
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

    public function getAllEggDistributions()
    {
        try {
            $whereClause = [
                'record_status' => 'Accessible',
            ];

            $eggDistribution = $this->select([
                'id,
                number_of_eggs as numberOfEggs,
                poultry_type as poultryType,
                poultry_breed as poultryBreed,
                COALESCE(recipient_user_id, "") as recipientUserId,
                recipient_first_name as recipientFirstName,
                COALESCE(recipient_middle_name, "") as recipientMiddleName,
                recipient_last_name as recipientLastName,
                CONCAT(recipient_first_name, " ", recipient_last_name) as recipientFullName,
                recipient_barangay as recipientBarangay,
                recipient_city_municipality as recipientCityMunicipality,
                recipient_province as recipientProvince,
                CONCAT(recipient_barangay, ", ", recipient_city_municipality, ", ", recipient_province) as recipientAddress,
                recipient_contact_number as recipientContactNumber,
                date_of_distribution as dateOfDistribution,
                COALESCE(farmer_association, "") as farmerAssociation,
                remarks'
            ])
                ->where($whereClause)
                ->orderBy('date_of_distribution', 'DESC')
                ->orderBy('farmerAssociation', 'ASC')
                ->orderBy('recipient_user_id', 'ASC')
                ->orderBy('recipient_last_name', 'ASC')
                ->orderBy('recipient_first_name', 'ASC')
                ->orderBy('number_of_eggs', 'DESC')
                ->findAll();

            return $eggDistribution;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getEggDistributionById($id)
    {
        try {
            $whereClause = [
                'record_status' => 'Accessible'
            ];

            $eggDistribution = $this->select('
                number_of_eggs as numberOfEggs,
                poultry_type as poultryType,
                poultry_breed as poultryBreed,
                COALESCE(NULLIF(recipient_user_id, ""), "") as recipientUserId,
                recipient_first_name as recipientFirstName,
                COALESCE(NULLIF(recipient_middle_name, ""), "") as recipientMiddleName,
                recipient_last_name as recipientLastName,
                CONCAT(recipient_first_name, " ", recipient_last_name) as recipientFullName,
                recipient_barangay as recipientBarangay,
                recipient_city_municipality as recipientCityMunicipality,
                recipient_province as recipientProvince,
                recipient_contact_number as recipientContactNumber,
                date_of_distribution as dateOfDistribution,
                COALESCE(NULLIF(farmer_association, ""), "") as farmerAssociation,
                remarks
            ')
                ->where($whereClause)
                ->find($id);

            return $eggDistribution;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertEggDistribution($data)
    {
        try {
            $bind = [
                'number_of_eggs' => $data->numberOfEggs,
                'poultry_type' => $data->poultryType,
                'poultry_breed' => $data->poultryBreed,
                'recipient_user_id' => $data->recipientUserId,
                'recipient_first_name' => $data->recipientFirstName,
                'recipient_middle_name' => $data->recipientMiddleName,
                'recipient_last_name' => $data->recipientLastName,
                'recipient_barangay' => $data->recipientBarangay,
                'recipient_city_municipality' => $data->recipientCityMunicipality,
                'recipient_province' => $data->recipientProvince,
                'recipient_contact_number' => $data->recipientContactNumber,
                'date_of_distribution' => $data->dateOfDistribution,
                'farmer_association' => $data->farmerAssociation,
                'remarks' => $data->remarks,
            ];

            $result = $this->insert($bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateEggDistribution($id, $data)
    {
        try {
            $bind = [
                'number_of_eggs' => $data->numberOfEggs,
                'poultry_type' => $data->poultryType,
                'poultry_breed' => $data->poultryBreed,
                'recipient_user_id' => $data->recipientUserId,
                'recipient_first_name' => $data->recipientFirstName,
                'recipient_middle_name' => $data->recipientMiddleName,
                'recipient_last_name' => $data->recipientLastName,
                'recipient_barangay' => $data->recipientBarangay,
                'recipient_city_municipality' => $data->recipientCityMunicipality,
                'recipient_province' => $data->recipientProvince,
                'recipient_contact_number' => $data->recipientContactNumber,
                'date_of_distribution' => $data->dateOfDistribution,
                'farmer_association' => $data->farmerAssociation,
                'remarks' => $data->remarks,
            ];

            $result = $this->update($id, $bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateEggDistributionRecordStatus($id, $status)
    {
        try {
            $bind = [
                'record_status' => $status
            ];

            $result = $this->update($id, $bind);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteEggDistribution($id)
    {
        try {
            $result = $this->delete($id);

            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

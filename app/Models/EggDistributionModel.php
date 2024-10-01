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
    protected $allowedFields = ['number_of_eggs', 'poultry_type_id', 'poultry_breed', 'recipient_user_id', 'recipient_first_name', 'recipient_middle_name', 'recipient_last_name', 'recipient_barangay', 'recipient_city_municipality', 'recipient_province', 'recipient_contact_number', 'date_of_distribution', 'remarks', 'farmer_association', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

            $eggDistribution = $this->select('
                egg_distribution.id,
                egg_distribution.number_of_eggs as numberOfEggs,
                egg_distribution.poultry_type_id as poultryTypeId,
                livestock_types.livestock_type_name as livestockTypeName,
                egg_distribution.poultry_breed as poultryBreed,
                COALESCE(egg_distribution.recipient_user_id, "") as recipientUserId,
                egg_distribution.recipient_first_name as recipientFirstName,
                COALESCE(egg_distribution.recipient_middle_name, "") as recipientMiddleName,
                egg_distribution.recipient_last_name as recipientLastName,
                CONCAT(egg_distribution.recipient_first_name, " ", egg_distribution.recipient_last_name) as recipientFullName,
                egg_distribution.recipient_barangay as recipientBarangay,
                egg_distribution.recipient_city_municipality as recipientCityMunicipality,
                egg_distribution.recipient_province as recipientProvince,
                CONCAT(egg_distribution.recipient_barangay, ", ", egg_distribution.recipient_city_municipality, ", ", egg_distribution.recipient_province) as recipientAddress,
                egg_distribution.recipient_contact_number as recipientContactNumber,
                egg_distribution.date_of_distribution as dateOfDistribution,
                COALESCE(egg_distribution.farmer_association, "") as farmerAssociation,
                egg_distribution.remarks
            ')
                ->join('livestock_types', 'livestock_types.id = egg_distribution.poultry_type_id')
                ->where($whereClause)
                ->orderBy('date_of_distribution', 'DESC')
                ->orderBy('number_of_eggs', 'DESC')
                ->orderBy('farmerAssociation', 'ASC')
                ->orderBy('recipient_user_id', 'ASC')
                ->orderBy('recipient_last_name', 'ASC')
                ->orderBy('recipient_first_name', 'ASC')
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
                egg_distribution.id,
                egg_distribution.number_of_eggs as numberOfEggs,
                egg_distribution.poultry_type_id as poultryTypeId,
                livestock_types.livestock_type_name as livestockTypeName,
                egg_distribution.poultry_breed as poultryBreed,
                COALESCE(egg_distribution.recipient_user_id, "") as recipientUserId,
                egg_distribution.recipient_first_name as recipientFirstName,
                COALESCE(egg_distribution.recipient_middle_name, "") as recipientMiddleName,
                egg_distribution.recipient_last_name as recipientLastName,
                CONCAT(egg_distribution.recipient_first_name, " ", egg_distribution.recipient_last_name) as recipientFullName,
                egg_distribution.recipient_barangay as recipientBarangay,
                egg_distribution.recipient_city_municipality as recipientCityMunicipality,
                egg_distribution.recipient_province as recipientProvince,
                CONCAT(egg_distribution.recipient_barangay, ", ", egg_distribution.recipient_city_municipality, ", ", egg_distribution.recipient_province) as recipientAddress,
                egg_distribution.recipient_contact_number as recipientContactNumber,
                egg_distribution.date_of_distribution as dateOfDistribution,
                COALESCE(egg_distribution.farmer_association, "") as farmerAssociation,
                egg_distribution.remarks
            ')
                ->join('livestock_types', 'livestock_types.id = egg_distribution.poultry_type_id')
                ->where($whereClause)
                ->orderBy('date_of_distribution', 'DESC')
                ->orderBy('number_of_eggs', 'DESC')
                ->orderBy('farmerAssociation', 'ASC')
                ->orderBy('recipient_user_id', 'ASC')
                ->orderBy('recipient_last_name', 'ASC')
                ->orderBy('recipient_first_name', 'ASC')
                ->find($id);

            return $eggDistribution;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function insertEggDistribution($data)
    {
        try {
            $bind = [
                'number_of_eggs' => $data->numberOfEggs,
                'poultry_type_id' => $data->poultryTypeId,
                'recipient_first_name' => $data->recipientFirstName,
                'recipient_middle_name' => $data->recipientMiddleName,
                'recipient_last_name' => $data->recipientLastName,
                'recipient_barangay' => $data->recipientBarangay,
                'recipient_city_municipality' => $data->recipientCityMunicipality,
                'recipient_province' => $data->recipientProvince,
                'recipient_contact_number' => $data->recipientContactNumber,
                'date_of_distribution' => $data->dateOfDistribution,
                'remarks' => $data->remarks,
            ];

            if (isset($data->recipientUserId)) {
                $bind['recipient_user_id'] = $data->recipientUserId;
            }

            if (isset($data->poultryBreed)) {
                $bind['poultry_breed'] = $data->poultryBreed;
            }

            if (isset($data->farmerAssociation)) {
                $bind['farmer_association'] = $data->farmerAssociation;
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

    public function updateEggDistribution($id, $data)
    {
        try {
            $bind = [
                'number_of_eggs' => $data->numberOfEggs,
                'poultry_type_id' => $data->poultryTypeId,
                'recipient_first_name' => $data->recipientFirstName,
                'recipient_middle_name' => $data->recipientMiddleName,
                'recipient_last_name' => $data->recipientLastName,
                'recipient_barangay' => $data->recipientBarangay,
                'recipient_city_municipality' => $data->recipientCityMunicipality,
                'recipient_province' => $data->recipientProvince,
                'recipient_contact_number' => $data->recipientContactNumber,
                'date_of_distribution' => $data->dateOfDistribution,
                'remarks' => $data->remarks,
            ];

            if (isset($data->recipientUserId)) {
                $bind['recipient_user_id'] = $data->recipientUserId;
            }

            if (isset($data->poultryBreedId)) {
                $bind['poultry_breed_id'] = $data->poultryBreedId;
            }

            if (isset($data->farmerAssociation)) {
                $bind['farmer_association'] = $data->farmerAssociation;
            }

            $result = $this->update($id, $bind);
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
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

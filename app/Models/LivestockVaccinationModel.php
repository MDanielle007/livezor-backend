<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockVaccinationModel extends Model
{
    protected $table = 'livestock_vaccinations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['vaccine_administrator_id', 'livestock_id', 'vaccination_name', 'vaccination_description', 'vaccination_date', 'record_status'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
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

    public function getAllLivestockVaccinations()
    {
        $livestockVaccinations = $this->findAll();
        return $livestockVaccinations;
    }

    public function getLivestockVaccination($id)
    {
        $livestockVaccination = $this->find($id);
        return $livestockVaccination;
    }

    public function getAllFarmerLivestockVaccinations($userId)
    {
        $whereClause = [
            'vaccine_administrator_id' => $userId,
            'record_status' => 'Accessible'
        ];

        $livestockVaccinations = $this->where($whereClause)->findAll();
        return $livestockVaccinations;
    }

    public function insertLivestockVaccination($data)
    {
        $bind = [
            'vaccine_administrator_id' => $data->vaccineAdministratorId,
            'livestock_id' => $data->livestockId,
            'vaccination_name' => $data->vaccinationName,
            'vaccination_description' => $data->vaccinationDescription,
            'vaccination_date' => $data->vaccinationDate
        ];

        $result = $this->insert($bind);

        return $result;
    }

    public function updateLivestockVaccination($id, $data)
    {
        $bind = [
            'vaccine_administrator_id' => $data->vaccineAdministratorId,
            'livestock_id' => $data->livestockId,
            'vaccination_name' => $data->vaccinationName,
            'vaccination_description' => $data->vaccinationDescription,
            'vaccination_date' => $data->vaccinationDate
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function updateLivestockVaccinationRecordStatus($id, $status){
        $bind = [
            'record_status' => $status,
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function deleteLivestockVaccination($id){
        $result = $this->delete($id);
        return $result;
    }
}

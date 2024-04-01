<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockMortalityModel extends Model
{
    protected $table = 'livestock_mortalities';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['livestock_id', 'farmer_id', 'cause_of_death', 'mortality_remarks', 'date_of_death', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllLivestockMortalities()
    {
        $whereClause = [
            'livestock_mortalities.record_status' => 'Accessible'
        ];

        $livestockMortalities = $this->select(
            'livestock_mortalities.id,
            livestock_mortalities.livestock_id as livestockId,
            livestocks.livestock_tag_id as livestockTagId,
            livestock_types.livestock_type_name as livestockType,
            livestock_mortalities.farmer_id as farmerId,
            CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName,
            livestock_mortalities.cause_of_death as causeOfDeath,
            livestock_mortalities.mortality_remarks as remarks,
            livestock_mortalities.date_of_death as dateOfDeath'
        )->join('livestocks', 'livestocks.id = livestock_mortalities.livestock_id')
            ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
            ->join('user_accounts', 'user_accounts.id = livestock_mortalities.dewormer_id')
            ->where($whereClause)
            ->orderBy('livestock_mortalities.created_at', 'DESC')
            ->findAll();
        return $livestockMortalities;
    }

    public function getLivestockMortality($id)
    {
        $whereClause = [
            'livestock_mortalities.record_status' => 'Accessible'
        ];

        $livestockMortality = $this->select(
            'livestock_mortalities.id,
            livestock_mortalities.livestock_id as livestockId,
            livestocks.livestock_tag_id as livestockTagId,
            livestock_types.livestock_type_name as livestockType,
            livestock_mortalities.farmer_id as farmerId,
            CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName,
            livestock_mortalities.cause_of_death as causeOfDeath,
            livestock_mortalities.mortality_remarks as remarks,
            livestock_mortalities.date_of_death as dateOfDeath'
        )->join('livestocks', 'livestocks.id = livestock_mortalities.livestock_id')
            ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
            ->join('user_accounts', 'user_accounts.id = livestock_mortalities.dewormer_id')
            ->where($whereClause)
            ->find($id);
        return $livestockMortality;
    }

    public function getAllFarmerLivestockMortalities($userId)
    {
        $whereClause = [
            'livestock_mortalities.farmer_id' => $userId,
            'livestock_mortalities.record_status' => 'Accessible'
        ];

        $livestockMortalities = $this->select(
            'livestock_mortalities.id,
            livestock_mortalities.livestock_id as livestockId,
            livestocks.livestock_tag_id as livestockTagId,
            livestock_types.livestock_type_name as livestockType,
            livestock_mortalities.cause_of_death as causeOfDeath,
            livestock_mortalities.mortality_remarks as remarks,
            livestock_mortalities.date_of_death as dateOfDeath'
        )
            ->join('livestocks', 'livestocks.id = livestock_mortalities.livestock_id')
            ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
            ->where($whereClause)
            ->orderBy('livestock_mortalities.created_at', 'DESC')
            ->findAll();

        return $livestockMortalities;
    }

    public function getAllCompleteLivestockMortalities()
    {
        $whereClause = [
            'livestock_mortalities.record_status' => 'Accessible'
        ];

        $livestockMortalities = $this->select(
            'livestock_mortalities.id,
            livestock_mortalities.livestock_id as livestockId,
            livestocks.livestock_tag_id as livestockTagId,
            livestocks.livestock_type_id as livestockTypeId,
            livestock_mortalities.farmer_id as farmerId,
            user_accounts.user_id as farmerUserId,
            CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as farmerName,
            livestock_mortalities.cause_of_death as causeOfDeath,
            livestock_mortalities.mortality_remarks as remarks,
            livestock_mortalities.date_of_death as dateOfDeath'
        )->join('livestocks', 'livestocks.id = livestock_mortalities.livestock_id')
            ->join('user_accounts', 'user_accounts.id = livestock_mortalities.farmer_id')
            ->where($whereClause)->findAll();
        return $livestockMortalities;
    }

    public function insertLivestockMortality($data)
    {
        $bind = [
            'farmer_id' => $data->farmerId,
            'livestock_id' => $data->livestockId,
            'cause_of_death' => $data->causeOfDeath,
            'mortality_remarks' => $data->remarks,
            'date_of_death' => $data->dateOfDeath,
        ];

        $result = $this->insert($bind);

        return $result;
    }

    public function updateLivestockMortality($id, $data)
    {
        $bind = [
            'farmer_id' => $data->farmerId,
            'livestock_id' => $data->livestockId,
            'cause_of_death' => $data->causeOfDeath,
            'mortality_remarks' => $data->remarks,
            'date_of_death' => $data->dateOfDeath,
        ];
        $result = $this->update($id, $bind);

        return $result;
    }

    public function updateLivestockMortalityRecordStatus($id, $status)
    {
        $bind = [
            'record_status' => $status,
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function deleteLivestockMortality($id)
    {
        $result = $this->delete($id);
        return $result;
    }
}

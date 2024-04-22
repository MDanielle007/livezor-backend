<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockAdvisoriesModel extends Model
{
    protected $table = 'livestock_advisories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['subject', 'content', 'target_farmer_id', 'is_general', 'date_published', 'is_read', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllLivestockAdvisories()
    {
        try {
            $livestockAdvisories = $this->select(
                'id,
                subject,
                content,
                target_farmer_id as targetFarmerId,
                is_general as isGeneral,
                IF(is_general, "General", "Farmer") as advisoryType,
                date_published as datePublished
            ')->orderBy('date_published', 'DESC')->findAll();

            return $livestockAdvisories;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getLivestockAdvisory($id)
    {
        $livestockAdvisory = $this->select(
            'id,
            subject,
            content,
            target_farmer_id as targetFarmerId,
            is_general as isGeneral,
            IF(is_general, "General", "Farmer") as advisoryType,
            date_published as datePublished,
            is_read as isRead'
        )->find($id);
        return $livestockAdvisory;
    }

    public function getAllFarmerLivestockAdvisories($userid)
    {
        $livestockAdvisories = $this->select(
            'id,
            subject,
            content,
            IF(is_general, "General", "Farmer") as advisoryType,
            date_published as datePublished,
            is_read as isRead'
        )->where('target_farmer_id', $userid)->orderBy('date_published','DESC')->findAll();
        return $livestockAdvisories;
    }

    public function getAllGeneralLivestockAdvisories()
    {
        $livestockAdvisories = $this
            ->select(
                'id,
            subject,
            content,
            date_published as datePublished,
            is_read as isRead,
            record_status as recordStatus'
            )->where('is_general', 1)->findAll();
        return $livestockAdvisories;
    }

    public function sendLivestockAdvisory($data)
    {
        $targetFarmerId = $data->targetFarmerId ?? null;

        $bind = [
            'subject' => $data->subject,
            'content' => $data->content,
            'is_general' => $data->isGeneral
        ];

        if ($targetFarmerId !== null) {
            $bind['target_farmer_id'] = $targetFarmerId;
        }

        $result = $this->insert($bind);

        return $result;
    }

    public function updateLivestockAdvisory($id, $data)
    {
        $bind = [
            'subject' => $data->subject,
            'content' => $data->content,
            'is_general' => $data->isGeneral,
        ];

        if ($data->isGeneral === false) {
            $bind['target_farmer_id'] = $data->targetFarmerId;
        }

        $result = $this->update($id, $bind);

        return $result;
    }

    public function updateLivestockAdvisoryReadStatus($id, $data)
    {
        $bind = [
            'is_read' => $data->isRead
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function updateLivestockAdvisoryRecordStatus($id, $status)
    {
        $bind = [
            'record_status' => $status
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function deleteLivestockAdvisory($id)
    {
        $result = $this->delete($id);
        return $result;
    }
}

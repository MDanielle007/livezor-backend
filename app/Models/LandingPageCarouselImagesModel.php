<?php

namespace App\Models;

use CodeIgniter\Model;

class LandingPageCarouselImagesModel extends Model
{
    protected $table = 'landingpage_carousel_images';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['title', 'subtitle', 'image', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getLandingPageCarouselImages()
    {
        try {
            $data = $this->select([
                'title',
                'subtitle',
                'image'
            ])
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    public function getSettingCarouselImages()
    {
        try {
            $data = $this->select([
                'id',
                'title',
                'subtitle',
                'image'
            ])
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    public function insertLandingPageCarouselImages($data)
    {
        try {
            return $this->insert($data);
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    public function updateLandingPageCarouselImages($id, $data)
    {
        try {
            $bind = (object) [
                'title' => $data->title,
                'subtitle' => $data->subtitle,
            ];

            return $this->update($id,$bind);
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    public function deleteLandingPageCarouselImages($id)
    {
        try {
            return $this->delete($id);
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }
}

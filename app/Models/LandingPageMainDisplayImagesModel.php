<?php

namespace App\Models;

use CodeIgniter\Model;

class LandingPageMainDisplayImagesModel extends Model
{
    protected $table = 'landingpage_main_display_images';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['image_filename', 'order_num', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getLandingPageMainDisplayImages()
    {
        try {
            $data = $this->select('
                image_filename as imageFilename,
                order_num as orderNum
            ')
                ->orderBy('order_num', 'ASC')
                ->findAll();

            return $data;
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    public function getSettingDisplayImages()
    {
        try {
            $data = $this->select('
                id,
                image_filename as imageFilename,
                order_num as orderNum
            ')->findAll();

            return $data;
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    

    public function insertLandingPageMainDisplayImages($data)
    {
        try {
            //code...
            $bind = (object) [
                'image_filename' => $data->imageFilename,
                'order_num' => $data->orderNum,
            ];

            return $this->insert($bind);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function updateLandingPageMainDisplayImages($id, $data)
    {
        try {
            //code...
            $bind = (object) [
                'order_num' => $data->orderNum,
            ];

            return $this->update($id, $bind);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function deleteLandingPageMainDisplayImages($id){
        try {
            //code...
            $res = $this->delete($id);
            return $res;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class LandingPageMainDisplayTextModel extends Model
{
    protected $table = 'landingpage_display_title';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['main_display_title', 'main_display_subtitle', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getLandingPageMainDisplayTexts()
    {
        try {
            return $this->select([
                'main_display_title as mainDisplayTitle',
                'main_display_subtitle as mainDisplaySubtitle'
            ])->first();
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function getSettingsDisplayTexts()
    {
        try {
            return $this->select([
                'id',
                'main_display_title as mainDisplayTitle',
                'main_display_subtitle as mainDisplaySubtitle'
            ])->first();
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }

    public function updateLandingPageMainDisplayTexts($id, $data)
    {
        try {
            //code...
            $bind = (object) [
                'main_display_title' => $data->mainDisplayTitle,
                'main_display_subtitle' => $data->mainDisplaySubtitle
            ];

            return $this->update($id, $bind);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }
}

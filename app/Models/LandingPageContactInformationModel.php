<?php

namespace App\Models;

use CodeIgniter\Model;

class LandingPageContactInformationModel extends Model
{
    protected $table = 'landingpage_contact_info';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['setting', 'value', 'created_at', 'updated_at', 'deleted_at'];

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

    // Retrieve and decode data
    public function getSetting($setting)
    {
        $result = $this->where('setting', $setting)->first();
        if ($result) {
            // Decode JSON if value is an array
            return $this->isJson($result['value']) ? json_decode($result['value'], true) : $result['value'];
        }
        return null;
    }

    // Helper function to check if a string is JSON
    private function isJson($string)
    {
        json_decode($string);
        return (json_last_error() === JSON_ERROR_NONE);
    }

    public function saveSetting($setting, $value)
    {
        try {
            $data = [
                'value' => is_array($value) ? json_encode($value) : $value,
            ];
    
            return $this->where('setting', $setting)->set($data)->update();
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return null;
        }
    }
}

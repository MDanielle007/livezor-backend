<?php

namespace App\Models;

use CodeIgniter\Model;

class LivestockVaccinationModel extends Model
{
    protected $table = 'livestock_vaccinations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = ['vaccine_administrator_id', 'livestock_id', 'vaccination_name', 'vaccination_description', 'vaccination_remarks', 'vaccination_date', 'record_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function getAllLivestockVaccinations()
    {
        $whereClause = [
            'livestock_vaccinations.record_status' => 'Accessible',
            'livestocks.category' => 'Livestock'
        ];

        $livestockVaccinations = $this->select(
            'livestock_vaccinations.id,
            livestock_vaccinations.vaccine_administrator_id as vaccineAdministratorId,
            CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as vaccineAdministratorName,
            livestock_vaccinations.livestock_id as livestockId,
            livestocks.livestock_tag_id as livestockTagId,
            livestock_types.livestock_type_name as livestockType,
            livestock_vaccinations.vaccination_name as vaccinationName,
            livestock_vaccinations.vaccination_description as vaccinationDescription,
            livestock_vaccinations.vaccination_remarks as remarks,
            livestock_vaccinations.vaccination_date as vaccinationDate'
        )->join('livestocks', 'livestocks.id = livestock_vaccinations.livestock_id')
            ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
            ->join('user_accounts', 'user_accounts.id = livestock_vaccinations.vaccine_administrator_id')
            ->where($whereClause)
            ->orderBy('livestock_vaccinations.vaccination_date', 'DESC')
            ->orderBy('livestocks.livestock_tag_id', 'ASC')
            ->findAll();
        return $livestockVaccinations;
    }

    public function getAllPoultryVaccinations()
    {
        $whereClause = [
            'livestock_vaccinations.record_status' => 'Accessible',
            'livestocks.category' => 'Poultry'
        ];

        $livestockVaccinations = $this->select(
            'livestock_vaccinations.id,
            livestock_vaccinations.vaccine_administrator_id as vaccineAdministratorId,
            CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as vaccineAdministratorName,
            livestock_vaccinations.livestock_id as livestockId,
            livestocks.livestock_tag_id as livestockTagId,
            livestock_types.livestock_type_name as livestockType,
            livestock_vaccinations.vaccination_name as vaccinationName,
            livestock_vaccinations.vaccination_description as vaccinationDescription,
            livestock_vaccinations.vaccination_remarks as remarks,
            livestock_vaccinations.vaccination_date as vaccinationDate'
        )->join('livestocks', 'livestocks.id = livestock_vaccinations.livestock_id')
            ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
            ->join('user_accounts', 'user_accounts.id = livestock_vaccinations.vaccine_administrator_id')
            ->where($whereClause)
            ->orderBy('livestock_vaccinations.vaccination_date', 'DESC')
            ->orderBy('livestocks.livestock_tag_id', 'ASC')
            ->findAll();
        return $livestockVaccinations;
    }

    public function getLivestockVaccination($id)
    {
        $whereClause = [
            'livestock_vaccinations.record_status' => 'Accessible'
        ];

        $livestockVaccination = $this->select(
            'livestock_vaccinations.id,
            livestock_vaccinations.vaccine_administrator_id as vaccineAdministratorId,
            CONCAT(user_accounts.first_name, " ", user_accounts.last_name) as vaccineAdministratorName,
            livestock_vaccinations.livestock_id as livestockId,
            livestocks.livestock_tag_id as livestockTagId,
            livestock_types.livestock_type_name as livestockType,
            livestock_vaccinations.vaccination_name as vaccinationName,
            livestock_vaccinations.vaccination_description as vaccinationDescription,
            livestock_vaccinations.vaccination_remarks as remarks,
            livestock_vaccinations.vaccination_date as vaccinationDate'
        )->join('livestocks', 'livestocks.id = livestock_vaccinations.livestock_id')
            ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
            ->join('user_accounts', 'user_accounts.id = livestock_vaccinations.vaccine_administrator_id')
            ->where($whereClause)
            ->orderBy('livestocks.livestock_tag_id', 'ASC')
            ->orderBy('livestock_vaccinations.created_at', 'DESC')
            ->find($id);
        return $livestockVaccination;
    }

    public function getAllFarmerLivestockVaccinations($userId)
    {
        $whereClause = [
            'vaccine_administrator_id' => $userId,
            'record_status' => 'Accessible'
        ];

        $livestockVaccinations = $this->select(
            'id,
            vaccine_administrator_id as vaccineAdministratorId,
            livestock_id as livestockId,
            vaccination_name as vaccinationName,
            vaccination_description as vaccinationDescription,
            vaccination_remarks as remarks,
            vaccination_date as vaccinationDate'
        )->where($whereClause)->findAll();
        return $livestockVaccinations;
    }

    public function getAllFarmerCompleteLivestockVaccinations($userId)
    {
        $whereClause = [
            'livestock_vaccinations.vaccine_administrator_id' => $userId,
            'livestock_vaccinations.record_status' => 'Accessible'
        ];

        $livestockVaccinations = $this->select(
            'livestock_vaccinations.id,
            livestock_vaccinations.vaccine_administrator_id as vaccineAdministratorId,
            livestock_vaccinations.livestock_id as livestockId,
            livestocks.livestock_tag_id as livestockTagId,
            livestock_types.livestock_type_name as livestockType,
            livestock_vaccinations.vaccination_name as vaccinationName,
            livestock_vaccinations.vaccination_description as vaccinationDescription,
            livestock_vaccinations.vaccination_remarks as remarks,
            livestock_vaccinations.vaccination_date as vaccinationDate'
        )->join('livestocks', 'livestocks.id = livestock_vaccinations.livestock_id')
            ->join('livestock_types', 'livestock_types.id = livestocks.livestock_type_id')
            ->where($whereClause)
            ->orderBy('livestocks.livestock_tag_id', 'ASC')
            ->orderBy('livestock_vaccinations.created_at', 'DESC')->findAll();

        return $livestockVaccinations;
    }

    public function insertLivestockVaccination($data)
    {
        try {
            $bind = [
                'vaccine_administrator_id' => $data->vaccineAdministratorId,
                'livestock_id' => $data->livestockId,
                'vaccination_name' => $data->vaccinationName,
                'vaccination_description' => $data->vaccinationDescription,
                'vaccination_remarks' => $data->remarks,
                'vaccination_date' => $data->vaccinationDate
            ];

            $result = $this->insert($bind);

            return $result;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function updateLivestockVaccination($id, $data)
    {
        $bind = [
            'vaccine_administrator_id' => $data->vaccineAdministratorId,
            'livestock_id' => $data->livestockId,
            'vaccination_name' => $data->vaccinationName,
            'vaccination_description' => $data->vaccinationDescription,
            'vaccination_remarks' => $data->remarks,
            'vaccination_date' => $data->vaccinationDate
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function updateLivestockVaccinationRecordStatus($id, $status)
    {
        $bind = [
            'record_status' => $status,
        ];

        $result = $this->update($id, $bind);

        return $result;
    }

    public function deleteLivestockVaccination($id)
    {
        $result = $this->delete($id);
        return $result;
    }

    public function getOverallLivestockVaccinationCount()
    {
        try {
            $livestockVaccinationCount = $this->where(['record_status' => 'Accessible'])->countAllResults();

            return $livestockVaccinationCount;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerOverallLivestockVaccinationCount($userId)
    {
        try {
            $livestockVaccinationCount = $this->where(['vaccine_administrator_id' => $userId, 'record_status' => 'Accessible'])->countAllResults();

            return $livestockVaccinationCount;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockVaccinationCountInCurrentMonth()
    {
        try {
            $whereClause = [
                'livestock_vaccinations.record_status' => 'Accessible',
                'MONTH(livestock_vaccinations.vaccination_date)' => date('m'),
                'YEAR(livestock_vaccinations.vaccination_date)' => date('Y'),
                'livestocks.livestock_health_status' => 'Alive'
            ];

            $livestockVaccinationCountInCurrentMonth = $this->join('livestocks', 'livestocks.id = livestock_vaccinations.livestock_id')->where($whereClause)->countAllResults();

            return $livestockVaccinationCountInCurrentMonth;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }

    public function getTopVaccines()
    {
        try {
            $whereClause = [
                'record_status' => 'Accessible',
                'YEAR(vaccination_date)' => date('Y')
            ];

            $this->select('vaccination_name as name, COUNT(*) as value')
                ->where($whereClause)
                ->groupBy('vaccination_name')
                ->orderBy('value', 'DESC')
                ->orderBy('vaccination_name', 'ASC')
                ->limit(10);

            return $this->get()->getResult();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getVaccinationCountByMonth()
    {
        // Get the current year
        $currentYear = date('Y');

        // Build the query
        $this->select('MONTH(livestock_vaccinations.vaccination_date) AS month, COUNT(*) AS count')
            ->join('livestocks', 'livestocks.id = livestock_vaccinations.livestock_id')
            ->where("YEAR(livestock_vaccinations.vaccination_date)", $currentYear)
            ->where('livestocks.category','Livestock')
            ->groupBy('MONTH(livestock_vaccinations.vaccination_date)')
            ->orderBy('MONTH(livestock_vaccinations.vaccination_date)');

        // Execute the query and return the result
        return $this->get()->getResult();
    }

    public function getPoultryVaccinationCountByMonth()
    {
        // Get the current year
        $currentYear = date('Y');

        // Build the query
        $this->select('MONTH(livestock_vaccinations.vaccination_date) AS month, COUNT(*) AS count')
            ->join('livestocks', 'livestocks.id = livestock_vaccinations.livestock_id')
            ->where("YEAR(livestock_vaccinations.vaccination_date)", $currentYear)
            ->where('livestocks.category','Poultry')
            ->groupBy('MONTH(livestock_vaccinations.vaccination_date)')
            ->orderBy('MONTH(livestock_vaccinations.vaccination_date)');

        // Execute the query and return the result
        return $this->get()->getResult();
    }

    public function getVaccinationCountLast4Months()
    {
        try {
            $currentMonth = date('F');
            $currentYear = date('Y');

            $months = [];
            for ($i = 3; $i >= 0; $i--) {
                $month = date('F', strtotime("-$i months"));
                $months[] = $month;
            }

            $vaccinationCounts = [];
            foreach ($months as $month) {
                $count = $this->select('COUNT(*) as vaccinationCount')
                    ->where('MONTH(vaccination_date)', date('m', strtotime($month)))
                    ->where('YEAR(vaccination_date)', $currentYear)
                    ->get()
                    ->getRowArray();

                $vaccinationCounts[] = [
                    'month' => $month,
                    'vaccinationCount' => $count['vaccinationCount'] ?? 0,
                ];
            }

            return $vaccinationCounts;
        } catch (\Throwable $th) {
            // Handle exceptions
            return [];
        }
    }

    public function getVaccinationCountWholeYear()
    {
        try {
            $currentYear = date('Y');

            $vaccinationCounts = [];
            for ($month = 1; $month <= 12; $month++) {
                $count = $this->selectCount('*')
                    ->where('MONTH(vaccination_date)', $month)
                    ->where('YEAR(vaccination_date)', $currentYear)
                    ->countAllResults();

                $monthName = date("F", mktime(0, 0, 0, $month, 1));

                $vaccinationCounts[] = [
                    'month' => $monthName,
                    'count' => $count,
                ];
            }

            return $vaccinationCounts;
        } catch (\Throwable $th) {
            // Handle exceptions
            return [];
        }
    }

    public function getVaccinationCountSelectedYear()
    {
        try {
            $currentYear = date('Y');

            $vaccinationCounts = [];
            for ($month = 1; $month <= 12; $month++) {
                $count = $this->selectCount('*')
                    ->where('MONTH(vaccination_date)', $month)
                    ->where('YEAR(vaccination_date)', $currentYear)
                    ->countAllResults();

                $monthName = date("F", mktime(0, 0, 0, $month, 1));

                $vaccinationCounts[] = [
                    'month' => $monthName,
                    'count' => $count,
                ];
            }

            return $vaccinationCounts;
        } catch (\Throwable $th) {
            // Handle exceptions
            return [];
        }
    }
}

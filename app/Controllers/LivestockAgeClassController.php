<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\LivestockAgeClassModel;

class LivestockAgeClassController extends ResourceController
{
    private $livestockAgeClass;

    public function __construct()
    {
        $this->livestockAgeClass = new LivestockAgeClassModel();
    }

    public function insertLivestockAgeClass()
    {
        try {
            $data = $this->request->getJSON();

            $data->isOffspring = 0;

            $minAge = $data->minAge;
            $maxAge = $data->maxAge;
            $minAgeUnit = $data->minAgeUnit;
            $maxAgeUnit = $data->maxAgeUnit;
            $data->ageClassRange = "";

            if ($data->stage == 'Youngest') {
                $data->isOffspring = 1;
                $data->ageMinDays = 0;
                $data->ageClassRange = "0 days - $maxAge $maxAgeUnit";
                $data->ageMaxDays = $this->calculateAgeDays($data->maxAge, $data->maxAgeUnit);
            } else if ($data->stage == 'Oldest') {
                $data->ageMinDays = $this->calculateAgeDays($data->minAge, $data->minAgeUnit);
                $data->ageMaxDays = null;
                $data->ageClassRange = "$minAge $minAgeUnit and above";
            } else if ($data->stage == 'InBetween') {
                $data->ageMinDays = $this->calculateAgeDays($data->minAge, $data->minAgeUnit);
                $data->ageMaxDays = $this->calculateAgeDays($data->maxAge, $data->maxAgeUnit);
                $data->ageClassRange = "$minAge $minAgeUnit - $maxAge $maxAgeUnit";
            }

            $response = $this->livestockAgeClass->insertLivestockAgeClass($data);
            return $this->respond(['message' => 'New Livestock Age Classification Successfully Added', 'success' => $response], 200);
        } catch (\Throwable $th) {
            return $this->respond(['message' => $th->getMessage(), 'result' => 'Error'], 200);
        }
    }

    private function calculateAgeDays($age, $unit)
    {
        $ageDays = 0;
        if ($unit == 'years') {
            $ageDays = $age * 365;
        } else if ($unit == 'months') {
            $ageDays = $age * 30;
        } else if ($unit == 'weeks') {
            $ageDays = $age * 7;
        } else if ($unit == 'days') {
            $ageDays = $age;
        }

        return $ageDays;
    }

    public function getLivestockAgeClasses()
    {
        $livestockAgeClasses = $this->livestockAgeClass->getLivestockAgeClasses();

        return $this->respond($livestockAgeClasses, 200);
    }
    
    public function getPoultryAgeClasses()
    {
        $poultryAgeClasses = $this->livestockAgeClass->getPoultryAgeClasses();

        return $this->respond($poultryAgeClasses, 200);
    }

    public function getLivestockAgeClass($id)
    {
        $livestockAgeClass = $this->livestockAgeClass->getLivestockAgeClass($id);

        return $this->respond($livestockAgeClass, 200);
    }

    public function updateLivestockAgeClass($id)
    {
        try {
            $data = $this->request->getJSON();
            $response = $this->livestockAgeClass->updateLivestockAgeClass($id, $data);
            return $this->respond(['message' => 'Livestock Age Classification Successfully Updated', 'result' => $response], 200);
        } catch (\Throwable $th) {
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function deleteLivestockAgeClass($id)
    {
        $response = $this->livestockAgeClass->deleteLivestockAgeClass($id);
        return $this->respond(['message' => 'Livestock Age Classification Successfully Deleted', 'result' => $response], 200);
    }
    public function getLivestockAgeClassIdAndName()
    {
        try {
            $livestockAgeClasses = $this->livestockAgeClass->getLivestockAgeClassIdAndName();

            return $this->respond($livestockAgeClasses, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockAgeClassIdAndNameById($livestockTypeId)
    {
        try {
            $livestockAgeClasses = $this->livestockAgeClass->getLivestockAgeClassIdAndNameById($livestockTypeId);

            return $this->respond($livestockAgeClasses, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AnimalSamplesModel;
use App\Models\FarmerAuditModel;
use App\Models\LivestockModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class AnimalSamplesController extends ResourceController
{
    private $animalSamples;
    private $farmerAudit;
    private $livestock;
    public function __construct()
    {
        $this->animalSamples = new AnimalSamplesModel();
        $this->farmerAudit = new FarmerAuditModel();
        $this->livestock = new LivestockModel();
        helper('jwt');
    }

    public function getAllAnimalSamples()
    {
        try {
            $category = $this->request->getGet('category');

            $data = $this->animalSamples->getAllAnimalSample($category);
            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllAnimalSampleByUser()
    {
        try {
            $header = $this->request->getHeader("Authorization");
            $userId = null;
            $decoded = decodeToken($header);

            $userType = $decoded->aud;
            $userId = $userType == 'Farmer'
                ? $decoded->sub->id
                : $this->request->getGet('fui');

            $data = $this->animalSamples->getAllAnimalSampleByUser($userId);
            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllAnimalSampleByAnimal()
    {
        try {
            $animal = $this->request->getGet('animal');

            $data = $this->animalSamples->getAllAnimalSampleByAnimalId($animal);
            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function insertAnimalSample(){
        try {
            //code...
            $data = $this->request->getJSON();

            $result = $this->animalSamples->insertAnimalSample($data);
            if(!$result){
                return $this->fail('Failed to add data', ResponseInterface::HTTP_BAD_REQUEST);
            }

            $livestockTagId = $this->livestock->getLivestockTagIdById($data->animalId);

            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $auditLog = (object) [
                'livestockId' => $data->animalId,
                'farmerId' => $userId,
                'action' => "Add",
                'title' => "Get New Animal Sample",
                'description' => "Get New Animal Sample of $livestockTagId",
                'entityAffected' => "Animal Sample",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            if(!$resultAudit){
                return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->respond(['result' => $result], 200, 'Animal Sample Successfully Added');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to add data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function insertMultipleAnimalSample()
    {
        try {
            $data = $this->request->getJSON();

            $animals = $data->animals;

            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $auditLog = (object) [
                'farmerId' => $userId,
                'action' => "Add",
                'title' => "Get New Animal Sample",
                'entityAffected' => "Animal Sample",
            ];

            $result = null;
            foreach ($animals as $animal) {
                $data->animalId = $animal;

                $auditLog->livestockId = $animal;
                $livestockTagId = $this->livestock->getLivestockTagIdById($data->animalId);
                $auditLog->description = "Get New Animal Sample of $livestockTagId";

                $result = $this->animalSamples->insertAnimalSample($data);
                if(!$result){
                    return $this->fail('Failed to add data', ResponseInterface::HTTP_BAD_REQUEST);
                }
                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                if(!$resultAudit){
                    return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
                }
            }

            return $this->respond(['result' => $result], 200, 'Animal Sample Successfully Added');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to add record', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateAnimalSample()
    {
        try {
            $data = $this->request->getJSON();

            $result = $this->animalSamples->updateAnimalSample($data->id, $data);
            if(!$result){
                return $this->fail('Failed to update data', ResponseInterface::HTTP_BAD_REQUEST);
            }

            $livestockTagId = $this->livestock->getLivestockTagIdById($data->animalId);

            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $auditLog = (object) [
                'livestockId' => $data->animalId,
                'farmerId' => $userId,
                'action' => "Edit",
                'title' => "Updated Animal Sample Record",
                'description' => "Updated Animal Sample Record of $livestockTagId",
                'entityAffected' => "Animal Sample",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            if(!$resultAudit){
                return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->respond(['result' => $result], 200, 'Animal Sample Successfully Updated');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to update record', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteAnimalSample()
    {
        try {
            $id = $this->request->getGet('sample');

            $animal = $this->livestock->getAnimalByAnimalSample($id);

            $response = $this->animalSamples->deleteAnimalSample($id);

            $livestockTagId = $animal['livestock_tag_id'];
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);

            $auditLog = (object) [
                'farmerId' => $userId,
                'livestockId' => $animal['id'],
                'action' => "Delete",
                'title' => "Deleted Animal Sample Record",
                'description' => "Deleted Animal Sample Record of $livestockTagId",
                'entityAffected' => "Animal Sample",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            if(!$resultAudit){
                return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->respond(['result' => $response], 200, 'Animal Sample Record Successfully Deleted');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to delete record', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getOverallAnimalSampleCount(){
        try {
            //code...
            $data = $this->animalSamples->getOverallAnimalSampleCount();
            return $this->respond(['sampleCount' => "$data"], 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getUserOverallAnimalSampleCount($userId)
    {
        try {
            //code...
            $data = $this->animalSamples->getUserOverallAnimalSampleCount($userId);
            return $this->respond(['sampleCount' => "$data"], 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getTopAnimalObservations()
    {
        try {
            //code...
            $data = $this->animalSamples->getTopAnimalObservations();
            return $this->respond($data, 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getTopAnimalSampleFindings()
    {
        try {
            //code...
            $data = $this->animalSamples->getTopAnimalSampleFindings();
            return $this->respond($data, 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    
    public function getTopAnimalSampleType()
    {
        try {
            //code...
            $data = $this->animalSamples->getTopAnimalSampleType();
            return $this->respond($data, 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSampleCountLast4Months()
    {
        try {
            $data = $this->animalSamples->getSampleCountLast4Months();

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAnimalSampleCountByMonth()
    {
        try {
            $data = $this->animalSamples->getAnimalSampleCountByMonth();

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}

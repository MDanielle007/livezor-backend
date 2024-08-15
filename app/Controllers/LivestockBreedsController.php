<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LivestockTypeModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\LivestockBreedModel;

class LivestockBreedsController extends ResourceController
{
    private $livestockBreed;
    private $livestockType;

    public function __construct()
    {
        $this->livestockBreed = new LivestockBreedModel();
        $this->livestockType = new LivestockTypeModel();
    }

    public function insertLivestockBreed()
    {
        try {
            $data = $this->request->getJSON();
            $response = $this->livestockBreed->insertLivestockBreed($data);
            if (!$response) {
                return $this->fail('Failed to insert record', ResponseInterface::HTTP_BAD_REQUEST);
            }
            return $this->respond(['result' => $response], 200, 'New Livestock Breed Successfully Added');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to insert record', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getLivestockBreeds()
    {
        try {
            $livestockBreeds = $this->livestockBreed->getLivestockBreeds();

            return $this->respond($livestockBreeds, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getPoultryBreeds()
    {
        try {
            $poultryBreeds = $this->livestockBreed->getPoultryBreeds();

            return $this->respond($poultryBreeds, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockBreed($id)
    {
        try {
            $livestockBreed = $this->livestockBreed->getLivestockBreed($id);

            return $this->respond($livestockBreed, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateLivestockBreed($id)
    {
        try {
            $data = $this->request->getJSON();
            $response = $this->livestockBreed->updateLivestockBreed($id, $data);
            if (!$response) {
                return $this->fail('Failed to update record', ResponseInterface::HTTP_BAD_REQUEST);
            }

            return $this->respond(['result' => $response], 200, 'Livestock Breed Successfully Updated');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to update record', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteLivestockBreed()
    {
        try {
            $id = $this->request->getGet('breed');

            $response = $this->livestockBreed->deleteLivestockBreed($id);
            if (!$response) {
                return $this->fail('Failed to delete record', ResponseInterface::HTTP_BAD_REQUEST);
            }

            return $this->respond(['result' => $response], 200, 'Livestock Breed Successfully Deleted');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to delete record', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getLivestockBreedIdAndName()
    {
        try {
            $livestockBreeds = $this->livestockBreed->getLivestockBreedIdAndName();

            return $this->respond($livestockBreeds, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockBreedIdAndNameById($livestockTypeId)
    {
        try {
            $livestockBreeds = $this->livestockBreed->getLivestockBreedIdAndNameById($livestockTypeId);

            return $this->respond($livestockBreeds, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

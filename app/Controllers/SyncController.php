<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AnimalParasiteControlModel;
use App\Models\FarmerAuditModel;
use App\Models\FarmerLivestockModel;
use App\Models\LivestockBreedingsModel;
use App\Models\LivestockBreedModel;
use App\Models\LivestockModel;
use App\Models\LivestockPregnancyModel;
use App\Models\LivestockVaccinationModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class SyncController extends ResourceController
{
    private $livestock;
    private $livestockVaccination;
    private $animalParasiteControl;
    private $livestockBreeding;
    private $livestockPregnancy;
    private $livestockBreed;
    private $farmerLivestock;
    private $farmerAudit;


    public function __construct()
    {
        $this->livestock = new LivestockModel();
        $this->livestockVaccination = new LivestockVaccinationModel();
        $this->animalParasiteControl = new AnimalParasiteControlModel();
        $this->livestockBreeding = new LivestockBreedingsModel();
        $this->livestockPregnancy = new LivestockPregnancyModel();
        $this->livestockBreed = new LivestockBreedModel();
        $this->farmerLivestock = new FarmerLivestockModel();
        $this->farmerAudit = new FarmerAuditModel();
    }
    protected $modelName = 'App\Models\LivestockModel';
    protected $format = 'json';

    public function sync()
    {
        $data = $this->request->getJSON(true);

        if (empty($data)) {
            return $this->fail("No data received", 400);
        }

        $response = [
            'livestock' => [],
            'vaccinations' => [],
            'parasiteControl' => [],
            'breeding' => [],
            'pregnancy' => [],
            'mortality' => []
        ];

        try {
            if (isset($data['livestock'])) {
                $response['livestock'] = $this->syncLivestock($data['livestock']);
            }
            if (isset($data['vaccinations'])) {
                $response['vaccinations'] = $this->syncVaccinations($data['vaccinations']);
            }
            if (isset($data['parasiteControl'])) {
                $response['parasiteControl'] = $this->syncParasiteControl($data['parasiteControl']);
            }
            if (isset($data['breeding'])) {
                $response['breeding'] = $this->syncBreeding($data['breeding']);
            }
            if (isset($data['pregnancy'])) {
                $response['pregnancy'] = $this->syncPregnancy($data['pregnancy']);
            }

            return $this->respond($response);
        } catch (\Exception $e) {
            log_message('error', $e->getMessage() . ": " . $e->getLine());
            log_message('error', json_encode($e->getTrace()));
            return $this->fail($e->getMessage(), 500);
        }
    }

    private function syncLivestock($livestockData)
    {
        try {
            $results = [];

            foreach ($livestockData as $livestock) {
                if ($livestock['syncAction'] === 'insert') {
                    $results[] = $this->insertLivestock($livestock);
                } elseif ($livestock['syncAction'] === 'update') {
                    $results[] = $this->updateLivestock($livestock);
                } elseif ($livestock['syncAction'] === 'delete') {
                    $results[] = $this->deleteLivestock($livestock['id'], $livestock['syncIdentifier']);
                }
            }

            return $results;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    private function insertLivestock($livestock)
    {
        try {
            $insertData = (object) [
                'breedingEligibility' => $livestock['breedingEligibility'], //
                'dateOfBirth' => $livestock['dateOfBirth'], //
                'livestockAgeClassId' => $livestock['livestockAgeClassId'], // 
                'livestockHealthStatus' => $livestock['livestockHealthStatus'],
                'livestockTagId' => $livestock['livestockTagId'],
                'livestockType' => $livestock['livestockType'],
                'livestockTypeId' => $livestock['livestockTypeId'], //
                'sex' => $livestock['sex'], //
                'category' => 'Livestock' //,
            ];

            $breedName = $livestock['breedName'];
            if ($breedName != '') {
                if (is_string($breedName)) {
                    $insertData->livestockBreedId = $this->livestockBreed->getLivestockBreedIdByName($breedName, $insertData->livestockTypeId);
                } else {
                    $insertData->livestockBreedId = $breedName->code;
                }
            }

            $livestockId = $this->livestock->insertLivestock($insertData);

            if ($livestockId) {
                $response = $this->farmerLivestock->associateFarmerLivestock((object) [
                    'farmerId' => $livestock['userId'],
                    'livestockId' => $livestockId,
                    'acquiredDate' => $livestock['acquiredDate']
                ]);

                return ['id' => $livestockId, 'status' => 'success', 'syncIdentifier' => $livestock['syncIdentifier']];
            } else {
                return ['status' => 'failed', 'errors' => $this->livestock->errors(), 'syncIdentifier' => $livestock['syncIdentifier']];
            }
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return ['status' => 'failed', 'errors' => $th->getMessage(), 'syncIdentifier' => $livestock['syncIdentifier']];
        }
    }

    private function updateLivestock($livestock)
    {
        try {
            $updateData = (object) [
                'id' => $livestock['id'], 
                'breedName' => $livestock['breedName'],
                'breedingEligibility' => $livestock['breedingEligibility'], 
                'dateOfBirth' => $livestock['dateOfBirth'], 
                'livestockAgeClassId' => $livestock['livestockAgeClassId'], 
                'livestockHealthStatus' => $livestock['livestockHealthStatus'],
                'livestockTagId' => $livestock['livestockTagId'], 
                'livestockTypeId' => $livestock['livestockTypeId'], 
                'sex' => $livestock['sex'], 
            ];

            $breedName = $livestock['breedName'];
            if ($breedName != '') {
                if (is_string($breedName)) {
                    $updateData->livestockBreedId = $this->livestockBreed->getLivestockBreedIdByName($breedName, $updateData->livestockTypeId);
                } else {
                    $updateData->livestockBreedId = $breedName->code;
                }
            }

            if ($this->livestock->updateLivestock($livestock['id'], $updateData)) {
                return ['id' => $livestock['id'], 'status' => 'success', 'syncIdentifier' => $livestock['syncIdentifier']];
            } else {
                return ['status' => 'failed', 'errors' => $this->livestock->errors(), 'syncIdentifier' => $livestock['syncIdentifier']];
            }
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return ['status' => 'failed', 'errors' => $th->getMessage(), 'syncIdentifier' => $livestock['syncIdentifier']];
        }
    }

    private function deleteLivestock($id, $syncIdentifier)
    {
        if ($this->livestock->deleteLivestock($id)) {
            return ['id' => $id, 'status' => 'success', 'syncIdentifier' => $syncIdentifier];
        } else {
            return ['status' => 'failed', 'errors' => $this->livestock->errors(), 'syncIdentifier' => $syncIdentifier];
        }
    }

    private function syncVaccinations($vaccinationData)
    {
        try {
            $results = [];

            foreach ($vaccinationData as $vaccination) {
                if ($vaccination['syncAction'] === 'insert') {
                    $results[] = $this->insertVaccination($vaccination);
                } elseif ($vaccination['syncAction'] === 'update') {
                    $results[] = $this->updateVaccination($vaccination);
                } elseif ($vaccination['syncAction'] === 'delete') {
                    $results[] = $this->deleteVaccination($vaccination['id'], $vaccination['syncIdentifier']);
                }
            }

            return $results;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    private function insertVaccination($vaccination)
    {
        try {
            $insertData = (object) [
                'userId' => $vaccination['userId'],
                'livestockId' => $vaccination['livestockId'],
                'administratorName' => $vaccination['administratorName'],
                'vaccinationName' => $vaccination['vaccinationName'],
                'vaccinationDescription' => $vaccination['vaccinationDescription'],
                'remarks' => $vaccination['remarks'],
                'vaccinationLocation' => $vaccination['vaccinationLocation'],
                'vaccinationDate' => $vaccination['vaccinationDate']
            ];

            $vaccinationId = $this->livestockVaccination->insertLivestockVaccination($insertData);

            if ($vaccinationId) {
                return ['id' => $vaccinationId, 'status' => 'success', 'syncIdentifier' => $vaccination['syncIdentifier']];
            } else {
                return ['status' => 'failed', 'errors' => $this->livestockVaccination->errors(), 'syncIdentifier' => $vaccination['syncIdentifier']];
            }
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return ['status' => 'failed', 'errors' => $th->getMessage(), 'syncIdentifier' => $vaccination['syncIdentifier']];
        }
    }

    private function updateVaccination($vaccination)
    {
        try {
            //code...
            $updateData = (object) [
                'userId' => $vaccination['userId'],
                'livestockId' => $vaccination['livestockId'],
                'administratorName' => $vaccination['administratorName'],
                'vaccinationName' => $vaccination['vaccinationName'],
                'vaccinationDescription' => $vaccination['vaccinationDescription'],
                'remarks' => $vaccination['remarks'],
                'vaccinationLocation' => $vaccination['vaccinationLocation'],
                'vaccinationDate' => $vaccination['vaccinationDate']
            ];

            if ($this->livestockVaccination->updateLivestock($vaccination['id'], $updateData)) {
                return ['id' => $vaccination['id'], 'status' => 'success', 'syncIdentifier' => $vaccination['syncIdentifier']];
            } else {
                return ['status' => 'failed', 'errors' => $this->livestock->errors(), 'syncIdentifier' => $vaccination['syncIdentifier']];
            }
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return ['status' => 'failed', 'errors' => $th->getMessage(), 'syncIdentifier' => $vaccination['syncIdentifier']];
        }
    }
    private function deleteVaccination($id, $syncIdentifier)
    {
        try {
            if ($this->livestockVaccination->deleteLivestockVaccination($id)) {
                return ['id' => $id, 'status' => 'success', 'syncIdentifier' => $syncIdentifier];
            } else {
                return ['status' => 'failed', 'errors' => $this->livestockVaccination->errors(), 'syncIdentifier' => $syncIdentifier];
            }
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return ['status' => 'failed', 'errors' => $th->getMessage(), 'syncIdentifier' => $syncIdentifier];
        }
    }

    private function syncParasiteControl($parasiteControlData)
    {
        try {
            $results = [];

            foreach ($parasiteControlData as $parasiteControl) {
                if ($parasiteControl['syncAction'] === 'insert') {
                    $results[] = $this->insertLivestock($parasiteControl);
                } elseif ($parasiteControl['syncAction'] === 'update') {
                    $results[] = $this->updateLivestock($parasiteControl);
                } elseif ($parasiteControl['syncAction'] === 'delete') {
                    $results[] = $this->deleteLivestock($parasiteControl['id'], $parasiteControl['syncIdentifier']);
                }
            }

            return $results;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    private function insertParasiteControl($parasiteControl)
    {
        try {
            //code...
            $insertData = (object) [
                'farmerId' => $parasiteControl['userId'],
                'livestockId' => $parasiteControl['livestockId'],
                'drugName' => $parasiteControl['drugName'],
                'administratorName' => $parasiteControl['administratorName'],
                'parasiteName' => '',
                'dosage' => $parasiteControl['dosage'],
                'administrationMethod' => $parasiteControl['administrationMethod'],
                'remarks' => $parasiteControl['remarks'],
                'nextApplicationDate' => $parasiteControl['nextApplicationDate'],
                'applicationDate' => $parasiteControl['applicationDate'],
                'applicationLocation' => $parasiteControl['applicationLocation'],
            ];

            $parasiteControlId = $this->animalParasiteControl->insertAnimalParasiteControl($insertData);
            if ($parasiteControlId) {
                return ['id' => $parasiteControlId, 'status' => 'success', 'syncIdentifier' => $parasiteControl['syncIdentifier']];
            } else {
                return ['status' => 'failed', 'errors' => $this->animalParasiteControl->errors(), 'syncIdentifier' => $parasiteControl['syncIdentifier']];
            }
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return ['status' => 'failed', 'errors' => $th->getMessage(), 'syncIdentifier' => $parasiteControl['syncIdentifier']];
        }
    }

    private function updateParasiteControl($parasiteControl)
    {
        try {
            //code...
            $updateData = (object) [
                'farmerId' => $parasiteControl['userId'],
                'livestockId' => $parasiteControl['livestockId'],
                'drugName' => $parasiteControl['drugName'],
                'administratorName' => $parasiteControl['administratorName'],
                'parasiteName' => '',
                'dosage' => $parasiteControl['dosage'],
                'administrationMethod' => $parasiteControl['administrationMethod'],
                'remarks' => $parasiteControl['remarks'],
                'nextApplicationDate' => $parasiteControl['nextApplicationDate'],
                'applicationDate' => $parasiteControl['applicationDate'],
                'applicationLocation' => $parasiteControl['applicationLocation'],
            ];

            
            if ($this->animalParasiteControl->updateAnimalParasiteControl($parasiteControl['id'], $updateData)) {
                return ['id' => $parasiteControl['id'], 'status' => 'success', 'syncIdentifier' => $parasiteControl['syncIdentifier']];
            } else {
                return ['status' => 'failed', 'errors' => $this->animalParasiteControl->errors(), 'syncIdentifier' => $parasiteControl['syncIdentifier']];
            }
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return ['status' => 'failed', 'errors' => $th->getMessage(), 'syncIdentifier' => $parasiteControl['syncIdentifier']];
        }
    }

    private function deleteParasiteControl($id, $syncIdentifier)
    {
        try {
            //code...
            if ($this->animalParasiteControl->deleteAnimalParasiteControl($id)) {
                return ['id' => $id, 'status' => 'success', 'syncIdentifier' => $syncIdentifier];
            } else {
                return ['status' => 'failed', 'errors' => $this->animalParasiteControl->errors(), 'syncIdentifier' => $syncIdentifier];
            }
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return ['status' => 'failed', 'errors' => $th->getMessage(), 'syncIdentifier' => $syncIdentifier];
        }
    }

    private function syncBreeding($breedingData)
    {
        try {
            $results = [];

            foreach ($breedingData as $breeding) {
                if ($breeding['syncAction'] === 'insert') {
                    $results[] = $this->insertBreeding($breeding);
                } elseif ($breeding['syncAction'] === 'update') {
                    $results[] = $this->updateBreeding($breeding);
                } elseif ($breeding['syncAction'] === 'delete') {
                    $results[] = $this->deleteBreeding($breeding['id'], $breeding['syncIdentifier']);
                }
            }

            return $results;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    private function insertBreeding($breeding)
    {
        try {
            //code...
            $insertData = (object)[
                'farmerId' => $breeding['userId'],
                'livestockTypeId' => $breeding['livestockTypeId'],
                'maleLivestockTagId' => $breeding['maleLivestockTagId'],
                'femaleLivestockTagId' => $breeding['femaleLivestockTagId'],
                'breedResult' => $breeding['breedResult'],
                'remarks' => $breeding['remarks'],
                'breedDate' => $breeding['breedDate'],
            ];

            $breedingId = $this->livestockBreeding->insertLivestockBreeding($insertData);
            if ($breedingId) {
                return ['id' => $breedingId, 'status' => 'success', 'syncIdentifier' => $breeding['syncIdentifier']];
            } else {
                return ['status' => 'failed', 'errors' => $this->animalParasiteControl->errors(), 'syncIdentifier' => $breeding['syncIdentifier']];
            }
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return ['status' => 'failed', 'errors' => $th->getMessage(), 'syncIdentifier' => $breeding['syncIdentifier']];
        }
    }
    private function updateBreeding($breeding)
    {
        try {
            //code...
            $updateData = (object)[
                'farmerId' => $breeding['userId'],
                'livestockTypeId' => $breeding['livestockTypeId'],
                'maleLivestockTagId' => $breeding['maleLivestockTagId'],
                'femaleLivestockTagId' => $breeding['femaleLivestockTagId'],
                'breedResult' => $breeding['breedResult'],
                'remarks' => $breeding['remarks'],
                'breedDate' => $breeding['breedDate'],
            ];

            if ($this->livestockBreeding->updateLivestockBreeding($breeding['id'], $updateData)) {
                return ['id' => $breeding['id'], 'status' => 'success', 'syncIdentifier' => $breeding['syncIdentifier']];
            } else {
                return ['status' => 'failed', 'errors' => $this->livestockBreeding->errors(), 'syncIdentifier' => $breeding['syncIdentifier']];
            }
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return ['status' => 'failed', 'errors' => $th->getMessage(), 'syncIdentifier' => $breeding['syncIdentifier']];
        }
    }
    private function deleteBreeding($id, $syncIdentifier)
    {
        try {
            //code...
            if ($this->livestockBreeding->deleteLivestockBreeding($id)) {
                return ['id' => $id, 'status' => 'success', 'syncIdentifier' => $syncIdentifier];
            } else {
                return ['status' => 'failed', 'errors' => $this->animalParasiteControl->errors(), 'syncIdentifier' => $syncIdentifier];
            }
        } catch (\Throwable $th) {
            //throw $th;\
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return ['status' => 'failed', 'errors' => $th->getMessage(), 'syncIdentifier' => $syncIdentifier];
        }
    }

    private function syncPregnancy($pregnancyData)
    {
        try {
            $results = [];

            foreach ($pregnancyData as $pregnancy) {
                if ($pregnancy['syncAction'] === 'insert') {
                    $results[] = $this->insertPregnancy($pregnancy);
                } elseif ($pregnancy['syncAction'] === 'update') {
                    $results[] = $this->updatePregnancy($pregnancy);
                } elseif ($pregnancy['syncAction'] === 'delete') {
                    $results[] = $this->deletePregnancy($pregnancy['id'], $pregnancy['syncIdentifier']);
                }
            }

            return $results;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    private function insertPregnancy($pregnancy){
        try {
            //code...
            $insertData = (object)[
                'breedingId' => $pregnancy['breedingId'],
                'livestockId' => $pregnancy['livestockId'],
                'pregnancyStartDate' => $pregnancy['pregnancyDate'],
                'outcome' => $pregnancy['outcome'],
                'expectedDeliveryDate' => $pregnancy['expectedDueDate'],
                'actualDeliveryDate' => $pregnancy['actualDueDate'],
                'pregnancyNotes' => $pregnancy['remarks']
            ];

            $pregnancyId = $this->livestockPregnancy->insertLivestockPregnancy($insertData);

            if ($pregnancyId) {
                return ['id' => $pregnancyId, 'status' => 'success', 'syncIdentifier' => $pregnancy['syncIdentifier']];
            } else {
                return ['status' => 'failed', 'errors' => $this->livestockPregnancy->errors(), 'syncIdentifier' => $pregnancy['syncIdentifier']];
            }
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return ['status' => 'failed', 'errors' => $th->getMessage(), 'syncIdentifier' => $pregnancy['syncIdentifier']];
        }
    }

    private function updatePregnancy($pregnancy){
        try {
            $updateData = (object)[
                'breedingId' => $pregnancy['breedingId'],
                'livestockId' => $pregnancy['livestockId'],
                'pregnancyStartDate' => $pregnancy['pregnancyDate'],
                'outcome' => $pregnancy['outcome'],
                'expectedDeliveryDate' => $pregnancy['expectedDueDate'],
                'actualDeliveryDate' => $pregnancy['actualDueDate'],
                'pregnancyNotes' => $pregnancy['remarks']
            ];
            if ($this->livestockPregnancy->updateLivestockPregnancy($pregnancy['id'], $updateData)) {
                return ['id' => $pregnancy['id'], 'status' => 'success', 'syncIdentifier' => $pregnancy['syncIdentifier']];
            } else {
                return ['status' => 'failed', 'errors' => $this->animalParasiteControl->errors(), 'syncIdentifier' => $pregnancy['syncIdentifier']];
            }

        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return ['status' => 'failed', 'errors' => $th->getMessage(), 'syncIdentifier' => $pregnancy['syncIdentifier']];
        }
    }

    private function deletePregnancy($id, $syncIdentifier){
        try {
            //code...
            if ($this->livestockPregnancy->deleteLivestockPregnancy($id)) {
                return ['id' => $id, 'status' => 'success', 'syncIdentifier' => $syncIdentifier];
            } else {
                return ['status' => 'failed', 'errors' => $this->livestockPregnancy->errors(), 'syncIdentifier' => $syncIdentifier];
            }
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return ['status' => 'failed', 'errors' => $th->getMessage(), 'syncIdentifier' => $syncIdentifier];
        }
    }
}

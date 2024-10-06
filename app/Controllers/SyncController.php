<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AnimalParasiteControlModel;
use App\Models\FarmerAuditModel;
use App\Models\FarmerLivestockModel;
use App\Models\FarmInformationModel;
use App\Models\LivestockBreedingsModel;
use App\Models\LivestockBreedModel;
use App\Models\LivestockModel;
use App\Models\LivestockMortalityModel;
use App\Models\LivestockPregnancyModel;
use App\Models\LivestockTypeModel;
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
    private $livestockMortality;
    private $livestockBreed;
    private $farmerLivestock;
    private $farmerAudit;
    private $farms;
    private $livestockTypes;


    public function __construct()
    {
        $this->livestock = new LivestockModel();
        $this->livestockTypes = new LivestockTypeModel();
        $this->livestockVaccination = new LivestockVaccinationModel();
        $this->animalParasiteControl = new AnimalParasiteControlModel();
        $this->livestockBreeding = new LivestockBreedingsModel();
        $this->livestockPregnancy = new LivestockPregnancyModel();
        $this->livestockMortality = new LivestockMortalityModel();
        $this->livestockBreed = new LivestockBreedModel();
        $this->farmerLivestock = new FarmerLivestockModel();
        $this->farmerAudit = new FarmerAuditModel();
        $this->farms = new FarmInformationModel();
        helper('jwt');
    }
    protected $modelName = 'App\Models\LivestockModel';
    protected $format = 'json';

    public function syncA()
    {
        try {
            //code...
            $data = $this->request->getJSON(true);
            $lres = $this->syncLivestock($data['livestock'], 'Livestock');
            $pres = $this->syncLivestock($data['poultry'], 'Poultry');
            $response = $lres + $pres;
            return $this->respond($response);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail($th->getMessage(), 500);
        }
    }

    public function syncL()
    {
        try {
            //code...
            $data = $this->request->getJSON(true);
            $response = $this->syncLivestock($data, 'Livestock');

            return $this->respond($response);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail($th->getMessage(), 500);
        }
    }

    public function syncF()
    {
        try {
            $data = $this->request->getJSON(true);
            $header = $this->request->getHeader("Authorization");
            $decoded = decodeToken($header);
            $userType = $decoded->aud;
            if ($userType == 'Farmer') {
                $data['userId'] = getTokenUserId($header);
            } else {
                $data['userId'] = $this->request->getGet('fui');
            }
            //code...
            $response = $this->syncFarm($data);

            return $this->respond($response);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail($th->getMessage(), 500);
        }
    }

    public function syncH()
    {
        $data = $this->request->getJSON(true);

        if (empty($data)) {
            return $this->fail("No data received", 400);
        }

        $response = [
            'vaccination' => [],
            'parasiteControl' => [],
            'breeding' => [],
        ];

        try {
            if (isset($data['vaccination'])) {
                $response['vaccination'] = $this->syncVaccinations($data['vaccination']);
            }
            if (isset($data['parasiteControl'])) {
                $response['parasiteControl'] = $this->syncParasiteControl($data['parasiteControl']);
            }
            if (isset($data['breeding'])) {
                $response['breeding'] = $this->syncBreeding($data['breeding']);
            }

            return $this->respond($response);
        } catch (\Exception $e) {
            log_message('error', $e->getMessage() . ": " . $e->getLine());
            log_message('error', json_encode($e->getTrace()));
            return $this->fail($e->getMessage(), 500);
        }
    }

    public function syncP()
    {
        try {
            //code...
            $data = $this->request->getJSON(true);
            $response = $this->syncPregnancy($data);

            return $this->respond($response);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail($th->getMessage(), 500);
        }
    }


    public function syncM()
    {
        try {
            //code...
            $mortalityRecordsJson = $this->request->getPost('mortality');
            $data = json_decode($mortalityRecordsJson, true);
            $images = $this->request->getFiles();
            $result = null;
            if ($data['syncAction'] === 'insert') {
                $result = $this->insertMortality($data, $images);
            } elseif ($data['syncAction'] === 'update') {
                $result = $this->updateMortality($data);
            } elseif ($data['syncAction'] === 'delete') {
                $result = $this->deleteMortality($data);
            }

            if (!$result) {
                return $this->fail('Failed to sync mortality record', ResponseInterface::HTTP_BAD_REQUEST);
            }

            return $this->respond($result, 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail($th->getMessage(), 500);
        }
    }

    private function syncLivestock($livestockData, $category)
    {
        try {
            $results = [];

            foreach ($livestockData as $livestock) {
                if ($livestock['syncAction'] === 'insert') {
                    $results[] = $this->insertLivestock($livestock, $category);
                } elseif ($livestock['syncAction'] === 'update') {
                    $results[] = $this->updateLivestock($livestock, $category);
                } elseif ($livestock['syncAction'] === 'delete') {
                    $results[] = $this->deleteLivestock($livestock, $category);
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

    private function insertLivestock($livestock, $category)
    {
        try {
            $insertData = (object) [
                'breedingEligibility' => $livestock['breedingEligibility'], //
                'dateOfBirth' => $livestock['dateOfBirth'], //
                'sex' => $livestock['sex'], //
                'category' => $category,
                'height' => isset($livestock['height']) ? $livestock['height'] : null,
                'heightUnit' => isset($livestock['heightUnit']) ? $livestock['heightUnit'] : null,
                'weight' => isset($livestock['weight']) ? $livestock['weight'] : null,
                'weightUnit' => isset($livestock['weightUnit']) ? $livestock['weightUnit'] : null,
                'origin' => isset($livestock['origin']) ? $livestock['origin'] : null,
            ];

            $insertData->livestockAgeClassId = $category == 'Livestock' ? $livestock['livestockAgeClassId'] : $livestock['poultryAgeClassId'];
            $insertData->livestockHealthStatus = $category == 'Livestock' ? $livestock['livestockHealthStatus'] : $livestock['poultryHealthStatus'];
            $insertData->livestockTagId = $category == 'Livestock' ? $livestock['livestockTagId'] : $livestock['poultryTagId'];
            $insertData->livestockType = $category == 'Livestock' ? $livestock['livestockType'] : $livestock['poultryType'];
            $insertData->livestockTypeId = $category == 'Livestock' ? $livestock['livestockTypeId'] : $livestock['poultryTypeId'];

            log_message('error', json_encode($insertData));


            $breedName = isset($livestock['breedName']) ? $livestock['breedName'] : '';
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

                $livestockType = $this->livestockTypes->getLivestockTypeName($category == 'Livestock' ? $livestock['livestockTypeId'] : $livestock['poultryTypeId']);
                $livestockTagId = $category == 'Livestock' ? $livestock['livestockTagId'] : $livestock['poultryTagId'];

                $auditLog = (object) [
                    'livestockId' => $livestockId,
                    'farmerId' => $livestock['userId'],
                    'action' => "Add",
                    'title' => "Add New $category",
                    'description' => "Add New $category $livestockType, $livestockTagId",
                    'entityAffected' => $category,
                ];

                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                if (!$resultAudit) {
                    return ['status' => 'failed', 'errors' => $this->farmerAudit->errors(), 'syncIdentifier' => $livestock['syncIdentifier']];
                }

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

    private function updateLivestock($livestock, $category)
    {
        try {
            $updateData = (object) [
                'id' => $livestock['id'],
                'breedingEligibility' => $livestock['breedingEligibility'],
                'dateOfBirth' => $livestock['dateOfBirth'],
                'sex' => $livestock['sex'],
                'height' => $livestock['height'],
                'heightUnit' => $livestock['heightUnit'],
                'weight' => $livestock['weight'],
                'weightUnit' => $livestock['weightUnit'],
            ];

            $updateData->livestockAgeClassId = $category == 'Livestock' ? $livestock['livestockAgeClassId'] : $livestock['poultryAgeClassId'];
            $updateData->livestockHealthStatus = $category == 'Livestock' ? $livestock['livestockHealthStatus'] : $livestock['poultryHealthStatus'];
            $updateData->livestockTagId = $category == 'Livestock' ? $livestock['livestockTagId'] : $livestock['poultryTagId'];
            $updateData->livestockTypeId = $category == 'Livestock' ? $livestock['livestockTypeId'] : $livestock['poultryTypeId'];

            $breedName = isset($livestock['breedName']) ? $livestock['breedName'] : '';
            if ($breedName != '') {
                if (is_string($breedName)) {
                    $updateData->livestockBreedId = $this->livestockBreed->getLivestockBreedIdByName($breedName, $updateData->livestockTypeId);
                } else {
                    $updateData->livestockBreedId = $breedName->code;
                }
            }

            if ($this->livestock->updateLivestock($livestock['id'], $updateData)) {
                $livestockTagId = $category == 'Livestock' ? $livestock['livestockTagId'] : $livestock['poultryTagId'];

                $auditLog = (object) [
                    'livestockId' => $livestock['id'],
                    'farmerId' => $livestock['userId'],
                    'action' => "Edit",
                    'title' => "Edit $category Record",
                    'description' => "Updated details for $category $livestockTagId",
                    'entityAffected' => $category,
                ];

                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                if (!$resultAudit) {
                    return ['status' => 'failed', 'errors' => $this->farmerAudit->errors(), 'syncIdentifier' => $livestock['syncIdentifier']];
                }

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

    private function deleteLivestock($livestock, $category)
    {
        if ($this->livestock->deleteLivestock($livestock['id'])) {

            $livestockTagId = $category == 'Livestock' ? $livestock['livestockTagId'] : $livestock['poultryTagId'];

            log_message('info', json_encode($livestock));
            $auditLog = (object) [
                'livestockId' => $livestock['id'],
                'farmerId' => $livestock['userId'],
                'action' => "Delete",
                'title' => "Delete $category Record",
                'description' => "Delete $category $livestockTagId",
                'entityAffected' => $category,
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            if (!$resultAudit) {
                return ['status' => 'failed', 'errors' => $this->farmerAudit->errors(), 'syncIdentifier' => $livestock['syncIdentifier']];
            }

            return ['id' => $livestock['id'], 'status' => 'success', 'syncIdentifier' => $livestock['syncIdentifier']];
        } else {
            return ['status' => 'failed', 'errors' => $this->livestock->errors(), 'syncIdentifier' => $livestock['syncIdentifier']];
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
                    $results[] = $this->deleteVaccination($vaccination);
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
                $livestockTagId = $this->livestock->getLivestockTagIdById($vaccination['livestockId']);
                $category = $this->livestock->getLivestockCategoryById($vaccination['livestockId']);

                $vaccine = $vaccination['administratorName'];

                $auditLog = (object) [
                    'livestockId' => $vaccination['livestockId'],
                    'farmerId' => $vaccination['userId'],
                    'action' => "Add",
                    'title' => "Adminster Vaccination",
                    'description' => "Administer $vaccine to $category $livestockTagId",
                    'entityAffected' => "Vaccination",
                ];

                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                if (!$resultAudit) {
                    return ['status' => 'failed', 'errors' => $this->farmerAudit->errors(), 'syncIdentifier' => $vaccination['syncIdentifier']];
                }

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

            if ($this->livestockVaccination->updateLivestockVaccination($vaccination['id'], $updateData)) {

                $livestockTagId = $this->livestock->getLivestockTagIdById($vaccination['livestockId']);
                $category = $this->livestock->getLivestockCategoryById($vaccination['livestockId']);

                $auditLog = (object) [
                    'livestockId' => $vaccination['livestockId'],
                    'farmerId' => $vaccination['userId'],
                    'action' => "Edit",
                    'title' => "Update Vaccination Details",
                    'description' => "Update Vaccination of $category $livestockTagId",
                    'entityAffected' => "Vaccination",
                ];

                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                if (!$resultAudit) {
                    return ['status' => 'failed', 'errors' => $this->farmerAudit->errors(), 'syncIdentifier' => $vaccination['syncIdentifier']];
                }

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
    private function deleteVaccination($vaccination)
    {
        try {
            if ($this->livestockVaccination->deleteLivestockVaccination($vaccination['id'])) {
                $livestockTagId = $this->livestock->getLivestockTagIdById($vaccination['livestockId']);
                $auditLog = (object) [
                    'livestockId' => $vaccination['livestockId'],
                    'farmerId' => $vaccination['userId'],
                    'action' => "Delete",
                    'title' => "Delete Vaccination Record",
                    'description' => "Delete Vaccination of Livestock $livestockTagId",
                    'entityAffected' => "Vaccination",
                ];

                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                if (!$resultAudit) {
                    return ['status' => 'failed', 'errors' => $this->farmerAudit->errors(), 'syncIdentifier' => $vaccination['syncIdentifier']];
                }

                return ['id' => $vaccination['id'], 'status' => 'success', 'syncIdentifier' => $vaccination['syncIdentifier']];
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

    private function syncParasiteControl($parasiteControlData)
    {
        try {
            $results = [];

            foreach ($parasiteControlData as $parasiteControl) {
                if ($parasiteControl['syncAction'] === 'insert') {
                    $results[] = $this->insertParasiteControl($parasiteControl);
                } elseif ($parasiteControl['syncAction'] === 'update') {
                    $results[] = $this->updateParasiteControl($parasiteControl);
                } elseif ($parasiteControl['syncAction'] === 'delete') {
                    $results[] = $this->deleteParasiteControl($parasiteControl);
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
                $livestockTagId = $this->livestock->getLivestockTagIdById($parasiteControl['livestockId']);

                $drugName = $parasiteControl['drugName'];
                $dosage = $parasiteControl['dosage'];
                $administrationMethod = $parasiteControl['administrationMethod'];

                $auditLog = (object) [
                    'livestockId' => $parasiteControl['livestockId'],
                    'farmerId' => $parasiteControl['userId'],
                    'action' => "Add",
                    'title' => "Add Parasite Control",
                    'description' => "Add $drugName $administrationMethod $dosage to Livestock $livestockTagId",
                    'entityAffected' => "Parasite Control",
                ];

                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                if (!$resultAudit) {
                    return ['status' => 'failed', 'errors' => $this->farmerAudit->errors(), 'syncIdentifier' => $parasiteControl['syncIdentifier']];
                }

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

                $livestockTagId = $this->livestock->getLivestockTagIdById($parasiteControl['livestockId']);
                $auditLog = (object) [
                    'livestockId' => $parasiteControl['livestockId'],
                    'farmerId' => $parasiteControl['userId'],
                    'action' => "Edit",
                    'title' => "Update Parasite Control Details",
                    'description' => "Update Parasite Control of Livestock $livestockTagId",
                    'entityAffected' => "Parasite Control",
                ];

                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                if (!$resultAudit) {
                    return ['status' => 'failed', 'errors' => $this->farmerAudit->errors(), 'syncIdentifier' => $parasiteControl['syncIdentifier']];
                }

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

    private function deleteParasiteControl($parasiteControl)
    {
        try {
            //code...
            if ($this->animalParasiteControl->deleteAnimalParasiteControl($parasiteControl['id'])) {

                $livestockTagId = $this->livestock->getLivestockTagIdById($parasiteControl['livestockId']);
                $auditLog = (object) [
                    'livestockId' => $parasiteControl['livestockId'],
                    'farmerId' => $parasiteControl['userId'],
                    'action' => "Delete",
                    'title' => "Deleted Parasite Control Record",
                    'description' => "Deleted Parasite Control Record of Livestock $livestockTagId",
                    'entityAffected' => "Parasite Control",
                ];

                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                if (!$resultAudit) {
                    return ['status' => 'failed', 'errors' => $this->farmerAudit->errors(), 'syncIdentifier' => $parasiteControl['syncIdentifier']];
                }

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
                    $results[] = $this->deleteBreeding($breeding);
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
            $insertData = (object) [
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
                $auditLog = (object) [
                    'farmerId' => $breeding['userId'],
                    'action' => "Add",
                    'title' => "Breed Livestock",
                    'entityAffected' => "Breeding",
                ];

                $maleLivestockTagId = $breeding['maleLivestockTagId'];
                $femaleLivestockTagId = $breeding['femaleLivestockTagId'];

                $maleLivestock = $this->livestock->getFarmerLivestockIdByTag($maleLivestockTagId, $breeding['userId']);

                $auditLog->description = "Breed Livestock $maleLivestockTagId and $femaleLivestockTagId";
                $auditLog->livestockId = $maleLivestock;

                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                if (!$resultAudit) {
                    return ['status' => 'failed', 'errors' => $this->animalParasiteControl->errors(), 'syncIdentifier' => $breeding['syncIdentifier']];
                }

                $femaleLivestock = $this->livestock->getFarmerLivestockIdByTag($femaleLivestockTagId, $breeding['userId']);
                $auditLog->livestockId = $femaleLivestock;

                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                if (!$resultAudit) {
                    return ['status' => 'failed', 'errors' => $this->animalParasiteControl->errors(), 'syncIdentifier' => $breeding['syncIdentifier']];
                }

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
            $updateData = (object) [
                'farmerId' => $breeding['userId'],
                'livestockTypeId' => $breeding['livestockTypeId'],
                'maleLivestockTagId' => $breeding['maleLivestockTagId'],
                'femaleLivestockTagId' => $breeding['femaleLivestockTagId'],
                'breedResult' => $breeding['breedResult'],
                'remarks' => $breeding['remarks'],
                'breedDate' => $breeding['breedDate'],
            ];

            if ($this->livestockBreeding->updateLivestockBreeding($breeding['id'], $updateData)) {
                $auditLog = (object) [
                    'farmerId' => $breeding['userId'],
                    'action' => "Edit",
                    'title' => "Updated Livestock Breeding",
                    'entityAffected' => "Breeding",
                ];

                $maleLivestockTagId = $breeding['maleLivestockTagId'];
                $femaleLivestockTagId = $breeding['femaleLivestockTagId'];

                $maleLivestock = $this->livestock->getFarmerLivestockIdByTag($maleLivestockTagId, $breeding['userId']);

                $auditLog->description = "Breed Livestock $maleLivestockTagId and $femaleLivestockTagId";
                $auditLog->livestockId = $maleLivestock;

                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                if (!$resultAudit) {
                    return ['status' => 'failed', 'errors' => $this->animalParasiteControl->errors(), 'syncIdentifier' => $breeding['syncIdentifier']];
                }

                $femaleLivestock = $this->livestock->getFarmerLivestockIdByTag($femaleLivestockTagId, $breeding['userId']);
                $auditLog->livestockId = $femaleLivestock;

                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                if (!$resultAudit) {
                    return ['status' => 'failed', 'errors' => $this->animalParasiteControl->errors(), 'syncIdentifier' => $breeding['syncIdentifier']];
                }
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
    private function deleteBreeding($breeding)
    {
        try {
            //code...
            if ($this->livestockBreeding->deleteLivestockBreeding($breeding['id'])) {
                $auditLog = (object) [
                    'farmerId' => $breeding['userId'],
                    'action' => "Delete",
                    'title' => "Delete Livestock Breeding",
                    'entityAffected' => "Breeding",
                ];

                $maleLivestockTagId = $breeding['maleLivestockTagId'];
                $femaleLivestockTagId = $breeding['femaleLivestockTagId'];

                $maleLivestock = $this->livestock->getFarmerLivestockIdByTag($maleLivestockTagId, $breeding['userId']);

                $auditLog->description = "Breed Livestock $maleLivestockTagId and $femaleLivestockTagId";
                $auditLog->livestockId = $maleLivestock;

                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                if (!$resultAudit) {
                    return ['status' => 'failed', 'errors' => $this->animalParasiteControl->errors(), 'syncIdentifier' => $breeding['syncIdentifier']];
                }

                $femaleLivestock = $this->livestock->getFarmerLivestockIdByTag($femaleLivestockTagId, $breeding['userId']);
                $auditLog->livestockId = $femaleLivestock;

                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                if (!$resultAudit) {
                    return ['status' => 'failed', 'errors' => $this->animalParasiteControl->errors(), 'syncIdentifier' => $breeding['syncIdentifier']];
                }


                return ['id' => $breeding['id'], 'status' => 'success', 'syncIdentifier' => $breeding['syncIdentifier']];
            } else {
                return ['status' => 'failed', 'errors' => $this->animalParasiteControl->errors(), 'syncIdentifier' => $breeding['syncIdentifier']];
            }
        } catch (\Throwable $th) {
            //throw $th;\
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return ['status' => 'failed', 'errors' => $th->getMessage(), 'syncIdentifier' => $breeding['syncIdentifier']];
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
                    $results[] = $this->deletePregnancy($pregnancy);
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

    private function insertPregnancy($pregnancy)
    {
        try {
            //code...
            $insertData = (object) [
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
                $auditLog = (object) [
                    'livestockId' => $pregnancy['livestockId'],
                    'farmerId' => $pregnancy['userId'],
                    'action' => "Add",
                    'title' => "Pregnancy Livestock",
                    'description' => 'Add New Livestock Pregnancy Record',
                    'entityAffected' => "Pregnancy",
                ];

                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                if (!$resultAudit) {
                    return ['status' => 'failed', 'errors' => $this->livestockPregnancy->errors(), 'syncIdentifier' => $pregnancy['syncIdentifier']];
                }

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

    private function updatePregnancy($pregnancy)
    {
        try {
            $updateData = (object) [
                'breedingId' => $pregnancy['breedingId'],
                'livestockId' => $pregnancy['livestockId'],
                'pregnancyStartDate' => $pregnancy['pregnancyDate'],
                'outcome' => $pregnancy['outcome'],
                'expectedDeliveryDate' => $pregnancy['expectedDueDate'],
                'actualDeliveryDate' => $pregnancy['actualDueDate'],
                'pregnancyNotes' => $pregnancy['remarks']
            ];
            if ($this->livestockPregnancy->updateLivestockPregnancy($pregnancy['id'], $updateData)) {
                $outcome = $pregnancy['outcome'];
                $livestockTagId = $pregnancy['livestockTagId'];
                $auditLog = (object) [
                    'livestockId' => $pregnancy['livestockId'],
                    'farmerId' => $pregnancy['userId'],
                    'action' => "Edit",
                    'title' => "Updated Pregnancy Outcome",
                    'description' => "Updated Livestock Pregnancy Outcome $livestockTagId to $outcome",
                    'entityAffected' => "Pregnancy",
                ];

                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                if (!$resultAudit) {
                    return ['status' => 'failed', 'errors' => $this->livestockPregnancy->errors(), 'syncIdentifier' => $pregnancy['syncIdentifier']];
                }

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

    private function deletePregnancy($pregnancy)
    {
        try {
            //code...
            if ($this->livestockPregnancy->deleteLivestockPregnancy($pregnancy['id'])) {
                $livestockTagId = $pregnancy['livestockTagId'];
                $auditLog = (object) [
                    'livestockId' => $pregnancy['livestockId'],
                    'farmerId' => $pregnancy['userId'],
                    'action' => "Delete",
                    'title' => "Delete Livestock Pregnancy",
                    'entityAffected' => "Pregnancy",
                    'description' => "Deleted Pregnancy Livestock $livestockTagId"
                ];

                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                if (!$resultAudit) {
                    return ['status' => 'failed', 'errors' => $this->livestockPregnancy->errors(), 'syncIdentifier' => $pregnancy['syncIdentifier']];
                }
                return ['id' => $pregnancy['id'], 'status' => 'success', 'syncIdentifier' => $pregnancy['syncIdentifier']];
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

    private function insertMortality($data, $files)
    {
        try {
            //code...
            $uploadedData = [];
            $uploadPath = WRITEPATH . 'uploads/mortalities/';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true); // Create directory if it doesn't exist
            }

            if (isset($files['files'])) {
                foreach ($files['files'] as $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $newName = $file->getRandomName();
                        $file->move($uploadPath, $newName);

                        // Collect all uploaded data
                        $uploadedData[] = $newName;
                    } else {
                        return $this->fail($file->getErrorString(), 400);
                    }
                }
            }

            $response = $this->livestockMortality->insertLivestockMortality((object) [
                'farmerId' => $data['userId'],
                'livestockId' => $data['livestockId'],
                'causeOfDeath' => $data['causeOfDeath'],
                'remarks' => $data['remarks'],
                'dateOfDeath' => $data['dateOfDeath'],
                'images' => $uploadedData
            ]);

            if ($response) {
                $livestockTagId = $this->livestock->getLivestockTagIdById($data['livestockId']);

                $auditLog = (object) [
                    'livestockId' => $data['livestockId'],
                    'farmerId' => $data['userId'],
                    'action' => "Add",
                    'title' => "Report Livestock Mortality",
                    'description' => "Report Livestock Mortality of Livestock $livestockTagId",
                    'entityAffected' => "Mortality",
                ];

                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                if (!$resultAudit) {
                    return ['status' => 'failed', 'errors' => 'Failed to insert mortality record', 'syncIdentifier' => $data['syncIdentifier']];
                }
                return ['id' => $response, 'status' => 'success', 'syncIdentifier' => $data['syncIdentifier']];
            } else {
                return ['status' => 'failed', 'errors' => 'Failed to insert mortality record', 'syncIdentifier' => $data['syncIdentifier']];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ['status' => 'failed', 'errors' => $th->getMessage(), 'syncIdentifier' => $data['syncIdentifier']];
        }
    }

    private function updateMortality($data)
    {
        try {
            //code...
            $response = $this->livestockMortality->updateLivestockMortality($data['id'], (object) [
                'farmerId' => $data['userId'],
                'livestockId' => $data['livestockId'],
                'causeOfDeath' => $data['causeOfDeath'],
                'remarks' => $data['remarks'],
                'dateOfDeath' => $data['dateOfDeath']
            ]);

            if ($response) {
                $livestockTagId = $this->livestock->getLivestockTagIdById($data['livestockId']);

                $auditLog = (object) [
                    'livestockId' => $data['livestockId'],
                    'farmerId' => $data['userId'],
                    'action' => "Edit",
                    'title' => "Updated Livestock Mortality",
                    'description' => "Updated Livestock Mortality of Livestock $livestockTagId",
                    'entityAffected' => "Mortality",
                ];

                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                if (!$resultAudit) {
                    return ['status' => 'failed', 'errors' => 'Failed to insert mortality record', 'syncIdentifier' => $data['syncIdentifier']];
                }
                return ['id' => $data['id'], 'status' => 'success', 'syncIdentifier' => $data['syncIdentifier']];
            } else {
                return ['status' => 'failed', 'errors' => 'Failed to update mortality record', 'syncIdentifier' => $data['syncIdentifier']];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ['status' => 'failed', 'errors' => $th->getMessage(), 'syncIdentifier' => $data['syncIdentifier']];
        }
    }

    private function deleteMortality($data)
    {
        try {
            if ($this->livestockMortality->deleteLivestockMortality($data['id'])) {
                $livestockTagId = $this->livestock->getLivestockTagIdById($data['livestockId']);

                $auditLog = (object) [
                    'livestockId' => $data['livestockId'],
                    'farmerId' => $data['userId'],
                    'action' => "Delete",
                    'title' => "Deleted Livestock Mortality",
                    'description' => "Deleted Livestock Mortality of Livestock $livestockTagId",
                    'entityAffected' => "Mortality",
                ];

                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                if (!$resultAudit) {
                    return ['status' => 'failed', 'errors' => 'Failed to insert mortality record', 'syncIdentifier' => $data['syncIdentifier']];
                }
                return ['id' => $data['id'], 'status' => 'success', 'syncIdentifier' => $data['syncIdentifier']];
            } else {
                return ['status' => 'failed', 'errors' => $this->livestockMortality->errors(), 'syncIdentifier' => $data['syncIdentifier']];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ['status' => 'failed', 'errors' => $th->getMessage(), 'syncIdentifier' => $data['syncIdentifier']];
        }
    }

    private function syncFarm($farm)
    {
        try {
            $results = null;

            if ($farm['syncAction'] === 'insert') {
                $results = $this->insertFarm($farm);
            } elseif ($farm['syncAction'] === 'update') {
                $results = $this->updateFarm($farm);
            } elseif ($farm['syncAction'] === 'delete') {
                $results = $this->deleteFarm($farm['id'], $farm['syncIdentifier']);
            }

            return $results;
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return [];
        }
    }

    private function insertFarm($farm)
    {
        try {
            //code...
            $insertData = (object) [
                'farmerId' => $farm['userId'],
                'farmName' => $farm['farmName'],
                'farmUID' => $farm['farmUID'],
                'sitio' => $farm['sitio'],
                'barangay' => $farm['barangay'],
                'city' => $farm['city'],
                'province' => $farm['province'],
                'totalArea' => $farm['totalArea'],
                'totalAreaUnit' => $farm['totalAreaUnit'],
                'farmType' => $farm['farmType'],
                'latitude' => $farm['latitude'],
                'longitude' => $farm['longitude'],
                'dateEstablished' => $farm['dateEstablished'],
                'contactNumber' => $farm['contactNumber'],
                'ownerType' => $farm['ownerType'],
            ];

            $farmId = $this->farms->insertFarm($insertData);

            if ($farmId) {
                return ['id' => $farmId, 'status' => 'success', 'syncIdentifier' => $farm['syncIdentifier']];
            } else {
                return ['status' => 'failed', 'errors' => $this->farms->errors(), 'syncIdentifier' => $farm['syncIdentifier']];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ['status' => 'failed', 'errors' => $th->getMessage(), 'syncIdentifier' => $farm['syncIdentifier']];
        }
    }

    private function updateFarm($farm)
    {
        try {
            //code...
            $updateData = (object) [
                'farmerId' => $farm['userId'],
                'farmName' => $farm['farmName'],
                'sitio' => $farm['sitio'],
                'barangay' => $farm['barangay'],
                'city' => $farm['city'],
                'province' => $farm['province'],
                'totalArea' => $farm['totalArea'],
                'totalAreaUnit' => $farm['totalAreaUnit'],
                'farmType' => $farm['farmType'],
                'latitude' => $farm['latitude'],
                'longitude' => $farm['longitude'],
                'dateEstablished' => $farm['dateEstablished'],
                'contactNumber' => $farm['contactNumber'],
                'ownerType' => $farm['ownerType'],
            ];

            if ($this->farms->updateFarm($farm['id'], $updateData)) {
                return ['id' => $farm['id'], 'status' => 'success', 'syncIdentifier' => $farm['syncIdentifier']];
            } else {
                return ['status' => 'failed', 'errors' => $this->farms->errors(), 'syncIdentifier' => $farm['syncIdentifier']];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ['status' => 'failed', 'errors' => $th->getMessage(), 'syncIdentifier' => $farm['syncIdentifier']];
        }
    }

    private function deleteFarm($id, $syncIdentifier)
    {
        try {
            //code...
            if ($this->farms->deleteFarm($id)) {
                return ['id' => $id, 'status' => 'success', 'syncIdentifier' => $syncIdentifier];
            } else {
                return ['status' => 'failed', 'errors' => $this->farms->errors(), 'syncIdentifier' => $syncIdentifier];
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ['status' => 'failed', 'errors' => $th->getMessage(), 'syncIdentifier' => $syncIdentifier];
        }
    }
}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FarmerAuditModel;
use App\Models\LivestockAgeClassModel;
use App\Models\LivestockBreedModel;
use App\Models\LivestockTypeModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\LivestockModel;
use App\Models\FarmerLivestockModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\IOFactory;

class LivestocksController extends ResourceController
{
    private $livestock;
    private $livestockTypes;
    private $livestockAgeClass;
    private $livestockBreed;
    private $farmerLivestock;
    private $userModel;
    private $farmerAudit;

    public function __construct()
    {
        $this->livestock = new LivestockModel();
        $this->livestockTypes = new LivestockTypeModel();
        $this->livestockAgeClass = new LivestockAgeClassModel();
        $this->livestockBreed = new LivestockBreedModel();
        $this->farmerLivestock = new FarmerLivestockModel();
        $this->userModel = new UserModel();
        $this->farmerAudit = new FarmerAuditModel();
        helper('jwt');
        // helper('excel');
    }

    public function getAllLivestocks()
    {
        try {
            $livestocks = $this->livestock->getAllLivestock();

            return $this->respond($livestocks);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockReportData()
    {
        try {
            $selectClause = $this->request->getGet('selectClause');
            $minDate = $this->request->getGet('minDate');
            $maxDate = $this->request->getGet('maxDate');
            $category = 'Livestock';

            $livestocks = $this->livestock->getReportData($category, $selectClause, $minDate, $maxDate);

            // Call the helper function to generate the Excel file
            // $tempFile = export_to_excel($livestocks);
            return $this->respond($livestocks);
            // return $this->response->download($tempFile, null);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getLivestock($id)
    {
        try {
            $livestock = $this->livestock->getLivestockById($id);

            return $this->respond($livestock);
        } catch (\Throwable $th) {
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getFarmerAllLivestocks()
    {
        try {
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);

            $livestocks = $this->livestock->getFarmerAllLivestock($userId);

            return $this->respond($livestocks);
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function addSingleLivestock()
    {
        try {
            $data = $this->request->getJSON();
            $data->category = "Livestock";
            $response = $this->livestock->insertLivestock($data);

            return $this->respond(['result' => $response, 'message' => 'Livestock Successfully Added'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function addFarmerLivestock()
    {
        try {
            $header = $this->request->getHeader("Authorization");
            $decoded = decodeToken($header);
            $userType = $decoded->aud;
            $userId = getTokenUserId($header);

            $data = $this->request->getJSON();
            $data->category = "Livestock";

            if ($userType == 'Farmer') {
                $data->farmerId = $userId;
            }

            $breedName = $data->breedName;

            if($breedName != ''){
                if(is_string($breedName)){
                    $data->livestockBreedId = $this->livestockBreed->insertLivestockBreed((object)[
                        'livestockBreedName' => $breedName,
                        'livestockBreedDescription' => '',
                        'livestockTypeId' => $data->livestockTypeId
                    ]);
                }else{
                    $data->livestockBreedId = $breedName->code;
                }
            }

            $livestockId = $this->livestock->insertLivestock($data);
            
            if(!$livestockId){
                return $this->fail('Failed to add livestock');
            }

            $data->livestockId = $livestockId;

            $response = $this->farmerLivestock->associateFarmerLivestock($data);

            $livestockType = $this->livestockTypes->getLivestockTypeName($data->livestockTypeId);
            $livestockTagId = $data->livestockTagId;

            $auditLog = (object) [
                'livestockId' => $data->livestockId,
                'farmerId' => $userId,
                'action' => "Add",
                'title' => "Add New Livestock",
                'description' => "Add New Livestock $livestockType, $livestockTagId",
                'entityAffected' => "Livestock",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            if(!$resultAudit){
                return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }
            
            return $this->respond(['result' => $livestockId], 200, 'Livestock Successfully Added');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to add livestock', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function addMultipleFarmerLivestock()
    {
        try {
            $data = $this->request->getJSON();
            $data->category = "Livestock";

            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);

            $decoded = decodeToken($header);
            $userType = $decoded->aud;
            if ($userType == 'Farmer') {
                $data->farmerId = $userId;
            }

            $auditLog = (object) [
                'farmerId' => $userId,
                'action' => "Add",
                'title' => "Add New Livestock",
                'entityAffected' => "Livestock",
            ];

            $breedName = $data->breedName;

            if($breedName != ''){
                if(is_string($breedName)){
                    $data->livestockBreedId = $this->livestockBreed->insertLivestockBreed((object)[
                        'livestockBreedName' => $breedName,
                        'livestockBreedDescription' => '',
                        'livestockTypeId' => $data->livestockTypeId
                    ]);
                }else{
                    $data->livestockBreedId = $breedName->code;
                }
            }

            if ($data->maleLivestockCount > 0) {
                $data->sex = 'Male';
                $data->livestockAgeClassId = $data->maleLivestockAgeClassId;
                for ($i = 1; $i <= $data->maleLivestockCount; $i++) {
                    $livestockId = $this->livestock->insertLivestock($data);
                    if(!$livestockId){
                        return $this->fail('Failed to add livestock');
                    }
                    $auditLog->livestockId = $livestockId;
                    $data->livestockId = $livestockId;
                    $result = $this->farmerLivestock->associateFarmerLivestock($data);
                    $livestockType = $this->livestockTypes->getLivestockTypeName($data->livestockTypeId);

                    $auditLog->description = "Add New Livestock $livestockType";
                    $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                    if(!$resultAudit){
                        return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
                    }
                }
            }

            if ($data->femaleLivestockCount > 0) {
                $data->sex = 'Female';
                $data->livestockAgeClassId = $data->femaleLivestockAgeClassId;
                for ($i = 1; $i <= $data->femaleLivestockCount; $i++) {
                    $livestockId = $this->livestock->insertLivestock($data);
                    if(!$livestockId){
                        return $this->fail('Failed to add livestock');
                    }        
                    $auditLog->livestockId = $livestockId;
                    $data->livestockId = $livestockId;
                    $result = $this->farmerLivestock->associateFarmerLivestock($data);
                    $livestockType = $this->livestockTypes->getLivestockTypeName($data->livestockTypeId);

                    $auditLog->description = "Add New Livestock $livestockType";
                    $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                    if(!$resultAudit){
                        return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
                    }
                }
            }

            return $this->respond(['result' => $data->livestockId], 200, 'Livestock Successfully Added');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to add livestock', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateLivestock()
    {
        try {
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $decoded = decodeToken($header);

            $data = $this->request->getJSON();
            $data->category = "Livestock";

            $userType = $decoded->aud;
            if ($userType == 'Farmer') {
                $data->farmerId = $userId;
            }

            $breedName = $data->breedName;

            if($breedName != ''){
                if(is_string($breedName)){
                    $data->livestockBreedId = $this->livestockBreed->insertLivestockBreed((object)[
                        'livestockBreedName' => $breedName,
                        'livestockBreedDescription' => '',
                        'livestockTypeId' => $data->livestockTypeId
                    ]);
                }else{
                    $data->livestockBreedId = $breedName->code;
                }
            }

            $response = $this->livestock->updateLivestock($data->id, $data);
            if(!$response){
                return $this->fail('Failed to update livestock');
            }   

            $livestockTagId = $data->livestockTagId;

            $auditLog = (object) [
                'livestockId' => $data->id,
                'farmerId' => $userId,
                'action' => "Edit",
                'title' => "Edit Livestock Record",
                'description' => "Updated details for Livestock $livestockTagId",
                'entityAffected' => "Livestock",
            ];
            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            if(!$resultAudit){
                return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->respond(['result' => $response], 200, 'Livestock Successfully Updated');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to add livestock', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateLivestockHealthStatus($id)
    {
        try {
            $data = $this->request->getJSON();

            $livestockTagId = $data->livestockTagId;

            $data->livestockId = $id;
            $data->action = "Edit";
            $data->title = " Edit Livestock Record";
            $data->description = "Updated Livestock $livestockTagId's Health Status";
            $data->entityAffected = "Livestock";

            $response = $this->livestock->updateLivestockHealthStatus($id, $data);

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);
            if(!$resultAudit){
                return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->respond(['result' => $response, 'message' => 'Livestock Health Status Successfully Updated'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateLivestockRecordStatus($id)
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestock->updateLivestockRecordStatus($id, $data->recordStatus);

            $livestockTagId = $data->livestockTagId;

            $data->livestockId = $id;
            $data->action = $data->recordStatus == 'Archived' ? "Archived" : "Edit";
            $data->title = $data->recordStatus == 'Archived' ? "Archived Livestock Record" : "Unarchived Livestock Record";
            $data->description = $data->recordStatus == 'Archived' ? "Archived Livestock $livestockTagId" : "Unarchived Livestock $livestockTagId";
            $data->entityAffected = "Livestock";

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);
            if(!$resultAudit){
                return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->respond(['result' => $response, 'message' => 'Livestock Record Status Successfully Updated'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteLivestock()
    {
        try {
            $id = $this->request->getGet('livestock');

            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $livestockTagId = $this->livestock->getLivestockTagIdById($id);
            $response = $this->livestock->deleteLivestock($id);

            $auditLog = (object) [
                'livestockId' => $id,
                'farmerId' => $userId,
                'action' => "Delete",
                'title' => "Delete Livestock Record",
                'description' => "Delete Livestock $livestockTagId",
                'entityAffected' => "Livestock",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            if(!$resultAudit){
                return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }
            return $this->respond(['result' => $response], 200, 'Livestock Successfully Deleted');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            $this->fail('Failed to delete record', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getLivestockMappingData()
    {
        try {
            //code...
            $mappingData = $this->userModel->getBasicUserInfo();

            foreach ($mappingData as &$md) {
                $md['livestock'] = $this->livestock->getFarmerEachLivestockTypeCountData($md['id']);
            }


            return $this->respond(['farmers' => $mappingData]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getLivestockMappingDataByType($livestockTypeId)
    {
        try {
            //code...
            $mappingData = $this->userModel->getBasicUserInfo();

            foreach ($mappingData as &$md) {
                $md['livestock'] = $this->livestock->getFarmerEachSpecificLivestockTypeCountData($md['id'], $livestockTypeId);
            }

            $filteredMappingData = array_filter($mappingData, function ($md) {
                return !empty($md['livestock']);
            });

            return $this->respond(['farmers' => array_values($filteredMappingData)]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getFarmerLivestockTypeCountDataByCity()
    {
        try {
            $data = $this->livestock->getFarmerLivestockTypeCountDataByCity();

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getFarmerLivestockIdByTag()
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestock->getFarmerLivestockIdByTag($data->livestockTagId, $data->userId);

            return $this->respond($response);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllLivestockTypeAgeClassCount()
    {
        try {
            $data = $this->livestock->getAllLivestockTypeAgeClassCount();

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getFarmerLivestockTypeAgeClassCount()
    {
        try {
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);

            $data = $this->livestock->getFarmerLivestockTypeAgeClassCount($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllLivestockTypeCount()
    {
        try {
            $data = $this->livestock->getAllLivestockTypeCount();

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getFarmerLivestockTypeCount()
    {
        try {
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);

            $data = $this->livestock->getFarmerLivestockTypeCount($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllLivestockCountMonitored()
    {
        try {
            $livestockCount = $this->livestock->getAllLivestockCount();

            $data = [
                'totalLivestockCount' => "$livestockCount"
            ];

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getFarmerLivestockCount()
    {
        try {
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $data = $this->livestock->getFarmerLivestockCount($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllLivestockTagIDs()
    {
        try {
            $userId = $this->request->getGet('fui');

            $data = $this->livestock->getAllFarmerLivestockTagIDs($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllFarmerLivestockTagIDs()
    {
        try {

            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);

            $data = $this->livestock->getAllFarmerLivestockTagIDs($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getFarmerDistinctLivestockType()
    {
        try {
            $header = $this->request->getHeader("Authorization");
            $userId = null;
            $decoded = decodeToken($header);
            $userType = $decoded->aud;
            if ($userType == 'Farmer') {
                $userId = $decoded->sub->id;
            } else {
                $userId = $this->request->getGet('fui');
            }

            $data = $this->livestock->getFarmerDistinctLivestockType($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllFarmerLivestocksBySexAndType()
    {
        try {
            $header = $this->request->getHeader("Authorization");
            $userId = null;
            $decoded = decodeToken($header);
            $userType = $decoded->aud;
            if ($userType == 'Farmer') {
                $userId = $decoded->sub->id;
            } else {
                $userId = $this->request->getGet('fui');
            }

            $data = $this->livestock->getAllFarmerLivestocksBySexAndType($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));

        }
    }

    public function getAllBreedingEligibleLivestocks()
    {
        try {
            $breedingEligibleLivestocks = $this->livestock->getAllBreedingEligibleLivestocks();

            $livestockCount = $this->livestock->getAllLivestockCount();

            $breedingEligiblePercentage = 0;
            if ($livestockCount > 0) {
                $percentage = ($breedingEligibleLivestocks / $livestockCount) * 100;

                if (floor($percentage) == $percentage) {
                    // Display only whole numbers
                    $breedingEligiblePercentage = number_format($percentage, 0);
                } else {
                    // Display up to two decimal places
                    $breedingEligiblePercentage = number_format($percentage, 2);
                }
            }

            $data = [
                'livestockBreedingEligibleCount' => "$breedingEligibleLivestocks",
                'livestockBreedingEligiblePercentage' => $breedingEligiblePercentage . "%",
            ];


            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockCountByMonthAndType()
    {
        try {
            $animal = $this->request->getGet('animal');
            $origin = $this->request->getGet('origin');
            $year = $this->request->getGet('year');

            $livestockTypes = $this->livestockTypes->getAllLivestockTypeName($animal);

            $data = $this->livestock->getLivestockCountByMonthAndType($livestockTypes, $animal, $origin, $year);
            // $data = $this->livestock->getLivestockCountByMonthAndType($livestockTypes);


            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getLivestockHealthStatusesCount()
    {
        try {
            $data = $this->livestock->getLivestockHealthStatusesCount('Livestock');

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    // public function getAllLivestockTypeCountByCity($city)
    // {
    //     try {

    //         $cities = $this->request->getGet('cities');

    //         $data = [];
    //         $i = 1;
    //         foreach ($cities as $city) {
    //             $livestockTypeCount = $this->livestock->getAllLivestockTypeCountByCity($city);

    //             $totalLivestockCount = $this->livestock->getLivestockCountBycity($city);
    //             $data[] = [

    //                 'livestockType' => $livestockTypeCount,
    //                 'totalLivestockCount' => $totalLivestockCount
    //             ];
    //         }

    //         return $this->respond($data);
    //     } catch (\Throwable $th) {
    //         return $this->respond($th->getMessage());
    //     }
    // }

    public function getLivestockCountAllCity($origin)
    {
        try {
            $cities = [
                'Puerto Galera',
                'San Teodoro',
                'Baco',
                'Calapan City',
                'Naujan',
                'Victoria',
                'Socorro',
                'Pinamalayan',
                'Gloria',
                'Bansud',
                'Bongabong',
                'Roxas',
                'Mansalay',
                'Bulalacao'
            ];

            $data = [];
            $i = 1;
            foreach ($cities as $city) {
                $livestockTypeCount = $this->livestock->getAllLivestockTypeCountByCity($city, $origin);

                $totalLivestockCount = $this->livestock->getLivestockCountBycity($city, $origin);

                $registeredFarmersCount = $this->userModel->getFarmerCountByCity($city);
                $data[] = [
                    'id' => $i++,
                    'livestock' => $livestockTypeCount,
                    'totalLivestockCount' => $totalLivestockCount,
                    'city' => $city,
                    'registeredFarmersCount' => $registeredFarmersCount
                ];
            }

            return $this->respond($data);
        } catch (\Throwable $th) {
            return $this->respond($th->getMessage());
        }
    }

    public function getLivestockTypeCountAllCity($livestockTypeId)
    {
        try {
            $cities = [
                'Puerto Galera',
                'San Teodoro',
                'Baco',
                'Calapan City',
                'Naujan',
                'Victoria',
                'Socorro',
                'Pinamalayan',
                'Gloria',
                'Bansud',
                'Bongabong',
                'Roxas',
                'Mansalay',
                'Bulalacao'
            ];

            $data = [];
            foreach ($cities as $city) {
                $livestockTypeCount = $this->livestock->getLivestockTypeCountBycity($city, $livestockTypeId);
                $data[] = [
                    'livestock' => $livestockTypeCount,
                    'city' => $city,
                ];
            }

            return $this->respond($data);
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->respond($th->getMessage());
        }
    }

    public function getLivestockBreedCountAllCity($livestockBreedId)
    {
        try {
            $cities = [
                'Puerto Galera',
                'San Teodoro',
                'Baco',
                'Calapan City',
                'Naujan',
                'Victoria',
                'Socorro',
                'Pinamalayan',
                'Gloria',
                'Bansud',
                'Bongabong',
                'Roxas',
                'Mansalay',
                'Bulalacao'
            ];

            $data = [];
            foreach ($cities as $city) {
                $livestockBreedCount = $this->livestock->getLivestockBreedCountByCity($city, $livestockBreedId);
                $data[] = [
                    'livestock' => $livestockBreedCount,
                    'city' => $city,
                ];
            }

            return $this->respond($data);
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->respond($th->getMessage());
        }
    }

    public function getLivestockAgeClassCountAllCity($livestockAgeId)
    {
        try {
            $cities = [
                'Puerto Galera',
                'San Teodoro',
                'Baco',
                'Calapan City',
                'Naujan',
                'Victoria',
                'Socorro',
                'Pinamalayan',
                'Gloria',
                'Bansud',
                'Bongabong',
                'Roxas',
                'Mansalay',
                'Bulalacao'
            ];

            $data = [];
            foreach ($cities as $city) {
                $livestockAgeClassCount = $this->livestock->getLivestockAgeClassCountByCity($city, $livestockAgeId);
                $data[] = [
                    'livestock' => $livestockAgeClassCount,
                    'city' => $city,
                ];
            }

            return $this->respond($data);
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->respond($th->getMessage());
        }
    }

    public function getLivestockProductionCountWholeYear()
    {
        try {
            $data = $this->livestock->getLivestockProductionCountWholeYear();

            return $this->respond($data);
        } catch (\Throwable $th) {
            return $this->respond($th->getMessage());
        }
    }

    public function getLivestockProductionWholeYear()
    {
        try {
            $livestockTypes = $this->livestockTypes->getAllLivestockTypeIdName();

            $livestock = $this->livestock->getProductionCountByMonthAndType($livestockTypes);

            return $this->respond($livestock);
        } catch (\Throwable $th) {
            // Handle exceptions
            return $th->getMessage();
        }
    }

    public function getLivestockProductionSelectedYear($year, $origin)
    {
        try {
            $livestockTypes = $this->livestockTypes->getAllLivestockTypeIdName();

            $livestock = $this->livestock->getProductionCountByMonthYearAndType($livestockTypes, $year, $origin);

            return $this->respond($livestock);
        } catch (\Throwable $th) {
            // Handle exceptions
            return $th->getMessage();
        }
    }

    public function importLivestockData()
    {
        try {
            $livestockData = $this->request->getJSON();
            $success = false;

            $newL = null;
            $livestockTagId = "";
            foreach ($livestockData as $livestock) {
                if (!property_exists($livestock, 'LivestockType')) {
                    continue;
                }
                $success = false;
                $livestockTagId = isset($livestock->LivestockTagID) ? trim($livestock->LivestockTagID) : null;
                $category = 'Livestock';

                $livestockType = trim($livestock->LivestockType);
                $livestockTypeId = $this->livestockTypes->getLivestockTypeIdByName($livestockType, $category);

                $livestockAgeClassification = trim($livestock->LivestockAgeClassification);
                $livestockAgeClassificationId = $this->livestockAgeClass->getLivestockAgeClassIdByName($livestockAgeClassification, $livestockTypeId);

                $livestockBreed = trim($livestock->LivestockBreed);
                $livestockBreedId = $this->livestockBreed->getLivestockBreedIdByName($livestockBreed, $livestockTypeId);

                $dateOfBirth = $livestock->DateofBirth;
                $sex = $livestock->Sex;
                $breedingEligibility = $livestock->BreedingEligibility;
                $livestockHealthStatus = $livestock->HealthStatus;

                $newLivestock = $this->livestock->insertLivestock((object) [
                    'livestockTagId' => $livestockTagId,
                    'livestockTypeId' => $livestockTypeId,
                    'livestockBreedId' => $livestockBreedId,
                    'livestockAgeClassId' => $livestockAgeClassificationId,
                    'category' => $category,
                    'dateOfBirth' => $dateOfBirth,
                    'sex' => $sex,
                    'breedingEligibility' => $breedingEligibility,
                    'livestockHealthStatus' => $livestockHealthStatus
                ]);

                if (isset($livestock->FarmerUserID)) {
                    $farmerUserID = $livestock->FarmerUserID;
                    $farmer = $this->userModel->getIdByUserId($farmerUserID);
                    $acquiredDate = $dateOfBirth;

                    $newL = $newLivestock;

                    // $newLivestockAcquired = $this->farmerLivestock->associateFarmerLivestock((object) [
                    //     'livestockId' => $newLivestock,
                    //     'farmerId' => $farmer['id'],
                    //     'acquiredDate' => $acquiredDate
                    // ]);
                }

                $success = $newLivestock;
            }

            return $this->respond(['success' => $success, 'message' => 'Successfully imported Livestock Data', 'newL' => $newL, 'farmer' => $farmer['id'], 'acquiredDate' => $acquiredDate]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getTrace(), 'message' => $th->getMessage()]);
        }
    }

    public function getLivestockRecordsForReport()
    {
        try {
            $category = $this->request->getGet('category');
            $minDate = $this->request->getGet('minDate');
            $maxDate = $this->request->getGet('maxDate');

            $livestockRecordsReport = $this->livestock->getLivestockRecordForReport($category, $minDate, $maxDate);

            // Check if the report data is not null
            if (is_null($livestockRecordsReport) || empty($livestockRecordsReport)) {
                return $this->failNotFound('No data found for the given date range.');
            }

            // Generate Excel file using PhpSpreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->getPageSetup()
                ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL);

            // Set narrow margins
            $sheet->getPageMargins()->setTop(0.25);
            $sheet->getPageMargins()->setRight(0.25);
            $sheet->getPageMargins()->setLeft(0.25);
            $sheet->getPageMargins()->setBottom(0.25);

            // Fit to one page width
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            // 1st Row: LIVESTOCK VACCINATIONS
            $sheet->mergeCells('A1:M1');
            $sheet->setCellValue('A1', 'Department of Agriculture- MiMaRoPa');
            $sheet->getStyle('A1:M1')->applyFromArray([
                'font' => [
                    'name' => 'Arial',
                    'size' => 12,
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);

            // 1st Row: LIVESTOCK VACCINATIONS
            $sheet->mergeCells('A2:M2');
            $sheet->setCellValue('A2', 'Research Division-Livestock Department');
            $sheet->getStyle('A2:M2')->applyFromArray([
                'font' => [
                    'name' => 'Arial',
                    'size' => 11,
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);

            // 1st Row: LIVESTOCK VACCINATIONS
            $sheet->mergeCells('A3:M3');
            $sheet->setCellValue('A3', 'Barcenaga, Naujan Oriental Mindoro');
            $sheet->getStyle('A3:M3')->applyFromArray([
                'font' => [
                    'name' => 'Arial',
                    'size' => 11,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);

            $sheet->getRowDimension(4)->setRowHeight(15);


            // 5th Row: LIVESTOCK VACCINATIONS
            $sheet->mergeCells('A5:M5');
            $sheet->setCellValue('A5', strtoupper($category));
            $sheet->getStyle('A5:M5')->applyFromArray([
                'font' => [
                    'name' => 'Arial',
                    'bold' => true,
                    'size' => 14,
                    'color' => ['argb' => 'FFFFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FF203764'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);

            // 6th Row: Date Range
            $sheet->mergeCells('A6:M6');
            $startDate = date('F Y', strtotime($minDate));
            $endDate = date('F Y', strtotime($maxDate));
            $sheet->setCellValue('A6', "$startDate To $endDate");
            $sheet->getStyle('A6:M6')->applyFromArray([
                'font' => [
                    'name' => 'Arial',
                    'bold' => false,
                    'size' => 11,
                    'color' => ['argb' => 'FF000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFD9E1F2'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);

            // 7th Row: Blank Row
            $sheet->getRowDimension(7)->setRowHeight(15);

            // 8th Row: Date Exported
            $sheet->mergeCells('A8:M8');
            $dateExported = date('F j, Y');
            $sheet->setCellValue('A8', "Date Exported: $dateExported");
            $sheet->getStyle('A8:M8')->applyFromArray([
                'font' => [
                    'name' => 'Arial',
                    'bold' => false,
                    'size' => 11,
                    'color' => ['argb' => 'FF000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFFFD966'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);

            // 9th Row: Blank Row
            $sheet->getRowDimension(9)->setRowHeight(15);

            $headers = [];

            if (strtoupper($category) == 'LIVESTOCK') {
                $headers = [
                    'Livestock Tag ID',
                    'Livestock Type',
                    'Livestock Breed',
                    'Livestock Age Classification',
                    'Farmer User ID',
                    'Farmer Full Name',
                    'Farmer Address',
                    'Livestock Current Age',
                    'Sex',
                    'Breeding Eligibility',
                    'Date of Birth',
                    'Health Status',
                    'Origin'
                ];
            } else if (strtoupper($category) == 'POULTRY') {
                $headers = [
                    'Poultry Tag ID',
                    'Poultry Type',
                    'Poultry Breed',
                    'Poultry Age Classification',
                    'Farmer User ID',
                    'Farmer Full Name',
                    'Farmer Address',
                    'Poultry Current Age',
                    'Sex',
                    'Breeding Eligibility',
                    'Date of Birth',
                    'Health Status',
                    'Origin'
                ];
            }

            // 10th Row: Column Headers

            $headerStyles = [
                'font' => [
                    'name' => 'Arial',
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => 'FF000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFD9E1F2'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP, // Top align the text
                    'wrapText' => true, // Word wrap the text
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ];

            // Data Styles with Borders
            $dataStyles = [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP, // Top align the text
                    'wrapText' => true, // Word wrap the text
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ];

            $columnLetter = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($columnLetter . '10', $header);
                $sheet->getStyle($columnLetter . '10')->applyFromArray($headerStyles);
                $columnLetter++;
            }

            // Set column widths
            $columnWidths = [16, 16, 16, 16, 16, 20, 30, 12, 12, 12, 12, 12, 14];
            foreach (range('A', 'M') as $index => $columnID) {
                $sheet->getColumnDimension($columnID)->setWidth($columnWidths[$index]);
            }

            // Add data to the sheet starting from the 7th row
            $rowIndex = 11;
            foreach ($livestockRecordsReport as $record) {
                $columnLetter = 'A';
                foreach ($record as $value) {
                    $sheet->setCellValue($columnLetter . $rowIndex, $value);
                    $sheet->getStyle($columnLetter . $rowIndex)->applyFromArray($dataStyles);
                    $columnLetter++;
                }
                $rowIndex++;
            }

            $excelDirectory = WRITEPATH . "exports/excel/";
            $pdfDirectory = WRITEPATH . "exports/pdf/";
            if (!is_dir($excelDirectory)) {
                mkdir($excelDirectory, 0777, true); // Recursive directory creation
            }
            if (!is_dir($pdfDirectory)) {
                mkdir($pdfDirectory, 0777, true); // Recursive directory creation
            }

            // Generate unique ID
            $uniqueId = uniqid();

            // Format filenames
            $fileName = strtoupper($category) == 'LIVESTOCK' ? "LivestockRecordsReport_{$minDate}_{$maxDate}_{$uniqueId}" : "PoultryRecordsReport_{$minDate}_{$maxDate}_{$uniqueId}";
            $excelFilePath = $excelDirectory . "{$fileName}.xlsx";
            $pdfFilePath = $pdfDirectory . "{$fileName}.pdf";

            $excelContent = null;
            $pdfContent = null;

            try {
                $writer = new Xlsx($spreadsheet);
                $writer->save($excelFilePath);
                $excelContent = file_get_contents($excelFilePath);
            } catch (\Throwable $th) {
                //throw $th;
                log_message('error', $th->getMessage());
                log_message('error', json_encode($th->getTrace()));
            }

            $spreadsheet->getActiveSheet()->setShowGridlines(false);
            try {
                //code...
                $html = $this->generateHtmlFromSpreadsheet($spreadsheet);
                $options = new Options();
                $options->set('isHtml5ParserEnabled', true);
                $dompdf = new Dompdf($options);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('legal', 'landscape');
                $dompdf->render();
                $pdfOutput = $dompdf->output();
                $pdfFilePath = $pdfDirectory . "{$fileName}.pdf";
                file_put_contents($pdfFilePath, $pdfOutput);
                $pdfContent = file_get_contents($pdfFilePath);
            } catch (\Throwable $th) {
                //throw $th;
                log_message('error', $th->getMessage());
                log_message('error', json_encode($th->getTrace()));
            }

            // Delete the files after reading
            unlink($excelFilePath);
            unlink($pdfFilePath);

            // Return both Excel and PDF as base64 encoded strings
            return $this->respond([
                'excel' => base64_encode($excelContent),
                'pdf' => base64_encode($pdfContent),
                'fileName' => $fileName
            ]);
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage());
            log_message('error', json_encode($th->getTrace()));
            return $this->failServerError($th->getMessage());
        }
    }

    private function generateHtmlFromSpreadsheet($spreadsheet)
    {
        // Use PhpSpreadsheet to save the sheet as an HTML string
        $writer = IOFactory::createWriter($spreadsheet, 'Html');
        ob_start();
        $writer->save('php://output');
        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

    private function getLivestockOrPoultry($string)
    {
        $patterns = ['livestock', 'poultry'];

        foreach ($patterns as $pattern) {
            if (preg_match("/$pattern/", $string)) {
                return $pattern;
            }
        }
        return 'none';
    }

    private function splitAndCapitalize($string)
    {
        // Step 1: Insert a space before each capital letter (except the first letter)
        $spacedString = preg_replace('/(?<!^)([A-Z])/', ' $1', $string);

        // Step 2: Convert the entire string to lowercase
        $lowercasedString = strtolower($spacedString);

        // Step 3: Capitalize the first letter of each word
        $capitalizedString = ucwords($lowercasedString);

        return $capitalizedString;
    }

    private function getLivestockDisProdFarmerForReport($category, $origin, $minDate, $maxDate)
    {
        try {
            $originTable = $origin === 'Distribution' ? 'Distributed' : 'Produced';

            $livestockRecordsReport = $this->livestock->getLivestockDisProdForReport($category, $minDate, $maxDate, $originTable);

            // Check if the report data is not null
            if (is_null($livestockRecordsReport) || empty($livestockRecordsReport)) {
                return ['error'=> true, 'message' => 'No data found for the given date range.'];
            }

            // Generate Excel file using PhpSpreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->getPageSetup()
                ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL);

            // Set narrow margins
            $sheet->getPageMargins()->setTop(0.25);
            $sheet->getPageMargins()->setRight(0.25);
            $sheet->getPageMargins()->setLeft(0.25);
            $sheet->getPageMargins()->setBottom(0.25);

            // Fit to one page width
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            // 1st Row: LIVESTOCK VACCINATIONS
            $sheet->mergeCells('A1:M1');
            $sheet->setCellValue('A1', 'Department of Agriculture- MiMaRoPa');
            $sheet->getStyle('A1:M1')->applyFromArray([
                'font' => [
                    'name' => 'Arial',
                    'size' => 12,
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);

            // 1st Row: LIVESTOCK VACCINATIONS
            $sheet->mergeCells('A2:M2');
            $sheet->setCellValue('A2', 'Research Division-Livestock Department');
            $sheet->getStyle('A2:M2')->applyFromArray([
                'font' => [
                    'name' => 'Arial',
                    'size' => 11,
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);

            // 1st Row: LIVESTOCK VACCINATIONS
            $sheet->mergeCells('A3:M3');
            $sheet->setCellValue('A3', 'Barcenaga, Naujan Oriental Mindoro');
            $sheet->getStyle('A3:M3')->applyFromArray([
                'font' => [
                    'name' => 'Arial',
                    'size' => 11,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);

            $sheet->getRowDimension(4)->setRowHeight(15);

            $title = "$category $origin";
            // 5th Row: LIVESTOCK VACCINATIONS
            $sheet->mergeCells('A5:M5');
            $sheet->setCellValue('A5', strtoupper($title));
            $sheet->getStyle('A5:M5')->applyFromArray([
                'font' => [
                    'name' => 'Arial',
                    'bold' => true,
                    'size' => 14,
                    'color' => ['argb' => 'FFFFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FF203764'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);

            // 6th Row: Date Range
            $sheet->mergeCells('A6:M6');
            $startDate = date('F Y', strtotime($minDate));
            $endDate = date('F Y', strtotime($maxDate));
            $sheet->setCellValue('A6', "$startDate To $endDate");
            $sheet->getStyle('A6:M6')->applyFromArray([
                'font' => [
                    'name' => 'Arial',
                    'bold' => false,
                    'size' => 11,
                    'color' => ['argb' => 'FF000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFD9E1F2'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);

            // 7th Row: Blank Row
            $sheet->getRowDimension(7)->setRowHeight(15);

            // 8th Row: Date Exported
            $sheet->mergeCells('A8:M8');
            $dateExported = date('F j, Y');
            $sheet->setCellValue('A8', "Date Exported: $dateExported");
            $sheet->getStyle('A8:M8')->applyFromArray([
                'font' => [
                    'name' => 'Arial',
                    'bold' => false,
                    'size' => 11,
                    'color' => ['argb' => 'FF000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFFFD966'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);

            // 9th Row: Blank Row
            $sheet->getRowDimension(9)->setRowHeight(15);

            $headers = [
                'No.',
                'Farmer User ID',
                'Farmer Full Name',
                'Sitio',
                'Barangay',
                'Municipality/City',
                'Province',
                'Date of Birth',
                'Contact',
                'Animal',
                'Breed',
                'No. of Heads',
                'Date of ' . $origin
            ];

            // 10th Row: Column Headers

            $headerStyles = [
                'font' => [
                    'name' => 'Arial',
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => 'FF000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFD9E1F2'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP, // Top align the text
                    'wrapText' => true, // Word wrap the text
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ];

            // Data Styles with Borders
            $dataStyles = [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP, // Top align the text
                    'wrapText' => true, // Word wrap the text
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ];

            $columnLetter = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($columnLetter . '10', $header);
                $sheet->getStyle($columnLetter . '10')->applyFromArray($headerStyles);
                $columnLetter++;
            }

            // Set column widths
            $columnWidths = [8, 16, 20, 10, 10, 10, 10, 12, 12, 16, 16, 12, 12];
            foreach (range('A', 'M') as $index => $columnID) {
                $sheet->getColumnDimension($columnID)->setWidth($columnWidths[$index]);
            }

            // Add data to the sheet starting from the 7th row
            $rowIndex = 11;
            foreach ($livestockRecordsReport as $record) {
                $columnLetter = 'A';
                foreach ($record as $value) {
                    $sheet->setCellValue($columnLetter . $rowIndex, $value);
                    $sheet->getStyle($columnLetter . $rowIndex)->applyFromArray($dataStyles);
                    $columnLetter++;
                }
                $rowIndex++;
            }

            $excelDirectory = WRITEPATH . "exports/excel/";
            $pdfDirectory = WRITEPATH . "exports/pdf/";
            if (!is_dir($excelDirectory)) {
                mkdir($excelDirectory, 0777, true); // Recursive directory creation
            }
            if (!is_dir($pdfDirectory)) {
                mkdir($pdfDirectory, 0777, true); // Recursive directory creation
            }

            // Generate unique ID
            $uniqueId = uniqid();

            // Format filenames
            $fileName = "{$category}{$origin}sReport_{$minDate}_{$maxDate}_{$uniqueId}";
            $excelFilePath = $excelDirectory . "{$fileName}.xlsx";
            $pdfFilePath = $pdfDirectory . "{$fileName}.pdf";

            $excelContent = null;
            $pdfContent = null;

            try {
                $writer = new Xlsx($spreadsheet);
                $writer->save($excelFilePath);
                $excelContent = file_get_contents($excelFilePath);
            } catch (\Throwable $th) {
                //throw $th;
                log_message('error', $th->getMessage());
                log_message('error', json_encode($th->getTrace()));
            }

            $spreadsheet->getActiveSheet()->setShowGridlines(false);
            try {
                //code...
                $html = $this->generateHtmlFromSpreadsheet($spreadsheet);
                $options = new Options();
                $options->set('isHtml5ParserEnabled', true);
                $dompdf = new Dompdf($options);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('legal', 'landscape');
                $dompdf->render();
                $pdfOutput = $dompdf->output();
                $pdfFilePath = $pdfDirectory . "{$fileName}.pdf";
                file_put_contents($pdfFilePath, $pdfOutput);
                $pdfContent = file_get_contents($pdfFilePath);
            } catch (\Throwable $th) {
                //throw $th;
                log_message('error', $th->getMessage());
                log_message('error', json_encode($th->getTrace()));
            }

            // Delete the files after reading
            unlink($excelFilePath);
            unlink($pdfFilePath);

            // Return both Excel and PDF as base64 encoded strings
            return [
                'excel' => base64_encode($excelContent),
                'pdf' => base64_encode($pdfContent),
                'fileName' => $fileName
            ];
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage());
            log_message('error', json_encode($th->getTrace()));
            return $th->getMessage();
        }
    }

    public function getAnimalTypeCountMonitoring()
    {
        try {
            $data = $this->livestock->getLivestockTypeCountMonitoring();

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage());
            log_message('error', json_encode($th->getTrace()));
            return $this->failServerError($th->getMessage());
        }
    }

    public function getLivestockTypeCountMonitoring()
    {
        try {
            $category = $this->request->getGet('category');
            $data = $this->livestock->getLivestockTypeCountMonitoring($category);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage());
            log_message('error', json_encode($th->getTrace()));
            return $this->failServerError($th->getMessage());
        }
    }

    public function getLivestockBreedCountMonitoring()
    {
        try {
            $category = $this->request->getGet('category');
            $data = $this->livestock->getLivestockBreedCountMonitoring($category);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage());
            log_message('error', json_encode($th->getTrace()));
            return $this->failServerError($th->getMessage());
        }
    }

    public function getLivestockAgeCountMonitoring()
    {
        try {
            $category = $this->request->getGet('category');
            $data = $this->livestock->getLivestockAgeCountMonitoring($category);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage());
            log_message('error', json_encode($th->getTrace()));
            return $this->failServerError($th->getMessage());
        }
    }

    private function getLivestockDisProdMunicipalityForReport($category, $origin, $minDate, $maxDate)
    {
        try {
            $cities = [
                'Puerto Galera',
                'San Teodoro',
                'Baco',
                'Calapan City',
                'Naujan',
                'Victoria',
                'Socorro',
                'Pinamalayan',
                'Gloria',
                'Bansud',
                'Bongabong',
                'Roxas',
                'Mansalay',
                'Bulalacao'
            ];

            $originTable = $origin === 'Distribution' ? 'Distributed' : 'Produced';

            $data = $this->livestock->getLivestockDistributionForCities($category, $minDate, $maxDate, $originTable, $cities);
            // Check if the report data is not null
            if (is_null($data) || empty($data)) {
                return ['error'=> true, 'message' => 'No data found for the given date range.'];
            }

            // Generate Excel file using PhpSpreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->getPageSetup()
                ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT)
                ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL);

            // Set narrow margins
            $sheet->getPageMargins()->setTop(0.25);
            $sheet->getPageMargins()->setRight(0.25);
            $sheet->getPageMargins()->setLeft(0.25);
            $sheet->getPageMargins()->setBottom(0.25);

            // Fit to one page width
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            // 1st Row: LIVESTOCK VACCINATIONS
            $sheet->mergeCells('A1:I1');
            $sheet->setCellValue('A1', 'Department of Agriculture- MiMaRoPa');
            $sheet->getStyle('A1:I1')->applyFromArray([
                'font' => [
                    'name' => 'Arial',
                    'size' => 12,
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);

            // 1st Row: LIVESTOCK VACCINATIONS
            $sheet->mergeCells('A2:I2');
            $sheet->setCellValue('A2', 'Research Division-Livestock Department');
            $sheet->getStyle('A2:I2')->applyFromArray([
                'font' => [
                    'name' => 'Arial',
                    'size' => 11,
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);

            // 1st Row: LIVESTOCK VACCINATIONS
            $sheet->mergeCells('A3:I3');
            $sheet->setCellValue('A3', 'Barcenaga, Naujan Oriental Mindoro');
            $sheet->getStyle('A3:I3')->applyFromArray([
                'font' => [
                    'name' => 'Arial',
                    'size' => 11,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);

            $sheet->getRowDimension(4)->setRowHeight(15);

            $title = "$category $origin";
            // 5th Row: LIVESTOCK VACCINATIONS
            $sheet->mergeCells('A5:I5');
            $sheet->setCellValue('A5', strtoupper($title));
            $sheet->getStyle('A5:I5')->applyFromArray([
                'font' => [
                    'name' => 'Arial',
                    'bold' => true,
                    'size' => 14,
                    'color' => ['argb' => 'FFFFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FF203764'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);

            // 6th Row: Date Range
            $sheet->mergeCells('A6:I6');
            $startDate = date('F Y', strtotime($minDate));
            $endDate = date('F Y', strtotime($maxDate));
            $sheet->setCellValue('A6', "$startDate To $endDate");
            $sheet->getStyle('A6:I6')->applyFromArray([
                'font' => [
                    'name' => 'Arial',
                    'bold' => false,
                    'size' => 11,
                    'color' => ['argb' => 'FF000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFD9E1F2'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);

            // 7th Row: Blank Row
            $sheet->getRowDimension(7)->setRowHeight(15);

            // 8th Row: Date Exported
            $sheet->mergeCells('A8:I8');
            $dateExported = date('F j, Y');
            $sheet->setCellValue('A8', "Date Exported: $dateExported");
            $sheet->getStyle('A8:I8')->applyFromArray([
                'font' => [
                    'name' => 'Arial',
                    'bold' => false,
                    'size' => 11,
                    'color' => ['argb' => 'FF000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFFFD966'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]);

            // 9th Row: Blank Row
            $sheet->getRowDimension(9)->setRowHeight(15);

            $headers = [
                'Barangay',
                'Municipality/City',
                'Province',
                'Livestock Type',
                'Breed',
                'Age Classification',
                'Alive',
                'Dead',
                'No. of Heads',
            ];

            // 10th Row: Column Headers

            $headerStyles = [
                'font' => [
                    'name' => 'Arial',
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => 'FF000000'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFD9E1F2'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP, // Top align the text
                    'wrapText' => true, // Word wrap the text
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ];

            // Data Styles with Borders
            $dataStyles = [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP, // Top align the text
                    'wrapText' => true, // Word wrap the text
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ];

            $columnLetter = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($columnLetter . '10', $header);
                $sheet->getStyle($columnLetter . '10')->applyFromArray($headerStyles);
                $columnLetter++;
            }

            // Set column widths
            $columnWidths = [15, 20, 20, 17, 17, 17, 12, 12, 12];
            foreach (range('A', 'I') as $index => $columnID) {
                $sheet->getColumnDimension($columnID)->setWidth($columnWidths[$index]);
            }

            // Add data to the sheet starting from the 7th row
            $rowIndex = 11;
            foreach ($data as $record) {
                $columnLetter = 'A';
                foreach ($record as $value) {
                    $sheet->setCellValue($columnLetter . $rowIndex, $value);
                    $sheet->getStyle($columnLetter . $rowIndex)->applyFromArray($dataStyles);
                    $columnLetter++;
                }
                $rowIndex++;
            }

            $excelDirectory = WRITEPATH . "exports/excel/";
            $pdfDirectory = WRITEPATH . "exports/pdf/";
            if (!is_dir($excelDirectory)) {
                mkdir($excelDirectory, 0777, true); // Recursive directory creation
            }
            if (!is_dir($pdfDirectory)) {
                mkdir($pdfDirectory, 0777, true); // Recursive directory creation
            }

            // Generate unique ID
            $uniqueId = uniqid();

            // Format filenames
            $fileName = "{$category}{$origin}sReport_{$minDate}_{$maxDate}_{$uniqueId}";
            $excelFilePath = $excelDirectory . "{$fileName}.xlsx";
            $pdfFilePath = $pdfDirectory . "{$fileName}.pdf";

            $excelContent = null;
            $pdfContent = null;

            try {
                $writer = new Xlsx($spreadsheet);
                $writer->save($excelFilePath);
                $excelContent = file_get_contents($excelFilePath);
            } catch (\Throwable $th) {
                //throw $th;
                log_message('error', $th->getMessage());
                log_message('error', json_encode($th->getTrace()));
            }

            $spreadsheet->getActiveSheet()->setShowGridlines(false);
            try {
                //code...
                $html = $this->generateHtmlFromSpreadsheet($spreadsheet);
                $options = new Options();
                $options->set('isHtml5ParserEnabled', true);
                $dompdf = new Dompdf($options);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('legal', 'portrait');
                $dompdf->render();
                $pdfOutput = $dompdf->output();
                $pdfFilePath = $pdfDirectory . "{$fileName}.pdf";
                file_put_contents($pdfFilePath, $pdfOutput);
                $pdfContent = file_get_contents($pdfFilePath);
            } catch (\Throwable $th) {
                //throw $th;
                log_message('error', $th->getMessage());
                log_message('error', json_encode($th->getTrace()));
            }

            // Delete the files after reading
            unlink($excelFilePath);
            unlink($pdfFilePath);

            // Return both Excel and PDF as base64 encoded strings
            return [
                'excel' => base64_encode($excelContent),
                'pdf' => base64_encode($pdfContent),
                'fileName' => $fileName
            ];
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->respond($th->getMessage());
        }
    }

    public function getLivestockDisProdForReport()
    {
        try {
            //code...
            $category = $this->request->getGet('category');
            $origin = $this->request->getGet('origin');
            $type = $this->request->getGet('type');
            $minDate = $this->request->getGet('minDate');
            $maxDate = $this->request->getGet('maxDate');

            $data = null;

            if ($type == 'Farmer') {
                $data = $this->getLivestockDisProdFarmerForReport($category, $origin, $minDate, $maxDate);
            } else {
                $data = $this->getLivestockDisProdMunicipalityForReport($category, $origin, $minDate, $maxDate);
            }
            if(isset($data['error'])){
                return $this->failNotFound($data['message']);
            }
            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->respond($th->getMessage());
        }
    }

}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FarmerAuditModel;
use App\Models\FarmerLivestockModel;
use App\Models\LivestockModel;
use App\Models\LivestockTypeModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class PoultryController extends ResourceController
{
    private $poultry;
    private $poultryTypes;
    private $farmerPoultry;
    private $userModel;
    private $farmerAudit;

    public function __construct()
    {
        $this->poultry = new LivestockModel();
        $this->poultryTypes = new LivestockTypeModel();
        $this->farmerPoultry = new FarmerLivestockModel();
        $this->userModel = new UserModel();
        $this->farmerAudit = new FarmerAuditModel();
        helper('jwt');
    }

    public function getAllPoultries(){
        try {
            $poultries = $this->poultry->getAllPoultries();

            return $this->respond($poultries);
        } catch (\Throwable $th) {
            return $this->fail($th->getMessage());
        }
    }

    public function getPoultryReportData(){
        try {
            $selectClause = $this->request->getGet('selectClause');
            $minDate = $this->request->getGet('minDate');
            $maxDate = $this->request->getGet('maxDate');
            $category = 'Poultry';

            $poultries = $this->poultry->getReportData($category, $selectClause, $minDate, $maxDate);

            return $this->respond($poultries);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getPoultry($id){
        try {
            $poultry = $this->poultry->getPoultryById($id);

            return $this->respond($poultry);
        } catch (\Throwable $th) {
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getFarmerAllPoultries($userId){
        try {
            $poultries = $this->poultry->getFarmerAllPoultries($userId);

            return $this->respond($poultries);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function addPoultry(){
        try {
            $data = $this->request->getJSON();
            $data->category = "Poultry";
            $response = $this->poultry->insertLivestock($data);

            return $this->respond(['success' => $response, 'message' => 'Poultry Successfully Added'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    
    public function addFarmerPoultry(){
        try {
            $header = $this->request->getHeader("Authorization");
            $decoded = decodeToken($header);
            $userType = $decoded->aud;
            $userId = getTokenUserId($header);

            $data = $this->request->getJSON();
            $data->category = "Poultry";

            if($userType == 'Farmer'){
                $data->farmerId = $userId;
            }

            $livestockId = $this->poultry->insertLivestock($data);

            $data->livestockId = $livestockId;    

            $response = $this->farmerPoultry->associateFarmerLivestock($data);

            $livestockType = $this->poultryTypes->getLivestockTypeName($data->livestockTypeId);
            $livestockTagId = $data->livestockTagId;

            $auditLog = (object)[
                'livestockId' => $data->livestockId,
                'farmerId' => $userId,
                'action' => "Add",
                'title' => "Add New Poultry",
                'description' => "Add New Poultry $livestockType, $livestockTagId",
                'entityAffected' => "Livestock",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            return $this->respond(['success' => true, 'message' => 'Poultry Successfully Added'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->respond(['error' => 'Failed to add livestock']);
        }
    }

    public function addMultipleFarmerPoultries()
    {
        try {
            $data = $this->request->getJSON();
            $data->category = "Poultry";

            $data->breedingEligibility = "Not Age-Suited";

            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);

            $decoded = decodeToken($header);
            $userType = $decoded->aud;
            if($userType == 'Farmer'){
                $data->farmerId = $userId;
            }

            $auditLog = (object)[
                'farmerId' => $userId,
                'action' => "Add",
                'title' => "Add New Poultry",
                'entityAffected' => "Livestock",
            ];

            if ($data->malePoultryCount > 0) {
                $data->sex = 'Male';
                for ($i = 1; $i <= $data->malePoultryCount; $i++) {

                    $poultryId = $this->poultry->insertLivestock($data);
                    $data->livestockId = $poultryId;
                    $auditLog->livestockId = $poultryId;
                    $result = $this->farmerPoultry->associateFarmerLivestock($data);
                    $livestockType = $this->poultryTypes->getLivestockTypeName($data->livestockTypeId);

                    $auditLog->description = "Add New Poultry $livestockType";
                    $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                }
            }

            if ($data->femalePoultryCount > 0) {
                $data->sex = 'Female';
                for ($i = 1; $i <= $data->femalePoultryCount; $i++) {

                    $poultryId = $this->poultry->insertLivestock($data);
                    $auditLog->livestockId = $poultryId;
                    $data->livestockId = $poultryId;
                    $result = $this->farmerPoultry->associateFarmerLivestock($data);
                    $livestockType = $this->poultryTypes->getLivestockTypeName($data->livestockTypeId);

                    $auditLog->description = "Add New Poultry $livestockType";
                    $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                }
            }

            return $this->respond(['success' => true, 'message' => 'Poultry Successfully Added'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->respond(['error' => 'Failed to add poultry','errMsg' => $th->getMessage()]);
        }
    }

    public function updatePoultry(){
        try {
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $decoded = decodeToken($header);

            $data = $this->request->getJSON();
            $data->category = "Poultry";

            $userType = $decoded->aud;
            if($userType == 'Farmer'){
                $data->farmerId = $userId;
            }

            $response = $this->poultry->updateLivestock($data->id, $data);

            $livestockTagId = $data->livestockTagId;

            $auditLog = (object)[
                'livestockId' => $data->id,
                'farmerId' => $userId,
                'action' => "Edit",
                'title' => "Edit Poultry Record",
                'description' => "Updated details for Poultry $livestockTagId",
                'entityAffected' => "Livestock",
            ];
            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);

            return $this->respond(['success' => $response, 'message' => 'Poultry Successfully Updated'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->respond(['error' => 'Failed to update poultry']);
        }
    }

    public function updatePoultryHealthStatus($id)
    {
        try {
            $data = $this->request->getJSON();

            $livestockTagId = $data->livestockTagId;

            $data->livestockId = $id;
            $data->action = "Edit";
            $data->title = " Edit Poultry Record";
            $data->description = "Updated Poultry $livestockTagId's Health Status";
            $data->entityAffected = "Livestock";

            $response = $this->poultry->updateLivestockHealthStatus($id, $data);

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);

            return $this->respond(['result' => $response, 'message' => 'Livestock Health Status Successfully Updated'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updatePoultryRecordStatus($id)
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->poultry->updateLivestockRecordStatus($id, $data->recordStatus);

            $livestockTagId = $data->livestockTagId;

            $data->livestockId = $id;
            $data->action = $data->recordStatus == 'Archived' ? "Archived" : "Edit";
            $data->title = $data->recordStatus == 'Archived' ? "Archived Poultry Record" : "Unarchived Poultry Record";
            $data->description = $data->recordStatus == 'Archived' ? "Archived Poultry $livestockTagId" : "Unarchived Poultry $livestockTagId";
            $data->entityAffected = "Livestock";

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);

            return $this->respond(['result' => $response, 'message' => 'Poultry Record Status Successfully Updated'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deletePoultry()
    {
        try {
            $id = $this->request->getGet('poultry');

            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $livestockTagId = $this->poultry->getLivestockTagIdById($id);
            $response = $this->poultry->deleteLivestock($id);

            $auditLog = (object)[
                'livestockId' => $id,
                'farmerId' => $userId,
                'action' => "Delete",
                'title' => "Delete Poultry Record",
                'description' => "Delete Poultry $livestockTagId",
                'entityAffected' => "Livestock",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            return $this->respond(['result' => $response], 200, 'Poultry Successfully Deleted');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            $this->fail('Failed to delete record',ResponseInterface::HTTP_BAD_REQUEST);
        }
    }

    public function getPoultryMappingData()
    {
        try {
            //code...
            $mappingData = $this->userModel->getBasicUserInfo();

            foreach ($mappingData as &$md) {
                $md['poultries'] = $this->poultry->getFarmerEachPoultryTypeCountData($md['id']);
            }


            return $this->respond(['farmers' => $mappingData]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getFarmerPoultryTypeCountDataByCity()
    {
        try {
            $data = $this->poultry->getFarmerPoultryTypeCountDataByCity();

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }
    
    public function getFarmerPoultryIdByTag()
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->poultry->getFarmerPoultryIdByTag($data->livestockTagId, $data->userId);

            return $this->respond($response);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllPoultryTypeAgeClassCount()
    {
        try {
            $data = $this->poultry->getAllPoultryTypeAgeClassCount();

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getFarmerPoultryTypeAgeClassCount($userId)
    {
        try {
            $data = $this->poultry->getFarmerPoultryTypeAgeClassCount($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllPoultryTypeCount()
    {
        try {
            $data = $this->poultry->getAllPoultryTypeCount();

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getFarmerPoultryTypeCount($userId)
    {
        try {
            $data = $this->poultry->getFarmerPoultryTypeCount($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllPoultryCountMonitored()
    {
        try {
            $poultryCount = $this->poultry->getAllPoultryCount();

            $data = [
                'totalPoultryCount' => "$poultryCount"
            ];

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getFarmerPoultryCount($userId)
    {
        try {
            $data = $this->poultry->getFarmerPoultryCount($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllFarmerPoultryTagIDs($userId)
    {
        try {
            $data = $this->poultry->getAllFarmerPoultryTagIDs($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getFarmerDistinctPoultryType($userId)
    {
        try {
            $data = $this->poultry->getFarmerDistinctPoultryType($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getPoultryCountByMonthAndType()
    {
        try {
            $data = $this->poultry->getPoultryCountByMonthAndType();

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getPoultryHealthStatusesCount()
    {
        try {
            $data = $this->poultry->getLivestockHealthStatusesCount('Poultry');

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    
    public function getAllPoultryTypeCountByCity($city)
    {
        try {
            $poultryTypeCount = $this->poultry->getAllPoultryTypeCountByCity($city);

            $totalPoultryCount = $this->poultry->getPoultryCountByCity($city);
            $data = [
                'livestock' => $poultryTypeCount,
                'totalLivestockCount' => $totalPoultryCount
            ];
            return $this->respond($data);
        } catch (\Throwable $th) {
            return $this->respond($th->getMessage());
        }
    }

    public function getPoultryCountAllCity()
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
                $poultryTypeCount = $this->poultry->getAllPoultryTypeCountByCity($city);

                $totalPoultryCount = $this->poultry->getPoultryCountByCity($city);

                $registeredFarmersCount = $this->userModel->getFarmerCountByCity($city);
                $data[] = [
                    'id' => $i++,
                    'poultries' => $poultryTypeCount,
                    'totalPoultryCount' => $totalPoultryCount,
                    'city' => $city,
                    'registeredFarmersCount' => $registeredFarmersCount
                ];
            }

            return $this->respond($data);
        } catch (\Throwable $th) {
            return $this->respond($th->getMessage());
        }
    }

    public function getPoultryTypeCountAllCity($livestockTypeId)
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
                $livestockTypeCount = $this->poultry->getPoultryTypeCountByCity($city,$livestockTypeId);
                $data[] = [
                    'livestock' => $livestockTypeCount,
                    'city' => $city,
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
                $livestockTypeCount = $this->poultry->getPoultryTypeCountByCity($city,$livestockTypeId);
                $data[] = [
                    'livestock' => $livestockTypeCount,
                    'city' => $city,
                ];
            }

            return $this->respond($data);
        } catch (\Throwable $th) {
            return $this->respond($th->getMessage());
        }
    }
}

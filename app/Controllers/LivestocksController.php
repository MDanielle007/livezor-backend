<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FarmerAuditModel;
use App\Models\LivestockTypeModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\LivestockModel;
use App\Models\FarmerLivestockModel;

class LivestocksController extends ResourceController
{
    private $livestock;
    private $livestockTypes;
    private $farmerLivestock;
    private $userModel;
    private $farmerAudit;

    public function __construct()
    {
        $this->livestock = new LivestockModel();
        $this->livestockTypes = new LivestockTypeModel();
        $this->farmerLivestock = new FarmerLivestockModel();
        $this->userModel = new UserModel();
        $this->farmerAudit = new FarmerAuditModel();
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

    public function getLivestock($id)
    {
        try {
            $livestock = $this->livestock->getLivestockById($id);

            return $this->respond($livestock);
        } catch (\Throwable $th) {
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getFarmerAllLivestocks($userId)
    {
        try {
            $livestocks = $this->livestock->getFarmerAllLivestock($userId);

            return $this->respond($livestocks);
        } catch (\Throwable $th) {
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function addLivestock()
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestock->insertLivestock($data);

            return $this->respond(['result' => $response, 'message' => 'Livestock Successfully Added'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function addFarmerLivestock()
    {
        try {
            $data = $this->request->getJSON();

            $livestockId = $this->livestock->insertLivestock($data);

            $data->livestockId = $livestockId;

            $response = $this->farmerLivestock->associateFarmerLivestock($data);

            $livestockType = $this->livestockTypes->getLivestockTypeName($data->livestockTypeId);
            $livestockTagId = $data->livestockTagId;

            $data->action = "Add";
            $data->title = "Add New Livestock";
            $data->description = "Add New Livestock $livestockType, $livestockTagId";
            $data->entityAffected = "Livestock";

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);
            return $this->respond(['success' => true, 'message' => 'Livestock Successfully Added'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => 'Failed to add livestock']);
        }
    }

    public function updateLivestock($id)
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestock->updateLivestock($id, $data);
            
            $livestockTagId = $data->livestockTagId;

            $data->livestockId = $id;
            $data->action = "Edit";
            $data->title = " Edit Livestock Record";
            $data->description = "Updated details for Livestock $livestockTagId";
            $data->entityAffected = "Livestock";

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);

            return $this->respond(['success' => $response, 'message' => 'Livestock Successfully Updated'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => 'Failed to update livestock']);
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

            return $this->respond(['result' => $response, 'message' => 'Livestock Record Status Successfully Updated'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteLivestock($id)
    {
        try {
            $response = $this->livestock->deleteLivestock($id);

            $livestockTagId = $this->livestock->getLivestockTagIdById($id);

            $data = new \stdClass();
            $data->farmerId = $this->userModel->getFarmerByLivestock($id);
            $data->action = "Delete";
            $data->title = "Delete Livestock Record";
            $data->description = "Delete Livestock $livestockTagId";
            $data->entityAffected = "Livestock";

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);

            return $this->respond(['result' => $response, 'message' => 'Livestock Successfully Deleted'], 200);
        } catch (\Throwable $th) {
            //throw $th;
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

    // testing method
    public function getLivestockPrimaryData($id)
    {
        try {
            $livestock = $this->livestock->getLivestockPrimaryData($id);

            return $this->respond($livestock);
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

    public function getFarmerLivestockTypeAgeClassCount($userId)
    {
        try {
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

    public function getFarmerLivestockTypeCount($userId)
    {
        try {
            $data = $this->livestock->getFarmerLivestockTypeCount($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllLivestockCount()
    {
        try {
            $data = $this->livestock->getAllLivestockCount();

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
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

    public function getFarmerLivestockCount($userId)
    {
        try {
            $data = $this->livestock->getFarmerLivestockCount($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllFarmerLivestockTagIDs($userId)
    {
        try {
            $data = $this->livestock->getAllFarmerLivestockTagIDs($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getFarmerDistinctLivestockType($userId)
    {
        try {
            $data = $this->livestock->getFarmerDistinctLivestockType($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllFarmerLivestocksBySexAndType($userId)
    {
        try {
            $data = $this->livestock->getAllFarmerLivestocksBySexAndType($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
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
            $data = $this->livestock->getLivestockCountByMonthAndType();

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getLivestockHealthStatusesCount()
    {
        try {
            $data = $this->livestock->getLivestockHealthStatusesCount();

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockCountByMunicipality($municipality)
    {
        try {
            $livestockTypeCount = $this->livestock->getLivestockTypeCountByMunicipality($municipality);

            $totalLivestockCount = $this->livestock->getLivestockCountByMunicipality($municipality);
            $data = [
                'livestock' => $livestockTypeCount,
                'totalLivestockCount' => $totalLivestockCount
            ];
            return $this->respond($data);
        } catch (\Throwable $th) {
            return $this->respond($th->getMessage());
        }
    }

    public function getLivestockCountAllMunicipality()
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
                $livestockTypeCount = $this->livestock->getLivestockTypeCountBycity($city);

                $totalLivestockCount = $this->livestock->getLivestockCountBycity($city);

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

    public function getLivestockProductionSelectedYear($year)
    {
        try {
            $livestockTypes = $this->livestockTypes->getAllLivestockTypeIdName();

            $livestock = $this->livestock->getProductionCountByMonthYearAndType($livestockTypes,$year);

            return $this->respond($livestock);
        } catch (\Throwable $th) {
            // Handle exceptions
            return $th->getMessage();
        }
    }

}

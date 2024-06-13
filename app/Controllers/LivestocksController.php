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
            $data = $this->request->getJSON();
            $data->category = "Livestock";
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

    public function addMultipleFarmerLivestock()
    {
        try {
            $data = $this->request->getJSON();
            $data->category = "Livestock";
            $data->breedingEligibility = "Not Age-Suited";

            $data->action = "Add";
            $data->title = "Add New Livestock";
            $data->entityAffected = "Livestock";

            if ($data->maleLivestockCount > 0) {
                $data->sex = 'Male';
                for ($i = 1; $i <= $data->maleLivestockCount; $i++) {

                    $livestockId = $this->livestock->insertLivestock($data);
                    $data->livestockId = $livestockId;
                    $result = $this->farmerLivestock->associateFarmerLivestock($data);

                    $livestockType = $this->livestockTypes->getLivestockTypeName($data->livestockTypeId);

                    $data->description = "Add New Livestock $livestockType";
                    $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);
                }
            }

            if ($data->femaleLivestockCount > 0) {
                $data->sex = 'Female';
                for ($i = 1; $i <= $data->femaleLivestockCount; $i++) {

                    $livestockId = $this->livestock->insertLivestock($data);
                    $data->livestockId = $livestockId;
                    $result = $this->farmerLivestock->associateFarmerLivestock($data);
                    $livestockType = $this->livestockTypes->getLivestockTypeName($data->livestockTypeId);

                    $data->description = "Add New Livestock $livestockType";
                    $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);
                }
            }

            return $this->respond(['success' => true, 'message' => 'Livestock Successfully Added'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => 'Failed to add livestock', 'errMsg' => $th->getMessage()]);
        }
    }

    public function updateLivestock($id)
    {
        try {
            $data = $this->request->getJSON();
            $data->category = "Livestock";
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

    public function getLivestockMappingDataByType($livestockTypeId)
    {
        try {
            //code...
            $mappingData = $this->userModel->getBasicUserInfo();

            foreach ($mappingData as &$md) {
                $md['livestock'] = $this->livestock->getFarmerEachSpecificLivestockTypeCountData($md['id'], $livestockTypeId);
            }

            $filteredMappingData = array_filter($mappingData, function ($md) {
                return !empty ($md['livestock']);
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
            $livestockTypes = $this->livestockTypes->getAllLivestockTypeName();

            $data = $this->livestock->getLivestockCountByMonthAndType($livestockTypes);

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

    public function getAllLivestockTypeCountByCity($city)
    {
        try {
            $livestockTypeCount = $this->livestock->getAllLivestockTypeCountByCity($city);

            $totalLivestockCount = $this->livestock->getLivestockCountBycity($city);
            $data = [
                'livestock' => $livestockTypeCount,
                'totalLivestockCount' => $totalLivestockCount
            ];
            return $this->respond($data);
        } catch (\Throwable $th) {
            return $this->respond($th->getMessage());
        }
    }

    public function getLivestockCountAllCity()
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
                $livestockTypeCount = $this->livestock->getAllLivestockTypeCountByCity($city);

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

            $livestock = $this->livestock->getProductionCountByMonthYearAndType($livestockTypes, $year);

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

            if(strtoupper($category) == 'LIVESTOCK'){
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
            }else if(strtoupper($category) == 'POULTRY'){
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
            $columnWidths = [16, 16, 16, 16, 16, 20, 30, 12, 12, 12, 12,12,14];
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

    private function getLivestockOrPoultry($string) {
        $patterns = ['livestock', 'poultry'];
        
        foreach ($patterns as $pattern) {
            if (preg_match("/$pattern/", $string)) {
                return $pattern;
            }
        }
        return 'none';
    }

    private function splitAndCapitalize($string) {
        // Step 1: Insert a space before each capital letter (except the first letter)
        $spacedString = preg_replace('/(?<!^)([A-Z])/', ' $1', $string);
    
        // Step 2: Convert the entire string to lowercase
        $lowercasedString = strtolower($spacedString);
    
        // Step 3: Capitalize the first letter of each word
        $capitalizedString = ucwords($lowercasedString);
    
        return $capitalizedString;
    }

    public function getLivestockDisProdForReport()
    {
        try {
            $dbTableName = $this->request->getGet('category');
            $origin = $dbTableName == 'livestockDistribution' ? 'Distributed' : 'Produced';

            $category = $this->getLivestockOrPoultry($dbTableName);
            $minDate = $this->request->getGet('minDate');
            $maxDate = $this->request->getGet('maxDate');

            $livestockRecordsReport = $this->livestock->getLivestockDisProdForReport($category, $minDate, $maxDate, $origin);

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

            $title = $this->splitAndCapitalize($dbTableName);
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
                'No. of Heads'
            ];
            
            // Check the value of $dbTableName and append the appropriate header
            if ($dbTableName == 'livestockDistribution') {
                $headers[] = 'Date of Distribution';
            } elseif ($dbTableName == 'livestockProduction') {
                $headers[] = 'Date of Production';
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
            $columnWidths = [8, 16, 20, 10, 10, 10, 10, 12, 12, 16, 16, 12, 16];
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
            $fileName = strtoupper($category) == 'LIVESTOCK' ? "{$dbTableName}sReport_{$minDate}_{$maxDate}_{$uniqueId}" : "PoultryRecordsReport_{$minDate}_{$maxDate}_{$uniqueId}";
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

}

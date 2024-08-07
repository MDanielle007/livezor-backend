<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\ArimaPredictionLibrary;
use App\Models\FarmerAuditModel;
use App\Models\LivestockModel;
use App\Models\LivestockVaccinationModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponsableInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\IOFactory;

class LivestockVaccinationsController extends ResourceController
{
    private $livestockVaccination;
    private $livestock;
    private $farmerAudit;
    private $userModel;

    private $arimaPrediction;

    public function __construct()
    {
        $this->livestockVaccination = new LivestockVaccinationModel();
        $this->livestock = new LivestockModel();
        $this->farmerAudit = new FarmerAuditModel();
        $this->userModel = new UserModel();
        $this->arimaPrediction = new ArimaPredictionLibrary();
        helper('jwt');
    }

    public function getAllLivestockVaccinations()
    {
        try {
            $livestockVaccinations = $this->livestockVaccination->getAllLivestockVaccinations();

            return $this->respond($livestockVaccinations);

        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getLivestockVaccinationReportData()
    {
        try {
            $selectClause = $this->request->getGet('selectClause');
            $minDate = $this->request->getGet('minDate');
            $maxDate = $this->request->getGet('maxDate');
            $category = 'Livestock';

            $livestockVaccinations = $this->livestockVaccination->getReportData($category, $selectClause, $minDate, $maxDate);

            return $this->respond($livestockVaccinations);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getPoultryVaccinationReportData()
    {
        try {
            $selectClause = $this->request->getGet('selectClause');
            $minDate = $this->request->getGet('minDate');
            $maxDate = $this->request->getGet('maxDate');
            $category = 'Poultry';

            $livestockVaccinations = $this->livestockVaccination->getReportData($category, $selectClause, $minDate, $maxDate);

            return $this->respond($livestockVaccinations);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllPoultryVaccinations()
    {
        try {
            $livestockVaccinations = $this->livestockVaccination->getAllPoultryVaccinations();

            return $this->respond($livestockVaccinations);

        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getLivestockVaccination($id)
    {
        try {
            $livestockVaccination = $this->livestockVaccination->getLivestockVaccination($id);

            return $this->respond($livestockVaccination);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllFarmerLivestockVaccinations($userId)
    {
        try {
            $livestockVaccinations = $this->livestockVaccination->getAllFarmerLivestockVaccinations($userId);

            return $this->respond($livestockVaccinations);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllUserCompleteLivestockVaccinations($uid)
    {
        try {

            $userId = $this->userModel->getIdByUserId($uid);

            $livestockVaccinations = $this->livestockVaccination->getAllFarmerCompleteLivestockVaccinations($userId);

            return $this->respond($livestockVaccinations);

        } catch (\Throwable $th) {
            //throw $th;

            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllFarmerCompleteLivestockVaccinations()
    {
        try {
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);

            $livestockVaccinations = $this->livestockVaccination->getAllFarmerCompleteLivestockVaccinations($userId);

            return $this->respond($livestockVaccinations);

        } catch (\Throwable $th) {
            //throw $th;

            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function insertLivestockVaccination()
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockVaccination->insertLivestockVaccination($data);

            $livestockTagId = $this->livestock->getLivestockTagIdById($data->livestockId);

            $vaccine = $data->vaccinationName;

            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $auditLog = (object) [
                'livestockId' => $data->livestockId,
                'farmerId' => $userId,
                'action' => "Add",
                'title' => "Adminster Vaccination",
                'description' => "Administer $vaccine to Livestock $livestockTagId",
                'entityAffected' => "Vaccination",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);

            return $this->respond(['success' => true, 'message' => 'Livestock Vaccination Successfully Added'], 200);

        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->respond(['error' => $th->getMessage(), 'message' => 'Failed Livestock Vaccination'], 200);
        }
    }

    public function insertMultipleLivestockVaccination()
    {
        try {
            $data = $this->request->getJSON();

            $livestock = $data->livestock;

            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $decoded = decodeToken($header);
            $userType = $decoded->aud;
            if($userType == 'Farmer'){
                $data->vaccineAdministratorId = $userId;
            }

            $auditLog = (object) [
                'farmerId' => $userId,
                'action' => "Add",
                'title' => "Adminster Vaccination",
                'entityAffected' => "Vaccination",
            ];


            foreach ($livestock as $ls) {
                $data->livestockId = $ls;
                $auditLog->livestockId = $ls;

                $response = $this->livestockVaccination->insertLivestockVaccination($data);

                $livestockTagId = $this->livestock->getLivestockTagIdById($data->livestockId);

                $vaccine = $data->vaccinationName;

                $auditLog->description = "Administer $vaccine to Livestock $livestockTagId";

                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            }
            return $this->respond(['success' => true, 'message' => 'Livestock Vaccination Successfully Added'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->respond(['error' => $th->getMessage(), 'message' => 'Failed Livestock Vaccination'], 200);
        }
    }

    public function updateLivestockVaccination()
    {
        try {
            $data = $this->request->getJSON();

            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $decoded = decodeToken($header);
            $userType = $decoded->aud;
            if($userType == 'Farmer'){
                $data->vaccineAdministratorId = $userId;
            }

            $response = $this->livestockVaccination->updateLivestockVaccination($data->id, $data);

            $livestockTagId = $this->livestock->getLivestockTagIdById($data->livestockId);

            $auditLog = (object) [
                'livestockId' => $data->livestockId,
                'farmerId' => $userId,
                'action' => "Edit",
                'title' => "Update Vaccination Details",
                'description' => "Update Vaccination of Livestock $livestockTagId",
                'entityAffected' => "Vaccination",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);

            return $this->respond(['success' => true, 'message' => 'Livestock Vaccination Successfully Updated'], ResponseInterface::HTTP_OK);

        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to add livestock vaccination', ResponseInterface::HTTP_BAD_REQUEST);
        }
    }

    public function updateLivestockVaccinationRecordStatus($id)
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockVaccination->updateLivestockVaccinationRecordStatus($id, $data->recordStatus);

            $livestock = $this->livestock->getLivestockByVaccination($id);
            $livestockTagId = $livestock['livestock_tag_id'];

            $data->farmerId = $data->vaccineAdministratorId;
            $data->livestockId = $livestock['id'];
            $data->action = $data->recordStatus == 'Archived' ? "Archived" : "Edit";
            $data->title = $data->recordStatus == 'Archived' ? "Archived Vaccination Record" : "Unarchived Vaccination Record";
            $data->description = $data->recordStatus == 'Archived' ? "Archived Vaccination of Livestock $livestockTagId" : "Unarchived Vaccination of Livestock $livestockTagId";
            $data->entityAffected = "Vaccination";

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);

            return $this->respond(['result' => $response, 'message' => 'Livestock Vaccination Record Status Successfully Updated'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteLivestockVaccination()
    {
        try {
            $id = $this->request->getGet('vaccination');

            $livestock = $this->livestock->getLivestockByVaccination($id);
            $livestockTagId = $livestock['livestock_tag_id'];
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);

            $response = $this->livestockVaccination->deleteLivestockVaccination($id);
            
            $auditLog = (object) [
                'farmerId' => $userId,
                'livestockId' => $livestock['id'],
                'action' => "Delete",
                'title' => "Delete Vaccination Record",
                'description' => "Delete Vaccination of Livestock $livestockTagId",
                'entityAffected' => "Vaccination",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);

            return $this->respond(['result' => $response], 200, 'Livestock Vaccination Successfully Deleted');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to delete record', ResponseInterface::HTTP_BAD_REQUEST);
        }
    }

    public function getOverallLivestockVaccinationCount()
    {
        try {
            $livestockVaccinationCount = $this->livestockVaccination->getOverallLivestockVaccinationCount();

            return $this->respond(['vaccinationCount' => "$livestockVaccinationCount"]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getFarmerOverallLivestockVaccinationCount($userId)
    {
        try {
            $livestockVaccinationCount = $this->livestockVaccination->getFarmerOverallLivestockVaccinationCount($userId);

            return $this->respond(['vaccinationCount' => "$livestockVaccinationCount"]);
        } catch (\Throwable $th) {
            //throw $th;
            // return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getLivestockVaccinationCountInCurrentMonth()
    {
        try {
            $livestockVaccinationCount = $this->livestockVaccination->getLivestockVaccinationCountInCurrentMonth();

            return $this->respond($livestockVaccinationCount);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getLivestockVaccinationPercetageInCurrentMonth()
    {
        try {
            $livestockVaccinationCount = $this->livestockVaccination->getLivestockVaccinationCountInCurrentMonth();

            $livestockCount = $this->livestock->getAllLivestockCount();

            $vaccinationPercentage = 0;
            if ($livestockCount > 0) {
                $percentage = ($livestockVaccinationCount / $livestockCount) * 100;

                if (floor($percentage) == $percentage) {
                    // Display only whole numbers
                    $vaccinationPercentage = number_format($percentage, 0);
                } else {
                    // Display up to two decimal places
                    $vaccinationPercentage = number_format($percentage, 2);
                }
            }

            $data = [
                'livestockVaccinationCount' => "$livestockVaccinationCount",
                'livestockVaccinationPercentage' => $vaccinationPercentage . "%",
            ];

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getTopVaccines()
    {
        try {
            $topVaccines = $this->livestockVaccination->getTopVaccines();

            return $this->respond($topVaccines);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getVaccinationCountByMonth()
    {
        try {
            $vaccinationCountByMonth = $this->livestockVaccination->getVaccinationCountByMonth();

            return $this->respond($vaccinationCountByMonth);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getVaccinationCountByMonthWithForecast()
    {
        try {
            // Fetch the time series data from the database
            $data = $this->livestockVaccination->getLivestockVaccinationCountByMonthTimeSeries();

            if (empty($data)) {
                return $this->failNotFound('No data found');
            }

            // Extract the latest year from the data
            $latestYear = max(array_column($data, 'year'));

            // Filter the data for the latest year
            $filteredData = array_filter($data, function ($item) use ($latestYear) {
                return $item['year'] == $latestYear;
            });

            // Ensure filtered data is an array
            $filteredData = array_values($filteredData);

            // Extract the time series values and the last month
            $timeSeries = array_column($filteredData, 'count');
            $lastMonth = max(array_column($filteredData, 'month'));

            // Calculate steps for forecasting (remaining months of the year)
            $steps = 12 - $lastMonth;

            // Send the filtered time series to the ARIMA model
            $response = $this->arimaPrediction->sendToFlask($timeSeries, $steps);

            if ($response['status'] !== 200) {
                // return $this->fail($response['message'], $response['status']);
                return $this->respond([
                    'original' => array_values($filteredData),
                    'combined' => []
                ], 200);
            }

            $forecast = $response['data']['forecast'];

            // Combine the original data with the forecast
            $combinedData = $filteredData;

            // Add forecasted values to the combined data
            for ($i = 0; $i < count($forecast); $i++) {
                $combinedData[] = [
                    'year' => $latestYear,
                    'month' => $lastMonth + $i + 1,
                    'count' => $forecast[$i]
                ];
            }

            // Ensure combinedData is sorted by month
            usort($combinedData, function ($a, $b) {
                return $a['month'] <=> $b['month'];
            });

            return $this->respond([
                'original' => array_values($filteredData),
                'combined' => array_values($combinedData)
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->respond(['error' => $th->getMessage(), 'trace' => $th->getTrace()]);
        }
    }

    public function getPoultryVaccinationCountByMonth()
    {
        try {
            $poultryVaccinationCountByMonth = $this->livestockVaccination->getPoultryVaccinationCountByMonth();

            return $this->respond($poultryVaccinationCountByMonth);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getVaccinationCountLast4Months()
    {
        try {
            $vaccinationCountLast4Months = $this->livestockVaccination->getVaccinationCountLast4Months();

            return $this->respond($vaccinationCountLast4Months);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
        }
    }

    public function getVaccinationCountWholeYear()
    {
        try {
            $vaccinationCountWholeYear = $this->livestockVaccination->getVaccinationCountWholeYear();

            return $this->respond($vaccinationCountWholeYear);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getLivestockVaccinationsForReport()
    {
        try {
            $category = 'Livestock';
            $minDate = $this->request->getGet('minDate');
            $maxDate = $this->request->getGet('maxDate');

            $livestockVaccinationReport = $this->livestockVaccination->getVaccinationsForReport($category, $minDate, $maxDate);

            // Check if the report data is not null
            if (is_null($livestockVaccinationReport) || empty($livestockVaccinationReport)) {
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
            $sheet->mergeCells('A1:K1');
            $sheet->setCellValue('A1', 'Department of Agriculture- MiMaRoPa');
            $sheet->getStyle('A1:K1')->applyFromArray([
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
            $sheet->mergeCells('A2:K2');
            $sheet->setCellValue('A2', 'Research Division-Livestock Department');
            $sheet->getStyle('A2:K2')->applyFromArray([
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
            $sheet->mergeCells('A3:K3');
            $sheet->setCellValue('A3', 'Barcenaga, Naujan Oriental Mindoro');
            $sheet->getStyle('A3:K3')->applyFromArray([
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
            $sheet->mergeCells('A5:K5');
            $sheet->setCellValue('A5', 'LIVESTOCK VACCINATIONS');
            $sheet->getStyle('A5:K5')->applyFromArray([
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
            $sheet->mergeCells('A6:K6');
            $startDate = date('F Y', strtotime($minDate));
            $endDate = date('F Y', strtotime($maxDate));
            $sheet->setCellValue('A6', "$startDate To $endDate");
            $sheet->getStyle('A6:K6')->applyFromArray([
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
            $sheet->mergeCells('A8:K8');
            $dateExported = date('F j, Y');
            $sheet->setCellValue('A8', "Date Exported: $dateExported");
            $sheet->getStyle('A8:K8')->applyFromArray([
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

            // 10th Row: Column Headers
            $headers = [
                'Livestock Tag ID',
                'Livestock Type',
                'Livestock Breed',
                'Livestock Age Classification',
                'Vaccine Administrator ID',
                'Vaccine Administrator Full Name',
                'Vaccine Administrator Address',
                'Vaccination Name',
                'Vaccination Description',
                'Remarks',
                'Date',
            ];

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
            $columnWidths = [16, 16, 16, 16, 16, 20, 30, 16, 30, 20, 12];
            foreach (range('A', 'K') as $index => $columnID) {
                $sheet->getColumnDimension($columnID)->setWidth($columnWidths[$index]);
            }

            // Add data to the sheet starting from the 7th row
            $rowIndex = 11;
            foreach ($livestockVaccinationReport as $record) {
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
            $fileName = "LivestockVaccinationReport_{$minDate}_{$maxDate}_{$uniqueId}";
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

    public function getPoultryVaccinationsForReport()
    {
        try {
            $category = 'Poultry';
            $minDate = $this->request->getGet('minDate');
            $maxDate = $this->request->getGet('maxDate');

            $livestockVaccinationReport = $this->livestockVaccination->getVaccinationsForReport($category, $minDate, $maxDate);

            // Check if the report data is not null
            if (is_null($livestockVaccinationReport) || empty($livestockVaccinationReport)) {
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
            $sheet->mergeCells('A1:K1');
            $sheet->setCellValue('A1', 'Department of Agriculture- MiMaRoPa');
            $sheet->getStyle('A1:K1')->applyFromArray([
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
            $sheet->mergeCells('A2:K2');
            $sheet->setCellValue('A2', 'Research Division-Livestock Department');
            $sheet->getStyle('A2:K2')->applyFromArray([
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
            $sheet->mergeCells('A3:K3');
            $sheet->setCellValue('A3', 'Barcenaga, Naujan Oriental Mindoro');
            $sheet->getStyle('A3:K3')->applyFromArray([
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
            $sheet->mergeCells('A5:K5');
            $sheet->setCellValue('A5', 'POULTRY VACCINATIONS');
            $sheet->getStyle('A5:K5')->applyFromArray([
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
            $sheet->mergeCells('A6:K6');
            $startDate = date('F Y', strtotime($minDate));
            $endDate = date('F Y', strtotime($maxDate));
            $sheet->setCellValue('A6', "$startDate To $endDate");
            $sheet->getStyle('A6:K6')->applyFromArray([
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
            $sheet->mergeCells('A8:K8');
            $dateExported = date('F j, Y');
            $sheet->setCellValue('A8', "Date Exported: $dateExported");
            $sheet->getStyle('A8:K8')->applyFromArray([
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

            // 10th Row: Column Headers
            $headers = [
                'Poultry Tag ID',
                'Poultry Type',
                'Poultry Breed',
                'Poultry Age Classification',
                'Vaccine Administrator ID',
                'Vaccine Administrator Full Name',
                'Vaccine Administrator Address',
                'Vaccination Name',
                'Vaccination Description',
                'Remarks',
                'Date',
            ];

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
            $columnWidths = [16, 16, 16, 16, 16, 20, 30, 16, 30, 20, 12];
            foreach (range('A', 'K') as $index => $columnID) {
                $sheet->getColumnDimension($columnID)->setWidth($columnWidths[$index]);
            }

            // Add data to the sheet starting from the 7th row
            $rowIndex = 11;
            foreach ($livestockVaccinationReport as $record) {
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
            $fileName = "PoultryVaccinationReport_{$minDate}_{$maxDate}_{$uniqueId}";
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

    public function getLivestockVaccinationByLivestock()
    {
        try {
            //code...
            $livestockId = $this->request->getGet('livestock');

            $data = $this->livestockVaccination->getLivestockVaccinationByLivestockId($livestockId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

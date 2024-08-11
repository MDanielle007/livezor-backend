<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\ArimaPredictionLibrary;
use App\Models\FarmerAuditModel;
use App\Models\LivestockModel;
use App\Models\LivestockMortalityModel;
use App\Models\UserModel;
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

class LivestockMortalityController extends ResourceController
{
    private $livestockMortality;
    private $livestock;
    private $userModel;
    private $farmerAudit;
    private $arimaPrediction;


    public function __construct()
    {
        $this->livestockMortality = new LivestockMortalityModel();
        $this->livestock = new LivestockModel();
        $this->userModel = new UserModel();
        $this->farmerAudit = new FarmerAuditModel();
        $this->arimaPrediction = new ArimaPredictionLibrary();
        helper('jwt');
    }

    public function getAllLivestockMortalities()
    {
        try {
            $livestockMortalities = $this->livestockMortality->getAllLivestockMortalities();

            $data = [];

            foreach ($livestockMortalities as $record) {
                $fileNamesArray = json_decode($record['images'], true);
    
                $data[] = [
                    'id' => $record['id'],
                    'livestockId' => $record['livestockId'],
                    'livestockTagId' => $record['livestockTagId'],
                    'livestockType' => $record['livestockType'],
                    'farmerId' => $record['farmerId'],
                    'farmerName' => $record['farmerName'],
                    'farmerUserId' => $record['farmerUserId'],
                    'causeOfDeath' => $record['causeOfDeath'],
                    'remarks' => $record['remarks'],
                    'dateOfDeath' => $record['dateOfDeath'],
                    'images' => $fileNamesArray
                ];
            }

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->failServerError($th->getMessage());
        }
    }

    public function getLivestockMortality($id)
    {
        try {
            $livestockMortality = $this->livestockMortality->getLivestockMortality($id);

            return $this->respond($livestockMortality);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllFarmerLivestockMortalities()
    {
        try {
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);

            $livestockMortalities = $this->livestockMortality->getAllFarmerLivestockMortalities($userId);

            $data = [];

            foreach ($livestockMortalities as $record) {
                $fileNamesArray = json_decode($record['images'], true);
    
                $data[] = [
                    'id' => $record['id'],
                    'livestockId' => $record['livestockId'],
                    'livestockTagId' => $record['livestockTagId'],
                    'livestockType' => $record['livestockType'],
                    'causeOfDeath' => $record['causeOfDeath'],
                    'remarks' => $record['remarks'],
                    'dateOfDeath' => $record['dateOfDeath'],
                    'images' => $fileNamesArray
                ];
            }

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->failServerError($th->getMessage());
        }
    }

    public function getAllCompleteLivestockMortalities()
    {
        try {
            $livestockMortalities = $this->livestockMortality->getAllCompleteLivestockMortalities();

            return $this->respond($livestockMortalities);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond($th->getMessage());
        }
    }

    // public function insertLivestockMortality()
    // {
    //     try {
    //         $data = $this->request->getJSON();

    //         $header = $this->request->getHeader("Authorization");
    //         $userId = getTokenUserId($header);
    //         $decoded = decodeToken($header);
    //         $userType = $decoded->aud;
    //         if ($userType == 'Farmer') {
    //             $data->farmerId = $userId;
    //         }

    //         $response = $this->livestockMortality->insertLivestockMortality($data);

    //         $livestockTagId = $this->livestock->getLivestockTagIdById($data->livestockId);

    //         $auditLog = (object) [
    //             'livestockId' => $data->livestockId,
    //             'farmerId' => $userId,
    //             'action' => "Add",
    //             'title' => "Report Livestock Mortality",
    //             'description' => "Report Livestock Mortality of Livestock $livestockTagId",
    //             'entityAffected' => "Mortality",
    //         ];

    //         $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);

    //         $data->livestockHealthStatus = 'Dead';
    //         $result = $this->livestock->updateLivestockHealthStatus($data->livestockId, $data);

    //         return $this->respond(['success' => $result, 'message' => 'Livestock Mortality Successfully Added'], 200);

    //     } catch (\Throwable $th) {
    //         //throw $th;
    //         log_message('error', $th->getMessage() . ": " . $th->getLine());
    //         log_message('error', json_encode($th->getTrace()));
    //         return $this->respond(['error' => $th->getMessage()], 200);
    //     }
    // }

    public function insertLivestockMortality()
    {
        try {
            //code...
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $decoded = decodeToken($header);
            $userType = $decoded->aud;

            $farmer = null;
            if ($userType == 'Farmer') {
                $farmerId = $userId;
            }else{
                $farmerId = $this->request->getPost('farmerId');   
            }

            $livestockId = $this->request->getPost('livestockId');
            $causeOfDeath = $this->request->getPost('causeOfDeath');
            $remarks = $this->request->getPost('remarks');
            $dateOfDeath = $this->request->getPost('dateOfDeath');

            $files = $this->request->getFiles();

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
                'farmerId' => $farmerId,
                'livestockId' => $livestockId,
                'causeOfDeath' => $causeOfDeath,
                'remarks' => $remarks,
                'dateOfDeath' => $dateOfDeath,
                'images' => $uploadedData
            ]);

            $livestockTagId = $this->livestock->getLivestockTagIdById($livestockId);

            $auditLog = (object) [
                'livestockId' => $livestockId,
                'farmerId' => $userId,
                'action' => "Add",
                'title' => "Report Livestock Mortality",
                'description' => "Report Livestock Mortality of Livestock $livestockTagId",
                'entityAffected' => "Mortality",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);

            $result = $this->livestock->updateLivestockHealthStatus($livestockId,(object)[ 'livestockHealthStatus' => 'Dead']);

            return $this->respond(['result' => $response], 200, 'Livestock Mortality Successfully Added');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->failServerError('Failed to process your request');
        }
    }

    public function updateLivestockMortality()
    {
        try {
            $data = $this->request->getJSON();

            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $decoded = decodeToken($header);
            $userType = $decoded->aud;
            if ($userType == 'Farmer') {
                $data->farmerId = $userId;
            }

            $response = $this->livestockMortality->updateLivestockMortality($data->id, $data);

            $livestockTagId = $this->livestock->getLivestockTagIdById($data->livestockId);

            $auditLog = (object) [
                'livestockId' => $data->livestockId,
                'farmerId' => $userId,
                'action' => "Edit",
                'title' => "Updated Livestock Mortality",
                'description' => "Updated Livestock Mortality of Livestock $livestockTagId",
                'entityAffected' => "Mortality",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);

            return $this->respond(['success' => $response, 'message' => 'Livestock Mortality Successfully Updated'], 200);

        } catch (\Throwable $th) {
            return $this->respond(['error' => $th->getMessage()], 200);
        }
    }

    public function updateLivestockMortalityRecordStatus($id)
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockMortality->updateLivestockMortalityRecordStatus($id, $data->recordStatus);

            $livestockTagId = $this->livestock->getLivestockTagIdById($data->livestockId);

            $data->farmerId;
            $data->livestockId;
            $data->action = $data->recordStatus ? "Archived" : "Edit";
            $data->title = $data->recordStatus ? "Archived Livestock Mortality" : "Unarchived Livestock Mortality";
            $data->description = $data->recordStatus ? "Archived Livestock Mortality of Livestock $livestockTagId" : "Unarchived Livestock Mortality of Livestock $livestockTagId";
            $data->entityAffected = "Mortality";

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);
            return $this->respond(['result' => $response, 'message' => 'Livestock Mortality Record Status Successfully Updated'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteLivestockMortality()
    {
        try {
            $id = $this->request->getGet('mortality');

            $livestock = $this->livestock->getLivestockByMortality($id);

            $response = $this->livestockMortality->deleteLivestockMortality($id);

            $livestockTagId = $livestock['livestock_tag_id'];
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);

            $auditLog = (object) [
                'farmerId' => $userId,
                'livestockId' => $livestock['id'],
                'action' => "Delete",
                'title' => "Deleted Livestock Mortality",
                'description' => "Deleted Livestock Mortality of Livestock $livestockTagId",
                'entityAffected' => "Mortality",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);

            return $this->respond(['result' => $response], 200, 'Livestock Mortality Successfully Deleted');

        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to delete record', ResponseInterface::HTTP_BAD_REQUEST);
        }
    }

    public function getOverallLivestockMortalitiesCount()
    {
        try {
            $livestockMortalities = $this->livestockMortality->getOverallLivestockMortalitiesCount();

            return $this->respond(['mortalityCount' => "$livestockMortalities"]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getOverallLivestockMortalitiesCountInCurrentYear()
    {
        try {
            $livestockMortalities = $this->livestockMortality->getOverallLivestockMortalitiesCountInCurrentYear();

            return $this->respond(['mortalityCount' => "$livestockMortalities"]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerOverallLivestockMortalitiesCount($userId)
    {
        try {
            $livestockMortalities = $this->livestockMortality->getFarmerOverallLivestockMortalitiesCount($userId);

            return $this->respond(['mortalityCount' => "$livestockMortalities"]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockMortalitiesCountLastMonth()
    {
        try {
            $livestockMortalitiesCount = $this->livestockMortality->getLivestockMortalitiesCountLastMonth();

            $livestockCount = $this->livestock->getOverallLivestockCount();

            $mortalityPercentage = 0;
            if ($livestockCount > 0) {
                $percentage = ($livestockMortalitiesCount / $livestockCount) * 100;

                if (floor($percentage) == $percentage) {
                    // Display only whole numbers
                    $mortalityPercentage = number_format($percentage, 0);
                } else {
                    // Display up to two decimal places
                    $mortalityPercentage = number_format($percentage, 2);
                }
            }

            $data = [
                'livestockMortalitiesCount' => "$livestockMortalitiesCount",
                'livestockMortalitiesPercentage' => $mortalityPercentage . "%",
            ];

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond($th->getMessage());
        }
    }

    public function getTopMortalityCause()
    {
        try {
            $livestockMortalities = $this->livestockMortality->getTopMortalityCause();

            return $this->respond($livestockMortalities);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getMortalityCountByMonth()
    {
        try {
            $livestockMortalities = $this->livestockMortality->getMortalityCountByMonth();

            return $this->respond($livestockMortalities);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockTypeMortalityCount()
    {
        try {
            $livestockMortalities = $this->livestockMortality->getLivestockTypeMortalityCount();

            return $this->respond($livestockMortalities);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getMortalityReportData()
    {
        try {
            $selectClause = $this->request->getGet('selectClause');
            $minDate = $this->request->getGet('minDate');
            $maxDate = $this->request->getGet('maxDate');
            $livestocks = $this->livestockMortality->getReportData($selectClause, $minDate, $maxDate);

            return $this->respond($livestocks);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getMortalityCountByMonthWithForecast()
    {
        try {
            // Fetch the time series data from the database
            $data = $this->livestockMortality->getLivestockMortalityCountByMonthTimeSeries();

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
                $livestockMortalities = $this->livestockMortality->getMortalityCountByMonth();

                return $this->respond([
                    'original' => array_values($livestockMortalities),
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
            log_message('error', $th->getMessage());
            return $this->respond(['error' => $th->getMessage(), 'trace' => $th->getTrace()]);
        }
    }


    public function getMortalitiesCountLast4Months()
    {
        try {
            $livestockmortalities = $this->livestockMortality->getMortalitiesCountLast4Months();

            return $this->respond($livestockmortalities);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockMortalitiesForReport()
    {
        try {
            $minDate = $this->request->getGet('minDate');
            $maxDate = $this->request->getGet('maxDate');

            $livestockMortalitiesReport = $this->livestockMortality->getMortalitiesForReport($minDate, $maxDate);

            // Check if the report data is not null
            if (is_null($livestockMortalitiesReport) || empty($livestockMortalitiesReport)) {
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
            $sheet->setCellValue('A5', 'LIVESTOCK MORALITIES');
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
                'Age',
                'Farmer User ID',
                'Farmer Full Name',
                'Farmer Address',
                'Cause of Death',
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
            $columnWidths = [16, 16, 16, 16, 14, 16, 20, 30, 20, 20, 12];
            foreach (range('A', 'K') as $index => $columnID) {
                $sheet->getColumnDimension($columnID)->setWidth($columnWidths[$index]);
            }

            // Add data to the sheet starting from the 7th row
            $rowIndex = 11;
            foreach ($livestockMortalitiesReport as $record) {
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
            $fileName = "LivestockMortalitiesReport_{$minDate}_{$maxDate}_{$uniqueId}";
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
}

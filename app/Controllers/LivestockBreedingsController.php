<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FarmerAuditModel;
use App\Models\LivestockBreedingsModel;
use App\Models\LivestockModel;
use App\Models\LivestockPregnancyModel;
use App\Models\LivestockTypeModel;
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

class LivestockBreedingsController extends ResourceController
{
    private $livestockBreeding;
    private $livestockPregnancy;
    private $livestock;
    private $userModel;
    private $farmerAudit;
    private $livestockType;

    public function __construct()
    {
        $this->livestockBreeding = new LivestockBreedingsModel();
        $this->livestockPregnancy = new LivestockPregnancyModel();
        $this->livestock = new LivestockModel();
        $this->userModel = new UserModel();
        $this->farmerAudit = new FarmerAuditModel();
        $this->livestockType = new LivestockTypeModel();
        helper('jwt');
        helper('reportfields');
    }

    public function getAllLivestockBreedings()
    {
        try {
            $livestockBreedings = $this->livestockBreeding->getAllLivestockBreedings();

            return $this->respond($livestockBreedings);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockBreedingReportData()
    {
        try {
            $data = $this->request->getJSON(true);

            $selectedFields = $data['selectedFields'];
            $minDate = $data['minDate'];
            $maxDate = $data['maxDate'];
            $selectClause = getSelectedClauses($selectedFields);


            $breedings = $this->livestockBreeding->getReportData($selectClause, $minDate, $maxDate);

            return $this->respond($breedings);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getLivestockBreeding($id)
    {
        try {
            $livestockBreeding = $this->livestockBreeding->getLivestockBreeding($id);

            return $this->respond($livestockBreeding);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllFarmerLivestockBreedings()
    {
        try {
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);

            $livestockBreedings = $this->livestockBreeding->getAllFarmerLivestockBreedings($userId);

            return $this->respond($livestockBreedings);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertLivestockBreeding()
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

            $breedingId = $this->livestockBreeding->insertLivestockBreeding($data);

            $auditLog = (object) [
                'farmerId' => $userId,
                'action' => "Add",
                'title' => "Breed Livestock",
                'entityAffected' => "Breeding",
            ];

            $maleLivestockTagId = $data->maleLivestockTagId;
            $femaleLivestockTagId = $data->femaleLivestockTagId;

            $maleLivestock = $this->livestock->getFarmerLivestockIdByTag($data->maleLivestockTagId, $data->farmerId);

            $auditLog->description = "Breed Livestock $maleLivestockTagId and $femaleLivestockTagId";
            $auditLog->livestockId = $maleLivestock;

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            if (!$resultAudit) {
                return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            $femaleLivestock = $this->livestock->getFarmerLivestockIdByTag($data->femaleLivestockTagId, $data->farmerId);
            $auditLog->livestockId = $femaleLivestock;

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            if (!$resultAudit) {
                return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            $data->livestockId = $femaleLivestock;
            $livestockType = $this->livestockType->getLivestockTypeName($data->livestockId);
            $livestockPregnantStatus = $this->livestock->updateLivestockPregnantStatus($data->livestockId, true);

            $result = null;

            if ($data->breedResult == 'Successful Breeding') {
                $femaleLivestockId = $this->livestock->getFarmerLivestockIdByTag($data->femaleLivestockTagId, $data->farmerId);

                $data->breedingId = $breedingId;
                $data->livestockId = $femaleLivestockId;
                $data->pregnancyStartDate = $data->breedDate;

                $result = $this->livestockPregnancy->insertLivestockPregnancyByBreeding($data);
                if (!$result) {
                    log_message('error', json_encode($this->livestockPregnancy->errors()));
                    return $this->fail('Failed to record pregnancy details', ResponseInterface::HTTP_BAD_REQUEST);
                }
            }


            return $this->respond(['result' => $result], 200, 'Livestock Breeding Successfully Added');

        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to add breeding record', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function calculateExpectedDeliveryDate($breedDate, $livestockType)
    {
        // Define gestation periods for different livestock types (in days)
        $gestationPeriods = [
            'Sheep' => 150,
            'Cattle' => 280,
            'Pig' => 114,
            'Goat' => 150,
            'Carabao' => 309,
            // Add more livestock types and their corresponding gestation periods here
        ];

        // Check if the provided livestock type exists in the gestation periods array
        if (array_key_exists($livestockType, $gestationPeriods)) {
            // Calculate the expected delivery date
            $gestationPeriod = $gestationPeriods[$livestockType];
            $expectedDeliveryDate = date('Y-m-d', strtotime($breedDate . ' + ' . $gestationPeriod . ' days'));
            return $expectedDeliveryDate;
        } else {
            // If the provided livestock type is not found, return null or throw an exception
            return null; // or throw new Exception('Livestock type not found');
        }
    }

    public function updateLivestockBreeding()
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

            $response = $this->livestockBreeding->updateLivestockBreeding($data->id, $data);

            $data->action = "Edit";
            $data->title = "Updated Livestock Breeding";
            $data->entityAffected = "Breeding";

            $auditLog = (object) [
                'farmerId' => $userId,
                'action' => "Edit",
                'title' => "Updated Livestock Breeding",
                'entityAffected' => "Breeding",
            ];

            $maleLivestockTagId = $data->maleLivestockTagId;
            $femaleLivestockTagId = $data->femaleLivestockTagId;

            $maleLivestock = $this->livestock->getFarmerLivestockIdByTag($data->maleLivestockTagId, $data->farmerId);

            $auditLog->description = "Updated Livestock Breeding of Livestock $maleLivestockTagId and $femaleLivestockTagId";
            $auditLog->livestockId = $maleLivestock;

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            if (!$resultAudit) {
                return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            $femaleLivestock = $this->livestock->getFarmerLivestockIdByTag($data->femaleLivestockTagId, $data->farmerId);
            $auditLog->livestockId = $femaleLivestock;
            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            if (!$resultAudit) {
                return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->respond(['success' => true, 'message' => 'Livestock Breeding Successfully Updated'], 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
        }
    }

    public function updateLivestockBreedingRecordStatus($id)
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockBreeding->updateLivestockBreedingRecordStatus($id, $data->recordStatus);

            $data->farmerId = $data->userId;
            $data->action = $data->recordStatus == 'Archive' ? 'Archive' : "Edit";
            $data->title = $data->recordStatus == 'Archived' ? 'Archived Livestock Breeding Record' : "Updated Livestock Breeding Record";
            $data->entityAffected = $data->recordStatus == 'Archived' ? 'Archived' : "Breeding";

            $maleLivestockTagId = $data->maleLivestockTagId;
            $femaleLivestockTagId = $data->femaleLivestockTagId;

            $data->description = $data->recordStatus == 'Archived' ? "Archived Livestock Breeding of Livestock $maleLivestockTagId and $femaleLivestockTagId" : "Updated Livestock Breeding of Livestock $maleLivestockTagId and $femaleLivestockTagId";
            $data->livestockId = $this->livestock->getFarmerLivestockIdByTag($data->femaleLivestockTagId, $data->farmerId);
            $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);
            if (!$resultAudit) {
                return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->respond(['result' => $response, 'message' => 'Livestock Breeding Record Status Successfully Updated'], 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteLivestockBreeding()
    {
        try {
            $id = $this->request->getGet('breeding');
            $farmerId = $this->request->getGet('fui');

            $livestock = $this->livestockBreeding->getLivestockByBreeding($id);
            $response = $this->livestockBreeding->deleteLivestockBreeding($id);

            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);

            $maleLivestockTagId = $livestock['maleLivestockTagId'];
            $femaleLivestockTagId = $livestock['femaleLivestockTagId'];

            $femaleLivestock = $this->livestock->getFarmerLivestockIdByTag($femaleLivestockTagId, $farmerId);
            $auditLog = (object) [
                'livestockId' => $femaleLivestock['id'],
                'farmerId' => $userId,
                'action' => "Delete",
                'title' => "Delete Livestock Breeding",
                'entityAffected' => "Breeding",
                'description' => "Updated Livestock Breeding of Livestock $maleLivestockTagId and $femaleLivestockTagId"
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            if (!$resultAudit) {
                return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }
            return $this->respond(['result' => $auditLog], 200, 'Livestock Breeding Successfully Deleted');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to delete record', ResponseInterface::HTTP_BAD_REQUEST);
        }
    }

    public function getAllBreedingParentOffspringData()
    {
        try {
            $livestockBreedings = $this->livestockBreeding->getAllBreedingParentOffspringData();
            return $this->respond($livestockBreedings);
        } catch (\Throwable $th) {
            //throw $th;

            return $this->respond(['error' => $th->getMessage()], 200);
        }
    }

    public function getOverallLivestockBreedingCount()
    {
        try {
            $livestockBreedingCount = $this->livestockBreeding->getOverallLivestockBreedingCount();
            return $this->respond(['breedingCount' => "$livestockBreedingCount"]);
        } catch (\Throwable $th) {
            //throw $th;

            return $this->respond(['error' => $th->getMessage()], 200);
        }
    }

    public function getOverallLivestockBreedingCountInCurrentYear()
    {
        try {
            $livestockBreedingCount = $this->livestockBreeding->getOverallLivestockBreedingCountInCurrentYear();
            return $this->respond(['breedingCount' => "$livestockBreedingCount"]);
        } catch (\Throwable $th) {
            //throw $th;

            return $this->respond(['error' => $th->getMessage()], 200);
        }
    }

    public function getFarmerOverallLivestockBreedingCount($userId)
    {
        try {
            $livestockBreedingCount = $this->livestockBreeding->getFarmerOverallLivestockBreedingCount($userId);
            return $this->respond(['breedingCount' => "$livestockBreedingCount"]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockBreedingSuccessPercentage()
    {
        try {
            $successCount = $this->livestockBreeding->getLivestockBreedingCountByResultInCurrentYear("Successful Breeding");

            $livestockBreedings = $this->livestockBreeding->getOverallLivestockBreedingCountInCurrentYear();

            $breedingPercentage = 0;
            if ($livestockBreedings > 0) {
                $percentage = ($successCount / $livestockBreedings) * 100;

                if (floor($percentage) == $percentage) {
                    // Display only whole numbers
                    $breedingPercentage = number_format($percentage, 0);
                } else {
                    // Display up to two decimal places
                    $breedingPercentage = number_format($percentage, 2);
                }
            }

            return $this->respond(['breedingPercent' => "$breedingPercentage%"]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockBreedingResultsCount()
    {
        try {
            $successfulCount = $this->livestockBreeding->getLivestockBreedingCountByResultInCurrentYear("Successful Breeding");

            $unsuccessfulCount = $this->livestockBreeding->getLivestockBreedingCountByResultInCurrentYear("Unsuccessful Breeding");

            $data = [
                'Successful Breeding' => $successfulCount,
                'Unsuccessful Breeding' => $unsuccessfulCount
            ];

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getBreedingsCountLast4Months()
    {
        try {
            $livestockBreedings = $this->livestockBreeding->getBreedingsCountLast4Months();
            return $this->respond($livestockBreedings);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockTypeBreedingsCount()
    {
        try {
            $livestockBreedings = $this->livestockBreeding->getLivestockTypeBreedingsCount();
            return $this->respond($livestockBreedings);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getBreedingCountByMonth()
    {
        try {
            $livestockBreedings = $this->livestockBreeding->getBreedingCountByMonth();
            return $this->respond($livestockBreedings);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockBreedingForReport()
    {
        try {
            $minDate = $this->request->getGet('minDate');
            $maxDate = $this->request->getGet('maxDate');

            $livestockBreedingReport = $this->livestockBreeding->getBreedingsForReport($minDate, $maxDate);

            // Check if the report data is not null
            if (is_null($livestockBreedingReport) || empty($livestockBreedingReport)) {
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

            // 5th Row: LIVESTOCK VACCINATIONS
            $sheet->mergeCells('A5:I5');
            $sheet->setCellValue('A5', 'LIVESTOCK BREEDING');
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

            // 10th Row: Column Headers
            $headers = [
                'Farmer User ID',
                'Farmer Full Name',
                'Farmer Address',
                'Livestock Type',
                'Male Livestock',
                'Male Livestock',
                'Breeding Result',
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
            $columnWidths = [16, 20, 30, 16, 20, 20, 16, 20, 14];
            foreach (range('A', 'I') as $index => $columnID) {
                $sheet->getColumnDimension($columnID)->setWidth($columnWidths[$index]);
            }

            // Add data to the sheet starting from the 7th row
            $rowIndex = 11;
            foreach ($livestockBreedingReport as $record) {
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
            $fileName = "LivestockBreedingReport_{$minDate}_{$maxDate}_{$uniqueId}";
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

    public function getAllLivestockBreedingsByLivestock()
    {
        try {
            $farmerId = $this->request->getGet('farmer');
            $livestockId = $this->request->getGet('livestock');

            $data = $this->livestockBreeding->getAllLivestockBreedingsByLivestockId($livestockId, $farmerId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

}

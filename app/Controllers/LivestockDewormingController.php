<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\ArimaPredictionLibrary;
use App\Models\FarmerAuditModel;
use App\Models\LivestockDewormingModel;
use App\Models\LivestockModel;
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

class LivestockDewormingController extends ResourceController
{
    private $livestockDewormings;
    private $livestock;
    private $userModel;
    private $farmerAudit;

    private $arimaPrediction;

    public function __construct()
    {
        $this->livestockDewormings = new LivestockDewormingModel();
        $this->livestock = new LivestockModel();
        $this->userModel = new UserModel();
        $this->farmerAudit = new FarmerAuditModel();
        $this->arimaPrediction = new ArimaPredictionLibrary();
        helper('jwt');
    }

    public function getAllLivestockDewormings()
    {
        try {
            $livestockDewormings = $this->livestockDewormings->getAllLivestockDewormings();

            return $this->respond($livestockDewormings);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockDewormingReportData()
    {
        try {
            $selectClause = $this->request->getGet('selectClause');
            $minDate = $this->request->getGet('minDate');
            $maxDate = $this->request->getGet('maxDate');

            $livestockDewormings = $this->livestockDewormings->getReportData($selectClause, $minDate, $maxDate);

            return $this->respond($livestockDewormings);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getLivestockDeworming($id)
    {
        try {
            $livestockDeworming = $this->livestockDewormings->getLivestockDeworming($id);

            return $this->respond($livestockDeworming);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllFarmerLivestockDewormings()
    {
        try {
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);

            $livestockDewormings = $this->livestockDewormings->getAllFarmerLivestockDewormings($userId);

            return $this->respond($livestockDewormings);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertLivestockDeworming()
    {
        try {
            $data = $this->request->getJSON();

            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $decoded = decodeToken($header);
            $userType = $decoded->aud;
            if($userType == 'Farmer'){
                $data->dewormerId = $userId;
            }

            $response = $this->livestockDewormings->insertLivestockDeworming($data);
            if(!$response){
                return $this->fail('Failed to add deworming');
            }

            $livestockTagId = $this->livestock->getLivestockTagIdById($data->livestockId);

            $dosage = $data->dosage;
            $administrationMethod = $data->administrationMethod;

            $auditLog = (object)[
                'livestockId' => $data->livestockId,
                'farmerId' => $userId,
                'action' => "Add",
                'title' => "Adminster Deworming",
                'description' => "Deworm $administrationMethod $dosage to Livestock $livestockTagId",
                'entityAffected' => "Deworming",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            if(!$resultAudit){
                return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->respond(['result' => $response], 200, 'Livestock Deworming Successfully Added');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to add deworming', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function insertMultipleLivestockDeworming()
    {
        try {
            $data = $this->request->getJSON();

            $livestock = $data->livestock;

            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $decoded = decodeToken($header);
            $userType = $decoded->aud;
            if($userType == 'Farmer'){
                $data->dewormerId = $userId;
            }

            $auditLog = (object)[
                'farmerId' => $userId,
                'action' => "Add",
                'title' => "Adminster Deworming",
                'entityAffected' => "Deworming",
            ];

            $res = null;

            foreach ($livestock as $ls) {
                $data->livestockId = $ls;
                $auditLog->livestockId = $ls;

                $response = $this->livestockDewormings->insertLivestockDeworming($data);
                if(!$response){
                    return $this->fail('Failed to add deworming');
                }

                $livestockTagId = $this->livestock->getLivestockTagIdById($data->livestockId);

                $dosage = $data->dosage;
                $administrationMethod = $data->administrationMethod;

                $auditLog->description = "Deworm $administrationMethod $dosage to Livestock $livestockTagId";

                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                if(!$resultAudit){
                    return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
                }
                $res = $response;
            }

            return $this->respond(['result' => $res], 200, 'Livestock Deworming Successfully Added');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to add deworming', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateLivestockDeworming()
    {
        try {
            $data = $this->request->getJSON();

            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $decoded = decodeToken($header);
            $userType = $decoded->aud;
            if($userType == 'Farmer'){
                $data->dewormerId = $userId;
            }

            $response = $this->livestockDewormings->updateLivestockDeworming($data->id, $data);
            if(!$response){
                return $this->fail('Failed to update deworming');
            }

            $livestockTagId = $this->livestock->getLivestockTagIdById($data->livestockId);

            $auditLog = (object)[
                'livestockId' => $data->livestockId,
                'farmerId' => $userId,
                'action' => "Edit",
                'title' => "Update Deworming Details",
                'description' => "Update Deworming of Livestock $livestockTagId",
                'entityAffected' => "Deworming",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            if(!$resultAudit){
                return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->respond(['result' => $response], 200, 'Livestock Deworming Successfully Updated');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to update deworming', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateLivestockDewormingRecordStatus($id)
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->livestockDewormings->updateLivestockDewormingRecordStatus($id, $data->recordStatus);

            $livestock = $this->livestock->getLivestockByDeworming($id);
            $livestockTagId = $livestock['livestock_tag_id'];

            $data->farmerId = $data->dewormerId;
            $data->livestockId = $livestock['id'];
            $data->action = $data->recordStatus == 'Archived' ? "Archived" : "Edit";
            $data->title = $data->recordStatus == 'Archived' ? "Archived Deworming Record" : "Unarchived Deworming Record";
            $data->description = $data->recordStatus == 'Archived' ? "Archived Deworming of Livestock $livestockTagId" : "Unarchived Deworming of Livestock $livestockTagId";
            $data->entityAffected = "Deworming";

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($data);
            if(!$resultAudit){
                return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->respond(['success' => $response, 'message' => 'Livestock Deworming Record Status Successfully Updated'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteLivestockDewormingRecord()
    {
        try {
            $id = $this->request->getGet('deworming');

            $livestock = $this->livestock->getLivestockByDeworming($id);
            $response = $this->livestockDewormings->deleteLivestockDewormingRecord($id);

            $livestockTagId = $livestock['livestock_tag_id'];
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $auditLog = (object) [
                'farmerId' => $userId,
                'livestockId' => $livestock['id'],
                'action' => "Delete",
                'title' => "Deleted Deworming Record",
                'description' => "Deleted Deworming of Livestock $livestockTagId",
                'entityAffected' => "Deworming",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            if(!$resultAudit){
                return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->respond(['result' => $response], 200, 'Livestock Deworming Successfully Deleted');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to delete record', ResponseInterface::HTTP_BAD_REQUEST);
        }
    }

    public function getOverallLivestockDewormingCount()
    {
        try {
            $livestockDewormingCount = $this->livestockDewormings->getOverallLivestockDewormingCount();

            return $this->respond(['dewormingCount' => "$livestockDewormingCount"]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerOverallLivestockDewormingCount($userId)
    {
        try {
            $livestockDewormingCount = $this->livestockDewormings->getFarmerOverallLivestockDewormingCount($userId);

            return $this->respond(['dewormingCount' => "$livestockDewormingCount"]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getDewormingCountLast4Months()
    {
        try {
            $livestockDewormingCountLast4Months = $this->livestockDewormings->getDewormingCountLast4Months();

            return $this->respond($livestockDewormingCountLast4Months);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getTopLivestockTypeDewormedCount()
    {
        try {
            $livestockDeworming = $this->livestockDewormings->getTopLivestockTypeDewormedCount();

            return $this->respond($livestockDeworming);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAdministrationMethodsCount()
    {
        try {
            $livestockDeworming = $this->livestockDewormings->getAdministrationMethodsCount();

            return $this->respond($livestockDeworming);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getDewormingCountByMonth()
    {
        try {
            $livestockDeworming = $this->livestockDewormings->getDewormingCountByMonth();

            return $this->respond($livestockDeworming);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getDewormingCountByMonthWithForecast()
    {
        try {
            // Fetch the time series data from the database
            $data = $this->livestockDewormings->getLivestockDewormingsCountByMonthTimeSeries();

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
                $livestockDeworming = $this->livestockDewormings->getDewormingCountByMonth();

                return $this->respond([
                    'original' => array_values($livestockDeworming),
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

    public function getLivestockDewormingsForReport()
    {
        try {
            $minDate = $this->request->getGet('minDate');
            $maxDate = $this->request->getGet('maxDate');

            $livestockDewormingReport = $this->livestockDewormings->getDewormingsForReport($minDate, $maxDate);

            // Check if the report data is not null
            if (is_null($livestockDewormingReport) || empty($livestockDewormingReport)) {
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
            $sheet->mergeCells('A1:L1');
            $sheet->setCellValue('A1', 'Department of Agriculture- MiMaRoPa');
            $sheet->getStyle('A1:L1')->applyFromArray([
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
            $sheet->mergeCells('A2:L2');
            $sheet->setCellValue('A2', 'Research Division-Livestock Department');
            $sheet->getStyle('A2:L2')->applyFromArray([
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
            $sheet->mergeCells('A3:L3');
            $sheet->setCellValue('A3', 'Barcenaga, Naujan Oriental Mindoro');
            $sheet->getStyle('A3:L3')->applyFromArray([
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
            $sheet->mergeCells('A5:L5');
            $sheet->setCellValue('A5', 'LIVESTOCK DEWORMING');
            $sheet->getStyle('A5:L5')->applyFromArray([
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
            $sheet->mergeCells('A6:L6');
            $startDate = date('F Y', strtotime($minDate));
            $endDate = date('F Y', strtotime($maxDate));
            $sheet->setCellValue('A6', "$startDate To $endDate");
            $sheet->getStyle('A6:L6')->applyFromArray([
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
            $sheet->mergeCells('A8:L8');
            $dateExported = date('F j, Y');
            $sheet->setCellValue('A8', "Date Exported: $dateExported");
            $sheet->getStyle('A8:L8')->applyFromArray([
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
                'Farmer User ID',
                'Farmer Full Name',
                'Farmer Address',
                'Dosage',
                'Method',
                'Remarks',
                'Deworming Date',
                'Next Deworming Date',
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
            $columnWidths = [16, 16, 16, 16, 16, 20, 30, 14, 14, 20, 12, 12];
            foreach (range('A', 'L') as $index => $columnID) {
                $sheet->getColumnDimension($columnID)->setWidth($columnWidths[$index]);
            }

            // Add data to the sheet starting from the 7th row
            $rowIndex = 11;
            foreach ($livestockDewormingReport as $record) {
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
            $fileName = "LivestockDewormingReport_{$minDate}_{$maxDate}_{$uniqueId}";
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

    public function getLivestockDewormingByLivestock(){
        try {
            //code...
            $livestockId = $this->request->getGet('livestock');

            $data = $this->livestockDewormings->getLivestockDewormingByLivestockId($livestockId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

}

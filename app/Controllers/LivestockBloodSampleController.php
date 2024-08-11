<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FarmerAuditModel;
use App\Models\LivestockBloodSampleModel;
use App\Models\LivestockModel;
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

class LivestockBloodSampleController extends ResourceController
{
    private $livestockBloodSample;
    private $farmerAudit;
    private $livestock;
    public function __construct()
    {
        $this->livestockBloodSample = new LivestockBloodSampleModel();
        $this->farmerAudit = new FarmerAuditModel();
        $this->livestock = new LivestockModel();
        helper('jwt');
    }

    public function getAllLivestockBloodSamples()
    {
        try {
            $livestockBloodSamples = $this->livestockBloodSample->getAllLivestockBloodSamples();
            return $this->respond($livestockBloodSamples);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getBloodSampleReportData(){
        try {
            $selectClause = $this->request->getGet('selectClause');
            $minDate = $this->request->getGet('minDate');
            $maxDate = $this->request->getGet('maxDate');
            $livestocks = $this->livestockBloodSample->getReportData($selectClause, $minDate, $maxDate);

            return $this->respond($livestocks);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getAllFarmerLivestockBloodSamples($userId)
    {
        try {
            $livestockBloodSamples = $this->livestockBloodSample->getAllFarmerLivestockBloodSamples($userId);

            return $this->respond($livestockBloodSamples);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockBloodSample($id)
    {
        try {
            $livestockBloodSample = $this->livestockBloodSample->getLivestockBloodSample($id);

            return $this->respond($livestockBloodSample);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertLivestockBloodSample()
    {
        try {
            $data = $this->request->getJSON();

            $result = $this->livestockBloodSample->insertLivestockBloodSample($data);

            $livestockTagId = $this->livestock->getLivestockTagIdById($data->livestockId);

            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $auditLog = (object)[
                'livestockId' => $data->livestockId,
                'farmerId' => $userId,
                'action' => "Add",
                'title' => "Add New Livestock Blood Sample",
                'description' => "Add New Livestock Blood Sample $livestockTagId",
                'entityAffected' => "Blood Sample",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            if(!$resultAudit){
                return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->respond(['message' => 'Livestock Blood Sample Successfully Added', 'success' => $result], 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to add blood sample', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function insertMultipleLivestockBloodSample()
    {
        try {
            $data = $this->request->getJSON();

            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $auditLog = (object)[
                'farmerId' => $userId,
                'action' => "Add",
                'title' => "Add New Livestock Blood Sample",
                'entityAffected' => "Blood Sample",
            ];

            $livestocks = $data->livestock;

            $result = null;
            foreach ($livestocks as $ls) {
                $data->livestockId = $ls;
                $auditLog->livestockId = $ls;
    
                $livestockTagId = $this->livestock->getLivestockTagIdById($data->livestockId);
                $auditLog->description = "Add New Livestock Blood Sample $livestockTagId";

                $result = $this->livestockBloodSample->insertLivestockBloodSample($data);
                
                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                if(!$resultAudit){
                    return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
                }
            }

            return $this->respond(['success' => $result, 'message' => 'Livestock Blood Sample Successfully Added']);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
        }
    }

    public function updateLivestockBloodSample()
    {
        try {
            $data = $this->request->getJSON();

            $result = $this->livestockBloodSample->updateLivestockBloodSample($data->id, $data);

            $livestockTagId = $this->livestock->getLivestockTagIdById($data->livestockId);

            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $auditLog = (object)[
                'livestockId' => $data->livestockId,
                'farmerId' => $userId,
                'action' => "Edit",
                'title' => "Updated Livestock Blood Sample",
                'description' => "Updated Livestock Blood Sample $livestockTagId",
                'entityAffected' => "Blood Sample",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            if(!$resultAudit){
                return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->respond(['message' => 'Livestock Blood Sample Successfully Updated', 'success' => $result], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteLivestockBloodSample()
    {
        try {
            $id = $this->request->getGet('sample');

            $livestock = $this->livestock->getLivestockByBloodSample($id);

            $response = $this->livestockBloodSample->deleteLivestockBloodSample($id);

            $livestockTagId = $livestock['livestock_tag_id'];
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);

            $auditLog = (object) [
                'farmerId' => $userId,
                'livestockId' => $livestock['id'],
                'action' => "Delete",
                'title' => "Deleted Livestock Blood Sample",
                'description' => "Deleted Livestock Blood Sample of Livestock $livestockTagId",
                'entityAffected' => "Blood Sample",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            if(!$resultAudit){
                return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->respond([ 'result' => $response], 200, 'Livestock Blood Sample Successfully Deleted');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to delete record', ResponseInterface::HTTP_BAD_REQUEST);
        }
    }

    public function getOverallLivestockBloodSampleCount()
    {
        try {
            $result = $this->livestockBloodSample->getOverallLivestockBloodSampleCount();

            return $this->respond(['bloodSampleCount' => "$result"], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getFarmerOverallLivestockBloodSampleCount($userId)
    {
        try {
            $result = $this->livestockBloodSample->getFarmerOverallLivestockBloodSampleCount($userId);

            return $this->respond(['bloodSampleCount' => "$result"], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getRecentLivestockBloodSample(){
        try {
            $livestockBloodSample = $this->livestockBloodSample->getRecentLivestockBloodSample();

            return $this->respond($livestockBloodSample, 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getTopLivestockObservations(){
        try {
            $livestockObservations = $this->livestockBloodSample->getTopLivestockObservations();

            return $this->respond($livestockObservations);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getTopBloodSampleFindings(){
        try {
            $findings = $this->livestockBloodSample->getTopBloodSampleFindings();

            return $this->respond($findings);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getBloodCountLast4Months(){
        try {
            $livestockBloodSampleCountLast4Months = $this->livestockBloodSample->getBloodCountLast4Months();

            return $this->respond($livestockBloodSampleCountLast4Months);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getBloodSampleCountByMonth(){
        try {
            $livestockBloodSampleCountByMonth = $this->livestockBloodSample->getBloodSampleCountByMonth();

            return $this->respond($livestockBloodSampleCountByMonth);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getLivestockBloodSamplesForReport()
    {
        try {
            $minDate = $this->request->getGet('minDate');
            $maxDate = $this->request->getGet('maxDate');

            $livestockBloodSampleReport = $this->livestockBloodSample->getBloodSamplesForReport($minDate, $maxDate);

            // Check if the report data is not null
            if (is_null($livestockBloodSampleReport) || empty($livestockBloodSampleReport)) {
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
            $sheet->mergeCells('A1:J1');
            $sheet->setCellValue('A1', 'Department of Agriculture- MiMaRoPa');
            $sheet->getStyle('A1:J1')->applyFromArray([
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
            $sheet->mergeCells('A2:J2');
            $sheet->setCellValue('A2', 'Research Division-Livestock Department');
            $sheet->getStyle('A2:J2')->applyFromArray([
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
            $sheet->mergeCells('A3:J3');
            $sheet->setCellValue('A3', 'Barcenaga, Naujan Oriental Mindoro');
            $sheet->getStyle('A3:J3')->applyFromArray([
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
            $sheet->mergeCells('A5:J5');
            $sheet->setCellValue('A5', 'LIVESTOCK BLOOD SAMPLES');
            $sheet->getStyle('A5:J5')->applyFromArray([
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
            $sheet->mergeCells('A6:J6');
            $startDate = date('F Y', strtotime($minDate));
            $endDate = date('F Y', strtotime($maxDate));
            $sheet->setCellValue('A6', "$startDate To $endDate");
            $sheet->getStyle('A6:J6')->applyFromArray([
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
            $sheet->mergeCells('A8:J8');
            $dateExported = date('F j, Y');
            $sheet->setCellValue('A8', "Date Exported: $dateExported");
            $sheet->getStyle('A8:J8')->applyFromArray([
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
                'Observation',
                'Findings',
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
            $columnWidths = [16, 16, 16, 16, 16, 20, 30, 20, 20, 12];
            foreach (range('A', 'J') as $index => $columnID) {
                $sheet->getColumnDimension($columnID)->setWidth($columnWidths[$index]);
            }

            // Add data to the sheet starting from the 7th row
            $rowIndex = 11;
            foreach ($livestockBloodSampleReport as $record) {
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
            $fileName = "LivestockBloodSampleReport_{$minDate}_{$maxDate}_{$uniqueId}";
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


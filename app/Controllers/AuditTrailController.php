<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FarmerAuditModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use ErrorException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AuditTrailController extends ResourceController
{
    private $auditTrails;

    public function __construct()
    {
        $this->auditTrails = new FarmerAuditModel();
        helper('jwt');
    }

    public function getAllAuditTrailLogs()
    {
        try {
            //code...
            $data = $this->auditTrails->getAllAuditTrailLogs();
            // return $this->respond($auditTrails);
            return $this->respond($data, 200, 'Data retrieved successfully');
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to retrieve data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllFarmerAuditTrailLogs()
    {
        try {
            //code...
            $auditTrails = $this->auditTrails->getAllFarmerAuditTrailLogs();
            return $this->respond($auditTrails);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond($th->getMessage());
        }
    }

    public function getAuditReportData()
    {
        try {
            $selectClause = $this->request->getGet('selectClause');
            $minDate = $this->request->getGet('minDate');
            $maxDate = $this->request->getGet('maxDate');

            $auditTrails = $this->auditTrails->getReportData($selectClause, $minDate, $maxDate);

            return $this->respond($auditTrails);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getFarmerAuditTrailLogs()
    {
        try {
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);

            $auditTrails = $this->auditTrails->getFarmerAuditTrailLogs($userId);
            return $this->respond($auditTrails);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
        }
    }

    public function getAuditTrailLogsByEntity($entity)
    {
        try {
            $auditTrails = $this->auditTrails->getAuditTrailLogsByEntity($entity);
            return $this->respond($auditTrails);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAuditTrailLogsByAction($action)
    {
        try {
            $auditTrails = $this->auditTrails->getAuditTrailLogsByAction($action);
            return $this->respond($auditTrails);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertAuditTrailLog()
    {
        try {
            $data = $this->request->getJSON();

            $result = $this->auditTrails->insertAuditTrailLog($data);

            return $this->respond(['message' => 'Audit Trail inserted successfully', 'result' => $result]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond($th->getMessage());
        }
    }

    public function updateAuditTrailLog($id)
    {
        try {
            $data = $this->request->getJSON();

            $result = $this->auditTrails->updateAuditTrailLog($id, $data);

            return $this->respond(['message' => 'Audit Trail updated successfully', 'result' => $result]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updateAuditTrailRecordStatus($id)
    {
        try {
            $data = $this->request->getJSON();

            $result = $this->auditTrails->updateAuditTrailRecordStatus($id, $data->recordStatus);

            return $this->respond(['message' => 'Audit Trail record status updated successfully', 'result' => $result]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteAuditTrailLog($id)
    {
        try {
            $result = $this->auditTrails->deleteAuditTrailLog($id);

            return $this->respond(['message' => 'Audit Trail deleted successfully', 'result' => $result]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getUserAuditTrailsForReport()
    {
        try {
            $minDate = $this->request->getGet('minDate');
            $maxDate = $this->request->getGet('maxDate');

            $userAuditTrailReport = $this->auditTrails->getUserAuditTrailForReport($minDate, $maxDate);

            // Check if the report data is not null
            if (is_null($userAuditTrailReport) || empty($userAuditTrailReport)) {
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
            $sheet->mergeCells('A1:H1');
            $sheet->setCellValue('A1', 'Department of Agriculture- MiMaRoPa');
            $sheet->getStyle('A1:H1')->applyFromArray([
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
            $sheet->mergeCells('A2:H2');
            $sheet->setCellValue('A2', 'Research Division-Livestock Department');
            $sheet->getStyle('A2:H2')->applyFromArray([
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
            $sheet->mergeCells('A3:H3');
            $sheet->setCellValue('A3', 'Barcenaga, Naujan Oriental Mindoro');
            $sheet->getStyle('A3:H3')->applyFromArray([
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
            $sheet->mergeCells('A5:H5');
            $sheet->setCellValue('A5', 'USER AUDIT TRAIL');
            $sheet->getStyle('A5:H5')->applyFromArray([
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
            $sheet->mergeCells('A6:H6');
            $startDate = date('F Y', strtotime($minDate));
            $endDate = date('F Y', strtotime($maxDate));
            $sheet->setCellValue('A6', "$startDate To $endDate");
            $sheet->getStyle('A6:H6')->applyFromArray([
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
            $sheet->mergeCells('A8:H8');
            $dateExported = date('F j, Y');
            $sheet->setCellValue('A8', "Date Exported: $dateExported");
            $sheet->getStyle('A8:H8')->applyFromArray([
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
                'Action',
                'Title',
                'Description',
                'Entity Affected',
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
            $columnWidths = [16, 20, 30, 16, 16, 40, 16, 16];
            foreach (range('A', 'H') as $index => $columnID) {
                $sheet->getColumnDimension($columnID)->setWidth($columnWidths[$index]);
            }

            // Add data to the sheet starting from the 7th row
            $rowIndex = 11;
            foreach ($userAuditTrailReport as $record) {
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
            $fileName = "userAuditTrailReport_{$minDate}_{$maxDate}_{$uniqueId}";
            $excelFilePath = $excelDirectory . "{$fileName}.xlsx";
            $pdfFilePath = $pdfDirectory . "{$fileName}.pdf";

            // Save the Excel file
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

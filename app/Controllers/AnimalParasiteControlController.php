<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\ArimaPredictionLibrary;
use App\Models\AnimalParasiteControlModel;
use App\Models\FarmerAuditModel;
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

class AnimalParasiteControlController extends ResourceController
{
    private $animalParasiteControl;
    private $livestock;
    private $userModel;
    private $farmerAudit;

    private $arimaPrediction;
    public function __construct()
    {
        $this->animalParasiteControl = new AnimalParasiteControlModel();
        $this->livestock = new LivestockModel();
        $this->userModel = new UserModel();
        $this->farmerAudit = new FarmerAuditModel();
        $this->arimaPrediction = new ArimaPredictionLibrary();
        helper('jwt');
    }

    public function getAllAnimalParasiteControls()
    {
        try {
            //code...
            $category = $this->request->getGet('category');

            $data = $this->animalParasiteControl->getAllAnimalParasiteControls($category);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllAnimalParasiteControlByAnimal()
    {
        try {
            //code...
            $livestockId = $this->request->getGet('animal');

            $data = $this->animalParasiteControl->getAllAnimalParasiteControlByAnimalId($livestockId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllAnimalParasiteControlByUser()
    {
        try {
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);

            $data = $this->animalParasiteControl->getAllAnimalParasiteControlByUserId($userId);

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function insertParasiteControl()
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

            $response = $this->animalParasiteControl->insertAnimalParasiteControl($data);
            if (!$response) {
                return $this->fail('Failed to add parasite control');
            }

            $livestockTagId = $this->livestock->getLivestockTagIdById($data->livestockId);

            $drugName = $data->drugName;
            $dosage = $data->dosage;
            $administrationMethod = $data->administrationMethod;

            $auditLog = (object) [
                'livestockId' => $data->livestockId,
                'farmerId' => $userId,
                'action' => "Add",
                'title' => "Add Parasite Control",
                'description' => "Add $drugName $administrationMethod $dosage to Livestock $livestockTagId",
                'entityAffected' => "Parasite Control",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            if (!$resultAudit) {
                return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->respond(['result' => $response], 200, 'Livestock Parasite Control Successfully Added');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to add parasite control', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function insertMultipleParasiteControl()
    {
        try {
            $data = $this->request->getJSON();

            $livestock = $data->livestock;

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
                'title' => "Add Parasite Control",
                'entityAffected' => "Parasite Control",
            ];

            $res = null;

            foreach ($livestock as $ls) {
                $data->livestockId = $ls;
                $auditLog->livestockId = $ls;

                $response = $this->animalParasiteControl->insertAnimalParasiteControl($data);
                if (!$response) {
                    return $this->fail('Failed to add parasite control');
                }

                $livestockTagId = $this->livestock->getLivestockTagIdById($data->livestockId);

                $drugName = $data->drugName;
                $dosage = $data->dosage;
                $administrationMethod = $data->administrationMethod;

                $auditLog->description = "Add $drugName $administrationMethod $dosage to Livestock $livestockTagId";

                $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
                if (!$resultAudit) {
                    return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
                }
                $res = $response;
            }

            return $this->respond(['result' => $res], 200, 'Livestock Parasite Control Successfully Added');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to add parasite control', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateParasiteControl(){
        try {
            $data = $this->request->getJSON();

            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $decoded = decodeToken($header);
            $userType = $decoded->aud;
            if($userType == 'Farmer'){
                $data->farmerId = $userId;
            }

            $response = $this->animalParasiteControl->updateAnimalParasiteControl($data->id, $data);
            if(!$response){
                return $this->fail('Failed to update parasite control');
            }

            $livestockTagId = $this->livestock->getLivestockTagIdById($data->livestockId);

            $auditLog = (object)[
                'livestockId' => $data->livestockId,
                'farmerId' => $userId,
                'action' => "Edit",
                'title' => "Update Parasite Control Details",
                'description' => "Update Parasite Control of Livestock $livestockTagId",
                'entityAffected' => "Parasite Control",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            if(!$resultAudit){
                return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->respond(['result' => $response], 200, 'Livestock Parasite Control Successfully Updated');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to update parasite control', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteParasiteControl(){
        try {
            $id = $this->request->getGet('parasiteControl');

            $livestock = $this->livestock->getLivestockByParasiteControl($id);
            $response = $this->animalParasiteControl->deleteAnimalParasiteControl($id);

            $livestockTagId = $livestock['livestock_tag_id'];
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $auditLog = (object) [
                'farmerId' => $userId,
                'livestockId' => $livestock['id'],
                'action' => "Delete",
                'title' => "Deleted Parasite Control Record",
                'description' => "Deleted Parasite Control Record of Livestock $livestockTagId",
                'entityAffected' => "Parasite Control",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            if(!$resultAudit){
                return $this->fail('Failed to record action', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->respond(['result' => $response], 200, 'Livestock Parasite Control Successfully Deleted');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to delete record', ResponseInterface::HTTP_BAD_REQUEST);
        }
    }

    public function getOverallAnimalParasiteControlCount(){
        try {
            $count = $this->animalParasiteControl->getOverallAnimalParasiteControlCount();

            return $this->respond(['count' => "$count"]);
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getOverallAnimalParasiteControlCountByUser($userId){
        try {
            $id = $this->request->getGet('parasiteControl');
            $count = $this->animalParasiteControl->getOverallAnimalParasiteControlCountByUserId($userId);

            return $this->respond(['count' => "$count"]);
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getParasiteControlCountLast4Months(){
        try {
            $data = $this->animalParasiteControl->getParasiteControlCountLast4Months();

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getTopAnimalTypeParasiteControlCount(){
        try {
            //code...
            $data = $this->animalParasiteControl->getTopAnimalTypeParasiteControlCount();

            return $this->respond($data);
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAdministrationMethodsCount(){
        try {
            //code...
            $data = $this->animalParasiteControl->getAdministrationMethodsCount();

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getParasiteControlCountByMonth(){
        try {
            //code...
            $data = $this->animalParasiteControl->getParasiteControlCountByMonth();

            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getParasiteControlCountByMonthWithForecast()
    {
        try {
            // Fetch the time series data from the database
            $data = $this->animalParasiteControl->getLivestockDewormingsCountByMonthTimeSeries();

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
                $parasiteControl = $this->animalParasiteControl->getParasiteControlCountByMonth();

                return $this->respond([
                    'original' => array_values($parasiteControl),
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
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

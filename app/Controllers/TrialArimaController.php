<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LivestockMortalityModel;
use App\Models\LivestockVaccinationModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Config\Services;
use App\Libraries\ArimaPredictionLibrary;

class TrialArimaController extends ResourceController
{
    private $vaccinations;

    private $arimaPrediction;

    public function __construct()
    {
        $this->vaccinations = new LivestockVaccinationModel();
        $this->arimaPrediction = new ArimaPredictionLibrary();
    }

    public function trialArima()
    {
        try {
            // Initialize cURL request service
            $client = Services::curlrequest();

            // Sample data and order for ARIMA model
            $data = [
                'data' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                'order' => [1, 1, 1]
            ];

            // Send POST request
            $response = $client->post('http://127.0.0.1:5000/trial', [
                'json' => $data
            ]);

            // Decode JSON response
            $result = json_decode($response->getBody(), true);

            // Return the result as a response
            return $this->respond($result);
        } catch (\Throwable $th) {
            // Log the error message and trace for debugging
            log_message('error', $th->getMessage());
            log_message('error', json_encode($th->getTrace()));

            // Return error response with proper status code
            return $this->respond([
                'error' => $th->getMessage(),
                'trace' => $th->getTrace()
            ]);
        }
    }

    public function trialArimaVaccination()
    {
        try {
            // Fetch the time series data from the database
            $data = $this->vaccinations->getLivestockVaccinationCountByMonthTimeSeries();

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
                return $this->fail($response['message'], $response['status']);
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

    private function sendToFlask($timeSeries, $steps)
    {
        $url = 'http://127.0.0.1:5000/api/arimavax';
        $client = \Config\Services::curlrequest();
        $payload = json_encode(['time_series' => $timeSeries, 'steps' => $steps]);

        try {
            $response = $client->post($url, [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => $payload
            ]);

            $status = $response->getStatusCode();
            $data = json_decode($response->getBody(), true);

            if ($status !== 200) {
                return [
                    'status' => $status,
                    'message' => $data['error'] ?? 'Unknown error'
                ];
            }

            return [
                'status' => 200,
                'data' => $data
            ];
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return [
                'status' => ResponseInterface::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ];
        }
    }

    public function trialDataFetching()
    {
        try {
            $mortalities = new LivestockMortalityModel();

            // Fetch the time series data from the database
            $data = $mortalities->getLivestockMortalityCountByMonthTimeSeries();

            if (empty($data)) {
                return $this->failNotFound('No data found');
            }

            // Extract the latest year from the data
            $latestYear = max(array_column($data, 'year'));

            // Filter the data for the latest year
            $filteredData = array_filter($data, function ($item) use ($latestYear) {
                return $item['year'] == $latestYear;
            });

            $timeSeries = array_column($filteredData, 'count');
            $lastMonth = max(array_column($filteredData, 'month'));

            // Calculate the number of steps for the forecast
            $steps = 12 - $lastMonth;

            // Send the filtered time series to the ARIMA model
            $response = $this->sendToFlask($timeSeries, $steps);

            if ($response['status'] !== 200) {
                // Specific handling for ARIMA "not enough data points" error
                // if ($response['message'] == 'Not enough data points to fit ARIMA model') {
                //     return $this->fail($response['message'], $response['status']);
                // }

                // // General error handling
                // return $this->fail($response['message'], $response['status']);
                return $this->respond([
                    'original' => array_values($filteredData),
                    'combined' => []
                ], 200);
            }

            $forecast = $response['data']['forecast'];

            // Combine the original data with the forecast
            $combinedData = $filteredData;
            $lastMonth = max(array_column($filteredData, 'month'));

            // Add forecasted values to the combined data
            for ($i = 0; $i < count($forecast); $i++) {
                $combinedData[] = [
                    'year' => $latestYear,
                    'month' => $lastMonth + $i + 1, // Assuming the forecast is for the next few months
                    'count' => $forecast[$i]
                ];
            }

            return $this->respond([
                'original' => array_values($filteredData),
                'combined' => array_values($combinedData)
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage());
            return [];
        }
    }
}

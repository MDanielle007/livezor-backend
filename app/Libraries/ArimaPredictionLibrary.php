<?php
namespace App\Libraries;

use CodeIgniter\HTTP\ResponseInterface;

class ArimaPredictionLibrary
{
    public function sendToFlask($timeSeries, $steps)
    {

        $url = getenv('ARIMA_URL');
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
}
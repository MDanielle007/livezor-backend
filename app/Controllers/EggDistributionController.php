<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EggDistributionModel;
use App\Models\FarmerAuditModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class EggDistributionController extends ResourceController
{
    private $eggDistributions;
    private $farmerAudit;
    public function __construct()
    {
        $this->eggDistributions = new EggDistributionModel();
        $this->farmerAudit = new FarmerAuditModel();
        helper('jwt');
    }

    public function getAllEggDistributions()
    {
        try {
            $data = $this->eggDistributions->getAllEggDistributions();
            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function getEggDistributionById($id)
    {
        try {
            $data = $this->eggDistributions->getEggDistributionById($id);
            return $this->respond($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertEggDistribution()
    {
        try {
            $data = $this->request->getJSON();

            $eggDistribution = $this->eggDistributions->insertEggDistribution($data);

            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $fullName = $data->firstName.' ' .$data->lastName;
            $auditLog = (object)[
                'livestockId' => $data->livestockId,
                'farmerId' => $userId,
                'action' => "Add",
                'title' => "Add New Egg Distribution",
                'description' => "Add New Egg Distribution to $fullName",
                'entityAffected' => "Egg Distribution",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);

            return $this->respond(['result' => $eggDistribution], 200, 'Egg Distribution Successfully Added');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to add egg distribution', ResponseInterface::HTTP_BAD_REQUEST);
        }
    }

    public function updateEggDistribution()
    {
        try {
            $data = $this->request->getJSON();

            $response = $this->eggDistributions->updateEggDistribution($data->id, $data);

            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $fullName = $data->recipientFirstName.' ' .$data->recipientLastName;
            $auditLog = (object)[
                'farmerId' => $userId,
                'action' => "Edit",
                'title' => "Updated Egg Distribution",
                'description' => "Updated Egg Distribution to $fullName",
                'entityAffected' => "Egg Distribution",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);

            return $this->respond(['result' => $response], 200, 'Egg Distribution Successfully Updated');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to update egg distribution', ResponseInterface::HTTP_BAD_REQUEST);
        }
    }

    public function updateEggDistributionRecordStatus($id)
    {
        try {
            $data = $this->request->getJSON();
            $response = $this->eggDistributions->updateEggDistributionRecordStatus($id, $data->recordStatus);
            return $this->respond(['result' => $response, 'message' => 'Egg Distribution Successfully Added'], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function deleteEggDistribution()
    {
        try {
            $id = $this->request->getGet('eggDistribution');

            $eggDistribution = $this->eggDistributions->getEggDistributionById($id);

            $response = $this->eggDistributions->deleteEggDistribution($id);

            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);
            $fullName = $eggDistribution['recipientFirstName'].' ' .$eggDistribution['recipientLastName'];
            $date = $eggDistribution['dateOfDistribution'];
            $auditLog = (object)[
                'farmerId' => $userId,
                'action' => "Delete",
                'title' => "Deleted Egg Distribution",
                'description' => "Deleted Egg Distribution to $fullName in $date",
                'entityAffected' => "Egg Distribution",
            ];

            $resultAudit = $this->farmerAudit->insertAuditTrailLog($auditLog);
            return $this->respond(['result' => $response], 200, 'Egg Distribution Successfully Added');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to delete record', ResponseInterface::HTTP_BAD_REQUEST);
        }
    }
}

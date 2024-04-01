<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FarmerAuditModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class AuditTrailController extends ResourceController
{
    private $auditTrails;

    public function __construct()
    {
        $this->auditTrails = new FarmerAuditModel();
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

    public function getFarmerAuditTrailLogs($id)
    {
        try {
            $auditTrails = $this->auditTrails->getFarmerAuditTrailLogs($id);
            return $this->respond($auditTrails);
        } catch (\Throwable $th) {
            //throw $th;
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

}

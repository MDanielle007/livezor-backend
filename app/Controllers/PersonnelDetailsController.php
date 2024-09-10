<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PersonnelDetailsModel;
use App\Models\PersonnelPositionsModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class PersonnelDetailsController extends ResourceController
{
    private $personnelDetails;
    private $personnelPositions;

    public function __construct()
    {
        $this->personnelDetails = new PersonnelDetailsModel();
        $this->personnelPositions = new PersonnelPositionsModel();
        helper('jwt');
    }

    public function getAllPersonnelDetails()
    {
        try {
            $personnelDetails = $this->personnelDetails->getAllPersonnelDetails();
            return $this->respond($personnelDetails);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getPersonnelDetailById($id)
    {
        try {
            $personnelDetails = $this->personnelDetails->getPersonnelDetail($id);
            return $this->respond($personnelDetails);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getPersonnelDetailByUserId()
    {
        try {
            $header = $this->request->getHeader("Authorization");
            $userId = getTokenUserId($header);

            $personnelDetails = $this->personnelDetails->getPersonnelDetailByUserId($userId);
            return $this->respond($personnelDetails);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insertPersonnelDetails()
    {
        try {
            $data = $this->request->getJSON();

            $result = $this->personnelDetails->insertPersonnelDetails($data);

            return $this->respond(['message' => 'Personnel Details inserted successfully', 'result' => $result]);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respond(['error' => $th->getMessage()]);
        }
    }

    public function updatePersonnelDetails()
    {
        try {
            $data = $this->request->getJSON();
            $header = $this->request->getHeader("Authorization");
            $data->userId = getTokenUserId($header);

            $position = $data->position;

            if($position != ''){
                if(is_string($position)){
                    $data->positionId = $this->personnelPositions->insertPosition((object)[
                        'positionName' => $position,
                        'departmentId' => $data->departmentId
                    ]);
                }else{
                    $data->positionId = $position->code;
                }
            }

            $result = $this->personnelDetails->updatePersonnelDetails($data->id, $data);

            return $this->respond(['result' => $result], 200, 'Personnel Details Updated successfully');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to update data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deletePersonnelDetails($id){
        try {
            $result = $this->personnelDetails->deletePersonnelDetails($id);

            return $this->respond(['message' => 'Personnel Details deleted successfully', 'result' => $result]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

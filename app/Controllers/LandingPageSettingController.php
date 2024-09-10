<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LandingPageAgriAnimalsModel;
use App\Models\LandingPageCarouselImagesModel;
use App\Models\LandingPageContactInformationModel;
use App\Models\LandingPageMainDisplayImagesModel;
use App\Models\LandingPageMainDisplayTextModel;
use App\Models\LandingPageOrganizationChartModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;


class LandingPageSettingController extends ResourceController
{
    private $mainDisplayText;
    private $mainDisplayImages;
    private $agriAnimals;
    private $carouselImages;
    private $organizationChart;
    private $contactInfo;
    public function __construct()
    {
        $this->mainDisplayText = new LandingPageMainDisplayTextModel();
        $this->mainDisplayImages = new LandingPageMainDisplayImagesModel();
        $this->agriAnimals = new LandingPageAgriAnimalsModel();
        $this->carouselImages = new LandingPageCarouselImagesModel();
        $this->organizationChart = new LandingPageOrganizationChartModel();
        $this->contactInfo = new LandingPageContactInformationModel();
    }

    public function getSettingsData()
    {
        try {
            $mainDisplayTextData = $this->mainDisplayText->getMainDisplayTexts();

            $data = [
                'mainDisplayText' => $mainDisplayTextData
            ];

            return $this->respond($data, 200);
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getImage($filename)
    {
        try {
            $filePath = WRITEPATH . 'uploads/landingpage/' . $filename;

            if (file_exists($filePath)) {
                // Determine the file MIME type
                $mimeType = mime_content_type($filePath);

                // Set the headers for file download
                return $this->response
                    ->setHeader('Content-Type', $mimeType)
                    ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
                    ->setBody(file_get_contents($filePath));
            } else {
                return $this->failNotFound('Image not found.');
            }
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->failNotFound('Image not found.');
        }
    }


    public function getLandingPageMainDisplayTexts() // for landing page
    {
        try {
            $data = $this->mainDisplayText->getLandingPageMainDisplayTexts();

            return $this->respond($data, 200);
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSettingsDisplayTexts()
    {
        try {
            $data = $this->mainDisplayText->getSettingsDisplayTexts();

            return $this->respond($data, 200);
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateSettingsDisplayText()
    {
        try {
            $data = $this->request->getJSON();
            $result = $this->mainDisplayText->updateLandingPageMainDisplayTexts($data->id, $data);

            if (!$result) {
                return $this->fail($this->mainDisplayText->errors());
            }

            return $this->respond(['result' => $result], 200, 'Landing Page Main Display Text Successfully Updated');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to update data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getLandingPageMainDisplayImages() // for landing page
    {
        try {
            //code...
            $data = $this->mainDisplayImages->getLandingPageMainDisplayImages();

            foreach ($data as &$images) {
                $images['imageFilename'] = base_url() . 'uploads/' . $images['imageFilename'];
            }

            return $this->respond($data, 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSettingDisplayImages()
    {
        try {
            //code...
            $data = $this->mainDisplayImages->getSettingDisplayImages();

            foreach ($data as &$images) {
                $images['imageFilename'] = base_url() . 'uploads/' . $images['imageFilename'];
            }

            return $this->respond($data, 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function insertLandingPageMainDisplayImages()
    {
        try {
            //code...
            $orderNum = $this->request->getPost('orderNum');
            $file = $this->request->getFile('displayImage');
            $newName = $file->getRandomName();
            if ($file->isValid() && !$file->hasMoved()) {
                $file->move('./uploads', $newName);
            } else {
                return $this->fail('Invalid file upload');
            }

            $result = $this->mainDisplayImages->insertLandingPageMainDisplayImages((object) [
                'imageFilename' => $newName,
                'orderNum' => $orderNum,
            ]);

            if (!$result) {
                return $this->fail($this->mainDisplayImages->errors());
            }

            return $this->respond(['result' => $result], 200, 'Landing Page Main Display Images Successfully Inserted');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to insert data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateLandingPageMainDisplayImages()
    {
        try {
            //code...
            $data = $this->request->getJSON();

            $imagesOrder = $data->imagesOrder;

            $result = null;
            foreach ($imagesOrder as $image) {
                $result = $this->mainDisplayImages->updateLandingPageMainDisplayImages($image->id, $image);
                if (!$result) {
                    return $this->fail($this->mainDisplayImages->errors());
                }
            }

            return $this->respond(['result' => $result], 200, 'Landing Page Main Display Images Successfully Updated');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to update data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteLandingPageMainDisplayImages()
    {
        try {
            $id = $this->request->getGet('displayImage');

            $result = $this->mainDisplayImages->deleteLandingPageMainDisplayImages($id);

            if (!$result) {
                return $this->fail($this->mainDisplayImages->errors());
            }

            return $this->respond(['result' => $result], 200, 'Landing Page Main Display Images Successfully Deleted');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to delete data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getLandingPageAgriAnimals() // for landing page
    {
        try {
            //code...
            $data = $this->agriAnimals->getLandingPageAgriAnimals();

            return $this->respond($data, 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSettingsAgriAnimals()
    {
        try {
            //code...
            $data = $this->agriAnimals->getSettingsAgriAnimals();

            return $this->respond($data, 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function insertLandingPageAgriAnimals()
    {
        try {
            //code...
            $data = $this->request->getJSON();

            $result = $this->agriAnimals->insertLandingPageAgriAnimals($data);

            if (!$result) {
                return $this->fail($this->agriAnimals->errors());
            }

            return $this->respond(['result' => $result], 200, 'Landing Page Agri Animals Successfully Inserted');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to insert data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateLandingPageAgriAnimals()
    {
        try {
            $data = $this->request->getJSON();

            $result = $this->agriAnimals->updateLandingPageAgriAnimals($data->id, $data);

            if (!$result) {
                return $this->fail($this->agriAnimals->errors());
            }

            return $this->respond(['result' => $result], 200, 'Landing Page Agri Animals Successfully Updated');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to update data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteLandingPageAgriAnimals()
    {
        try {
            $id = $this->request->getGet('animal');

            $result = $this->agriAnimals->deleteLandingPageAgriAnimals($id);

            if (!$result) {
                return $this->fail($this->agriAnimals->errors());
            }

            return $this->respond(['result' => $result], 200, 'Landing Page Agri Animals Successfully Deleted');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to delete data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getLandingPageCarouselImages()
    {
        try {
            //code...
            $data = $this->carouselImages->getLandingPageCarouselImages();

            return $this->respond($data, 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSettingCarouselImages()
    {
        try {
            //code...
            $data = $this->carouselImages->getSettingCarouselImages();

            return $this->respond($data, 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function insertLandingPageCarouselImages()
    {
        try {
            //code...
            $title = $this->request->getPost('title');
            $subtitle = $this->request->getPost('subtitle');

            $file = $this->request->getFile('image');
            $newName = "";

            $uploadPath = WRITEPATH . 'uploads/landingpage/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true); // Create directory if it doesn't exist
            }

            if (isset($file)) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move($uploadPath, $newName);
                    // You can also save the file path to your database
                } else {
                    return $this->fail('Invalid file upload');
                }
            }

            $result = $this->carouselImages->insertLandingPageCarouselImages((object) [
                'title' => $title,
                'subtitle' => $subtitle,
                'image' => $newName
            ]);

            if (!$result) {
                return $this->fail($this->carouselImages->errors());
            }

            return $this->respond(['result' => $result], 200, 'Landing Page Carousel Image Successfully Inserted');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to insert data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateLandingPageCarouselImages()
    {
        try {
            //code...
            $data = $this->request->getJSON();

            $result = $this->carouselImages->updateLandingPageCarouselImages($data->id, $data);

            if (!$result) {
                return $this->fail($this->carouselImages->errors());
            }

            return $this->respond(['result' => $result], 200, 'Landing Page Carousel Image Successfully Update');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to update data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteLandingPageCarouselImages()
    {
        try {
            //code...
            $id = $this->request->getGet('image');

            $result = $this->carouselImages->deleteLandingPageCarouselImages($id);

            if (!$result) {
                return $this->fail($this->carouselImages->errors());
            }

            return $this->respond(['result' => $result], 200, 'Landing Page Carousel Image Successfully Deleted');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to delete data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getLandingPageOrganizationCharts()
    {
        try {
            //code...
            $data = $this->organizationChart->getLandingPageOrganizationCharts();

            return $this->respond($data, 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSettingOrganizationCharts()
    {
        try {
            //code...
            $data = $this->organizationChart->getSettingOrganizationCharts();

            return $this->respond($data, 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateLandingPageOrgChart()
    {
        try {
            //code...
            $id = $this->request->getPost('id');
            $name = $this->request->getPost('name');
            $subtitle = $this->request->getPost('subtitle');


            $file = $this->request->getFile('newImage');
            $newName = "";

            $uploadPath = WRITEPATH . 'uploads/landingpage/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true); // Create directory if it doesn't exist
            }

            if (isset($file)) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move($uploadPath, $newName);
                    // You can also save the file path to your database
                } else {
                    return $this->fail('Invalid file upload');
                }
            }

            $result = $this->organizationChart->updateLandingPageOrgChart($id, (object) [
                'id' => $id,
                'name' => $name,
                'subtitle' => $subtitle,
                'image' => $newName
            ]);

            if (!$result) {
                return $this->fail($this->carouselImages->errors());
            }

            return $this->respond(['result' => $result], 200, 'Landing Page Organizational Chart Successfully Updated');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to update data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getContactInformation()
    {
        try {
            $email = $this->contactInfo->getSetting('email');
            $location = $this->contactInfo->getSetting('location');
            $availbleDays = $this->contactInfo->getSetting('availableDays');
            $openHour = $this->contactInfo->getSetting('openHour');
            $closedHour = $this->contactInfo->getSetting('closedHour');
            $locationMap = $this->contactInfo->getSetting('locationMap');

            //code...
            $data = [
                'email' => $email,
                'location' => $location,
                'availableDays' => is_array($availbleDays) ? $availbleDays : json_decode($availbleDays, true),
                'openHour' => $openHour,
                'closedHour' => $closedHour,
                'locationMap' => $locationMap
            ];

            return $this->respond($data, 200);
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to fetch data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateContactInformation()
    {
        try {
            $data = $this->request->getJSON();

            $result = $this->contactInfo->saveSetting($data->setting, $data->value);

            if (!$result) {
                return $this->fail($this->contactInfo->errors());
            }

            return $this->respond(['result' => $result], 200, 'Landing Page Contact Information Successfully Updated');
        } catch (\Throwable $th) {
            //throw $th;
            log_message('error', $th->getMessage() . ": " . $th->getLine());
            log_message('error', json_encode($th->getTrace()));
            return $this->fail('Failed to ipdate data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

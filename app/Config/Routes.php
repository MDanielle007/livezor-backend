<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// API routes
$routes->group('api', function ($routes) {
    $routes->post('login', 'AuthController::loginAuth');
    $routes->put('logout', 'AuthController::userLogOut');
    $routes->post('password-reset', 'AuthController::requestPasswordLink');
    $routes->put('password-reset', 'AuthController::requestPasswordReset', ['filter' => 'resetPassFilter']);

    // Livestocks endpoint routes
    $routes->group('livestock', ['filter' => 'authFilter'] ,function ($routes) {
        // Livestock Types endpoint routes
        $routes->get('all-livestock-types', 'LivestockTypesController::getLivestockTypes');
        $routes->get('all-livestock-types-idnames', 'LivestockTypesController::getLivestockTypesIdAndName');
        $routes->get('livestock-type/(:any)', 'LivestockTypesController::getLivestockType/$1');

        // Poultry Types endpoints routes
        $routes->get('all-poultry-types', 'PoultryTypeController::getPoultryTypes');
        $routes->get('all-poultry-types-idnames', 'PoultryTypeController::getPoultryTypesIdAndName');
        $routes->get('poultry-type/(:any)', 'PoultryTypeController::getPoultryType/$1');

        // Livestock/Poultry Breeds endpoint routes
        $routes->get('all-livestock-breeds', 'LivestockBreedsController::getLivestockBreeds');
        $routes->get('all-poultry-breeds', 'LivestockBreedsController::getPoultryBreeds');
        $routes->get('all-breeds-idnames', 'LivestockBreedsController::getBreedIdAndName');
        // $routes->get('all-livestock-breeds-idnames', 'LivestockBreedsController::getLivestockBreedIdAndName');
        $routes->get('all-livestock-breeds-idnames/(:any)', 'LivestockBreedsController::getLivestockBreedIdAndNameById/$1');
        $routes->get('livestock-breed/(:any)', 'LivestockBreedsController::getLivestockBreed/$1');

        // Livestock Age Classifications endpoint routes
        $routes->get('all-age-classes', 'LivestockAgeClassController::getAnimalAgeClasses');
        $routes->get('all-livestock-age-classes', 'LivestockAgeClassController::getLivestockAgeClasses');
        $routes->get('all-livestock-age-classes-base', 'LivestockAgeClassController::getLivestockAgeClassBase');
        $routes->get('all-poultry-age-classes', 'LivestockAgeClassController::getPoultryAgeClasses');
        $routes->get('all-livestock-ageclass-idnames', 'LivestockAgeClassController::getLivestockAgeClassIdAndName');
        $routes->get('all-livestock-ageclass-idnames/(:any)', 'LivestockAgeClassController::getLivestockAgeClassIdAndNameById/$1');
        $routes->get('livestock-age-class/(:any)', 'LivestockAgeClassController::getLivestockAgeClass/$1');
    });

    // Admin endpoint routes
    $routes->group('admin', ['filter' => 'authAdminFilter'], function ($routes) {
        $routes->get('get-user-image/(:any)', 'UserController::getUserImage/$1');
        $routes->get('get-mortality-image/(:any)', 'UserController::getMortalityImage/$1');

        // Admin Dashboard routes
        $routes->get('livestock-type-count', 'LivestocksController::getAllLivestockTypeCount');
        $routes->get('livestock-type-ageclass-count', 'LivestocksController::getAllLivestockTypeAgeClassCount');
        $routes->get('livestock-healthstat-count', 'LivestocksController::getLivestockHealthStatusesCount');
        $routes->get('vaccination-percent-current-month', 'LivestockVaccinationsController::getVaccinationPercetageInCurrentMonth');
        $routes->get('livestock-breeding-eligible-percent', 'LivestocksController::getAllBreedingEligibleLivestocks');
        $routes->get('livestock-count', 'LivestocksController::getAllLivestockCountMonitored');
        $routes->get('farmer-count', 'UserController::getFarmerCount');
        $routes->get('livestock-mortalities-percent-last-month', 'LivestockMortalityController::getLivestockMortalitiesCountLastMonth');
        $routes->get('livestock-distribution-month', 'LivestocksController::getLivestockCountByMonthAndType');
        $routes->get('top-vaccine', 'LivestockVaccinationsController::getTopVaccines');
        $routes->get('vaccination-distribution-month', 'LivestockVaccinationsController::getVaccinationCountByMonth');
        $routes->get('vaccination-distribution-month-forecast', 'LivestockVaccinationsController::getVaccinationCountByMonthWithForecast');
        $routes->get('vaccination-poultry-distribution-month', 'LivestockVaccinationsController::getPoultryVaccinationCountByMonth');
        $routes->get('top-mortality', 'LivestockMortalityController::getTopMortalityCause');
        $routes->get('mortality-distribution-month', 'LivestockMortalityController::getMortalityCountByMonth');
        $routes->get('mortality-distribution-month-forecast', 'LivestockMortalityController::getMortalityCountByMonthWithForecast');
        $routes->get('livestock-vaccination-count', 'LivestockVaccinationsController::getOverallLivestockVaccinationCount');
        $routes->get('livestock-deworming-count', 'LivestockDewormingController::getOverallLivestockDewormingCount');
        $routes->get('deworming-distribution-month', 'LivestockDewormingController::getDewormingCountByMonth');
        $routes->get('deworming-distribution-month-forecast', 'LivestockDewormingController::getDewormingCountByMonthWithForecast');
        $routes->get('livestock-vaccination-count/(:any)', 'LivestockVaccinationsController::getFarmerOverallLivestockVaccinationCount/$1');
        $routes->get('livestock-vaccination-count-current-month', 'LivestockVaccinationsController::getLivestockVaccinationCountInCurrentMonth');
        $routes->get('livestock-mortalities-count', 'LivestockMortalityController::getOverallLivestockMortalitiesCount');
        $routes->get('livestock-mortalities-count/(:any)', 'LivestockMortalityController::getFarmerOverallLivestockMortalitiesCount/$1');
        $routes->get('livestock-deworming-count/(:any)', 'LivestockDewormingController::getFarmerOverallLivestockDewormingCount/$1');
        $routes->get('livestock-count-city', 'LivestocksController::getLivestockCountAllCity');
        $routes->get('livestock-count-city/(:any)', 'LivestocksController::getLivestockCountAllCity/$1');
        $routes->get('livestock-production-monthly', 'LivestocksController::getLivestockProductionCountByMonthInCurrentYear');
        $routes->get('livestock-type-count-city/(:any)', 'LivestocksController::getLivestockTypeCountAllCity/$1');
        $routes->get('livestock-breed-count-city/(:any)', 'LivestocksController::getLivestockBreedCountAllCity/$1');
        $routes->get('livestock-ageclass-count-city/(:any)', 'LivestocksController::getLivestockAgeClassCountAllCity/$1');
        $routes->get('livestock-type-count-monitoring', 'LivestocksController::getLivestockTypeCountMonitoring');
        $routes->get('livestock-breed-count-monitoring', 'LivestocksController::getLivestockBreedCountMonitoring');
        $routes->get('livestock-ageclass-count-monitoring', 'LivestocksController::getLivestockAgeCountMonitoring');
        
        // User Management endpoint routes
        $routes->post('upload', 'UserController::uploadUserImage');
        $routes->post('register-user', 'UserController::registerUser');
        $routes->get('all-users', 'UserController::getAllUsers');
        $routes->get('get-user-info/(:any)', 'UserController::getUser/$1');
        $routes->put('update-user-profile/(:any)', 'UserController::updateUser/$1');
        $routes->put('update-user-personal-info/(:any)', 'UserController::updateUserPersonalInfo/$1'); // not used
        $routes->put('update-user-account-info/(:any)', 'UserController::updateUserAccountInfo/$1');
        $routes->put('update-user-password/(:any)', 'UserController::updateUserPassword/$1');
        $routes->put('update-user-record-stat/(:any)', 'UserController::updateUserRecordStatus/$1');
        $routes->delete('delete-user/(:any)', 'UserController::deleteUser/$1');
        $routes->get('get-farmers-basic', 'UserController::getAllFarmersBasicInfo');
        $routes->get('get-all-farmers-type-count', 'UserController::getAllFarmerLivestockTypeCount');
        $routes->get('get-all-farmers-type-count-by-address', 'UserController::getAllFarmerLivestockTypeCountByAddress');

        $routes->get('get-admin-tokens', 'UserController::getAllAdminFirebaseToken');
        $routes->get('get-farmer-tokens', 'UserController::getAllFarmerFirebaseToken');
        $routes->get('get-user-tokens/(:any)', 'UserController::getUserFirebaseToken/$1');

        // Personnel endpoints routes
        $routes->get('all-personnel-details', 'PersonnelDetailsController::getAllPersonnelDetails');
        $routes->get('all-personnel-details/(:any)', 'PersonnelDetailsController::getPersonnelDetailById/$1');
        $routes->get('all-personnel-details-user', 'PersonnelDetailsController::getPersonnelDetailByUserId');
        $routes->post('add-personnel-details', 'PersonnelDetailsController::insertPersonnelDetails');
        $routes->put('update-personnel-details', 'PersonnelDetailsController::updatePersonnelDetails');
        $routes->delete('delete-personnel-details-record/(:any)', 'PersonnelDetailsController::deletePersonnelDetails/$1');

        // Personnel positions endpoints routes
        $routes->get('all-positions', 'PersonnelPositionsController::getPersonnelPositions');
        $routes->get('all-position/(:any)', 'PersonnelPositionsController::getPersonnelPosition/$1');
        $routes->get('all-position-department/(:any)', 'PersonnelPositionsController::getPersonnelPositionByDepartmentId/$1');
        $routes->post('add-position', 'PersonnelPositionsController::insertPersonnelPosition');
        $routes->put('update-position/(:any)', 'PersonnelPositionsController::updatePersonnelPosition/$1');
        $routes->delete('delete-position-record/(:any)', 'PersonnelPositionsController::deletePersonnelPosition/$1');

        // Personnel departments endpoints routes
        $routes->get('all-departments', 'PersonnelDepartmentsController::getAllDepartments');
        $routes->get('all-department/(:any)', 'PersonnelDepartmentsController::getDepartment/$1');
        $routes->post('add-department', 'PersonnelDepartmentsController::insertDepartment');
        $routes->put('update-department/(:any)', 'PersonnelDepartmentsController::updateDepartment/$1');
        $routes->delete('delete-department-record/(:any)', 'PersonnelDepartmentsController::deleteDepartment/$1');
        
        // Livestock endpoint routes
        $routes->get('all-livestock', 'LivestocksController::getAllLivestocks');
        $routes->get('all-farmer-livestocks/(:any)', 'LivestocksController::getFarmerAllLivestocks/$1');
        $routes->get('livestock/(:any)', 'LivestocksController::getLivestock/$1');
        $routes->post('add-livestock', 'LivestocksController::addLivestock');
        $routes->post('add-farmer-livestock', 'LivestocksController::addFarmerLivestock');
        $routes->post('add-livestock-multiple', 'LivestocksController::addMultipleFarmerLivestock');
        $routes->put('update-livestock', 'LivestocksController::updateLivestock');
        $routes->put('livestock-health/(:any)', 'LivestocksController::updateLivestockHealthStatus/$1');
        $routes->put('livestock-record-stat/(:any)', '::updateLivestockRecordStatus/$1');
        $routes->delete('delete-livestock-record', 'LivestocksController::deleteLivestock');
        $routes->get('all-livestocks-tagid', 'LivestocksController::getAllLivestockTagIDs');
        $routes->get('livestock-production-year', 'LivestocksController::getLivestockProductionCountWholeYear');
        $routes->get('livestock-type-production-year', 'LivestocksController::getLivestockProductionWholeYear');
        $routes->get('livestock-type-production-year/(:any)/(:any)', 'LivestocksController::getLivestockProductionSelectedYear/$1/$2');
        $routes->get('get-livestock-report-data', 'LivestocksController::getLivestockReportData');
        $routes->post('import-livestock-data','LivestocksController::importLivestockData');
        $routes->get('get-livestock-freport', 'LivestocksController::getLivestockRecordsForReport');
        $routes->get('get-livestock-disprod-freport', 'LivestocksController::getLivestockDisProdForReport');

        $routes->post('insert-livestock-type', 'LivestockTypesController::insertLivestockType');
        $routes->put('update-livestock-type/(:any)', 'LivestockTypesController::updateLivestockType/$1');
        $routes->delete('delete-livestock-type', 'LivestockTypesController::deleteLivestockType');

        $routes->post('insert-poultry-type', 'PoultryTypeController::insertPoultryType');
        $routes->put('update-poultry-type/(:any)', 'PoultryTypeController::updatePoultryType/$1');
        $routes->delete('delete-poultry-type', 'PoultryTypeController::deletePoultryType');

        $routes->post('insert-livestock-breed', 'LivestockBreedsController::insertLivestockBreed');
        $routes->put('update-livestock-breed/(:any)', 'LivestockBreedsController::updateLivestockBreed/$1');
        $routes->delete('delete-livestock-breed', 'LivestockBreedsController::deleteLivestockBreed');

        $routes->post('insert-livestock-age-class', 'LivestockAgeClassController::insertLivestockAgeClass');
        $routes->put('update-livestock-age-class/(:any)', 'LivestockAgeClassController::updateLivestockAgeClass/$1');
        $routes->delete('delete-livestock-age-class', 'LivestockAgeClassController::deleteLivestockAgeClass');


        // Poultry endpoints routes
        $routes->get('all-poultries', 'PoultryController::getAllPoultries');
        $routes->get('all-farmer-poultries/(:any)', 'PoultryController::getFarmerAllPoultries/$1');
        $routes->get('poultry/(:any)', 'PoultryController::getPoultry/$1');
        $routes->post('add-poultry', 'PoultryController::addPoultry');
        $routes->post('add-farmer-poultry', 'PoultryController::addFarmerPoultry');
        $routes->post('add-poultry-multiple', 'PoultryController::addMultipleFarmerPoultries');
        $routes->put('update-poultry', 'PoultryController::updatePoultry');
        $routes->put('poultry-health/(:any)', 'PoultryController::updatePoultryHealthStatus/$1');
        $routes->put('poultry-record-stat/(:any)', 'PoultryController::updatePoultryRecordStatus/$1');
        $routes->delete('delete-poultry-record', 'PoultryController::deletePoultry');
        $routes->get('poultry-type-ageclass-count', 'PoultryController::getAllPoultryTypeAgeClassCount');
        $routes->get('poultry-type-count', 'PoultryController::getAllPoultryTypeCount');
        $routes->get('poultry-count', 'PoultryController::getAllPoultryCountMonitored');
        $routes->get('all-poultry-tagid/(:any)', 'PoultryController::getAllFarmerPoultryTagIDs/$1');
        $routes->get('poultry-distribution-month', 'PoultryController::getPoultryCountByMonthAndType');
        $routes->get('poultry-healthstat-count', 'PoultryController::getPoultryHealthStatusesCount');
        $routes->get('poultry-count-city/(:any)', 'PoultryController::getAllPoultryTypeCountByCity/$1');
        $routes->get('poultry-count-city', 'PoultryController::getPoultryCountAllCity');
        $routes->get('poultry-type-count-city/(:any)', 'PoultryController::getPoultryTypeCountAllCity/$1');
        $routes->get('livestock-production-year', 'PoultryController::getLivestockProductionCountWholeYear');
        $routes->get('get-poultry-report-data', 'PoultryController::getPoultryReportData');
        $routes->get('get-poultry-freport', 'LivestocksController::getLivestockRecordsForReport');

        // Livestock Vaccination endpoint routes
        $routes->get('all-vaccinations', 'LivestockVaccinationsController::getAllLivestockVaccinations');
        $routes->get('all-poultry-vaccinations', 'LivestockVaccinationsController::getAllPoultryVaccinations');
        $routes->get('all-farmer-livestock-vaccinations/(:any)', 'LivestockVaccinationsController::getAllUserCompleteLivestockVaccinations/$1');
        $routes->get('all-livestock-livestock-vaccinations', 'LivestockVaccinationsController::getLivestockVaccinationByLivestock');
        $routes->get('livestock-vaccination/(:any)', 'LivestockVaccinationsController::getLivestockVaccination/$1');
        $routes->post('add-livestock-vaccination', 'LivestockVaccinationsController::insertLivestockVaccination');
        $routes->post('add-livestock-vaccination-multiple', 'LivestockVaccinationsController::insertMultipleLivestockVaccination');
        $routes->put('update-livestock-vaccination', 'LivestockVaccinationsController::updateLivestockVaccination');
        $routes->put('update-livestock-vaccination-record-stat/(:any)', 'LivestockVaccinationsController::updateLivestockVaccinationRecordStatus/$1');
        $routes->delete('delete-livestock-vaccination', 'LivestockVaccinationsController::deleteLivestockVaccination');
        $routes->get('all-vaccinations-4months', 'LivestockVaccinationsController::getVaccinationCountLast4Months');
        $routes->get('all-vaccinations-year', 'LivestockVaccinationsController::getVaccinationCountWholeYear');
        $routes->get('get-livestock-vaccinations-report-data', 'LivestockVaccinationsController::getLivestockVaccinationReportData');
        $routes->get('get-poultry-vaccinations-report-data', 'LivestockVaccinationsController::getPoultryVaccinationReportData');
        $routes->get('get-livestock-vaccinations-freport', 'LivestockVaccinationsController::getLivestockVaccinationsForReport');
        $routes->get('get-poultry-vaccinations-freport', 'LivestockVaccinationsController::getPoultryVaccinationsForReport');


        // Livestock Deworming endpoint routes
        $routes->get('all-livestock-dewormings', 'LivestockDewormingController::getAllLivestockDewormings');
        $routes->get('all-farmer-livestock-dewormings/(:any)', 'LivestockDewormingController::getAllFarmerLivestockDewormings'); // not used
        $routes->get('all-livestock-livestock-dewormings', 'LivestockDewormingController::getLivestockDewormingByLivestock');
        $routes->get('livestock-deworming/(:any)', 'LivestockDewormingController::getLivestockDeworming/$1');
        $routes->post('add-livestock-deworming', 'LivestockDewormingController::insertLivestockDeworming');
        $routes->post('add-livestock-deworming-multiple', 'LivestockDewormingController::insertMultipleLivestockDeworming');
        $routes->put('update-livestock-deworming', 'LivestockDewormingController::updateLivestockDeworming');
        $routes->put('update-livestock-deworming-record-stat/(:any)', 'LivestockDewormingController::updateLivestockDewormingRecordStatus/$1');
        $routes->delete('delete-livestock-deworming', 'LivestockDewormingController::deleteLivestockDewormingRecord');
        $routes->get('all-deworming-4months', 'LivestockDewormingController::getDewormingCountLast4Months');
        $routes->get('livestock-type-deworming-count', 'LivestockDewormingController::getTopLivestockTypeDewormedCount');
        $routes->get('deworming-administration-method-count', 'LivestockDewormingController::getAdministrationMethodsCount');
        $routes->get('get-livestock-dewormings-report-data', 'LivestockDewormingController::getLivestockDewormingReportData');
        $routes->get('get-livestock-dewormings-freport', 'LivestockDewormingController::getLivestockDewormingsForReport');

        // Livestock Parasite Control endpoint routes
        $routes->get('all-parasite-control', 'AnimalParasiteControlController::getAllAnimalParasiteControls');
        $routes->get('all-user-parasite-control', 'AnimalParasiteControlController::getAllAnimalParasiteControlByUser'); // not used
        $routes->get('all-animal-parasite-control', 'AnimalParasiteControlController::getAllAnimalParasiteControlByAnimal');
        $routes->post('add-parasite-control', 'AnimalParasiteControlController::insertParasiteControl');
        $routes->post('add-parasite-control-multiple', 'AnimalParasiteControlController::insertMultipleParasiteControl');
        $routes->put('update-parasite-control', 'AnimalParasiteControlController::updateParasiteControl');
        $routes->delete('delete-parasite-control', 'AnimalParasiteControlController::deleteParasiteControl');
        $routes->get('parasite-control-count', 'AnimalParasiteControlController::getOverallAnimalParasiteControlCount');
        $routes->get('parasite-control-count/(:any)', 'AnimalParasiteControlController::getOverallAnimalParasiteControlCountByUser/$1');
        $routes->get('all-parasite-control-4months', 'AnimalParasiteControlController::getParasiteControlCountLast4Months');
        $routes->get('animal-type-parasite-control-count', 'AnimalParasiteControlController::getTopAnimalTypeParasiteControlCount');
        $routes->get('parasite-control-method-count', 'AnimalParasiteControlController::getAdministrationMethodsCount');
        $routes->get('parasite-control-distribution-month', 'AnimalParasiteControlController::getParasiteControlCountByMonth');
        $routes->get('parasite-control-distribution-month-forecast', 'AnimalParasiteControlController::getParasiteControlCountByMonthWithForecast');
        // $routes->get('get-livestock-dewormings-report-data', 'AnimalParasiteControlController::getLivestockDewormingReportData');
        // $routes->get('get-livestock-dewormings-freport', 'AnimalParasiteControlController::getLivestockDewormingsForReport');

        // Livestock Breedings endpoint routes
        $routes->get('all-livestock-breedings', 'LivestockBreedingsController::getAllLivestockBreedings'); 
        $routes->get('all-farmer-livestock-breedings/(:any)', 'LivestockBreedingsController::getAllFarmerLivestockBreedings/$1'); // not used
        $routes->get('all-livestock-livestock-breedings', 'LivestockBreedingsController::getAllLivestockBreedingsByLivestock');
        $routes->get('livestock-breeding/(:any)', 'LivestockBreedingsController::getLivestockBreeding/$1');
        $routes->post('add-livestock-breeding', 'LivestockBreedingsController::insertLivestockBreeding');
        $routes->put('update-livestock-breeding', 'LivestockBreedingsController::updateLivestockBreeding');
        $routes->put('update-livestock-breeding-record-stat/(:any)', 'LivestockBreedingsController::updateLivestockBreedingRecordStatus/$1');
        $routes->delete('delete-livestock-breeding', 'LivestockBreedingsController::deleteLivestockBreeding');
        $routes->get('get-all-breeding-parent-offspring', 'LivestockBreedingsController::getAllBreedingParentOffspringData');
        $routes->post('successful-livestock-breeding', 'LivestockBreedingsController::addSuccessfulLivestockBreeding');
        $routes->get('livestock-breeding-count', 'LivestockBreedingsController::getOverallLivestockBreedingCount');
        $routes->get('livestock-breeding-count-current-year', 'LivestockBreedingsController::getOverallLivestockBreedingCountInCurrentYear');
        $routes->get('livestock-breeding-success-percent', 'LivestockBreedingsController::getLivestockBreedingSuccessPercentage');
        $routes->get('livestock-breeding-results-count', 'LivestockBreedingsController::getLivestockBreedingResultsCount');
        $routes->get('all-breeding-4months', 'LivestockBreedingsController::getBreedingsCountLast4Months');
        $routes->get('livestock-type-breeding-count', 'LivestockBreedingsController::getLivestockTypeBreedingsCount');
        $routes->get('breeding-distribution-month', 'LivestockBreedingsController::getBreedingCountByMonth');
        $routes->get('get-livestock-breedings-report-data', 'LivestockBreedingsController::getLivestockBreedingReportData');
        $routes->get('distinct-livestock-types', 'LivestocksController::getFarmerDistinctLivestockType');
        $routes->get('livestock-types-sex', 'LivestocksController::getAllFarmerLivestocksBySexAndType');
        $routes->get('get-livestock-breedings-freport', 'LivestockBreedingsController::getLivestockBreedingForReport');

        // Livestock Pregnancy endpoint routes
        $routes->get('all-livestock-pregnancies', 'LivestockPregnancyController::getAllLivestockPregnancies');
        $routes->get('livestock-pregnancy/(:any)', 'LivestockPregnancyController::getLivestockPregnancy/$1');
        $routes->get('all-farmer-livestock-pregnancies/(:any)', 'LivestockPregnancyController::getAllFarmerLivestockPregnancies/$1'); // not used
        $routes->get('all-livestock-livestock-pregnancies', 'LivestockPregnancyController::getAllLivestockPregnanciesByLivestock');
        $routes->put('successful-livestock-pregnancy/(:any)', 'LivestockPregnancyController::addSuccessfulLivestockPregnancy/$1');
        $routes->put('update-livestock-pregnancy', 'LivestockPregnancyController::updateLivestockPregnancy');
        $routes->get('get-livestock-pregnancies-report-data', 'LivestockPregnancyController::getLivestockPregnancyReportData');
        $routes->get('get-livestock-pregnancies-freport', 'LivestockPregnancyController::getLivestockPregnanciesForReport');
        $routes->delete('delete-livestock-pregnancy', 'LivestockPregnancyController::deleteLivestockPregnancy');

        // Livestock Offspring endpoint routes
        $routes->get('all-livestock-offspring', 'LivestockOffspringController::getAllLivestockOffspringRecords');
        $routes->get('livestock-offspring/(:any)', 'LivestockOffspringController::getLivestockOffspringRecord/$1');
        $routes->get('all-farmer-livestock-offspring/(:any)', 'LivestockOffspringController::getAllFarmerLivestockOffspringRecords/$1');
        $routes->post('add-livestock-offspring', 'LivestockOffspringController::insertLivestockOffspringRecord');
        $routes->put('update-livestock-offspring/(:any)', 'LivestockOffspringController::updateLivestockOffspringRecord/$1');
        $routes->put('update-livestock-offspring-record-stat/(:any)', 'LivestockOffspringController::updateLivestockOffspringRecordStatus/$1');
        $routes->delete('delete-livestock-offspring/(:any)', 'LivestockOffspringController::deleteLivestockOffspringRecord/$1');
        $routes->get('all-complete-livestock-offspring', 'LivestockOffspringController::getAllCompleteLivestockOffspringRecord');
        $routes->get('livestock-offspring-count/(:any)', 'LivestockOffspringController::getLivestockOffspringCount/$1');

        // Livestock Egg Production endpoint routes
        $routes->get('all-livestock-eggprods', 'LivestockEggProductionController::getAllEggProductions');
        $routes->get('all-farmer-livestock-eggprods/(:any)', 'LivestockEggProductionController::getAllFarmerEggProductions/$1');
        $routes->get('livestock-eggprods/(:any)', 'LivestockEggProductionController::getEggProduction/$1');
        $routes->post('add-livestock-eggprods', 'LivestockEggProductionController::insertEggProduction');
        $routes->put('update-livestock-eggprod', 'LivestockEggProductionController::updateEggProduction');
        $routes->put('update-livestock-eggprod-record-stat/(:any)', 'LivestockEggProductionController::updateEggProductionRecordStatus/$1'); // not used
        $routes->delete('delete-livestock-eggprod', 'LivestockEggProductionController::deleteEggProduction');
        $routes->get('get-egg-productions-report-data', 'LivestockEggProductionController::getLivestockEggProductionReportData');
        $routes->get('get-egg-productions-freport', 'LivestockEggProductionController::getPoultryEggProductionsForReport');

        $routes->get('active-eggprods-batch', 'EggProductionBatchGroupController::getAllActiveEggProductionBatchGroups');
        $routes->get('eggprods-distribution-month', 'LivestockEggProductionController::getEggProductionCountByMonth');
        $routes->get('eggprod-current-year-count', 'LivestockEggProductionController::getCurrentYearEggProductionCount');
        $routes->get('poultry-type-eggprod-count', 'LivestockEggProductionController::getTopPoultryTypeEggProducedCount');
        $routes->get('active-eggprod-batch-count', 'EggProductionBatchGroupController::getAllActiveBatchWithEggsProduced');

        $routes->get('all-eggprocessing-batch', 'EggProcessingBatchController::getEggProcessingBatches');
        $routes->post('add-eggprocessing-batch', 'EggProcessingBatchController::insertEggProcessingBatch');
        $routes->put('update-eggprocessing-batch', 'EggProcessingBatchController::updateEggProcessingBatch');
        $routes->delete('delete-egg-processing', 'EggProcessingBatchController::deleteEggProcessingBatch');
        $routes->get('get-egg-processing-report-data', 'EggProcessingBatchController::getLivestockEggProcessingBatchReportData');
        $routes->get('get-egg-processing-freport', 'EggProcessingBatchController::getEggProcessingBatchesForReport');

        $routes->get('all-eggmonitoring-logs', 'EggProcessingBatchController::getAllEggMonitoringLogs');
        $routes->get('get-egg-monitoring-logs-report-data', 'EggProcessingBatchController::getEggMonitoringLogsReportData');
        $routes->get('get-egg-monitoring-logs-freport', 'EggProcessingBatchController::getEggMonitoringLogsForReport');

        // Livestock Mortality endpoint routes
        $routes->get('all-livestock-mortalities', 'LivestockMortalityController::getAllLivestockMortalities');
        $routes->get('all-farmer-livestock-mortalities/(:any)', 'LivestockMortalityController::getAllFarmerLivestockMortalities/$1'); // not used
        $routes->get('livestock-mortality/(:any)', 'LivestockMortalityController::getLivestockMortality/$1');
        $routes->post('add-livestock-mortality', 'LivestockMortalityController::insertLivestockMortality'); 
        $routes->put('update-livestock-mortality', 'LivestockMortalityController::updateLivestockMortality');
        $routes->put('update-livestock-mortality-record-stat/(:any)', 'LivestockMortalityController::updateLivestockMortalityRecordStatus/$1');
        $routes->delete('delete-livestock-mortality', 'LivestockMortalityController::deleteLivestockMortality');
        $routes->get('all-complete-livestock-mortalities', 'LivestockMortalityController::getAllCompleteLivestockMortalities');
        $routes->get('livestock-mortalities-count-current-year', 'LivestockMortalityController::getOverallLivestockMortalitiesCountInCurrentYear');
        $routes->get('livestock-type-mortality-count', 'LivestockMortalityController::getLivestockTypeMortalityCount');
        $routes->get('all-mortality-4months', 'LivestockMortalityController::getMortalitiesCountLast4Months');
        $routes->get('get-livestock-mortalities-report-data', 'LivestockMortalityController::getMortalityReportData');
        $routes->get('get-livestock-mortalities-freport', 'LivestockMortalityController::getLivestockMortalitiesForReport');

        // Animal Sample endpoint routes
        $routes->get('all-animalsamples', 'AnimalSamplesController::getAllAnimalSamples');
        $routes->get('all-user-animalsamples', 'AnimalSamplesController::getAllAnimalSampleByUser');
        $routes->get('all-animal-animalsamples', 'AnimalSamplesController::getAllAnimalSampleByAnimal');
        $routes->post('add-animalsamples', 'AnimalSamplesController::insertAnimalSample');
        $routes->post('add-animalsamples-multiple', 'AnimalSamplesController::insertMultipleAnimalSample');
        $routes->put('update-animalsamples', 'AnimalSamplesController::updateAnimalSample');
        $routes->delete('delete-animalsamples', 'AnimalSamplesController::deleteAnimalSample');
        $routes->get('animalsamples-count', 'AnimalSamplesController::getOverallAnimalSampleCount');
        $routes->get('animalsamples-count/(:any)', 'AnimalSamplesController::getUserOverallAnimalSampleCount/$1');
        $routes->get('top-observation-samples', 'AnimalSamplesController::getTopAnimalObservations');
        $routes->get('top-findings-samples', 'AnimalSamplesController::getTopAnimalSampleFindings');
        $routes->get('top-samples-types', 'AnimalSamplesController::getTopAnimalSampleType');
        $routes->get('all-animalsamples-4months', 'AnimalSamplesController::getSampleCountLast4Months');
        $routes->get('animalsamples-distribution-month', 'AnimalSamplesController::getAnimalSampleCountByMonth');
        // $routes->get('get-livestock-fecal-samples-report-data', 'AnimalSamplesController::getFecalSampleReportData');
        // $routes->get('get-livestock-fecal-samples-freport', 'AnimalSamplesController::getLivestockFecalSamplesForReport');

        // Livestock Fecal Sample endpoint routes
        $routes->get('all-livestock-fecalsamples', 'LivestockFecalSampleController::getAllLivestockFecalSample');
        $routes->get('all-farmer-livestock-fecalsamples/(:any)', 'LivestockFecalSampleController::getAllFarmerLivestockFecalSamples/$1');
        $routes->get('all-livestock-livestock-fecalsamples/(:any)', 'LivestockFecalSampleController::getLivestockFecalSampleByLivestockId/$1');
        $routes->get('livestock-fecalsamples/(:any)', 'LivestockFecalSampleController::getLivestockFecalSample/$1');
        $routes->post('add-livestock-fecalsamples', 'LivestockFecalSampleController::insertLivestockFecalSample');
        $routes->post('add-livestock-fecalsamples-multiple', 'LivestockFecalSampleController::insertMultipleLivestockFecalSample');
        $routes->put('update-livestock-fecalsamples', 'LivestockFecalSampleController::updateLivestockFecalSample');
        $routes->delete('delete-livestock-fecalsample', 'LivestockFecalSampleController::deleteLivestockFecalSample');
        $routes->get('livestock-fecalsamples-count', 'LivestockFecalSampleController::getOverallLivestockFecalSampleCount');
        $routes->get('livestock-fecalsamples-count/(:any)', 'LivestockFecalSampleController::getFarmerOverallLivestockFecalSampleCount/$1');
        $routes->get('recent-livestock-fecalsample', 'LivestockFecalSampleController::getRecentLivestockFecalSample');
        $routes->get('top-observation-fecalsample', 'LivestockFecalSampleController::getTopLivestockObservations');
        $routes->get('top-findings-fecalsample', 'LivestockFecalSampleController::getTopFecalSampleFindings');
        $routes->get('all-fecalsample-4months', 'LivestockFecalSampleController::getFecalCountLast4Months');
        $routes->get('fecalsample-distribution-month', 'LivestockFecalSampleController::getFecalSampleCountByMonth');
        $routes->get('get-livestock-fecal-samples-report-data', 'LivestockFecalSampleController::getFecalSampleReportData');
        $routes->get('get-livestock-fecal-samples-freport', 'LivestockFecalSampleController::getLivestockFecalSamplesForReport');

        // Livestock Blood Sample endpoint routes
        $routes->get('all-livestock-bloodsamples', 'LivestockBloodSampleController::getAllLivestockBloodSamples');
        $routes->get('all-farmer-livestock-bloodsamples/(:any)', 'LivestockBloodSampleController::getAllFarmerLivestockBloodSamples/$1');
        $routes->get('all-livestock-livestock-bloodsamples/(:any)', 'LivestockBloodSampleController::getLivestockBloodSampleByLivestockId/$1');
        $routes->get('livestock-bloodsamples/(:any)', 'LivestockBloodSampleController::getLivestockBloodSample/$1');
        $routes->post('add-livestock-bloodsamples', 'LivestockBloodSampleController::insertLivestockBloodSample');
        $routes->post('add-livestock-bloodsamples-multiple', 'LivestockBloodSampleController::insertMultipleLivestockBloodSample');
        $routes->put('update-livestock-bloodsamples', 'LivestockBloodSampleController::updateLivestockBloodSample');
        $routes->delete('delete-livestock-bloodsample', 'LivestockBloodSampleController::deleteLivestockBloodSample');
        $routes->get('livestock-bloodsamples-count', 'LivestockBloodSampleController::getOverallLivestockBloodSampleCount');
        $routes->get('livestock-bloodsamples-count/(:any)', 'LivestockBloodSampleController::getFarmerOverallLivestockBloodSampleCount/$1');
        $routes->get('recent-livestock-bloodsample', 'LivestockBloodSampleController::getRecentLivestockBloodSample');
        $routes->get('top-observation-bloodsample', 'LivestockBloodSampleController::getTopLivestockObservations');
        $routes->get('top-findings-bloodsample', 'LivestockBloodSampleController::getTopBloodSampleFindings');
        $routes->get('all-bloodsample-4months', 'LivestockBloodSampleController::getBloodCountLast4Months');
        $routes->get('bloodsample-distribution-month', 'LivestockBloodSampleController::getBloodSampleCountByMonth');
        $routes->get('get-livestock-blood-samples-report-data', 'LivestockBloodSampleController::getBloodSampleReportData');
        $routes->get('get-livestock-blood-samples-freport', 'LivestockBloodSampleController::getLivestockBloodSamplesForReport');

        // Egg Distribution
        $routes->get('all-egg-distributions', 'EggDistributionController::getAllEggDistributions');
        $routes->get('egg-distributions/(:any)', 'EggDistributionController::getEggDistributionById/$1');
        $routes->post('add-egg-distribution', 'EggDistributionController::insertEggDistribution');
        $routes->put('update-egg-distribution', 'EggDistributionController::updateEggDistribution');
        $routes->delete('delete-egg-distribution', 'EggDistributionController::deleteEggDistribution');

        // Livestock Advisories endpoint routes
        $routes->get('all-livestock-advisories', 'LivestockAdvisoriesController::getAllLivestockAdvisories');
        $routes->get('all-farmer-livestock-advisories/(:any)', 'LivestockAdvisoriesController::getAllFarmerLivestockAdvisories/$1'); // not used
        $routes->get('all-general-livestock-advisories', 'LivestockAdvisoriesController::getAllGeneralLivestockAdvisories');
        $routes->get('get-livestock-advisory/(:any)', 'LivestockAdvisoriesController::getLivestockAdvisory/$1');
        $routes->post('send-livestock-advisory', 'LivestockAdvisoriesController::sendLivestockAdvisories');
        $routes->put('update-livestock-advisory/(:any)', 'LivestockAdvisoriesController::updateLivestockAdvisory/$1');
        $routes->put('update-livestock-advisory-read/(:any)', 'LivestockAdvisoriesController::updateLivestockAdvisoryReadStatus/$1');
        $routes->put('update-livestock-advisory-record-stat/(:any)', 'LivestockAdvisoriesController::updateLivestockAdvisoryRecordStatus/$1');
        $routes->delete('delete-livestock-advisory/(:any)', 'LivestockAdvisoriesController::deleteLivestockAdvisory/$1');

        // Farmer Audit Trail endpoint routes
        $routes->get('all-farmer-audit-trails', 'AuditTrailController::getAllFarmerAuditTrailLogs');
        $routes->get('all-audit-trails', 'AuditTrailController::getAllAuditTrailLogs');
        $routes->get('farmer-audit-trails/(:any)', 'AuditTrailController::getFarmerAuditTrailLogs/$1'); // not used
        $routes->get('audit-trail-entity/(:any)', 'AuditTrailController::getAuditTrailLogsByEntity/$1');
        $routes->get('audit-trail-action/(:any)', 'AuditTrailController::getAuditTrailLogsByAction/$1');
        $routes->post('add-audit-trail', 'AuditTrailController::insertAuditTrailLog');

        // Farmer Association endpoint routes
        $routes->get('get-farmer-associationc', 'FarmerAssociationController::getAllFarmerAssociationComplete');
        $routes->get('get-farmer-associationc/(:any)', 'FarmerAssociationController::getFarmerAssociationComplete/$1');
        $routes->get('get-farmer-association', 'FarmerAssociationController::getFarmerAssociation');
        $routes->post('add-farmer-association', 'FarmerAssociationController::insertFarmerAssociation');
        $routes->post('add-farmer-association-multiple', 'FarmerAssociationController::insertMultipleFarmerAssociation');
        $routes->put('update-farmer-association/(:any)', 'FarmerAssociationController::updateFarmerAssociation/$1');
        $routes->delete('delete-farmer-association/(:any)', 'FarmerAssociationController::deleteFarmerAssociation/$1');

        // Farmer User Association endpoint routes
        $routes->get('get-farmeruser-association', 'FarmerUserAssociationController::getAllFarmerUserAssociations');
        $routes->get('get-farmeruser-association/(:any)', 'FarmerUserAssociationController::getFarmerUserAssociationsById/$1');
        $routes->get('get-farmeruser-association-user', 'FarmerUserAssociationController::getFarmerUserAssociationsByUserId');
        $routes->post('add-farmeruser-association', 'FarmerUserAssociationController::insertFarmerUserAssociation');
        $routes->post('add-farmeruser-association-multiple-farmer', 'FarmerUserAssociationController::insertMultipleFarmerUserAssociations');
        $routes->post('add-farmeruser-association-multiple', 'FarmerUserAssociationController::insertMultipleFarmerUserAssociationsInAFarmer');
        $routes->put('update-farmeruser-association/(:any)', 'FarmerUserAssociationController::updateFarmerUserAssociation/$1');
        $routes->delete('delete-farmeruser-association/(:any)', 'FarmerUserAssociationController::deleteFarmerUserAssociation/$1');

        // Charts endpoint routes
        $routes->get('livestock-mapping-data', 'LivestocksController::getLivestockMappingData');
        $routes->get('livestock-mapping-data/(:any)', 'LivestocksController::getLivestockMappingDataByType/$1');
        $routes->get('livestock-farmer-count-city', 'LivestocksController::getFarmerLivestockTypeCountDataByCity');
        $routes->get('poultry-mapping-data', 'PoultryController::getPoultryMappingData');
        $routes->get('poultry-farmer-count-city', 'PoultryController::getFarmerPoultryTypeCountDataByCity');

        $routes->get('get-admin-profile', 'UserController::getAdminUserInfo');

        // testings routes
        $routes->get('livestock-primary-data/(:any)', 'LivestocksController::getLivestockPrimaryData/$1');
        $routes->get('get-user-name/(:any)', 'UserController::getUserName/$1');
        $routes->get('get-farmer-livestock-tag-id', 'LivestocksController::getFarmerLivestockIdByTag');
        $routes->get('get-farmer-poultry-tag-id', 'PoultryController::getFarmerPoultryIdByTag');

        $routes->get('trial-arima', 'TrialArimaController::trialDataFetching');
        $routes->get('trial-report', 'LivestockVaccinationsController::getLivestockVaccinationsForReport');
    });

    // Farmers endpoint routes
    $routes->group('farmer', ['filter' => 'authFarmerFilter'], function ($routes) {
        $routes->get('get-user-image/(:any)', 'UserController::getUserImage/$1');
        $routes->get('get-mortality-image/(:any)', 'UserController::getMortalityImage/$1');

        $routes->get('distinct-livestock-types', 'LivestocksController::getFarmerDistinctLivestockType');
        $routes->get('livestock-types-sex', 'LivestocksController::getAllFarmerLivestocksBySexAndType');
        $routes->get('distinct-poultry-types/(:any)', 'PoultryController::getFarmerDistinctPoultryType/$1');

        // Farmer Dashboard routes
        $routes->get('livestock-type-age-class', 'LivestocksController::getFarmerLivestockTypeAgeClassCount');
        $routes->get('livestock-count', 'LivestocksController::getFarmerLivestockCount');
        $routes->get('livestock-type-count', 'LivestocksController::getFarmerLivestockTypeCount');
        $routes->get('poultry-type-age-class/(:any)', 'PoultryController::getAllPoultryTypeAgeClassCount/$1');
        $routes->get('poultry-type-count/(:any)', 'PoultryController::getFarmerPoultryTypeCount/$1');
        $routes->get('poultry-count/(:any)', 'PoultryController::getFarmerPoultryCount/$1');

        // Farmer Livestocks endpoint routes
        $routes->post('add-livestock', 'LivestocksController::addFarmerLivestock');
        $routes->post('add-livestock-multiple', 'LivestocksController::addMultipleFarmerLivestock');
        $routes->get('all-livestocks', 'LivestocksController::getFarmerAllLivestocks');
        $routes->get('all-livestocks-tagid', 'LivestocksController::getAllFarmerLivestockTagIDs');
        $routes->put('update-livestock', 'LivestocksController::updateLivestock');
        $routes->put('livestock-health/(:any)', 'LivestocksController::updateLivestockHealthStatus/$1');
        $routes->put('livestock-record-stat/(:any)', 'LivestocksController::updateLivestockRecordStatus/$1');
        $routes->delete('delete-livestock-record', 'LivestocksController::deleteLivestock');

        // Farmer Livestock Vaccinations endpoint routes
        $routes->get('all-livestock-vaccinations', 'LivestockVaccinationsController::getAllFarmerCompleteLivestockVaccinations');
        $routes->post('add-livestock-vaccination', 'LivestockVaccinationsController::insertLivestockVaccination');
        $routes->post('add-livestock-vaccination-multiple', 'LivestockVaccinationsController::insertMultipleLivestockVaccination');
        $routes->put('update-livestock-vaccination', 'LivestockVaccinationsController::updateLivestockVaccination');
        $routes->put('update-livestock-vaccination-record-stat/(:any)', 'LivestockVaccinationsController::updateLivestockVaccinationRecordStatus/$1');
        $routes->get('all-livestock-livestock-vaccinations/(:any)', 'LivestockVaccinationsController::getLivestockVaccinationByLivestockId/$1');
        $routes->delete('delete-livestock-vaccination', 'LivestockVaccinationsController::deleteLivestockVaccination');

        $routes->get('all-livestock-dewormings', 'LivestockDewormingController::getAllFarmerLivestockDewormings');
        $routes->post('add-livestock-deworming', 'LivestockDewormingController::insertLivestockDeworming');
        $routes->post('add-livestock-deworming-multiple', 'LivestockDewormingController::insertMultipleLivestockDeworming');
        $routes->put('update-livestock-deworming', 'LivestockDewormingController::updateLivestockDeworming');
        $routes->put('update-livestock-deworming-record-stat/(:any)', 'LivestockDewormingController::updateLivestockDewormingRecordStatus/$1');
        $routes->get('all-livestock-livestock-dewormings/(:any)', 'LivestockDewormingController::getLivestockDewormingByLivestockId/$1');
        $routes->delete('delete-livestock-deworming', 'LivestockDewormingController::deleteLivestockDewormingRecord');

        // Farmer Livestock Breedings endpoint routes
        $routes->get('all-livestock-breedings', 'LivestockBreedingsController::getAllFarmerLivestockBreedings'); // not used
        $routes->post('add-livestock-breeding', 'LivestockBreedingsController::insertLivestockBreeding');
        $routes->put('update-livestock-breeding', 'LivestockBreedingsController::updateLivestockBreeding');
        $routes->put('update-livestock-breeding-record-stat/(:any)', 'LivestockBreedingsController::updateLivestockBreedingRecordStatus/$1');

        $routes->get('pregnant-livestock-count/(:any)', 'LivestockPregnancyController::getFarmerPregnantLivestockCount/$1');
        $routes->get('all-livestock-pregnancy', 'LivestockPregnancyController::getAllFarmerLivestockPregnancies');
        $routes->put('successful-livestock-pregnancy/(:any)', 'LivestockPregnancyController::addSuccessfulLivestockPregnancy/$1');
        $routes->put('update-livestock-pregnancy', 'LivestockPregnancyController::updateLivestockPregnancy');
        $routes->get('all-livestock-livestock-pregnancies/(:any)', 'LivestockPregnancyController::getAllLivestockPregnanciesByLivestockId/$1');

        // Farmer Livestock Egg Production endpoint routes
        $routes->get('all-livestock-eggprods/(:any)', 'LivestockEggProductionController::getAllFarmerEggProductions/$1');
        $routes->post('add-livestock-eggprods', 'LivestockEggProductionController::insertEggProduction');
        $routes->put('update-livestock-eggprods/(:any)', 'LivestockEggProductionController::updateEggProduction/$1');
        $routes->put('update-livestock-eggprods-record-stat/(:any)', 'LivestockEggProductionController::updateEggProductionRecordStatus/$1');

        // Farmer Livestock Mortality endpoint routes
        $routes->get('all-livestock-mortalities', 'LivestockMortalityController::getAllFarmerLivestockMortalities');
        $routes->post('add-livestock-mortality', 'LivestockMortalityController::insertLivestockMortality');
        $routes->put('update-livestock-mortality', 'LivestockMortalityController::updateLivestockMortality');
        $routes->put('update-livestock-mortality-record-stat/(:any)', 'LivestockMortalityController::updateLivestockMortalityRecordStatus/$1');
        $routes->delete('delete-livestock-mortality', 'LivestockMortalityController::deleteLivestockMortality');

        // Livestock Advisories endpoint routes
        $routes->get('all-livestock-advisories', 'LivestockAdvisoriesController::getAllFarmerLivestockAdvisories');
        $routes->put('update-livestock-advisory-read/(:any)', 'LivestockAdvisoriesController::updateLivestockAdvisoryReadStatus/$1');

        // Farmer User Management endpoint routes
        $routes->get('get-farmer-profile', 'UserController::getFarmerUserInfo');
        $routes->put('update-user-profile', 'UserController::updateUserPersonalInfo');

        $routes->get('get-farmer-association', 'FarmerAssociationController::getFarmerAssociation');
        $routes->get('get-farmeruser-association-user', 'FarmerUserAssociationController::getFarmerUserAssociationsByFarmer');
        $routes->post('add-farmeruser-association', 'FarmerUserAssociationController::insertFarmerUserAssociation');
        $routes->post('add-farmeruser-association-multiple-farmer', 'FarmerUserAssociationController::insertMultipleFarmerUserAssociations');
        $routes->post('add-farmeruser-association-multiple', 'FarmerUserAssociationController::insertMultipleFarmerUserAssociationsInAFarmer');
        $routes->put('update-farmeruser-association/(:any)', 'FarmerUserAssociationController::updateFarmerUserAssociation/$1');

        $routes->get('get-audit-trails', 'AuditTrailController::getFarmerAuditTrailLogs');
    });
});
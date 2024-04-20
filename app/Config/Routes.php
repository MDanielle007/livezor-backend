<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// API routes
$routes->group('api', static function ($routes) {
    $routes->post('login', 'UserController::loginAuth');
    $routes->post('logout', 'UserController::userLogOut');

    // Livestocks endpoint routes
    $routes->group('livestock', static function ($routes) {
        // Livestock Types endpoint routes
        $routes->get('all-livestock-types', 'LivestockTypesController::getLivestockTypes');
        $routes->get('all-livestock-types-idnames', 'LivestockTypesController::getLivestockTypesIdAndName');
        $routes->get('livestock-type/(:any)', 'LivestockTypesController::getLivestockType/$1');
        $routes->post('insert-livestock-type', 'LivestockTypesController::insertLivestockType');
        $routes->put('update-livestock-type/(:any)', 'LivestockTypesController::updateLivestockType/$1');
        $routes->delete('delete-livestock-type/(:any)', 'LivestockTypesController::deleteLivestockType/$1');

        // Poultry Types endpoints routes
        $routes->get('all-poultry-types', 'PoultryTypeController::getPoultryTypes');
        $routes->get('all-poultry-types-idnames', 'PoultryTypeController::getPoultryTypesIdAndName');
        $routes->get('poultry-type/(:any)', 'PoultryTypeController::getPoultryType/$1');
        $routes->post('insert-poultry-type', 'PoultryTypeController::insertPoultryType');
        $routes->put('update-poultry-type/(:any)', 'PoultryTypeController::updatePoultryType/$1');
        $routes->delete('delete-poultry-type/(:any)', 'PoultryTypeController::deletePoultryType/$1');

        // Livestock/Poultry Breeds endpoint routes
        $routes->get('all-livestock-breeds', 'LivestockBreedsController::getLivestockBreeds');
        $routes->get('all-poultry-breeds', 'LivestockBreedsController::getPoultryBreeds');
        $routes->get('all-livestock-breeds-idnames', 'LivestockBreedsController::getLivestockBreedIdAndName');
        $routes->get('all-livestock-breeds-idnames/(:any)', 'LivestockBreedsController::getLivestockBreedIdAndNameById/$1');
        $routes->get('livestock-breed/(:any)', 'LivestockBreedsController::getLivestockBreed/$1');
        $routes->post('insert-livestock-breed', 'LivestockBreedsController::insertLivestockBreed');
        $routes->put('update-livestock-breed/(:any)', 'LivestockBreedsController::updateLivestockBreed/$1');
        $routes->delete('delete-livestock-breed/(:any)', 'LivestockBreedsController::deleteLivestockBreed/$1');

        // Livestock Age Classifications endpoint routes
        $routes->get('all-livestock-age-classes', 'LivestockAgeClassController::getLivestockAgeClasses');
        $routes->get('all-poultry-age-classes', 'LivestockAgeClassController::getPoultryAgeClasses');
        $routes->get('all-livestock-ageclass-idnames', 'LivestockAgeClassController::getLivestockAgeClassIdAndName');
        $routes->get('all-livestock-ageclass-idnames/(:any)', 'LivestockAgeClassController::getLivestockAgeClassIdAndNameById/$1');
        $routes->get('livestock-age-class/(:any)', 'LivestockAgeClassController::getLivestockAgeClass/$1');
        $routes->post('insert-livestock-age-class', 'LivestockAgeClassController::insertLivestockAgeClass');
        $routes->put('update-livestock-age-class/(:any)', 'LivestockAgeClassController::updateLivestockAgeClass/$1');
        $routes->delete('delete-livestock-age-class/(:any)', 'LivestockAgeClassController::deleteLivestockAgeClass/$1');
    });

    // Admin endpoint routes
    $routes->group('admin', static function ($routes) {
        // Admin Dashboard routes
        $routes->get('livestock-type-count', 'LivestocksController::getAllLivestockTypeCount');
        $routes->get('livestock-type-ageclass-count', 'LivestocksController::getAllLivestockTypeAgeClassCount');
        $routes->get('livestock-healthstat-count', 'LivestocksController::getLivestockHealthStatusesCount');
        $routes->get('livestock-vaccination-percent-current-month', 'LivestockVaccinationsController::getLivestockVaccinationPercetageInCurrentMonth');
        $routes->get('livestock-breeding-eligible-percent', 'LivestocksController::getAllBreedingEligibleLivestocks');
        $routes->get('livestock-count', 'LivestocksController::getAllLivestockCountMonitored');
        $routes->get('farmer-count', 'UserController::getFarmerCount');
        $routes->get('livestock-mortalities-percent-last-month', 'LivestockMortalityController::getLivestockMortalitiesCountLastMonth');
        $routes->get('livestock-distribution-month', 'LivestocksController::getLivestockCountByMonthAndType');
        $routes->get('top-vaccine', 'LivestockVaccinationsController::getTopVaccines');
        $routes->get('vaccination-distribution-month', 'LivestockVaccinationsController::getVaccinationCountByMonth');
        $routes->get('vaccination-poultry-distribution-month', 'LivestockVaccinationsController::getPoultryVaccinationCountByMonth');
        $routes->get('top-mortality', 'LivestockMortalityController::getTopMortalityCause');
        $routes->get('mortality-distribution-month', 'LivestockMortalityController::getMortalityCountByMonth');
        $routes->get('livestock-vaccination-count', 'LivestockVaccinationsController::getOverallLivestockVaccinationCount');
        $routes->get('livestock-deworming-count', 'LivestockDewormingController::getOverallLivestockDewormingCount');
        $routes->get('deworming-distribution-month', 'LivestockDewormingController::getDewormingCountByMonth');
        $routes->get('livestock-vaccination-count/(:any)', 'LivestockVaccinationsController::getFarmerOverallLivestockVaccinationCount/$1');
        $routes->get('livestock-vaccination-count-current-month', 'LivestockVaccinationsController::getLivestockVaccinationCountInCurrentMonth');
        $routes->get('livestock-mortalities-count', 'LivestockMortalityController::getOverallLivestockMortalitiesCount');
        $routes->get('livestock-mortalities-count/(:any)', 'LivestockMortalityController::getFarmerOverallLivestockMortalitiesCount/$1');
        $routes->get('livestock-deworming-count/(:any)', 'LivestockDewormingController::getFarmerOverallLivestockDewormingCount/$1');
        $routes->get('livestock-count-city/(:any)', 'LivestocksController::getAllLivestockTypeCountByCity/$1');
        $routes->get('livestock-count-city', 'LivestocksController::getLivestockCountAllCity');
        $routes->get('livestock-production-monthly', 'LivestocksController::getLivestockProductionCountByMonthInCurrentYear');
        $routes->get('livestock-type-count-city/(:any)', 'LivestocksController::getLivestockTypeCountAllCity/$1');
        

        // User Management endpoint routes
        $routes->post('upload', 'UserController::uploadUserImage');
        $routes->post('register-user', 'UserController::registerUser');
        $routes->get('all-users', 'UserController::getAllUsers');
        $routes->get('get-user-profile/(:any)', 'UserController::getUser/$1');
        $routes->put('update-user-profile/(:any)', 'UserController::updateUser/$1');
        $routes->put('update-user-personal-info/(:any)', 'UserController::updateUserPersonalInfo/$1');
        $routes->put('update-user-account-info/(:any)', 'UserController::updateUserAccountInfo/$1');
        $routes->put('update-user-password/(:any)', 'UserController::updateUserPassword/$1');
        $routes->put('update-user-record-stat/(:any)', 'UserController::updateUserRecordStatus/$1');
        $routes->delete('delete-user/(:any)', 'UserController::deleteUser/$1');
        $routes->get('get-farmers-basic', 'UserController::getAllFarmersBasicInfo');
        $routes->get('get-all-farmers-type-count', 'UserController::getAllFarmerLivestockTypeCount');

        $routes->get('get-admin-tokens', 'UserController::getAllAdminFirebaseToken');
        $routes->get('get-farmer-tokens', 'UserController::getAllFarmerFirebaseToken');
        $routes->get('get-user-tokens/(:any)', 'UserController::getUserFirebaseToken/$1');
        
        // Livestock endpoint routes
        $routes->get('all-livestock', 'LivestocksController::getAllLivestocks');
        $routes->get('all-farmer-livestocks/(:any)', 'LivestocksController::getFarmerAllLivestocks/$1');
        $routes->get('livestock/(:any)', 'LivestocksController::getLivestock/$1');
        $routes->post('add-livestock', 'LivestocksController::addLivestock');
        $routes->post('add-farmer-livestock', 'LivestocksController::addFarmerLivestock');
        $routes->post('add-livestock-multiple', 'LivestocksController::addMultipleFarmerLivestock');
        $routes->put('update-livestock/(:any)', 'LivestocksController::updateLivestock/$1');
        $routes->put('livestock-health/(:any)', 'LivestocksController::updateLivestockHealthStatus/$1');
        $routes->put('livestock-record-stat/(:any)', '::updateLivestockRecordStatus/$1');
        $routes->delete('delete-livestock-record/(:any)', 'LivestocksController::deleteLivestock/$1');
        $routes->get('all-livestocks-tagid/(:any)', 'LivestocksController::getAllFarmerLivestockTagIDs/$1');
        $routes->get('livestock-production-year', 'LivestocksController::getLivestockProductionCountWholeYear');
        $routes->get('livestock-type-production-year', 'LivestocksController::getLivestockProductionWholeYear');
        $routes->get('livestock-type-production-year/(:any)', 'LivestocksController::getLivestockProductionSelectedYear/$1');

        // Poultry endpoints routes
        $routes->get('all-poultries', 'PoultryController::getAllPoultries');
        $routes->get('all-farmer-poultries/(:any)', 'PoultryController::getFarmerAllPoultries/$1');
        $routes->get('poultry/(:any)', 'PoultryController::getPoultry/$1');
        $routes->post('add-poultry', 'PoultryController::addPoultry');
        $routes->post('add-farmer-poultry', 'PoultryController::addFarmerPoultry');
        $routes->post('add-poultry-multiple', 'PoultryController::addMultipleFarmerPoultries');
        $routes->put('update-poultry/(:any)', 'PoultryController::updatePoultry/$1');
        $routes->put('poultry-health/(:any)', 'PoultryController::updatePoultryHealthStatus/$1');
        $routes->put('poultry-record-stat/(:any)', 'PoultryController::updatePoultryRecordStatus/$1');
        $routes->delete('delete-poultry-record/(:any)', 'PoultryController::deletePoultry/$1');
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

        // Livestock Vaccination endpoint routes
        $routes->get('all-livestock-vaccinations', 'LivestockVaccinationsController::getAllLivestockVaccinations');
        $routes->get('all-poultry-vaccinations', 'LivestockVaccinationsController::getAllPoultryVaccinations');
        $routes->get('all-farmer-livestock-vaccinations/(:any)', 'LivestockVaccinationsController::getAllFarmerCompleteLivestockVaccinations/$1');
        $routes->get('livestock-vaccination/(:any)', 'LivestockVaccinationsController::getLivestockVaccination/$1');
        $routes->post('add-livestock-vaccination', 'LivestockVaccinationsController::insertLivestockVaccination');
        $routes->post('add-livestock-vaccination-multiple', 'LivestockVaccinationsController::insertMultipleLivestockVaccination');
        $routes->put('update-livestock-vaccination/(:any)', 'LivestockVaccinationsController::updateLivestockVaccination/$1');
        $routes->put('update-livestock-vaccination-record-stat/(:any)', 'LivestockVaccinationsController::updateLivestockVaccinationRecordStatus/$1');
        $routes->delete('delete-livestock-vaccination/(:any)', 'LivestockVaccinationsController::deleteLivestockVaccination/$1');
        $routes->get('all-vaccinations-4months', 'LivestockVaccinationsController::getVaccinationCountLast4Months');
        $routes->get('all-vaccinations-year', 'LivestockVaccinationsController::getVaccinationCountWholeYear');


        // Livestock Deworming endpoint routes
        $routes->get('all-livestock-dewormings', 'LivestockDewormingController::getAllLivestockDewormings');
        $routes->get('all-farmer-livestock-dewormings/(:any)', 'LivestockDewormingController::getAllFarmerLivestockDewormings/$1');
        $routes->get('livestock-deworming/(:any)', 'LivestockDewormingController::getLivestockDeworming/$1');
        $routes->post('add-livestock-deworming', 'LivestockDewormingController::insertLivestockDeworming');
        $routes->post('add-livestock-deworming-multiple', 'LivestockDewormingController::insertMultipleLivestockDeworming');
        $routes->put('update-livestock-deworming/(:any)', 'LivestockDewormingController::updateLivestockDeworming/$1');
        $routes->put('update-livestock-deworming-record-stat/(:any)', 'LivestockDewormingController::updateLivestockDewormingRecordStatus/$1');
        $routes->delete('delete-livestock-deworming/(:any)', 'LivestockDewormingController::deleteLivestockDewormingRecord/$1');
        $routes->get('all-deworming-4months', 'LivestockDewormingController::getDewormingCountLast4Months');
        $routes->get('livestock-type-deworming-count', 'LivestockDewormingController::getTopLivestockTypeDewormedCount');
        $routes->get('deworming-administration-method-count', 'LivestockDewormingController::getAdministrationMethodsCount');


        // Livestock Breedings endpoint routes
        $routes->get('all-livestock-breedings', 'LivestockBreedingsController::getAllLivestockBreedings');
        $routes->get('all-farmer-livestock-breedings/(:any)', 'LivestockBreedingsController::getAllFarmerLivestockBreedings/$1');
        $routes->get('livestock-breeding/(:any)', 'LivestockBreedingsController::getLivestockBreeding/$1');
        $routes->post('add-livestock-breeding', 'LivestockBreedingsController::insertLivestockBreeding');
        $routes->put('update-livestock-breeding/(:any)', 'LivestockBreedingsController::updateLivestockBreeding/$1');
        $routes->put('update-livestock-breeding-record-stat/(:any)', 'LivestockBreedingsController::updateLivestockBreedingRecordStatus/$1');
        $routes->delete('delete-livestock-breeding/(:any)', 'LivestockBreedingsController::deleteLivestockBreeding/$1');
        $routes->get('get-all-breeding-parent-offspring', 'LivestockBreedingsController::getAllBreedingParentOffspringData');
        $routes->post('successful-livestock-breeding', 'LivestockBreedingsController::addSuccessfulLivestockBreeding');
        $routes->get('livestock-breeding-count', 'LivestockBreedingsController::getOverallLivestockBreedingCount');
        $routes->get('livestock-breeding-count-current-year', 'LivestockBreedingsController::getOverallLivestockBreedingCountInCurrentYear');
        $routes->get('livestock-breeding-success-percent', 'LivestockBreedingsController::getLivestockBreedingSuccessPercentage');
        $routes->get('livestock-breeding-results-count', 'LivestockBreedingsController::getLivestockBreedingResultsCount');
        $routes->get('all-breeding-4months', 'LivestockBreedingsController::getBreedingsCountLast4Months');
        $routes->get('livestock-type-breeding-count', 'LivestockBreedingsController::getLivestockTypeBreedingsCount');
        $routes->get('breeding-distribution-month', 'LivestockBreedingsController::getBreedingCountByMonth');
        
        // Livestock Pregnancy endpoint routes
        $routes->get('all-livestock-pregnancies', 'LivestockPregnancyController::getAllLivestockPregnancies');
        $routes->get('livestock-pregnancy/(:any)', 'LivestockPregnancyController::getLivestockPregnancy/$1');
        $routes->get('all-farmer-livestock-pregnancies/(:any)', 'LivestockPregnancyController::getAllFarmerLivestockPregnancies/$1');
        $routes->put('successful-livestock-pregnancy/(:any)', 'LivestockPregnancyController::addSuccessfulLivestockPregnancy/$1');
        $routes->put('update-livestock-pregnancy/(:any)', 'LivestockPregnancyController::updateLivestockPregnancy/$1');

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
        $routes->post('add-livestock-eggprod', 'LivestockEggProductionController::insertEggProduction');
        $routes->put('update-livestock-eggprod/(:any)', 'LivestockEggProductionController::updateEggProduction/$1');
        $routes->put('update-livestock-eggprod-record-stat/(:any)', 'LivestockEggProductionController::updateEggProductionRecordStatus/$1');
        $routes->delete('delete-livestock-eggprod/(:any)', 'LivestockEggProductionController::deleteEggProduction/$1');

        $routes->get('active-eggprods-batch', 'EggProductionBatchGroupController::getAllActiveEggProductionBatchGroups');
        $routes->get('eggprods-distribution-month', 'LivestockEggProductionController::getEggProductionCountByMonth');
        $routes->get('eggprod-current-year-count', 'LivestockEggProductionController::getCurrentYearEggProductionCount');
        $routes->get('poultry-type-eggprod-count', 'LivestockEggProductionController::getTopPoultryTypeEggProducedCount');
        $routes->get('active-eggprod-batch-count', 'EggProductionBatchGroupController::getAllActiveBatchWithEggsProduced');

        $routes->get('all-eggprocessing-batch', 'EggProcessingBatchController::getEggProcessingBatches');
        $routes->post('add-eggprocessing-batch', 'EggProcessingBatchController::insertEggProcessingBatch');
        $routes->put('update-eggprocessing-batch/(:any)', 'EggProcessingBatchController::updateEggProcessingBatch/$1');

        $routes->get('all-eggmonitoring-logs', 'EggProcessingBatchController::getAllEggMonitoringLogs');

        // Livestock Mortality endpoint routes
        $routes->get('all-livestock-mortalities', 'LivestockMortalityController::getAllLivestockMortalities');
        $routes->get('all-farmer-livestock-mortalities/(:any)', 'LivestockMortalityController::getAllFarmerLivestockMortalities/$1');
        $routes->get('livestock-mortality/(:any)', 'LivestockMortalityController::getLivestockMortality/$1');
        $routes->post('add-livestock-mortality', 'LivestockMortalityController::insertLivestockMortality');
        $routes->put('update-livestock-mortality/(:any)', 'LivestockMortalityController::updateLivestockMortality/$1');
        $routes->put('update-livestock-mortality-record-stat/(:any)', 'LivestockMortalityController::updateLivestockMortalityRecordStatus/$1');
        $routes->delete('delete-livestock-mortality/(:any)', 'LivestockMortalityController::deleteLivestockMortality/$1');
        $routes->get('all-complete-livestock-mortalities', 'LivestockMortalityController::getAllCompleteLivestockMortalities');
        $routes->get('livestock-mortalities-count-current-year', 'LivestockMortalityController::getOverallLivestockMortalitiesCountInCurrentYear');
        $routes->get('livestock-type-mortality-count', 'LivestockMortalityController::getLivestockTypeMortalityCount');
        $routes->get('all-mortality-4months', 'LivestockMortalityController::getMortalitiesCountLast4Months');


        // Livestock Advisories endpoint routes
        $routes->get('all-livestock-advisories', 'LivestockAdvisoriesController::getAllLivestockAdvisories');
        $routes->get('all-farmer-livestock-advisories/(:any)', 'LivestockAdvisoriesController::getAllFarmerLivestockAdvisories/$1');
        $routes->get('all-general-livestock-advisories', 'LivestockAdvisoriesController::getAllGeneralLivestockAdvisories');
        $routes->get('get-livestock-advisory/$1', 'LivestockAdvisoriesController::getLivestockAdvisory/$1');
        $routes->post('send-livestock-advisory', 'LivestockAdvisoriesController::sendLivestockAdvisories');
        $routes->put('update-livestock-advisory/(:any)', 'LivestockAdvisoriesController::updateLivestockAdvisory/$1');
        $routes->put('update-livestock-advisory-read/(:any)', 'LivestockAdvisoriesController::updateLivestockAdvisoryReadStatus/$1');
        $routes->put('update-livestock-advisory-record-stat/(:any)', 'LivestockAdvisoriesController::updateLivestockAdvisoryRecordStatus/$1');
        $routes->delete('delete-livestock-advisory/(:any)', 'LivestockAdvisoriesController::deleteLivestockAdvisory/$1');


        // Farmer Audit Trail endpoint routes
        $routes->get('all-farmer-audit-trails', 'AuditTrailController::getAllFarmerAuditTrailLogs');
        $routes->get('farmer-audit-trails/(:any)', 'AuditTrailController::getFarmerAuditTrailLogs/$1');
        $routes->get('audit-trail-entity/(:any)', 'AuditTrailController::getAuditTrailLogsByEntity/$1');
        $routes->get('audit-trail-action/(:any)', 'AuditTrailController::getAuditTrailLogsByAction/$1');
        $routes->post('add-audit-trail', 'AuditTrailController::insertAuditTrailLog');
        $routes->put('update-livestock-mortality/(:any)', 'AuditTrailController::updateLivestockMortality/$1');
        $routes->put('update-livestock-mortality-record-stat/(:any)', 'AuditTrailController::updateLivestockMortalityRecordStatus/$1');
        $routes->delete('delete-livestock-mortality/(:any)', 'AuditTrailController::deleteLivestockMortality/$1');

        // Charts endpoint routes
        $routes->get('livestock-mapping-data', 'LivestocksController::getLivestockMappingData');
        $routes->get('livestock-farmer-count-city', 'LivestocksController::getFarmerLivestockTypeCountDataByCity');
        $routes->get('poultry-mapping-data', 'PoultryController::getPoultryMappingData');
        $routes->get('poultry-farmer-count-city', 'PoultryController::getFarmerPoultryTypeCountDataByCity');


        // testings routes
        $routes->get('livestock-primary-data/(:any)', 'LivestocksController::getLivestockPrimaryData/$1');
        $routes->get('get-user-name/(:any)', 'UserController::getUserName/$1');
        $routes->get('get-farmer-livestock-tag-id', 'LivestocksController::getFarmerLivestockIdByTag');
        $routes->get('get-farmer-poultry-tag-id', 'PoultryController::getFarmerPoultryIdByTag');
    });

    // Farmers endpoint routes
    $routes->group('farmer', static function ($routes) {
        $routes->get('distinct-livestock-types/(:any)', 'LivestocksController::getFarmerDistinctLivestockType/$1');
        $routes->get('livestock-types-sex/(:any)', 'LivestocksController::getAllFarmerLivestocksBySexAndType/$1');
        $routes->get('distinct-poultry-types/(:any)', 'PoultryController::getFarmerDistinctPoultryType/$1');

        // Farmer Dashboard routes
        $routes->get('livestock-type-age-class/(:any)', 'LivestocksController::getFarmerLivestockTypeAgeClassCount/$1');
        $routes->get('livestock-count/(:any)', 'LivestocksController::getFarmerLivestockCount/$1');
        $routes->get('livestock-type-count/(:any)', 'LivestocksController::getFarmerLivestockTypeCount/$1');
        $routes->get('poultry-type-age-class/(:any)', 'PoultryController::getAllPoultryTypeAgeClassCount/$1');
        $routes->get('poultry-type-count/(:any)', 'PoultryController::getFarmerPoultryTypeCount/$1');
        $routes->get('poultry-count/(:any)', 'PoultryController::getFarmerPoultryCount/$1');


        // Farmer Livestocks endpoint routes
        $routes->post('add-livestock', 'LivestocksController::addFarmerLivestock');
        $routes->post('add-livestock-multiple', 'LivestocksController::addMultipleFarmerLivestock');
        $routes->get('all-livestocks/(:any)', 'LivestocksController::getFarmerAllLivestocks/$1');
        $routes->get('all-livestocks-tagid/(:any)', 'LivestocksController::getAllFarmerLivestockTagIDs/$1');
        $routes->put('update-livestock/(:any)', 'LivestocksController::updateLivestock/$1');
        $routes->put('livestock-health/(:any)', 'LivestocksController::updateLivestockHealthStatus/$1');
        $routes->put('livestock-record-stat/(:any)', 'LivestocksController::updateLivestockRecordStatus/$1');

        // Farmer Livestock Vaccinations endpoint routes
        $routes->get('all-livestock-vaccinations/(:any)', 'LivestockVaccinationsController::getAllFarmerCompleteLivestockVaccinations/$1');
        $routes->post('add-livestock-vaccination', 'LivestockVaccinationsController::insertLivestockVaccination');
        $routes->post('add-livestock-vaccination-multiple', 'LivestockVaccinationsController::insertMultipleLivestockVaccination');
        $routes->put('update-livestock-vaccination/(:any)', 'LivestockVaccinationsController::updateLivestockVaccination/$1');
        $routes->put('update-livestock-vaccination-record-stat/(:any)', 'LivestockVaccinationsController::updateLivestockVaccinationRecordStatus/$1');

        $routes->get('all-livestock-dewormings/(:any)', 'LivestockDewormingController::getAllFarmerLivestockDewormings/$1');
        $routes->post('add-livestock-deworming', 'LivestockDewormingController::insertLivestockDeworming');
        $routes->post('add-livestock-deworming-multiple', 'LivestockDewormingController::insertMultipleLivestockDeworming');
        $routes->put('update-livestock-deworming/(:any)', 'LivestockDewormingController::updateLivestockDeworming/$1');
        $routes->put('update-livestock-deworming-record-stat/(:any)', 'LivestockDewormingController::updateLivestockDewormingRecordStatus/$1');

        // Farmer Livestock Breedings endpoint routes
        $routes->get('all-livestock-breedings/(:any)', 'LivestockBreedingsController::getAllFarmerLivestockBreedings/$1');
        $routes->post('add-livestock-breeding', 'LivestockBreedingsController::insertLivestockBreeding');
        $routes->put('update-livestock-breeding/(:any)', 'LivestockBreedingsController::updateLivestockBreeding/$1');
        $routes->put('update-livestock-breeding-record-stat/(:any)', 'LivestockBreedingsController::updateLivestockBreedingRecordStatus/$1');

        $routes->get('pregnant-livestock-count/(:any)', 'LivestockPregnancyController::getFarmerPregnantLivestockCount/$1');
        $routes->get('all-livestock-pregnancy/(:any)', 'LivestockPregnancyController::getAllFarmerLivestockPregnancies/$1');
        $routes->put('successful-livestock-pregnancy/(:any)', 'LivestockPregnancyController::addSuccessfulLivestockPregnancy/$1');
        $routes->put('update-livestock-pregnancy/(:any)', 'LivestockPregnancyController::updateLivestockPregnancy/$1');

        // Farmer Livestock Egg Production endpoint routes
        $routes->get('all-livestock-eggprods/(:any)', 'LivestockEggProductionController::getAllFarmerEggProductions/$1');
        $routes->post('add-livestock-eggprods', 'LivestockEggProductionController::insertEggProduction');
        $routes->put('update-livestock-eggprods/(:any)', 'LivestockEggProductionController::updateEggProduction/$1');
        $routes->put('update-livestock-eggprods-record-stat/(:any)', 'LivestockEggProductionController::updateEggProductionRecordStatus/$1');

        // Farmer Livestock Mortality endpoint routes
        $routes->get('all-livestock-mortalities/(:any)', 'LivestockMortalityController::getAllFarmerLivestockMortalities/$1');
        $routes->post('add-livestock-mortality', 'LivestockMortalityController::insertLivestockMortality');
        $routes->put('update-livestock-mortality/(:any)', 'LivestockMortalityController::updateLivestockMortality/$1');
        $routes->put('update-livestock-mortality-record-stat/(:any)', 'LivestockMortalityController::updateLivestockMortalityRecordStatus/$1');

        // Farmer User Management endpoint routes
        $routes->get('get-farmer-profile/(:any)', 'UserController::getFarmerUserInfo/$1');
        $routes->put('update-user-personal-info/(:any)', 'UserController::updateUserPersonalInfo/$1');

        $routes->get('get-audit-trails/(:any)', 'AuditTrailController::getFarmerAuditTrailLogs/$1');
    });
});
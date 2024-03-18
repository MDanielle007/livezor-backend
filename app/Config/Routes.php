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

        // Livestock Breeds endpoint routes
        $routes->get('all-livestock-breeds', 'LivestockBreedsController::getLivestockBreeds');
        $routes->get('all-livestock-breeds-idnames', 'LivestockBreedsController::getLivestockBreedIdAndName');
        $routes->get('all-livestock-breeds-idnames/(:any)', 'LivestockBreedsController::getLivestockBreedIdAndNameById/$1');
        $routes->get('livestock-breed/(:any)', 'LivestockBreedsController::getLivestockBreed/$1');
        $routes->post('insert-livestock-breed', 'LivestockBreedsController::insertLivestockBreed');
        $routes->put('update-livestock-breed/(:any)', 'LivestockBreedsController::updateLivestockBreed/$1');
        $routes->delete('delete-livestock-breed/(:any)', 'LivestockBreedsController::deleteLivestockBreed/$1');

        // Livestock Age Classifications endpoint routes
        $routes->get('all-livestock-age-classes', 'LivestockAgeClassController::getLivestockAgeClasses');
        $routes->get('all-livestock-ageclass-idnames', 'LivestockAgeClassController::getLivestockAgeClassIdAndName');
        $routes->get('all-livestock-ageclass-idnames/(:any)', 'LivestockAgeClassController::getLivestockAgeClassIdAndNameById/$1');
        $routes->get('livestock-age-class/(:any)', 'LivestockAgeClassController::getLivestockAgeClass/$1');
        $routes->post('insert-livestock-age-class', 'LivestockAgeClassController::insertLivestockAgeClass');
        $routes->put('update-livestock-age-class/(:any)', 'LivestockAgeClassController::updateLivestockAgeClass/$1');
        $routes->delete('delete-livestock-age-class/(:any)', 'LivestockAgeClassController::deleteLivestockAgeClass/$1');
    });

    // Admin endpoint routes
    $routes->group('admin', static function ($routes) {
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

        $routes->get('get-admin-tokens', 'UserController::getAllAdminFirebaseToken');
        $routes->get('get-farmer-tokens', 'UserController::getAllFarmerFirebaseToken');
        $routes->get('get-user-tokens/(:any)', 'UserController::getUserFirebaseToken/$1');
        
        // Livestock endpoint routes
        $routes->get('all-livestocks', 'LivestocksController::getAllLivestocks');
        $routes->get('all-farmer-livestocks/(:any)', 'LivestocksController::getFarmerAllLivestocks/$1');
        $routes->get('livestock/(:any)', 'LivestocksController::getLivestock/$1');
        $routes->post('add-livestock', 'LivestocksController::addLivestock');
        $routes->post('add-farmer-livestock', 'LivestocksController::addFarmerLivestock');
        $routes->put('update-livestock/(:any)', 'LivestocksController::updateLivestock/$1');
        $routes->put('livestock-health/(:any)', 'LivestocksController::updateLivestockHealthStatus/$1');
        $routes->put('livestock-record-stat/(:any)', 'LivestocksController::updateLivestockRecordStatus/$1');

        // Livestock Vaccination endpoint routes
        $routes->get('all-livestock-vaccinations', 'LivestockVaccinationsController::getAllLivestockVaccinations');
        $routes->get('all-farmer-livestock-vaccinations/(:any)', 'LivestockVaccinationsController::getAllFarmerLivestockVaccinations/$1');
        $routes->get('livestock-vaccination/(:any)', 'LivestockVaccinationsController::getLivestockVaccination/$1');
        $routes->post('add-livestock-vaccination', 'LivestockVaccinationsController::insertLivestockVaccination');
        $routes->put('update-livestock-vaccination/(:any)', 'LivestockVaccinationsController::updateLivestockVaccination/$1');
        $routes->put('update-livestock-vaccination-record-stat/(:any)', 'LivestockVaccinationsController::updateLivestockVaccinationRecordStatus/$1');
        $routes->delete('delete-livestock-vaccination/(:any)', 'LivestockVaccinationsController::deleteLivestockVaccination/$1');

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
        
        // Livestock Pregnancy endpoint routes
        $routes->get('all-livestock-pregnancies', 'LivestockPregnancyController::getAllLivestockPregnancies');
        $routes->get('livestock-pregnancy/(:any)', 'LivestockPregnancyController::getLivestockPregnancy/$1');
        $routes->get('all-farmer-livestock-pregnancies/(:any)', 'LivestockPregnancyController::getAllFarmerLivestockPregnancies/$1');
        $routes->put('successful-livestock-pregnancy/(:any)', 'LivestockPregnancyController::addSuccessfulLivestockPregnancy/$1');
        $routes->post('unsuccessful-livestock-pregnancy/(:any)', 'LivestockPregnancyController::addUnsuccessfulLivestockPregnancy/$1');

        // Livestock Offspring endpoint routes
        $routes->get('all-livestock-offspring', 'LivestockOffspringController::getAllLivestockOffspringRecords');
        $routes->get('livestock-offspring/(:any)', 'LivestockOffspringController::getLivestockOffspringRecord/$1');
        $routes->get('all-farmer-livestock-offspring/(:any)', 'LivestockOffspringController::getAllFarmerLivestockOffspringRecords/$1');
        $routes->post('add-livestock-offspring', 'LivestockOffspringController::insertLivestockOffspringRecord');
        $routes->put('update-livestock-offspring/(:any)', 'LivestockOffspringController::updateLivestockOffspringRecord/$1');
        $routes->put('update-livestock-offspring-record-stat/(:any)', 'LivestockOffspringController::updateLivestockOffspringRecordStatus/$1');
        $routes->delete('delete-livestock-offspring/(:any)', 'LivestockOffspringController::deleteLivestockOffspringRecord/$1');
        $routes->get('all-complete-livestock-offspring', 'LivestockOffspringController::getAllCompleteLivestockOffspringRecord');


        // Livestock Egg Production endpoint routes
        $routes->get('all-livestock-eggprods', 'LivestockEggProductionController::getAllEggProductions');
        $routes->get('all-farmer-livestock-eggprods/(:any)', 'LivestockEggProductionController::getAllFarmerEggProductions/$1');
        $routes->get('livestock-eggprods/(:any)', 'LivestockEggProductionController::getEggProduction/$1');
        $routes->post('add-livestock-eggprod', 'LivestockEggProductionController::insertEggProduction');
        $routes->put('update-livestock-eggprod/(:any)', 'LivestockEggProductionController::updateEggProduction/$1');
        $routes->put('update-livestock-eggprod-record-stat/(:any)', 'LivestockEggProductionController::updateEggProductionRecordStatus/$1');
        $routes->delete('delete-livestock-eggprod/(:any)', 'LivestockEggProductionController::deleteEggProduction/$1');

        // Livestock Mortality endpoint routes
        $routes->get('all-livestock-mortalities', 'LivestockMortalityController::getAllLivestockMortalities');
        $routes->get('all-farmer-livestock-mortalities/(:any)', 'LivestockMortalityController::getAllFarmerLivestockMortalities/$1');
        $routes->get('livestock-mortality/(:any)', 'LivestockMortalityController::getLivestockMortality/$1');
        $routes->post('add-livestock-mortality', 'LivestockMortalityController::insertLivestockMortality');
        $routes->put('update-livestock-mortality/(:any)', 'LivestockMortalityController::updateLivestockMortality/$1');
        $routes->put('update-livestock-mortality-record-stat/(:any)', 'LivestockMortalityController::updateLivestockMortalityRecordStatus/$1');
        $routes->delete('delete-livestock-mortality/(:any)', 'LivestockMortalityController::deleteLivestockMortality/$1');
        $routes->get('all-complete-livestock-mortalities', 'LivestockMortalityController::getAllCompleteLivestockMortalities');


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

        // testings routes
        $routes->get('livestock-primary-data/(:any)', 'LivestocksController::getLivestockPrimaryData/$1');
        $routes->get('get-user-name/(:any)', 'UserController::getUserName/$1');
        $routes->get('get-farmer-livestock-tag-id', 'LivestocksController::getFarmerLivestockIdByTag');
    });

    // Farmers endpoint routes
    $routes->group('farmer', static function ($routes) {
        // Farmer Dashboard routes
        $routes->get('livestock-type-age-class/(:any)', 'LivestocksController::getFarmerLivestockTypeAgeClassCount/$1');
        $routes->get('livestock-count/(:any)', 'LivestocksController::getFarmerLivestockCount/$1');
        $routes->get('livestock-type-count/(:any)', 'LivestocksController::getFarmerLivestockTypeCount/$1');

        // Farmer Livestocks endpoint routes
        $routes->post('add-livestock', 'LivestocksController::addFarmerLivestock');
        $routes->get('all-livestocks/(:any)', 'LivestocksController::getFarmerAllLivestocks/$1');
        $routes->put('update-livestock/(:any)', 'LivestocksController::updateLivestock/$1');
        $routes->put('livestock-health/(:any)', 'LivestocksController::updateLivestockHealthStatus/$1');
        $routes->put('livestock-record-stat/(:any)', 'LivestocksController::updateLivestockRecordStatus/$1');

        // Farmer Livestock Vaccinations endpoint routes
        $routes->get('all-livestock-vaccinations/(:any)', 'LivestockVaccinationsController::getAllFarmerLivestockVaccinations/$1');
        $routes->post('add-livestock-vaccination', 'LivestockVaccinationsController::insertLivestockVaccination');
        $routes->put('update-livestock-vaccination/(:any)', 'LivestockVaccinationsController::updateLivestockVaccination/$1');
        $routes->put('update-livestock-vaccination-record-stat/(:any)', 'LivestockVaccinationsController::updateLivestockVaccinationRecordStatus/$1');

        // Farmer Livestock Breedings endpoint routes
        $routes->get('all-livestock-breedings/(:any)', 'LivestockBreedingsController::getAllFarmerLivestockBreedings/$1');
        $routes->post('add-livestock-breeding', 'LivestockBreedingsController::insertLivestockBreeding');
        $routes->put('update-livestock-breeding/(:any)', 'LivestockBreedingsController::updateLivestockBreeding/$1');
        $routes->put('update-livestock-breeding-record-stat/(:any)', 'LivestockBreedingsController::updateLivestockBreedingRecordStatus/$1');

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
        $routes->get('get-farmer-profile/(:any)', 'UserController::getUser/$1');
        $routes->put('update-user-personal-info/(:any)', 'UserController::updateUserPersonalInfo/$1');
    });
});
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

    // Livestocks routes
    $routes->group('livestock', static function ($routes) {
        // Livestock Types routes
        $routes->get('all-livestock-types', 'LivestockTypesController::getLivestockTypes');
        $routes->get('livestock-type/(:any)', 'LivestockTypesController::getLivestockType/$1');
        $routes->post('insert-livestock-type', 'LivestockTypesController::insertLivestockType');
        $routes->put('update-livestock-type/(:any)', 'LivestockTypesController::updateLivestockType/$1');
        $routes->delete('delete-livestock-type/(:any)', 'LivestockTypesController::deleteLivestockType/$1');

        // Livestock Breeds routes
        $routes->get('all-livestock-breeds', 'LivestockBreedsController::getLivestockBreeds');
        $routes->get('livestock-breed/(:any)', 'LivestockBreedsController::getLivestockBreed/$1');
        $routes->post('insert-livestock-breed', 'LivestockBreedsController::insertLivestockBreed');
        $routes->put('update-livestock-breed/(:any)', 'LivestockBreedsController::updateLivestockBreed/$1');
        $routes->delete('delete-livestock-breed/(:any)', 'LivestockBreedsController::deleteLivestockBreed/$1');

        // Livestock Age Classifications routes
        $routes->get('all-livestock-age-classes', 'LivestockAgeClassController::getLivestockAgeClasses');
        $routes->get('livestock-age-class/(:any)', 'LivestockAgeClassController::getLivestockAgeClass/$1');
        $routes->post('insert-livestock-age-class', 'LivestockAgeClassController::insertLivestockAgeClass');
        $routes->put('update-livestock-age-class/(:any)', 'LivestockAgeClassController::updateLivestockAgeClass/$1');
        $routes->delete('delete-livestock-age-class/(:any)', 'LivestockAgeClassController::deleteLivestockAgeClass/$1');
    });

    // Admin routes
    $routes->group('admin', static function ($routes) {
        // User Management routes
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

        // Livestock routes
        $routes->get('all-livestocks', 'LivestocksController::getAllLivestocks');
        $routes->get('all-farmer-livestocks/(:any)', 'LivestocksController::getFarmerAllLivestocks/$1');
        $routes->get('livestock/(:any)', 'LivestocksController::getLivestock/$1');

        // Livestock Vaccination routes
        $routes->get('all-livestock-vaccinations', 'LivestockVaccinationsController::getAllLivestockVaccinations');
        $routes->get('all-farmer-livestock-vaccinations/(:any)', 'LivestockVaccinationsController::getAllFarmerLivestockVaccinations/$1');
        $routes->get('livestock-vaccination/(:any)', 'LivestockVaccinationsController::getLivestockVaccination/$1');
        $routes->post('add-livestock-vaccination', 'LivestockVaccinationsController::insertLivestockVaccination');
        $routes->put('update-livestock-vaccination/(:any)', 'LivestockVaccinationsController::updateLivestockVaccination/$1');
        $routes->put('update-livestock-vaccination-record-stat/(:any)', 'LivestockVaccinationsController::updateLivestockVaccinationRecordStatus/$1');
        $routes->delete('delete-livestock-vaccination/(:any)', 'LivestockVaccinationsController::deleteLivestockVaccination/$1');

        // Livestock Breedings routes
        $routes->get('all-livestock-breedings', 'LivestockBreedingsController::getAllLivestockBreedings');
        $routes->get('all-farmer-livestock-breedings/(:any)', 'LivestockBreedingsController::getAllFarmerLivestockBreedings/$1');
        $routes->get('livestock-breeding/(:any)', 'LivestockBreedingsController::getLivestockBreeding/$1');
        $routes->post('add-livestock-breeding', 'LivestockBreedingsController::insertLivestockBreeding');
        $routes->put('update-livestock-breeding/(:any)', 'LivestockBreedingsController::updateLivestockBreeding/$1');
        $routes->put('update-livestock-breeding-record-stat/(:any)', 'LivestockBreedingsController::updateLivestockBreedingRecordStatus/$1');
        $routes->delete('delete-livestock-breeding/(:any)', 'LivestockBreedingsController::deleteLivestockBreeding/$1');

        // Livestock Egg Production routes
        $routes->get('all-livestock-eggprods', 'LivestockEggProductionController::getAllEggProductions');
        $routes->get('all-farmer-livestock-eggprods/(:any)', 'LivestockEggProductionController::getAllFarmerEggProductions/$1');
        $routes->get('livestock-eggprods/(:any)', 'LivestockEggProductionController::getEggProduction/$1');
        $routes->post('add-livestock-eggprod', 'LivestockEggProductionController::insertEggProduction');
        $routes->put('update-livestock-eggprod/(:any)', 'LivestockEggProductionController::updateEggProduction/$1');
        $routes->put('update-livestock-eggprod-record-stat/(:any)', 'LivestockEggProductionController::updateEggProductionRecordStatus/$1');
        $routes->delete('delete-livestock-eggprod/(:any)', 'LivestockEggProductionController::deleteEggProduction/$1');

        // Livestock Mortality routes
        $routes->get('all-livestock-mortalities', 'LivestockMortalityController::getAllLivestockMortalities');
        $routes->get('all-farmer-livestock-mortalities/(:any)', 'LivestockMortalityController::getAllFarmerLivestockMortalities/$1');
        $routes->get('livestock-mortality/(:any)', 'LivestockMortalityController::getLivestockMortality/$1');
        $routes->post('add-livestock-mortality', 'LivestockMortalityController::insertLivestockMortality');
        $routes->put('update-livestock-mortality/(:any)', 'LivestockMortalityController::updateLivestockMortality/$1');
        $routes->put('update-livestock-mortality-record-stat/(:any)', 'LivestockMortalityController::updateLivestockMortalityRecordStatus/$1');
        $routes->delete('delete-livestock-mortality/(:any)', 'LivestockMortalityController::deleteLivestockMortality/$1');
    });

    // Farmers routes
    $routes->group('farmer', static function ($routes) {
        // Farmer Livestocks routes
        $routes->post('add-livestock', 'LivestocksController::addFarmerLivestock');
        $routes->get('all-livestocks/(:any)', 'LivestocksController::getFarmerAllLivestocks/$1');
        $routes->put('update-livestock/(:any)', 'LivestocksController::updateLivestock/$1');
        $routes->put('livestock-health/(:any)', 'LivestocksController::updateLivestockHealthStatus/$1');
        $routes->put('livestock-record-stat/(:any)', 'LivestocksController::updateLivestockRecordStatus/$1');

        // Farmer Livestock Vaccinations routes
        $routes->get('all-livestock-vaccinations/(:any)', 'LivestockVaccinationsController::getAllFarmerLivestockVaccinations/$1');
        $routes->post('add-livestock-vaccination', 'LivestockVaccinationsController::insertLivestockVaccination');
        $routes->put('update-livestock-vaccination/(:any)', 'LivestockVaccinationsController::updateLivestockVaccination/$1');
        $routes->put('update-livestock-vaccination-record-stat/(:any)', 'LivestockVaccinationsController::updateLivestockVaccinationRecordStatus/$1');

        // Farmer Livestock Breedings routes
        $routes->get('all-livestock-breedings/(:any)', 'LivestockBreedingsController::getAllFarmerLivestockBreedings/$1');
        $routes->post('add-livestock-breeding', 'LivestockBreedingsController::insertLivestockBreeding');
        $routes->put('update-livestock-breeding/(:any)', 'LivestockBreedingsController::updateLivestockBreeding/$1');
        $routes->put('update-livestock-breeding-record-stat/(:any)', 'LivestockBreedingsController::updateLivestockBreedingRecordStatus/$1');

        // Farmer Livestock Egg Production routes
        $routes->get('all-livestock-eggprods/(:any)', 'LivestockEggProductionController::getAllFarmerEggProductions/$1');
        $routes->post('add-livestock-eggprods', 'LivestockEggProductionController::insertEggProduction');
        $routes->put('update-livestock-eggprods/(:any)', 'LivestockEggProductionController::updateEggProduction/$1');
        $routes->put('update-livestock-eggprods-record-stat/(:any)', 'LivestockEggProductionController::updateEggProductionRecordStatus/$1');

        // Farmer Livestock Mortality routes
        $routes->get('all-livestock-mortalities/(:any)', 'LivestockMortalityController::getAllFarmerLivestockMortalities/$1');
        $routes->post('add-livestock-mortality', 'LivestockMortalityController::insertLivestockMortality');
        $routes->put('update-livestock-mortality/(:any)', 'LivestockMortalityController::updateLivestockMortality/$1');
        $routes->put('update-livestock-mortality-record-stat/(:any)', 'LivestockMortalityController::updateLivestockMortalityRecordStatus/$1');

        // Farmer User Management routes
        $routes->get('get-farmer-profile/(:any)', 'UserController::getUser/$1');
        $routes->put('update-user-personal-info/(:any)', 'UserController::updateUserPersonalInfo/$1');
    });
});
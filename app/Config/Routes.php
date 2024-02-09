<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// API routes
$routes->group('api',static function($routes){
    $routes->post('login', 'UserController::loginAuth');

    // Admin routes
    $routes->group('admin',static function($routes){
        $routes->post('register-user', 'UserController::registerUser');
    });

    // Farmers routes
    $routes->group('farmer',static function($routes){
        $routes->get('hello', 'UserController::index');
    });
});
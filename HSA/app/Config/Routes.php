<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('hsa', ['namespace' => 'App\Controllers\HSA'], static function($routes) {
    $routes->get('index', 'HsaController::index');
    $routes->post('renombrar', 'HsaController::insertarArchivos');
});

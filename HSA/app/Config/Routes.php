<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('hsa', ['namespace' => 'App\Controllers\HSA'], static function($routes) {

    // Ruta principal
    $routes->get('index', 'HsaController::index');

    // Ruta para subir/renombrar archivos
    $routes->post('renombrar', 'HsaController::insertarArchivos');

    // Ruta para cargar componentes dinámicamente
    $routes->get('component/(:segment)', 'HsaController::componente/$1');


    // ============================================
    //    RUTAS PARA EL EDITOR DE JSON
    // ============================================

    // Obtener lista de JSON desde una ruta
    $routes->post('getFiles', 'JsonController::getFiles');

    // Guardar cambios en un JSON
    $routes->post('save', 'JsonController::save');

});





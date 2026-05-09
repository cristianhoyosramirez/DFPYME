<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

/* $routes->group('reportes', ['namespace' => 'App\Controllers\Reportes'], function ($routes) {
    $routes->get('ventas', 'ReportesController::ventas');
}); */

// 🔹 GRUPO REPORTES
$routes->group('reportes', function($routes) {
    $routes->get('ventas', 'Reportes\ReportesController::ventas');
    $routes->get('vehiculos', 'Reportes\ReportesController::vehiculos');
    $routes->get('registro', 'Reportes\ReportesController::registro');
});

// 🔹 GRUPO RESERVAS
$routes->group('reservas', function($routes) {
    $routes->get('gestion', 'Reportes\ReservasController::index');
    $routes->post('confirmar', 'Reportes\ReservasController::confirmar');
    $routes->post('actualizarNota', 'Reportes\ReservasController::actualizarNota');
    $routes->post('eliminar', 'Reportes\ReservasController::eliminar');
    $routes->post('busquedaPorHabitacion', 'Reportes\ReservasController::busquedaPorHabitacion');
    $routes->post('busquedaPorEstado', 'Reportes\ReservasController::busquedaPorEstado');
    $routes->post('cancelarReserva', 'Reportes\ReservasController::cancelarReserva');
    $routes->post('buscarHabitaiconesFecha', 'Reportes\ReservasController::buscarHabitaiconesFecha');
    $routes->post('actualizarFechaReserva', 'Reportes\ReservasController::actualizarFechaReserva');
});

$routes->group('habitaciones', ['namespace' => 'App\Controllers\habitaciones'], function ($routes) {
    $routes->post('crear', 'HabitacionesController::crear');
    $routes->post('crearVehiculo', 'HabitacionesController::crearVehiculo');
    $routes->post('buscarCliente', 'HabitacionesController::buscarCliente');
    $routes->post('reservar', 'HabitacionesController::reservar');
    $routes->post('consultarReserva', 'HabitacionesController::consultarReserva');
    $routes->POST('checkIn', 'HabitacionesController::checkIn');
    $routes->post('buscarCiudadProcedencia', 'HabitacionesController::buscarCiudadProcedencia');
    $routes->post('buscarCiudadDestino', 'HabitacionesController::buscarCiudadDestino');
    $routes->post('buscarPlaca', 'HabitacionesController::buscarPlaca');
    $routes->post('nuevaReserva', 'HabitacionesController::nuevaReserva');
    $routes->post('datosReserva', 'HabitacionesController::datosReserva');
}); 

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

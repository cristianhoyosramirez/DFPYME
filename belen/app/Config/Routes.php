<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');




$routes->group('categorias', ['namespace' => 'App\Controllers\Categorias'], function ($routes) {

    // Listar categorías
    $routes->get('/', 'CategoriasController::index');

    // Mostrar formulario de creación
    $routes->get('crear', 'CategoriasController::crear');

    // Guardar categoría
    $routes->post('guardar', 'CategoriasController::guardar');

    // Editar categoría
    $routes->get('editar/(:num)', 'CategoriasController::editar/$1');

    // Actualizar categoría
    $routes->post('actualizar/(:num)', 'CategoriasController::actualizar/$1');

    // Eliminar categoría
    $routes->post('eliminar/(:num)', 'CategoriasController::eliminar/$1');

}); 


$routes->group('productos', ['namespace' => 'App\Controllers\Productos'], function ($routes) {

    // Listar categorías
    $routes->get('/', 'ProductosController::index');

    // Mostrar formulario de creación
    $routes->get('crear', 'CategoriasController::crear');

    // Guardar categoría
    $routes->post('guardar', 'CategoriasController::guardar');

    // Editar categoría
    $routes->get('editar/(:num)', 'CategoriasController::editar/$1');

    // Actualizar categoría
    $routes->post('actualizar/(:num)', 'CategoriasController::actualizar/$1');

    // Eliminar categoría
    $routes->post('eliminar/(:num)', 'CategoriasController::eliminar/$1');

    $routes->post('bloque-compuesto', 'ProductosController::bloqueCompuesto');


}); 


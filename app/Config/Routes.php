<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rutas públicas
$routes->get('/', 'HomeController::index');
$routes->get('/welcome', 'HomeController::welcome');

// Rutas de autenticación
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::attemptLogin');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/register', 'AuthController::register');
$routes->post('/register', 'AuthController::attemptRegister');

// Dashboard
$routes->get('/dashboard', 'HomeController::dashboard');

// Rutas de Personas
$routes->get('/personas', 'PersonaController::index');
$routes->get('/personas/create', 'PersonaController::create');
$routes->post('/personas', 'PersonaController::store');
$routes->get('/personas/show/(:num)', 'PersonaController::show/$1');
$routes->get('/personas/edit/(:num)', 'PersonaController::edit/$1');
$routes->post('/personas/update/(:num)', 'PersonaController::update/$1');
$routes->post('/personas/delete/(:num)', 'PersonaController::delete/$1');
$routes->get('/personas/buscar', 'PersonaController::buscar');
$routes->get('/personas/estadisticas', 'PersonaController::estadisticas');

// Rutas de Evaluaciones
$routes->get('/evaluaciones', 'EvaluacionController::index');
$routes->get('/evaluaciones/create', 'EvaluacionController::create');
$routes->post('/evaluaciones', 'EvaluacionController::store');
$routes->get('/evaluaciones/show/(:num)', 'EvaluacionController::show/$1');
$routes->get('/evaluaciones/edit/(:num)', 'EvaluacionController::edit/$1');
$routes->post('/evaluaciones/update/(:num)', 'EvaluacionController::update/$1');
$routes->post('/evaluaciones/delete/(:num)', 'EvaluacionController::delete/$1');

// Rutas de Seguimientos
$routes->get('/seguimientos', 'SeguimientoController::index');
$routes->get('/seguimientos/create', 'SeguimientoController::create');
$routes->post('/seguimientos', 'SeguimientoController::store');
$routes->get('/seguimientos/show/(:num)', 'SeguimientoController::show/$1');
$routes->get('/seguimientos/edit/(:num)', 'SeguimientoController::edit/$1');
$routes->post('/seguimientos/update/(:num)', 'SeguimientoController::update/$1');
$routes->post('/seguimientos/delete/(:num)', 'SeguimientoController::delete/$1');
$routes->get('/seguimientos/pendientes', 'SeguimientoController::pendientes');
$routes->get('/seguimientos/proximos', 'SeguimientoController::proximos');
$routes->post('/seguimientos/cambiar-estado/(:num)', 'SeguimientoController::cambiarEstado/$1');

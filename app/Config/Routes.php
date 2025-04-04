<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
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

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// Rutas públicas (sin autenticación)
$routes->get('login', 'Usuarios::login');
$routes->post('login/auth', 'Usuarios::verificarLogin');
$routes->get('logout', 'Usuarios::logout');

// Rutas protegidas
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('home', 'Home::index');
    $routes->get('usuarios/cambio_clave', 'Usuarios::cambioClave');
    // otras rutas protegidas
}); // Controlador Usuarios, método logout



$routes->get('inscripciones/verFactura/(:num)', 'Inscripciones::verFactura/$1');
$routes->get('inscripciones/imprimirFactura/(:num)', 'Inscripciones::imprimirFactura/$1');

$routes->get('inscripciones/obtenerMensualidadesPendientes', 'Inscripciones::obtenerMensualidadesPendientes');
$routes->post('inscripciones/registrarPagoMensualidad', 'Inscripciones::registrarPagoMensualidad');
$routes->get('inscripciones/mensualidades', 'Inscripciones::mensualidades');
$routes->post('responsables/obtenerMunicipios', 'Responsables::obtenerMunicipios');
$routes->post('responsables/obtenerDistritos', 'Responsables::obtenerDistritos');



/**
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
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

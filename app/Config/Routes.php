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
    $routes->post('usuarios/actualizarClave', 'Usuarios::actualizarClave');
    

    // otras rutas protegidas
}); 
// Controlador Usuarios, método logout

$routes->get('calificaciones/buscar-docentes', 'Calificaciones::buscarDocentes');
$routes->get('calificaciones/buscar-cursos/(:num)', 'Calificaciones::buscarCursos/$1');
$routes->get('calificaciones/buscar-asignaturas/(:num)/(:num)', 'Calificaciones::buscarAsignaturas/$1/$2');
$routes->get('calificaciones/obtener-distribucion-asignatura', 'Calificaciones::obtenerDistribucionAsignatura');
$routes->get('calificaciones/estudiantes-por-curso/(:num)', 'Calificaciones::estudiantesPorCurso/$1');
$routes->post('calificaciones/bloquear-periodo', 'Calificaciones::bloquearPeriodo');
$routes->post('calificaciones/guardar-configuracion-periodos', 'Calificaciones::guardarConfiguracionPeriodos');
$routes->get('escuela/visualizar', 'Escuela::visualizar'); // Sin ID (usa sesión)
$routes->get('escuela/visualizar/(:num)', 'Escuela::visualizar/$1'); // Con ID directo
$routes->post('escuela/actualizar', 'Escuela::actualizar');
$routes->get('grados-y-secciones', 'GradosSecciones::index');
$routes->get('grados-y-secciones/grados', 'GradosSecciones::grados');
$routes->get('grados-y-secciones/grados/grados_inactivos', 'GradosSecciones::grados_inactivos');
$routes->get('grados-y-secciones/grados/inactivar_grado/(:num)', 'GradosSecciones::inactivar_grado/$1');
$routes->get('grados-y-secciones/grados/restaurar_grado/(:num)', 'GradosSecciones::restaurar_grado/$1');

$routes->get('grados-y-secciones/cursos', 'GradosSecciones::cursos');
$routes->get('grados-y-secciones/cursos/curso_nuevo', 'GradosSecciones::curso_nuevo');


$routes->get('grados-y-secciones/configurar_cursos', 'GradosSecciones::configurar_cursos');

$routes->get('gradossecciones/obtener_cursos_por_servicio/(:num)', 'GradosSecciones::obtenerCursosPorServicio/$1');
$routes->post('gradossecciones/guardar_configuracion_cursos', 'GradosSecciones::guardar_configuracion_cursos');


$routes->post('cursosbase/insertar', 'CursosBase::insertar');

/* ESTUDIANTES */
$routes->post('estudiantes/actualizar', 'Estudiantes::actualizar');


$routes->get('calificaciones/reporte', 'Calificaciones::generarReportePDF');




$routes->get('usuarios/listarUsuarios', 'Usuarios::listarUsuarios');


$routes->get('pagos/verFactura/(:num)', 'pagos::verFactura/$1');
$routes->get('pagos/imprimirFactura/(:num)', 'pagos::imprimirFactura/$1');

$routes->get('pagos/obtenerMensualidadesPendientes', 'pagos::obtenerMensualidadesPendientes');
$routes->post('pagos/registrarPagoMensualidad', 'pagos::registrarPagoMensualidad');
$routes->get('pagos/mensualidades', 'pagos::mensualidades');
$routes->post('responsables/obtenerMunicipios', 'Responsables::obtenerMunicipios');
$routes->post('responsables/obtenerDistritos', 'Responsables::obtenerDistritos');

$routes->get('reportes/estadisticas/estadistica_personal', 'Reportes::estadistica_personal');
$routes->get('reportes/listados/listado_personal', 'Reportes::listado_personal');



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

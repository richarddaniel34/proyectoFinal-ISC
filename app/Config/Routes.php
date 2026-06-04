<?php

namespace Config;

$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}


// CONFIG BASE
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false); // 👈 clave, pero ahora TODO debe estar declarado

// ======================
//  RUTAS PÚBLICAS
// ======================
$routes->get('login', 'Usuarios::login');
$routes->post('login/auth', 'Usuarios::verificarLogin');
$routes->get('logout', 'Usuarios::logout');

// ======================
//  RUTAS CON LOGIN
// ======================
$routes->group('', ['filter' => 'auth'], function ($routes) {

    $routes->get('home', 'Home::index');

    $routes->group('usuarios', function ($routes) {
        $routes->get('cambio_clave', 'Usuarios::cambioClave');
        $routes->post('actualizarClave', 'Usuarios::actualizarClave');
    });
});

// ======================
//  RUTAS COMPLETAS (CON DATOS DE USUARIO)
// ======================
$routes->group('', ['filter' => ['auth', 'usuarioData']], function ($routes) {

    // HOME
    $routes->get('home', 'Home::index');

    // USUARIOS
    $routes->group('usuarios', function ($routes) {
        $routes->get('listarUsuarios', 'Usuarios::listarUsuarios');
        $routes->get('getEscuelas', 'Usuarios::getEscuelas');
        $routes->post('cambiarEscuela', 'Usuarios::cambiarEscuela');
    });


    //Schoolyear
    $routes->group('schoolyear', function ($routes) {
        $routes->get('/', 'Schoolyear::index');
        $routes->get('nuevo', 'Schoolyear::nuevo');
        $routes->post('insertar', 'Schoolyear::insertar');
        $routes->post('actualizar/(:num)', 'Schoolyear::actualizar/$1');
        $routes->get('editar/(:num)', 'Schoolyear::editar/$1');
    });


    // ESTUDIANTES
    $routes->group('estudiantes', function ($routes) {
        $routes->get('/', 'Estudiantes::index');
        $routes->get('nuevo', 'Estudiantes::nuevo');
        $routes->post('insertar', 'Estudiantes::insertar');
        $routes->get('eliminados', 'Estudiantes::eliminados');
        $routes->get('editar/(:num)', 'Estudiantes::editar/$1');
        $routes->get('visualizar/(:num)', 'Estudiantes::visualizar/$1');
        $routes->post('actualizar', 'Estudiantes::actualizar');
    });

    // PERSONAL
    $routes->group('personal', function ($routes) {
        $routes->get('/', 'Personal::index');
        $routes->get('nuevo', 'Personal::nuevo');
        $routes->get('eliminados', 'Personal::eliminados');
        $routes->get('editar/(:num)', 'Personal::editar/$1');
        $routes->get('eliminar/(:num)', 'Personal::eliminar/$1');
        $routes->get('restaurar/(:num)', 'Personal::restaurar/$1');
        $routes->get('visualizar/(:num)', 'Personal::visualizar/$1');
    });

    // RESPONSABLES
    $routes->group('responsables', function ($routes) {
        $routes->get('/', 'Responsables::index');
        $routes->get('nuevo', 'Responsables::nuevo');
        $routes->get('eliminados', 'Responsables::eliminados');
        $routes->post('obtenerDistritos', 'Responsables::obtenerDistritos');
        $routes->get('buscar', 'Responsables::buscar');
    });

    // ESCUELA
    $routes->group('escuela', function ($routes) {
        $routes->get('/', 'Escuela::index');
        $routes->get('info_escuela', 'Escuela::info_escuela');
        $routes->get('nuevo', 'Escuela::nuevo');
        $routes->get('eliminados', 'Escuela::eliminados');
        $routes->get('editar/(:num)', 'Escuela::editar/$1');
        $routes->get('eliminar/(:num)', 'Escuela::eliminar/$1');
        $routes->get('restaurar/(:num)', 'Escuela::restaurar/$1');
        $routes->get('visualizar', 'Escuela::visualizar');
        $routes->get('visualizar/(:num)', 'Escuela::visualizar/$1');
        $routes->post('actualizar', 'Escuela::actualizar');
    });


    // GRADOS
    $routes->group('grados-y-secciones', function ($routes) {
        $routes->get('/', 'GradosSecciones::index');

        $routes->get('grados', 'GradosSecciones::grados');
        $routes->get('grados/grados_inactivos', 'GradosSecciones::grados_inactivos');
        $routes->get('grados/inactivar_grado/(:num)', 'GradosSecciones::inactivar_grado/$1');
        $routes->get('grados/restaurar_grado/(:num)', 'GradosSecciones::restaurar_grado/$1');

        $routes->get('cursos', 'GradosSecciones::cursos');
        $routes->get('cursos/curso_nuevo', 'GradosSecciones::curso_nuevo');

        $routes->get('configurar_cursos', 'GradosSecciones::configurar_cursos');
        $routes->get('obtener_cursos_por_servicio/(:num)', 'GradosSecciones::obtenerCursosPorServicio/$1');
        $routes->post('guardar_configuracion_cursos', 'GradosSecciones::guardar_configuracion_cursos');
        $routes->get(
            'obtenerCursosPorServicioInscripcion',
            'GradosSecciones::obtenerCursosPorServicioInscripcion'
        );
    });

    // CALIFICACIONES
    $routes->group('calificaciones', function ($routes) {
        $routes->get('/', 'Calificaciones::index');
        $routes->get('registro', 'Calificaciones::registro');
        $routes->get('completivo', 'Calificaciones::completivo');
        $routes->get('extraordinario', 'Calificaciones::extraordinario');
        $routes->get('especiales', 'Calificaciones::especiales');
        $routes->get('buscar-docentes', 'Calificaciones::buscarDocentes');
        $routes->get('buscar-cursos/(:num)', 'Calificaciones::buscarCursos/$1');
        $routes->get('buscar-asignaturas/(:num)/(:num)', 'Calificaciones::buscarAsignaturas/$1/$2');
        $routes->get('obtener-distribucion-asignatura', 'Calificaciones::obtenerDistribucionAsignatura');
        $routes->get('estudiantes-por-curso/(:num)', 'Calificaciones::estudiantesPorCurso/$1');

        $routes->get('estudiantes-completivo', 'Calificaciones::estudiantesCompletivo');
        $routes->get('estudiantes-extraordinario', 'Calificaciones::estudiantesExtraordinario');
        $routes->get('estudiantes-especial', 'Calificaciones::estudiantesEspecial');

        $routes->post('guardarNotas', 'Calificaciones::guardarNotas');
        $routes->post('guardarCompletivo', 'Calificaciones::guardarCompletivo');
        $routes->post('guardarExtraordinario', 'Calificaciones::guardarExtraordinario');
        $routes->post('guardarEspecial', 'Calificaciones::guardarEspecial');
        $routes->get('obtenerNotas', 'Calificaciones::obtenerNotas');

        $routes->post('guardar-configuracion-periodos', 'Calificaciones::guardarConfiguracionPeriodos');
        $routes->get('reporte', 'Calificaciones::generarReportePDF');
    });
    // PAGOS
    $routes->group('pagos', function ($routes) {
        $routes->get('/', 'Pagos::index');
        $routes->get('nueva_inscripcion', 'Pagos::nueva_Inscripcion');
        $routes->get('otros_pagos', 'Pagos::otros_pagos');
        $routes->post('registrar_inscripcion', 'Pagos::registrar_inscripcion');

        $routes->get('verFactura/(:num)', 'Pagos::verFactura/$1');
        $routes->get('imprimirFactura/(:num)', 'Pagos::imprimirFactura/$1');
        $routes->get('obtenerEstudiantes', 'Pagos::obtenerEstudiantes');

        $routes->get('obtenerMensualidadesPendientes', 'Pagos::obtenerMensualidadesPendientes');
        $routes->post('registrarPagoMensualidad', 'Pagos::registrarPagoMensualidad');
    });

    // REPORTES
    $routes->group('reportes', function ($routes) {
        $routes->get('/', 'Reportes::index');
        $routes->get('estadisticas/estadistica_personal', 'Reportes::estadistica_personal');
        $routes->get('listados/listado_personal', 'Reportes::listado_personal');
    });

    // DOCENTE GUIA
    $routes->group('docenteguia', function ($routes) {
        $routes->get('/', 'DocenteGuia::index');
        $routes->get('nuevo', 'DocenteGuia::nuevo');
        $routes->post('insertar', 'DocenteGuia::insertar');
        $routes->get('editar/(:num)', 'DocenteGuia::editar/$1');
        $routes->post('actualizar/(:num)', 'DocenteGuia::actualizar/$1');
    });

    // ASIGNATURAS
    $routes->group('asignaturas', function ($routes) {
        $routes->get('/', 'Asignaturas::index');
        $routes->get('nuevo', 'Asignaturas::nuevo');
        $routes->post('insertar', 'Asignaturas::insertar');

        $routes->get('inactivas', 'Asignaturas::asignaturasInactivas');
        $routes->get('editar/(:num)', 'Asignaturas::editar/$1');
        $routes->get('inactivar/(:num)', 'Asignaturas::inactivar/$1');
        $routes->get('restaurar/(:num)', 'Asignaturas::restaurar/$1');

        $routes->post('actualizar/(:num)', 'Asignaturas::actualizar/$1');
    });



    // DISTRIBUCIÓN DE ASIGNATURAS
    $routes->group('distribucion-asignaturas', function ($routes) {
        $routes->get('/', 'DistribucionAsignaturas::index');
        $routes->get('nuevo', 'DistribucionAsignaturas::nuevo');
        $routes->post('insertar', 'DistribucionAsignaturas::insertar');
        $routes->get('asignaturas', 'DistribucionAsignaturas::getAsignaturasAjax');
    });


    //INSCRIPCIONES
    $routes->group('inscripciones', function ($routes) {
        $routes->get('/', 'Inscripciones::index');
        $routes->get('relacion', 'Inscripciones::relacion');
        //$routes->get('obtenerCursosPorServicioInscripcion', 'inscripciones::obtenerCursosPorServicioInscripcion');
        $routes->get('obtenerInscripcionesPorCurso', 'inscripciones::obtenerInscripcionesPorCurso');
        $routes->get('obtenerCursosPorServicioRelacion', 'inscripciones::obtenerCursosPorServicioRelacion');
    });
});

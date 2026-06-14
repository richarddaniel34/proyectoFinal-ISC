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

        // Cambio de clave propio
        $routes->get('cambioClave', 'Usuarios::cambioClave');

        // Reset administrativo
        $routes->get('modalResetearClave/(:num)', 'Usuarios::modalResetearClave/$1');
        $routes->post('resetearClave', 'Usuarios::resetearClave');
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


    // STUDENTS
    $routes->group('student', function ($routes) {
        $routes->get('mis_pagos', 'student::mis_pagos');
        $routes->get('calificaciones', 'Estudiante::calificaciones');
        $routes->get('asistencia', 'Estudiante::asistencia');
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


    // ESTRUCTURA ACADEMICA
    $routes->group('estructura-academica', function ($routes) {
        $routes->get('/', 'EstructuraAcademica::index');
        //===========> GRADOS
        $routes->get('grados', 'EstructuraAcademica::grados');
        $routes->get('grados/inactivos', 'EstructuraAcademica::inactivos');
        $routes->get('grados/inactivarGrado/(:num)', 'EstructuraAcademica::inactivarGrado/$1');
        $routes->get('grados/restaurarGrado/(:num)', 'EstructuraAcademica::restaurarGrado/$1');
        //===========> CURSOS
        $routes->get('cursos', 'EstructuraAcademica::cursos');
        $routes->get('cursos/nuevo', 'EstructuraAcademica::cursoNuevo');
        $routes->post('cursos/guardar', 'EstructuraAcademica::guardarCursos');
        //===========> CURSOS POR AÑO
        $routes->get('configurarCursos', 'EstructuraAcademica::configurarCursos');
        $routes->get('configurarCursos/nuevo', 'EstructuraAcademica::nuevo');
        $routes->post('configurarCursos/guardar', 'EstructuraAcademica::guardar_configuracion_cursos');

        $routes->get(
            'obtener_cursos_por_servicio/(:segment)',
            'EstructuraAcademica::obtenerCursosPorServicio/$1'
        );

        $routes->get('obtenerCursosPorServicioInscripcion', 'EstructuraAcademica::obtenerCursosPorServicioInscripcion');

        $routes->post('configurarCursos/actualizar_curso/(:num)', 'EstructuraAcademica::actualizar_curso/$1');
    });



    // CALIFICACIONES
    $routes->group('calificaciones', function ($routes) {
        $routes->get('/', 'Calificaciones::index');
        $routes->get('registro', 'Calificaciones::registro');
        $routes->get('configurarra', 'Calificaciones::configurarra');
        $routes->get('completivo', 'Calificaciones::completivo');
        $routes->get('extraordinario', 'Calificaciones::extraordinario');
        $routes->get('especiales', 'Calificaciones::especiales');
        $routes->get('buscar-docentes', 'Calificaciones::buscarDocentes');
        $routes->get('buscar-cursos/(:num)', 'Calificaciones::buscarCursos/$1');
        $routes->get('buscar-asignaturas/(:num)/(:num)', 'Calificaciones::buscarAsignaturas/$1/$2');
        $routes->get('obtener-distribucion-asignatura', 'Calificaciones::obtenerDistribucionAsignatura');
        $routes->get('estudiantes-por-curso/(:num)', 'Calificaciones::estudiantesPorCurso/$1');
        $routes->get('obtener', 'Calificaciones::obtener');
        $routes->get('obtenerNotasTecnicas', 'Calificaciones::obtenerNotasTecnicas');

        $routes->get('estudiantes-completivo', 'Calificaciones::estudiantesCompletivo');
        $routes->get('estudiantes-extraordinario', 'Calificaciones::estudiantesExtraordinario');
        $routes->get('estudiantes-especial', 'Calificaciones::estudiantesEspecial');

        $routes->post('guardarNotasTecnicas', 'Calificaciones::guardarNotasTecnicas');
        $routes->post('guardarra', 'Calificaciones::guardarra');
        $routes->post('guardarNotas', 'Calificaciones::guardarNotas');
        $routes->post('guardarCompletivo', 'Calificaciones::guardarCompletivo');
        $routes->post('guardarExtraordinario', 'Calificaciones::guardarExtraordinario');
        $routes->post('guardarEspecial', 'Calificaciones::guardarEspecial');
        $routes->get('obtenerNotas', 'Calificaciones::obtenerNotas');

        $routes->get('tecnicas', 'Calificaciones::tecnicas');

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
        //$routes->get('obtenerEstudiantes', 'Pagos::obtenerEstudiantes');
        $routes->get('obtenerGrupoFamiliarPorEstudiante', 'Pagos::obtenerGrupoFamiliarPorEstudiante');

        $routes->get('obtenerMensualidadesPendientes', 'Pagos::obtenerMensualidadesPendientes');
        $routes->post('registrarPagoMensualidad', 'Pagos::registrarPagoMensualidad');
    });


    //ASISTENCIA
    $routes->group('asistencia', function ($routes) {
        $routes->get('/', 'Asistencia::index');
        $routes->get('nuevo', 'Asistencia::nuevo');

        //buscar
        $routes->get('buscar-cursos/(:num)', 'Asistencia::buscarCursos/$1');
        $routes->get('buscar-asignaturas/(:num)', 'Asistencia::buscarAsignaturas/$1');
        $routes->get('buscar-asignaturas/(:num)/(:num)', 'Asistencia::buscarAsignaturas/$1/$2');
        $routes->get('estudiantes-por-curso/(:num)', 'Asistencia::estudiantesPorCurso/$1');
        $routes->get('buscar-docentes', 'Asistencia::buscarDocentes');
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
    $routes->group('distribucion-academica', function ($routes) {
        $routes->get('/', 'DistribucionAcademica::index');
        $routes->get('nuevo', 'DistribucionAcademica::nuevo');
        $routes->post('insertar', 'DistribucionAcademica::insertar');
        $routes->get('asignaturas', 'DistribucionAcademica::getAsignaturasAjax');
        $routes->get('docentes', 'DistribucionAcademica::getDocentesAjax');
        $routes->post('actualizar-docente', 'DistribucionAcademica::actualizarDocente');
        $routes->post('copiar-anterior', 'DistribucionAcademica::copiarAnterior');
    });


    //INSCRIPCIONES
    $routes->group('inscripciones', function ($routes) {
        $routes->get('/', 'Inscripciones::index');
        $routes->get('relacion', 'Inscripciones::relacion');
        //$routes->get('obtenerCursosPorServicioInscripcion', 'inscripciones::obtenerCursosPorServicioInscripcion');
        $routes->get('obtenerInscripcionesPorCurso', 'inscripciones::obtenerInscripcionesPorCurso');
        $routes->get('obtenerCursosPorServicioRelacion', 'inscripciones::obtenerCursosPorServicioRelacion');
    });


    //CONFIGIRAR RA
    $routes->group('configuracion-ra', function ($routes) {

        $routes->get('/', 'ConfiguracionRaTecnica::index');

        $routes->get(
            'obtener',
            'ConfiguracionRaTecnica::obtener'
        );

        $routes->post(
            'guardar',
            'ConfiguracionRaTecnica::guardar'
        );
    });
});

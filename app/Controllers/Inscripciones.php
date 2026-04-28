<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\InscripcionesModel;
use App\Models\SchoolyearModel;
use \App\Models\ServiciosModel;
use \App\Models\EscuelaServiciosModel;
//use \App\Models\Cursos;
use App\Models\CursosModel;
use App\Models\EstudiantesModel;
use App\Models\UsuariosModel;

class Inscripciones extends BaseController
{
    protected $inscripciones;
    protected $schoolyear;
    protected $servicios;
    protected $escuelaservicios;
    protected $cursos;
    protected $estudiantes;
    protected $usuarios;


    public function __construct()
    {
        $this->inscripciones = new InscripcionesModel();
        $this->schoolyear = new SchoolyearModel();
        $this->servicios = new ServiciosModel();
        $this->escuelaservicios = new EscuelaServiciosModel();
        $this->cursos = new CursosModel();
        $this->estudiantes = new EstudiantesModel();
        $this->usuarios = new UsuariosModel();
    }

    public function relacion($activo = 1)
    {
        $id_escuela = session('id_escuela');
        $schoolYear = $this->schoolyear
            ->where('estado', 'En curso')
            ->orderBy('id', 'DESC')
            ->first();

        //Servicios
        $servicios = $this->escuelaservicios
            ->select('escuelas_servicios.id_servicio, servicios.nombre AS nombre_servicio, salidas_tecnicas.nombre AS nombre_salida')
            ->join('servicios', 'servicios.id = escuelas_servicios.id_servicio')
            ->join('salidas_tecnicas', 'servicios.id = salidas_tecnicas.id_servicio', 'left')
            ->where('escuelas_servicios.id_escuela', $id_escuela)
            ->findAll();

        // Cursos que tienen estudiantes inscritos para esta escuela y año escolar
        $cursos = $this->cursos
            ->distinct()
            ->from('cursos c') // alias para cursos
            ->select('c.id, cb.nombre_curso')
            ->join('cursos_base cb', 'cb.id = c.id_cursos_base')
            ->join('inscripciones i', 'i.id_curso = c.id', 'inner')
            ->where('cb.id_escuela', $id_escuela)
            ->where('i.id_schoolYear', $schoolYear['id'])
            ->orderBy('c.id', 'ASC')
            ->findAll();



        $data = [
            'titulo' => 'Relación de Estudiantes',
            'schoolYear' => $schoolYear,
            'servicios' => $servicios,
            'cursos' => $cursos,
        ];

        echo view('header');
        echo view('inscripciones/relacion', $data);
        echo view('footer');
    }


    /*METODO PARA CARGAR LAS INSCRIPCIONES POR CURSO, AÑO Y ESCUELA
*/
    /* public function obtenerInscripcionesPorCurso()
    {
        $idCurso = $this->request->getGet('id_curso');
        $idSchoolYear = $this->request->getGet('id_schoolyear');

        $inscripciones = $this->inscripciones
            ->distinct()
            ->from('inscripciones i')
            ->select('i.id AS id_inscripcion, i.id_curso AS id_curso, i.id_estudiante, CONCAT(e.apellido, ", ", e.nombre) AS estudiante, cb.nombre_curso AS nombre_curso, i.condicion_inicial, i.estado, i.condicion_final')
            ->join('estudiantes e', 'e.id = i.id_estudiante')
            ->join('cursos c', 'c.id = i.id_curso')
            ->join('cursos_base cb', 'cb.id = c.id_cursos_base')
            ->where('i.id_curso', $idCurso)
            ->where('i.id_schoolYear', $idSchoolYear)
            ->where('i.activo', 1)
            ->findAll();

        return $this->response->setJSON($inscripciones);
    }*/

    public function obtenerInscripcionesPorCurso()
    {
        $idCurso = $this->request->getGet('id_curso');
        $idSchoolYear = $this->request->getGet('id_schoolyear');

        $inscripciones = $this->inscripciones
            ->distinct()
            ->from('inscripciones i')
            ->select('i.id AS id_inscripcion, i.id_curso AS id_curso, i.id_estudiante, CONCAT(e.apellido, ", ", e.nombre) AS estudiante, cb.nombre_curso AS nombre_curso, i.condicion_inicial, i.estado, i.condicion_final')
            ->join('estudiantes e', 'e.id = i.id_estudiante')
            ->join('cursos c', 'c.id = i.id_curso')
            ->join('cursos_base cb', 'cb.id = c.id_cursos_base')
            ->where('i.id_curso', $idCurso)
            ->where('i.id_schoolYear', $idSchoolYear)
            ->where('i.activo', 1)
            ->findAll();

        return $this->response->setJSON($inscripciones);
    }


    /*
TRABAJAR CON LOS CHECK DE HABILITACION DE EDICION DE CURSOS, OPCIONALMENTE UNA VISTA SEPARA PARA CAMBIO DE TECNICO

*/

    public function obtenerCursosPorServicio()
    {
        $id_escuela = session('id_escuela');
        $id_servicio = $this->request->getGet('id_servicio');
        $salida = $this->request->getGet('salida'); // nombre de la especialidad
        $schoolYear = $this->schoolyear->where('estado', 'En curso')->first();

        $builder = $this->cursos
            ->select('c.id, cb.nombre_curso')
            ->join('cursos_base cb', 'cb.id = c.id_cursos_base', 'inner')
            ->where('cb.id_escuela', $id_escuela)
            ->where('cb.id_servicio', $id_servicio)
            ->where('c.id_schoolYear', $schoolYear['id'])
            ->where('c.activo', 1);

        // Si es técnico, buscar coincidencia exacta por nombre de salida
        if (!empty($salida)) {
            $builder->join('salidas_tecnicas st', 'st.id = cb.id_salida_tecnica', 'left');
            $builder->where('st.nombre', $salida);
        }

        $cursos = $builder->orderBy('cb.nombre_curso', 'ASC')->findAll();

        return $this->response->setJSON($cursos);
    }



    public function obtenerCursosPorServicioRelacion()
    {
        $id_escuela = session('id_escuela');
        $id_servicio = $this->request->getGet('id_servicio');
        $especialidad = $this->request->getGet('salida'); // <-- nombre más claro
        $schoolYear = $this->schoolyear->where('estado', 'En curso')->first();

        $builder = $this->cursos
            ->distinct()
            ->from('cursos c')
            ->select('c.id, cb.nombre_curso')
            ->join('cursos_base cb', 'cb.id = c.id_cursos_base')
            ->join('inscripciones i', 'i.id_curso = c.id', 'inner')
            ->where('cb.id_escuela', $id_escuela)
            ->where('cb.id_servicio', $id_servicio)
            ->where('i.id_schoolYear', $schoolYear['id']);

        if ($especialidad) {
            // Filtrar por la "salida" después del guion
            $builder->groupStart()
                ->like('SUBSTRING_INDEX(cb.nombre_curso, "-", -1)', $especialidad) // MySQL: toma todo después del último guion
                ->groupEnd();
        }

        $cursos = $builder->orderBy('c.id', 'ASC')->findAll();

        return $this->response->setJSON($cursos);
    }




    public function obtenerSeccionesDelMismoGrado()
    {
        $idCursoActual = $this->request->getGet('id_curso');
        $idEscuela = session('id_escuela');

        log_message('debug', "obtenerSeccionesDelMismoGrado - idCursoActual: " . json_encode($idCursoActual));
        log_message('debug', "obtenerSeccionesDelMismoGrado - idEscuela: " . json_encode($idEscuela));

        if (!$idCursoActual || !$idEscuela) {
            log_message('debug', "Faltan parámetros: idCursoActual o idEscuela");
            return $this->response->setJSON([]);
        }

        // Obtenemos el grado del curso actual
        $cursoActual = $this->cursos
            ->select('cb.id_grado, cb.nombre_curso as nombre_curso_actual')
            ->join('cursos_base cb', 'cb.id = cursos.id_cursos_base')
            ->where('cursos.id', $idCursoActual)
            ->first();

        log_message('debug', "Curso actual obtenido: " . json_encode($cursoActual));

        if (!$cursoActual) {
            log_message('debug', "No se encontró el curso actual con id: " . $idCursoActual);
            return $this->response->setJSON([]);
        }

        // Traemos todas las secciones de ese grado en la escuela
        $secciones = $this->cursos
            ->select('cursos.id, cb.nombre_curso')
            ->join('cursos_base cb', 'cb.id = cursos.id_cursos_base')
            ->where('cb.id_escuela', $idEscuela)
            ->where('cb.id_grado', $cursoActual['id_grado'])
            ->orderBy('cb.nombre_curso', 'ASC')
            ->findAll();

        log_message('debug', "Secciones encontradas: " . json_encode($secciones));

        if (!$secciones) {
            log_message('debug', "No se encontraron secciones para el grado: " . $cursoActual['id_grado']);
        }

        return $this->response->setJSON($secciones);
    }







    public function actualizarInscripciones()
    {
        $datos = $this->request->getPost('inscripciones');
        log_message('debug', 'Raw POST inscripciones: ' . $datos);

        $inscripciones = json_decode($datos, true);
        log_message('debug', 'Actualizar inscripciones decodificadas: ' . print_r($inscripciones, true));

        foreach ($inscripciones as $insc) {
            log_message('debug', 'Procesando inscripción: ' . print_r($insc, true));

            // 1️⃣ Obtener inscripción
            $registro = $this->inscripciones->find($insc['id_inscripcion']);
            if (!$registro) {
                log_message('debug', 'No se encontró inscripción con ID: ' . $insc['id_inscripcion']);
                continue;
            }
            log_message('debug', 'Registro actual de inscripción: ' . print_r($registro, true));

            $actualizar = [];

            // 2️⃣ Cambiar curso si viene
            if (isset($insc['curso'])) {
                $actualizar['id_curso'] = $insc['curso'];
            }

            // 3️⃣ Cambiar estado si viene
            if (isset($insc['estado'])) {
                $actualizar['estado'] = $insc['estado'];
            }

            // 4️⃣ Cambiar condición final si viene
            if (isset($insc['condicion_final'])) {
                $actualizar['condicion_final'] = $insc['condicion_final'];

                // 5️⃣ Reglas extra: si se retira, inactivar usuario
                if ($insc['condicion_final'] === 'Retirado') {
                    $actualizar['activo'] = 0;
                    log_message('debug', 'Estudiante retirado, desactivando: ' . $registro['id_estudiante']);
                    $this->estudiantes->update($registro['id_estudiante'], ['activo' => 0]);
                }
            }

            log_message('debug', 'Datos a actualizar ID ' . $insc['id_inscripcion'] . ': ' . print_r($actualizar, true));

            // 6️⃣ Guardar cambios en DB
            $this->inscripciones->update($insc['id_inscripcion'], $actualizar);
            log_message('debug', 'Inscripción actualizada ID ' . $insc['id_inscripcion']);
        }

        return $this->response->setJSON(['status' => 'ok']);
    }









    public function nuevo() {}
    public function insertar() {}
    public function editar() {}
    public function actualizar() {}
    public function eliminar() {}
    public function eliminados() {}
    public function restaurar() {}
    public function visualizar() {}
}

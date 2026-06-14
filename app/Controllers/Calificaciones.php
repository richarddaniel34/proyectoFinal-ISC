<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\CompetenciasModel;
use App\Models\PeriodosModel;
use App\Models\DistribucionAsignaturasModel;
use App\Models\InscripcionesModel;
use App\Models\CalificacionesModel;
use App\Models\PersonalModel;
use App\Models\EscuelaModel;
use App\Models\PeriodosConfiguracionUsuarioModel;
use App\Models\PromedioCompetenciasModel;
use App\Models\EvaluacionesFinalesModel;
use App\Models\SchoolyearModel;
use App\Models\RecuperacionModel;
use App\Models\ConfiguracionRaTecnicaModel;
use App\Models\CalificacionesTecnicasModel;
use App\Models\EvaluacionesFinalesTecnicasModel;

use TCPDF;

//App\Models\PeriodosConfiguracionUsuarioModel


class Calificaciones extends BaseController
{
    protected $competencias;
    protected $periodos;
    protected $distribucionasignaturas;
    protected $inscripciones;
    protected $calificaciones;
    protected $personal;
    protected $escuela;
    protected $configPeriodos;
    protected $db;
    protected $promedio_competencia;
    protected $evaluaciones_finales;
    protected $schoolYear;
    protected $recuperacion;
    protected $raModel;
    protected $calificacionesTecnicas;
    protected $evaluacionesTecnicas;



    public function __construct()
    {
        $this->competencias = new CompetenciasModel();
        $this->periodos = new PeriodosModel();
        $this->distribucionasignaturas = new DistribucionAsignaturasModel();
        $this->inscripciones = new InscripcionesModel();
        $this->calificaciones = new CalificacionesModel();
        $this->personal = new PersonalModel();
        $this->escuela = new EscuelaModel();
        $this->configPeriodos = new PeriodosConfiguracionUsuarioModel();
        $this->db = \Config\Database::connect();
        $this->promedio_competencia = new PromedioCompetenciasModel();
        $this->evaluaciones_finales = new EvaluacionesFinalesModel();
        $this->schoolYear = new SchoolyearModel();
        $this->recuperacion = new RecuperacionModel();
        $this->raModel = new ConfiguracionRaTecnicaModel();
        $this->calificacionesTecnicas = new CalificacionesTecnicasModel();
        $this->evaluacionesTecnicas = new EvaluacionesFinalesTecnicasModel();
        //$this->configPeriodosModel = new ConfigPeriodosModel();
    }

    /**
     * Vista principal del módulo de calificaciones.
     */

    public function index()
    {
        $data = ['titulo' => 'Calificaciones',];

        echo view('header');
        echo view('calificaciones/calificaciones', $data);
        echo view('footer');
    }


    /**
     * //////////////////////////////////////////////////////
     * =================== CALIFICACIONES ===================
     * /////////////////////////////////////////////////////
     */

    /**
     * Vista de registro de calificaciones
     */
    public function registro()
    {
        $schoolYearActual = $this->schoolYear->getEnCurso();

        $idSchoolYear = null;
        if (!empty($schoolYearActual)) {
            $idSchoolYear = $schoolYearActual[0]['id'];
        }

        $idUsuario   = session('personal_id');
        $tipoUsuario = session('tipo_usuario');
        $funcion     = null;

        $registroPersonal = $this->personal->find($idUsuario);

        if ($registroPersonal && isset($registroPersonal['funcion'])) {
            $funcion = $registroPersonal['funcion'];
        }

        $competencias = $this->competencias->findAll();
        $periodos     = $this->periodos->findAll();

        $data = [
            'titulo'                => 'Gestión de Calificaciones',
            'competencias'          => $competencias,
            'periodos'              => $periodos,
            'funcion'               => $funcion,
            'id_schoolyear_actual'  => $idSchoolYear,
            'usuario_actual'        => $registroPersonal,
            'tipo_usuario'          => $tipoUsuario,
        ];

        echo view('header');
        echo view('calificaciones/registro', $data);
        echo view('footer');
    }

    public function guardarNotas()
    {
        $post = $this->request->getPost();

        if (session('tipo_usuario') != 3) {
            return redirect()->back()->with('error', 'No tiene permiso para guardar calificaciones.');
        }

        $idInscripciones = $post['id_inscripcion'] ?? [];
        $idDistribucion  = $post['id_distribucion_asignatura'] ?? null;

        if (empty($idDistribucion)) {
            return redirect()->back()->with('error', 'Falta el ID de distribución de asignatura.');
        }

        if (empty($idInscripciones)) {
            return redirect()->back()->with('error', 'No hay estudiantes para guardar.');
        }

        $idPeriodos = [
            'P1' => 1,
            'P2' => 2,
            'P3' => 3,
            'P4' => 4,
        ];

        foreach ($idInscripciones as $idInscripcion) {

            $promediosCompetencias = [];

            foreach (['c1', 'c2', 'c3', 'c4'] as $idx => $comp) {

                $idCompetencia = $idx + 1;
                $notasCompetencia = [];

                foreach ($idPeriodos as $label => $idPeriodo) {

                    $keyNota = "{$comp}_{$label}";
                    $keyRp   = "rp_{$comp}_{$label}";

                    $nota = $post[$keyNota][$idInscripcion] ?? null;
                    $rp   = $post[$keyRp][$idInscripcion] ?? null;

                    if (($nota === null || $nota === '') && ($rp === null || $rp === '')) {
                        continue;
                    }

                    $notaBase = is_numeric($nota) ? (int) $nota : null;
                    $rpBase   = is_numeric($rp) ? (int) $rp : null;

                    // Validar nota normal
                    if ($notaBase !== null && ($notaBase < 0 || $notaBase > 100)) {
                        return redirect()->back()->with('error', 'Las calificaciones deben estar entre 0 y 100.');
                    }

                    // Validar RP
                    if ($rpBase !== null && ($rpBase < 0 || $rpBase > 100)) {
                        return redirect()->back()->with('error', 'Las recuperaciones pedagógicas deben estar entre 0 y 100.');
                    }

                    // RP no puede existir si no hay nota base
                    if ($rpBase !== null && $notaBase === null) {
                        return redirect()->back()->with('error', 'No puede registrar una RP sin una calificación del período.');
                    }

                    // RP solo aplica si la nota base es menor que 70
                    if ($rpBase !== null && $notaBase >= 70) {
                        return redirect()->back()->with('error', 'No puede registrar RP para una calificación aprobada.');
                    }

                    // RP no puede ser menor que la nota base
                    if ($rpBase !== null && $notaBase !== null && $rpBase < $notaBase) {
                        return redirect()->back()->with('error', 'La RP no puede ser menor que la calificación del período.');
                    }

                    if ($nota !== null && $nota !== '') {
                        $this->calificaciones->guardarOActualizar([
                            'id_inscripcion' => $idInscripcion,
                            'id_distribucion_asignatura' => $idDistribucion,
                            'id_competencia' => $idCompetencia,
                            'id_periodo' => $idPeriodo,
                            'nota' => $nota,
                        ]);
                    }

                    if ($rp !== null && $rp !== '') {
                        $this->recuperacion->guardarOActualizar([
                            'id_inscripcion' => $idInscripcion,
                            'id_distribucion_asignatura' => $idDistribucion,
                            'id_competencia' => $idCompetencia,
                            'id_periodo' => $idPeriodo,
                            'nota_rp' => $rp,
                        ]);
                    }

                    if ($notaBase !== null && $rpBase !== null) {
                        $notasCompetencia[] = max($notaBase, $rpBase);
                    } elseif ($notaBase !== null) {
                        $notasCompetencia[] = $notaBase;
                    } elseif ($rpBase !== null) {
                        $notasCompetencia[] = $rpBase;
                    }
                }

                if (!empty($notasCompetencia)) {
                    $promedio = array_sum($notasCompetencia) / 4;

                    $this->promedio_competencia->insertarOActualizar([
                        'id_inscripcion' => $idInscripcion,
                        'id_distribucion_asignatura' => $idDistribucion,
                        'id_competencia' => $idCompetencia,
                        'id_periodo' => 0,
                        'promedio' => $promedio,
                    ]);

                    $promediosCompetencias[] = $promedio;
                }
            }

            if (!empty($promediosCompetencias)) {
                $calificacionFinal = array_sum($promediosCompetencias) / count($promediosCompetencias);

                $this->evaluaciones_finales->insertarOActualizar([
                    'id_inscripcion' => $idInscripcion,
                    'id_distribucion_asignatura' => $idDistribucion,
                    'calificacion_final' => $calificacionFinal,
                    'calif_completiva' => null,
                    'calif_extraordinaria' => null,
                    'calif_especial' => null,
                    'situacion_asignatura' => $calificacionFinal >= 70 ? 'A' : 'R',
                ]);
            }
        }

        return redirect()->back()->with('mensaje', 'Notas guardadas correctamente.');
    }

   //========================================================




    /**
     * //////////////////////////////////////////////////////
     * ==================== COMPLETIVOS ====================
     * /////////////////////////////////////////////////////
     */

    // METODO QUE RENDERIZA LA VISTA DE REGISTRO DE COMPLETIVO
    public function completivo()
    {
        $schoolYearActual = $this->schoolYear->getEnCurso();


        $idSchoolYear = null;
        if (!empty($schoolYearActual)) {
            $idSchoolYear = $schoolYearActual[0]['id'];
        }

        $idUsuario   = session('personal_id');
        $tipoUsuario = session('tipo_usuario');
        $funcion     = null;

        $registroPersonal = $this->personal->find($idUsuario);

        if ($registroPersonal && isset($registroPersonal['funcion'])) {
            $funcion = $registroPersonal['funcion'];
        }
        $data = [
            'titulo'                => 'Registro de Completivo',
            'funcion'               => $funcion,
            'id_schoolyear_actual'  => $idSchoolYear,
            'usuario_actual'        => $registroPersonal,
            'tipo_usuario'          => $tipoUsuario,
        ];

        echo view('header');
        echo view('calificaciones/completivo', $data);
        echo view('footer');
    }

    // METODO QUE GUARDA O ACTUALIZA LAS NOTAS COMPLETIVAS
    public function guardarCompletivo()
    {
        $post = $this->request->getPost();

        if (session('tipo_usuario') != 3) {
            return redirect()->back()->with('error', 'No tiene permiso para guardar completivos.');
        }

        $idDistribucion = $post['id_distribucion_asignatura'] ?? null;
        $completivas = $post['calif_e_completiva'] ?? [];

        if (empty($idDistribucion)) {
            return redirect()->back()->with('error', 'Falta el ID de distribución de asignatura.');
        }

        if (empty($completivas)) {
            return redirect()->back()->with('error', 'No hay calificaciones completivas para guardar.');
        }

        foreach ($completivas as $idInscripcion => $notaCompletiva) {

            if ($notaCompletiva === null || $notaCompletiva === '') {
                continue;
            }

            $notaCompletiva = (float) $notaCompletiva;

            if ($notaCompletiva < 0 || $notaCompletiva > 100) {
                continue;
            }

            $registroFinal = $this->evaluaciones_finales
                ->where('id_inscripcion', $idInscripcion)
                ->where('id_distribucion_asignatura', $idDistribucion)
                ->first();

            if (!$registroFinal) {
                continue;
            }

            $calificacionFinal = (float) $registroFinal['calificacion_final'];

            $cf50  = $calificacionFinal * 0.5;
            $cec50 = $notaCompletiva * 0.5;
            $ccf   = $cf50 + $cec50;

            $situacion = $ccf >= 70 ? 'A' : 'R';

            $this->evaluaciones_finales
                ->where('id', $registroFinal['id'])
                ->set([
                    'calif_e_completiva' => $notaCompletiva,
                    'calif_completiva'   => $ccf,
                    'situacion_asignatura' => $situacion,
                ])
                ->update();
        }

        return redirect()->back()->with('mensaje', 'Completivos guardados correctamente.');
    }

    // METODO QUE TRAE LOS ESTUDIANTES, QUE REQUIEREN COMPLETIVOS
    public function estudiantesCompletivo()
    {
        $idDistribucion = $this->request->getGet('id_distribucion_asignatura');

        return $this->response->setJSON(
            $this->obtenerEstudiantesEvaluacion(
                $idDistribucion,
                'calificacion_final'
            )
        );
    }


    //========================================================




    /**
     * /////////////////////////////////////////////////////////
     * ==================== EXTRAORDINARIO ====================
     * ////////////////////////////////////////////////////////
     */


    public function extraordinario()
    {
        $data = ['titulo' => 'Registro de Extraordinario',];

        echo view('header');
        echo view('calificaciones/extraordinario', $data);
        echo view('footer');
    }

    public function guardarExtraordinario()
    {
        $post = $this->request->getPost();

        if (session('tipo_usuario') != 3) {
            return redirect()->back()->with('error', 'No tiene permiso para guardar extraordinarios.');
        }

        $idDistribucion = $post['id_distribucion_asignatura'] ?? null;
        $extraordinarias = $post['calif_e_extraordinaria'] ?? [];

        if (empty($idDistribucion)) {
            return redirect()->back()->with('error', 'Falta el ID de distribución de asignatura.');
        }

        if (empty($extraordinarias)) {
            return redirect()->back()->with('error', 'No hay calificaciones extraordinarias para guardar.');
        }

        foreach ($extraordinarias as $idInscripcion => $notaExtraordinaria) {

            if ($notaExtraordinaria === null || $notaExtraordinaria === '') {
                continue;
            }

            $notaExtraordinaria = (float) $notaExtraordinaria;

            if ($notaExtraordinaria < 0 || $notaExtraordinaria > 100) {
                continue;
            }

            $registroFinal = $this->evaluaciones_finales
                ->where('id_inscripcion', $idInscripcion)
                ->where('id_distribucion_asignatura', $idDistribucion)
                ->first();

            if (!$registroFinal) {
                continue;
            }

            $calificacionFinal = (float) $registroFinal['calificacion_final'];

            $cf30   = $calificacionFinal * 0.3;
            $ceex70 = $notaExtraordinaria * 0.7;
            $cexf   = $cf30 + $ceex70;

            $situacion = $cexf >= 70 ? 'A' : 'R';

            $this->evaluaciones_finales
                ->where('id', $registroFinal['id'])
                ->set([
                    'calif_e_extraordinaria' => $notaExtraordinaria,
                    'calif_extraordinaria'   => $cexf,
                    'situacion_asignatura'   => $situacion,
                ])
                ->update();
        }

        return redirect()->back()->with('mensaje', 'Extraordinarios guardados correctamente.');
    }


    public function estudiantesExtraordinario()
    {
        $idDistribucion = $this->request->getGet('id_distribucion_asignatura');

        return $this->response->setJSON(
            $this->obtenerEstudiantesEvaluacion(
                $idDistribucion,
                'calif_completiva'
            )
        );
    }


    //========================================================







    /**
     * /////////////////////////////////////////////////////////
     * ====================== ESPECIALES ======================
     * ////////////////////////////////////////////////////////
     */



    public function especiales()
    {
        $schoolYearActual = $this->schoolYear->getEnCurso();

        $idSchoolYear = null;
        if (!empty($schoolYearActual)) {
            $idSchoolYear = $schoolYearActual[0]['id'];
        }

        $idUsuario   = session('personal_id');
        $tipoUsuario = session('tipo_usuario');
        $funcion     = null;

        $registroPersonal = $this->personal->find($idUsuario);

        if ($registroPersonal && isset($registroPersonal['funcion'])) {
            $funcion = $registroPersonal['funcion'];
        }

        $data = [
            'titulo'               => 'Registro de Evaluación Especial',
            'funcion'              => $funcion,
            'id_schoolyear_actual' => $idSchoolYear,
            'usuario_actual'       => $registroPersonal,
            'tipo_usuario'         => $tipoUsuario,
        ];

        echo view('header');
        echo view('calificaciones/especiales', $data);
        echo view('footer');
    }


    public function guardarEspecial()
    {
        $post = $this->request->getPost();

        if (session('tipo_usuario') != 3) {
            return redirect()->back()->with('error', 'No tiene permiso para guardar evaluaciones especiales.');
        }

        $idDistribucion = $post['id_distribucion_asignatura'] ?? null;
        $especiales     = $post['calif_e_especial'] ?? [];

        if (empty($idDistribucion)) {
            return redirect()->back()->with('error', 'Falta el ID de distribución de asignatura.');
        }

        if (empty($especiales)) {
            return redirect()->back()->with('error', 'No hay calificaciones especiales para guardar.');
        }

        foreach ($especiales as $idInscripcion => $notaEspecial) {

            if ($notaEspecial === null || $notaEspecial === '') {
                continue;
            }

            $notaEspecial = (float) $notaEspecial;

            if ($notaEspecial < 0 || $notaEspecial > 100) {
                continue;
            }

            $registroFinal = $this->evaluaciones_finales
                ->where('id_inscripcion', $idInscripcion)
                ->where('id_distribucion_asignatura', $idDistribucion)
                ->first();

            if (!$registroFinal) {
                continue;
            }

            $calificacionFinal = (float) $registroFinal['calificacion_final'];
            $puntosFaltantes   = 100 - $calificacionFinal;

            if ($notaEspecial > $puntosFaltantes) {
                $notaEspecial = $puntosFaltantes;
            }

            $calificacionEspecialFinal = $calificacionFinal + $notaEspecial;

            $situacion = $calificacionEspecialFinal >= 70 ? 'A' : 'R';

            $this->evaluaciones_finales
                ->where('id', $registroFinal['id'])
                ->set([
                    'calif_e_especial'     => $notaEspecial,
                    'calif_especial'       => $calificacionEspecialFinal,
                    'situacion_asignatura' => $situacion,
                ])
                ->update();
        }

        return redirect()->back()->with('mensaje', 'Evaluaciones especiales guardadas correctamente.');
    }

    public function estudiantesEspecial()
    {
        $idDistribucion = $this->request->getGet('id_distribucion_asignatura');

        return $this->response->setJSON(
            $this->obtenerEstudiantesEvaluacion(
                $idDistribucion,
                'calif_extraordinaria'
            )
        );
    }

    //========================================================



    private function obtenerEstudiantesEvaluacion($idDistribucion, $campoFiltro)
    {
        if (empty($idDistribucion)) {
            return [];
        }

        return $this->db->table('evaluaciones_finales ef')
            ->select('
            ef.id_inscripcion,

            e.nombre,
            e.apellido,

            ef.calificacion_final,

            ef.calif_e_completiva,
            ef.calif_completiva,

            ef.calif_e_extraordinaria,
            ef.calif_extraordinaria,

            ef.calif_especial,

            ef.situacion_asignatura
        ')
            ->join('inscripciones i', 'i.id = ef.id_inscripcion')
            ->join('estudiantes e', 'e.id = i.id_estudiante')
            ->where('ef.id_distribucion_asignatura', $idDistribucion)
            ->where("ef.$campoFiltro <", 70)
            ->orderBy('e.apellido', 'ASC')
            ->orderBy('e.nombre', 'ASC')
            ->get()
            ->getResultArray();
    }







    /**
     * Los RP nunca deben ser menor al P, para calcular se tomara en cuenta los puntos que faltan para aprobar,
     * ejemplo: si tiene 68 le falta 32 punto para completar 100, las practicas, examenes se evaluan en base a 32.
     * 
     * 
     * La evaluacion especial, se hace en base a la calificacion final (Completando, como en los P,
     * en base a lo que falta para completar.)
     * 
     * Calificaiones Tecnicas: investigar 
     */





    public function buscarDocentes()
    {
        $idEscuela = session()->get('id_escuela');

        $search = $this->request->getGet('search');

        $builder = $this->personal
            ->select('personal.id, CONCAT(personal.nombre, " ", personal.apellido) AS text')
            ->join('nombramiento', 'nombramiento.id = personal.funcion', 'left')
            ->where('nombramiento.nombre', 'Docente')
            ->where('personal.id_escuela', $idEscuela); // <-- filtro por escuela

        if (!empty($search)) {
            $builder->like('personal.nombre', $search)
                ->orLike('personal.apellido', $search);
        }

        $docentes = $builder->findAll(10);

        return $this->response->setJSON($docentes);
    }

    /**
     * /////////////////////////////////////////////////
     * ================= CONFIGURAR RA =================
     * ////////////////////////////////////////////////
     * 
     * Configurar Resultados de Aprendizaje (RA)
     */
    public function configurarra()
    {
        $schoolYearActual = $this->schoolYear->getEnCurso();

        $idSchoolYear = null;
        if (!empty($schoolYearActual)) {
            $idSchoolYear = $schoolYearActual[0]['id'];
        }

        $idUsuario   = session('personal_id');
        $tipoUsuario = session('tipo_usuario');
        $funcion     = null;

        $registroPersonal = $this->personal->find($idUsuario);

        if ($registroPersonal && isset($registroPersonal['funcion'])) {
            $funcion = $registroPersonal['funcion'];
        }

        $periodos     = $this->periodos->findAll();

        $data = [
            'titulo_1' => 'CALIFICACIONES',
            'titulo_2' => "CONFIGURACION DE RA",
            'periodos'              => $periodos,
            'funcion'               => $funcion,
            'id_schoolyear_actual'  => $idSchoolYear,
            'usuario_actual'        => $registroPersonal,
            'tipo_usuario'          => $tipoUsuario,
        ];


        echo view('header');
        echo view('calificaciones/configurarra', $data);
        echo view('footer');
    }

    //GUARDA LA CONFIGURACION DEL RA
    public function guardarra()
    {
        $idDistribucion = $this->request->getPost('id_distribucion_asignatura');
        $idSchoolYear   = $this->request->getPost('id_schoolyear');

        $ras = $this->request->getPost('ra');

        if (empty($ras)) {
            return redirect()
                ->back()
                ->with('error', 'No se recibieron Resultados de Aprendizaje.');
        }

        $usuario = session('usuario_data.personal_id') ?? null;

        foreach ($ras as $numeroRa => $datos) {

            $valor = (float) ($datos['valor'] ?? 0);
            $minimo = ceil($valor * 0.70);

            $existente = $this->raModel->existeRa(
                $idDistribucion,
                $idSchoolYear,
                $numeroRa
            );

            $data = [
                'id_distribucion_asignatura' => $idDistribucion,
                'id_schoolyear'             => $idSchoolYear,
                'numero_ra'                 => $numeroRa,
                'valor_ra'                  => $valor,
                'minimo_ra'                 => $minimo,
                'activo'                    => 1,
                'updated_by'                => $usuario,
            ];

            if ($existente) {

                $data['fecha_edit'] = date('Y-m-d H:i:s');

                $this->raModel->update(
                    $existente['id'],
                    $data
                );
            } else {

                $data['created_by'] = $usuario;
                $data['fecha_alta'] = date('Y-m-d H:i:s');

                $this->raModel->insert($data);
            }
        }

        return redirect()
            ->back()
            ->with('mensaje', 'Configuración de RA guardada correctamente.');
    }

    //OBTENER LOS RA CONFIGURADOS
    public function obtener()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $idDistribucion = $this->request->getGet('id_distribucion_asignatura');
        $idSchoolYear   = $this->request->getGet('id_schoolyear');

        $ras = $this->raModel->getRasConfigurados(
            $idDistribucion,
            $idSchoolYear
        );

        return $this->response->setJSON($ras);
    }


    //========================================================

    public function buscarCursos($id_docente)
    {
        if ($this->request->isAJAX()) {
            $cursos = $this->distribucionasignaturas->getCursosPorDocente($id_docente);
            return $this->response->setJSON($cursos);
        }
    }



    public function estudiantesPorCurso($id_curso)
    {
        if ($this->request->isAJAX()) {

            $id_schoolyear = $this->request->getGet('id_schoolyear');

            //dd($id_curso, $id_schoolyear);

            $estudiantes = $this->inscripciones
                ->getEstudiantesPorCurso($id_curso, $id_schoolyear);

            return $this->response->setJSON($estudiantes);
        }
    }




    /*
    public function estudiantesPorCurso($id_curso)
    {
        if ($this->request->isAJAX()) {
            $estudiantes = $this->inscripciones->getEstudiantesPorCurso($id_curso);


            return $this->response->setJSON($estudiantes);
        }
    }
        */

    public function obtenerEstadoPeriodosDocente()
    {
        if ($this->request->isAJAX()) {
            $id_docente = $this->request->getGet('docente');
            $id_curso = $this->request->getGet('curso');
            $id_asignatura = $this->request->getGet('asignatura');

            // Buscar distribución específica
            $dist = $this->distribucionasignaturas
                ->where('id_personal', $id_docente)
                ->where('id_curso', $id_curso)
                ->where('id_asignatura', $id_asignatura)
                ->first();

            if (!$dist) {
                return $this->response->setJSON(['error' => 'Distribución no encontrada']);
            }


            $id_distribucion = $dist['id'];

            // Primero buscar configuración específica
            $configEspecifica = $this->db->table('periodos_configuracion_usuario')
                ->select('periodos.nombre, periodos_configuracion_usuario.bloqueado')
                ->join('periodos', 'periodos.id = periodos_configuracion_usuario.id_periodo')
                ->where('periodos_configuracion_usuario.id_distribucion_asignatura', $id_distribucion)
                ->where('periodos_configuracion_usuario.id_personal', $id_docente)
                ->get()
                ->getResultArray();

            if (!empty($configEspecifica)) {
                // Si hay configuración específica, usarla
                $estado = [];
                foreach ($configEspecifica as $conf) {
                    $estado[$conf['nombre']] = [
                        'bloqueado' => (bool)$conf['bloqueado']
                    ];
                }
                return $this->response->setJSON(['error' => 'Ruta no ejecutada correctamente']);
            }

            // Si no hay específica, buscar configuración global aplicada a esa distribución
            $configGlobal = $this->db->table('periodos_configuracion_usuario')
                ->select('periodos.nombre, periodos_configuracion_usuario.bloqueado')
                ->join('periodos', 'periodos.id = periodos_configuracion_usuario.id_periodo')
                ->where('periodos_configuracion_usuario.id_distribucion_asignatura', $id_distribucion)
                ->where('periodos_configuracion_usuario.id_personal', $id_docente)
                ->get()
                ->getResultArray();

            $estado = [];
            foreach ($configGlobal as $conf) {
                $estado[$conf['nombre']] = [
                    'bloqueado' => (bool)$conf['bloqueado']
                ];
            }

            return $this->response->setJSON($estado);
        }
    }

    public function obtenerDistribucionAsignatura()
    {
        $docenteId = $this->request->getGet('docente');
        $cursoId = $this->request->getGet('curso');
        $asignaturaId = $this->request->getGet('asignatura');

        $dist = $this->distribucionasignaturas
            ->where('id_personal', $docenteId)
            ->where('id_curso', $cursoId)
            ->where('id_asignatura', $asignaturaId)
            ->first();

        if ($dist) {
            return $this->response->setJSON(['success' => true, 'id' => $dist['id']]);
        } else {
            return $this->response->setJSON(['success' => false]);
        }
    }


    public function buscarAsignaturas($id_docente, $id_curso)
    {
        if ($this->request->isAJAX()) {
            $asignaturas = $this->distribucionasignaturas->getAsignaturasPorDocenteCurso($id_docente, $id_curso);
            return $this->response->setJSON($asignaturas);
        }
    }


    public function obtenerNotas()
    {
        $curso      = $this->request->getGet('curso');
        $asignatura = $this->request->getGet('asignatura');
        $schoolYear = $this->request->getGet('schoolYear');
        $periodo    = $this->request->getGet('periodo'); // P1, P2, P3, P4

        if (!$curso || !$asignatura || !$schoolYear || !$periodo) {
            log_message('error', "Parámetros inválidos: curso={$curso}, asignatura={$asignatura}, schoolYear={$schoolYear}, periodo={$periodo}");
            return $this->response->setJSON([]);
        }

        $periodoRow = $this->db->table('periodos')
            ->select('id')
            ->where('nombre', $periodo)
            ->get()
            ->getRowArray();

        if (!$periodoRow) {
            log_message('error', "No se encontró el período: {$periodo}");
            return $this->response->setJSON([]);
        }

        $idPeriodo = $periodoRow['id'];

        $distribucion = $this->db->table('distribucion_asignaturas')
            ->select('id')
            ->where('id_asignatura', $asignatura)
            ->where('id_curso', $curso)
            ->where('id_schoolyear', $schoolYear)
            ->get()
            ->getRowArray();

        if (!$distribucion) {
            log_message('debug', "No se encontró distribución para asignatura={$asignatura}, curso={$curso}, schoolYear={$schoolYear}");
            return $this->response->setJSON([]);
        }

        $idDistribucion = $distribucion['id'];

        $notas = $this->db->table('calificaciones c')
            ->select('
            c.id_inscripcion,
            c.id_competencia,
            c.nota,
            rp.nota_rp
        ')
            ->join(
                'recuperaciones_pedagogicas rp',
                'rp.id_inscripcion = c.id_inscripcion
             AND rp.id_distribucion_asignatura = c.id_distribucion_asignatura
             AND rp.id_periodo = c.id_periodo
             AND rp.id_competencia = c.id_competencia',
                'left'
            )
            ->where('c.id_distribucion_asignatura', $idDistribucion)
            ->where('c.id_periodo', $idPeriodo)
            ->get()
            ->getResultArray();

        $resultado = [];

        foreach ($notas as $n) {
            $idInscripcion = $n['id_inscripcion'];
            $comp = 'c' . $n['id_competencia'];

            if (!isset($resultado[$idInscripcion])) {
                $resultado[$idInscripcion] = [
                    'id_inscripcion' => $idInscripcion,
                    'c1_nota' => null,
                    'c1_rp' => null,
                    'c2_nota' => null,
                    'c2_rp' => null,
                    'c3_nota' => null,
                    'c3_rp' => null,
                    'c4_nota' => null,
                    'c4_rp' => null,
                ];
            }

            $resultado[$idInscripcion]["{$comp}_nota"] = $n['nota'];
            $resultado[$idInscripcion]["{$comp}_rp"]   = $n['nota_rp'];
        }

        log_message('debug', 'Notas formateadas para JS: ' . print_r(array_values($resultado), true));

        return $this->response->setJSON(array_values($resultado));
    }




    /**
     * //////////////////////////////////////////////////////////////////////
     * ====================== CALIFICACIONES TECNICAS ======================
     * /////////////////////////////////////////////////////////////////////
     */


    public function tecnicas()
    {
        $schoolYearActual = $this->schoolYear->getEnCurso();

        $idSchoolYear = null;
        if (!empty($schoolYearActual)) {
            $idSchoolYear = $schoolYearActual[0]['id'];
        }

        $idUsuario   = session('personal_id');
        $tipoUsuario = session('tipo_usuario');
        $funcion     = null;

        $registroPersonal = $this->personal->find($idUsuario);

        if ($registroPersonal && isset($registroPersonal['funcion'])) {
            $funcion = $registroPersonal['funcion'];
        }

        //obtener los periodos
        $periodosAcademicos = $this->periodos
            ->orderBy('id', 'ASC')
            ->findAll();


        $data = [
            'titulo'                => 'Calificaciones Tecnicas',
            'id_schoolyear_actual'  => $idSchoolYear,
            'usuario_actual'        => $registroPersonal,
            'tipo_usuario'          => $tipoUsuario,
            'periodos'              => $periodosAcademicos
        ];

        echo view('header');
        echo view('calificaciones/tecnicas', $data);
        echo view('footer');
    }


    public function guardarNotasTecnicas()
    {
        // dd($this->request->getPost());
        $idDistribucion = $this->request->getPost('id_distribucion_asignatura');
        $idSchoolYear   = $this->request->getPost('id_schoolyear');
        $idPeriodo      = $this->request->getPost('periodo');
        $raNotas        = $this->request->getPost('ra_notas');

        $usuario = session('usuario_data.personal_id') ?? session('personal_id') ?? null;
        /* dd([
            'id_distribucion_asignatura' => $idDistribucion,
            'id_schoolyear' => $idSchoolYear,
            'periodo' => $idPeriodo,
            'ra_notas' => $raNotas,
        ]);*/

        if (!$idDistribucion || !$idSchoolYear || !$idPeriodo || empty($raNotas)) {
            return redirect()
                ->back()
                ->with('error', 'Faltan datos para guardar las calificaciones técnicas.');
        }

        foreach ($raNotas as $idInscripcion => $ras) {

            foreach ($ras as $numeroRa => $datos) {

                $idRaConfig = $datos['id_ra_configuracion'] ?? null;

                if (!$idRaConfig) {
                    continue;
                }

                $cra = $datos['cra'] ?? null;
                $rp1 = $datos['rp1'] ?? null;
                $rp2 = $datos['rp2'] ?? null;

                if ($cra === '' && $rp1 === '' && $rp2 === '') {
                    continue;
                }

                $data = [
                    'id_inscripcion'              => $idInscripcion,
                    'id_distribucion_asignatura'  => $idDistribucion,
                    'id_schoolyear'               => $idSchoolYear,
                    'id_periodo'                  => $idPeriodo,
                    'id_ra_configuracion'         => $idRaConfig,
                    'cra'                         => $cra !== '' ? $cra : null,
                    'rp1'                         => $rp1 !== '' ? $rp1 : null,
                    'rp2'                         => $rp2 !== '' ? $rp2 : null,
                    'updated_by'                  => $usuario,
                ];

                $this->calificacionesTecnicas->guardarOActualizar($data);
            }

            $this->recalcularEvaluacionFinalTecnica(
                $idInscripcion,
                $idDistribucion,
                $idSchoolYear,
                $usuario
            );
        }

        return redirect()
            ->back()
            ->with('mensaje', 'Calificaciones técnicas guardadas correctamente.');
    }

    private function recalcularEvaluacionFinalTecnica($idInscripcion, $idDistribucion, $idSchoolYear, $usuario = null)
    {
        $notas = $this->calificacionesTecnicas
            ->select('
            calificaciones_tecnicas.*,
            configuracion_ra_tecnica.valor_ra,
            configuracion_ra_tecnica.minimo_ra
        ')
            ->join(
                'configuracion_ra_tecnica',
                'configuracion_ra_tecnica.id = calificaciones_tecnicas.id_ra_configuracion'
            )
            ->where('calificaciones_tecnicas.id_inscripcion', $idInscripcion)
            ->where('calificaciones_tecnicas.id_distribucion_asignatura', $idDistribucion)
            ->where('calificaciones_tecnicas.id_schoolyear', $idSchoolYear)
            ->findAll();

        $total = 0;
        $tieneReprobado = false;
        $usaRecuperacion = false;

        foreach ($notas as $nota) {

            $cra = $nota['cra'] !== null ? (float) $nota['cra'] : null;
            $rp1 = $nota['rp1'] !== null ? (float) $nota['rp1'] : null;
            $rp2 = $nota['rp2'] !== null ? (float) $nota['rp2'] : null;

            $minimo = (float) $nota['minimo_ra'];

            $mejor = max(
                $cra ?? 0,
                $rp1 ?? 0,
                $rp2 ?? 0
            );

            $total += $mejor;

            if (($rp1 !== null && $rp1 > 0) || ($rp2 !== null && $rp2 > 0)) {
                $usaRecuperacion = true;
            }

            if ($mejor < $minimo) {
                $tieneReprobado = true;
            }
        }

        $aprobadoEspecial = 0;
        $aprobado = 0;
        $reprobado = 0;

        if ($tieneReprobado) {
            $reprobado = 1;
        } elseif ($usaRecuperacion) {
            $aprobadoEspecial = 1;
        } else {
            $aprobado = 1;
        }

        $dataResumen = [
            'id_inscripcion'             => $idInscripcion,
            'id_distribucion_asignatura' => $idDistribucion,
            'id_schoolyear'              => $idSchoolYear,
            'total'                      => $total,
            'aprobado_especial'          => $aprobadoEspecial,
            'aprobado'                   => $aprobado,
            'reprobado'                  => $reprobado,
            'updated_by'                 => $usuario,
        ];

        $this->evaluacionesTecnicas->guardarOActualizar($dataResumen);
    }


    public function obtenerNotasTecnicas()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $idDistribucion = $this->request->getGet('id_distribucion_asignatura');
        $idSchoolYear   = $this->request->getGet('id_schoolyear');
        $idPeriodo      = $this->request->getGet('id_periodo');

        $notas = $this->calificacionesTecnicas
            ->getNotasPeriodo(
                $idDistribucion,
                $idSchoolYear,
                $idPeriodo
            );

        return $this->response->setJSON($notas);
    }

    /**
     * ======================================================================
     * /////////////////////////////////////////////////////////////////////
     */






    /**
     * REPORTES
     */
    public function generarReportePDF()
    {
        // ===== 1) ENTRADA Y VALIDACIONES BÁSICAS =====
        $req          = $this->request;
        $docenteId    = $req->getGetPost('docente');
        $cursoId      = $req->getGetPost('curso');
        $asignaturaId = $req->getGetPost('asignatura');
        $idDistrib    = $req->getGetPost('id_distribucion_asignatura');

        if (!$docenteId || !$cursoId || !$asignaturaId) {
            return redirect()->back()->with('error', 'Faltan parámetros (docente, curso o asignatura).');
        }

        // Fallback para id_distribucion_asignatura si no vino desde el front
        if (empty($idDistrib)) {
            $row = $this->db->table('distribucion_asignaturas')
                ->select('id')
                ->where('id_personal',   $docenteId)     // ajusta a id_docente si así se llama
                ->where('id_curso',      $cursoId)
                ->where('id_asignatura', $asignaturaId)
                ->get()->getRowArray();
            $idDistrib = $row['id'] ?? null;
            if (!$idDistrib) {
                return redirect()->back()->with('error', 'No se pudo resolver la distribución de la asignatura.');
            }
        }

        // ===== 2) CARGA DE METADATOS (Docente/Curso/Asignatura, Competencias, Alumnos) =====
        // TODO: ajusta a tus modelos reales
        // Docente
        $docente = $this->personal->find($docenteId); // ['nombre', 'apellido', ...]
        // Curso
        $curso   = $this->inscripciones->find($cursoId);     // ['nombre', ...]
        // Asignatura
        $asig    = $this->distribucionasignaturas->find($asignaturaId); // ['nombre','tipo_asignatura', ...]
        $esTecnica = (isset($asig['tipo_asignatura']) && (int)$asig['tipo_asignatura'] === 2);

        // Competencias (C1..C4)
        $competencias = $this->competencias->orderBy('id', 'ASC')->findAll(); // [{'id'=>1,'codigo_competencia'=>'c1',...}, ...]
        $compCodes = array_map(function ($c) {
            return strtolower($c['codigo_competencia']);
        }, $competencias ?: []);

        // Alumnos (inscripciones) del curso
        // Debe devolver id_inscripcion, nombre, apellido al menos
        $alumnos = $this->inscripciones->getAlumnosByCurso($cursoId); // TODO: implementa si no lo tienes

        // Periodos mapa etiqueta => id
        $periodos = ['P1' => 1, 'P2' => 2, 'P3' => 3, 'P4' => 4];

        // ===== 3) OBTENER CALIFICACIONES SEGÚN TIPO =====
        if (!$esTecnica) {
            // ACADÉMICO: esperamos una tabla tipo calificaciones con: id_inscripcion, id_distribucion_asignatura, id_competencia, id_periodo, nota, rp (rp puede ser null)
            // TODO: ajusta a tu modelo/consulta real
            $rows = $this->calificaciones->getByDistribucion($idDistrib);
            // Normaliza a mapa: $notas[id_insc][compCode][P#] = ['nota'=>float,'rp'=>float|null]
            $notas = [];
            foreach ($rows as $r) {
                // Necesitamos mapear id_competencia -> codigo c1..c4
                $compCode = null;
                foreach ($competencias as $c) {
                    if ((int)$c['id'] === (int)$r['id_competencia']) {
                        $compCode = strtolower($c['codigo_competencia']);
                        break;
                    }
                }
                if (!$compCode) continue;

                $label = array_search((int)$r['id_periodo'], $periodos, true);
                if (!$label) continue;

                $idInsc = (int)$r['id_inscripcion'];
                $notas[$idInsc][$compCode][$label] = [
                    'nota' => isset($r['nota']) ? (float)$r['nota'] : null,
                    'rp'   => array_key_exists('rp', $r) ? (($r['rp'] !== null && $r['rp'] !== '') ? (float)$r['rp'] : null) : null
                ];
            }
        } else {
        }

        // ===== 4) INICIALIZAR TCPDF =====
        // Crea tu clase con header/footer si quieres algo fijo
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Sistema de Calificaciones');
        $pdf->SetAuthor('Centro');
        $pdf->SetTitle('Reporte de Calificaciones');
        $pdf->SetSubject('Reporte de Calificaciones');
        $pdf->SetMargins(10, 18, 10);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();
        $pdf->SetFont('dejavusans', '', 9);

        // ===== 5) ENCABEZADO =====
        $nomDocente = trim(($docente['nombre'] ?? '') . ' ' . ($docente['apellido'] ?? ''));
        $nomCurso   = $curso['nombre'] ?? ('Curso ID ' . $cursoId);
        $nomAsig    = $asig['nombre']  ?? ('Asignatura ID ' . $asignaturaId);
        $tipoTexto  = $esTecnica ? 'Técnica' : 'Académica';

        $styles = '
    <style>
      .h1 { font-size:16px; font-weight:bold; }
      .sub { font-size:11px; }
      table { width:100%; border-collapse:collapse; }
      th, td { border:1px solid #333; padding:4px; }
      th { background:#efefef; text-align:center; }
      .center { text-align:center; }
      .right  { text-align:right; }
      .small { font-size:9px; }
      .ok { background-color:#d4edda; }
      .bad { background-color:#f8d7da; }
    </style>';

        $headerHtml = $styles . '
      <table cellspacing="0" cellpadding="3" border="0">
        <tr>
          <td class="h1" colspan="2">Reporte de Calificaciones (' . $tipoTexto . ')</td>
        </tr>
        <tr class="sub">
          <td>Curso: <strong>' . $nomCurso . '</strong></td>
          <td>Asignatura: <strong>' . $nomAsig . '</strong></td>
        </tr>
        <tr class="sub">
          <td>Docente: <strong>' . $nomDocente . '</strong></td>
          <td>Fecha: <strong>' . date('d/m/Y H:i') . '</strong></td>
        </tr>
      </table>
      <br/>';

        $pdf->writeHTML($headerHtml, true, false, true, false, '');

        // ===== 6) TABLAS POR PERIODO =====
        if (!$esTecnica) {
            // ---------- ACADÉMICO ----------
            foreach ($periodos as $label => $idp) {
                // THEAD de 2 filas
                $thead1 = '<tr><th rowspan="2" width="5%">No.</th><th rowspan="2" width="20%">Alumno</th>';
                foreach ($compCodes as $cc) {
                    $thead1 .= '<th colspan="2" class="center">' . strtoupper($cc) . '</th>';
                }
                $thead1 .= '</tr>';
                $thead2 = '<tr>';
                foreach ($compCodes as $cc) {
                    $thead2 .= '<th class="center" width="6%">' . $label . '</th><th class="center" width="6%">RP' . str_replace('P', '', $label) . '</th>';
                }
                $thead2 .= '</tr>';

                $rowsHtml = '';
                foreach ($alumnos as $idx => $al) {
                    $num = $idx + 1;
                    $nom = trim(($al['apellido'] ?? '') . ', ' . ($al['nombre'] ?? ''));
                    $rowsHtml .= '<tr>';
                    $rowsHtml .= '<td class="center">' . $num . '</td>';
                    $rowsHtml .= '<td>' . $nom . '</td>';

                    $idInsc = (int)$al['id_inscripcion'];
                    foreach ($compCodes as $cc) {
                        $nota = $notas[$idInsc][$cc][$label]['nota'] ?? null;
                        $rp   = $notas[$idInsc][$cc][$label]['rp']   ?? null;

                        $notaTxt = ($nota === null || $nota === '') ? '' : number_format((float)$nota, 2);
                        $rpTxt   = ($rp   === null || $rp   === '') ? '' : number_format((float)$rp,   2);

                        $c1 = ($notaTxt !== '' && (float)$notaTxt < 70.0) ? 'bad' : (($notaTxt === '') ? '' : 'ok');
                        $c2 = ($rpTxt   !== '' && (float)$rpTxt   < 70.0) ? 'bad' : (($rpTxt === '')  ? '' : 'ok');

                        $rowsHtml .= '<td class="center ' . $c1 . '">' . $notaTxt . '</td>';
                        $rowsHtml .= '<td class="center ' . $c2 . '">' . $rpTxt . '</td>';
                    }

                    $rowsHtml .= '</tr>';
                }

                $tabla = '<span class="small"><strong>' . $label . '</strong></span>
            <table cellspacing="0" cellpadding="3">
              <thead>' . $thead1 . $thead2 . '</thead>
              <tbody>' . $rowsHtml . '</tbody>
            </table><br/>';
                $pdf->writeHTML($tabla, true, false, true, false, '');
            }

            // ---------- RESUMEN PC ----------
            // PC por competencia = promedio de max(nota,RP) sobre 4 periodos
            $theadRes = '<tr><th width="5%">No.</th><th width="25%">Alumno</th>';
            foreach ($compCodes as $cc) {
                $theadRes .= '<th class="center">PC ' . strtoupper($cc) . '</th>';
            }
            $theadRes .= '<th class="center">Final anual</th></tr>';

            $rowsRes = '';
            foreach ($alumnos as $idx => $al) {
                $num = $idx + 1;
                $nom = trim(($al['apellido'] ?? '') . ', ' . ($al['nombre'] ?? ''));
                $idInsc = (int)$al['id_inscripcion'];

                $pcs = [];
                foreach ($compCodes as $cc) {
                    $sumEff = 0;
                    $count = 0;
                    foreach ($periodos as $label => $idp) {
                        $n = $notas[$idInsc][$cc][$label]['nota'] ?? null;
                        $r = $notas[$idInsc][$cc][$label]['rp']   ?? null;
                        if ($n !== null || $r !== null) {
                            $eff = max((float)($n ?? 0), (float)($r ?? -INF));
                            $sumEff += $eff;
                            $count++;
                        } else {
                            // si quieres forzar /4 siempre, comenta el if y usa $count = 4;
                            $sumEff += 0;
                            $count++;
                        }
                    }
                    $pc = $count ? $sumEff / $count : 0;
                    $pcs[$cc] = round($pc, 2);
                }
                $final = count($pcs) ? round(array_sum($pcs) / count($pcs), 2) : 0;

                $rowsRes .= '<tr>';
                $rowsRes .= '<td class="center">' . $num . '</td>';
                $rowsRes .= '<td>' . $nom . '</td>';
                foreach ($compCodes as $cc) {
                    $val = number_format($pcs[$cc], 2);
                    $rowsRes .= '<td class="center ' . ($pcs[$cc] < 70 ? 'bad' : 'ok') . '">' . $val . '</td>';
                }
                $rowsRes .= '<td class="center ' . ($final < 70 ? 'bad' : 'ok') . '">' . number_format($final, 2) . '</td>';
                $rowsRes .= '</tr>';
            }

            $tablaRes = '
        <span class="small"><strong>Resumen por Competencias</strong></span>
        <table cellspacing="0" cellpadding="3">
          <thead>' . $theadRes . '</thead>
          <tbody>' . $rowsRes . '</tbody>
        </table>';
            $pdf->writeHTML($tablaRes, true, false, true, false, '');
        } else {
            // ---------- TÉCNICO ----------
            foreach ($periodos as $label => $idp) {
                $thead = '<tr>
                <th width="5%">No.</th>
                <th width="35%">Alumno</th>
                <th class="center" width="15%">CAL</th>
                <th class="center" width="15%">AC</th>
                <th class="center" width="15%">%</th>
            </tr>';

                $rowsHtml = '';
                foreach ($alumnos as $idx => $al) {
                    $num = $idx + 1;
                    $nom = trim(($al['apellido'] ?? '') . ', ' . ($al['nombre'] ?? ''));
                    $idInsc = (int)$al['id_inscripcion'];

                    $cal = $tech[$idInsc][$label]['cal'] ?? null;
                    $ac  = $tech[$idInsc][$label]['ac']  ?? null;
                    $porc = null;
                    if ($ac !== null && (float)$ac > 0 && $cal !== null) {
                        $porc = ((float)$cal * 100.0) / (float)$ac;
                    }

                    $calTxt  = ($cal  === null || $cal  === '') ? '' : number_format((float)$cal,  2);
                    $acTxt   = ($ac   === null || $ac   === '') ? '' : number_format((float)$ac,   2);
                    $porcTxt = ($porc === null || $porc === '') ? '' : number_format((float)$porc, 2);

                    $rowsHtml .= '<tr>';
                    $rowsHtml .= '<td class="center">' . $num . '</td>';
                    $rowsHtml .= '<td>' . $nom . '</td>';
                    $rowsHtml .= '<td class="center">' . $calTxt . '</td>';
                    $rowsHtml .= '<td class="center">' . $acTxt . '</td>';
                    $rowsHtml .= '<td class="center ' . ($porc !== null && $porc < 70 ? 'bad' : 'ok') . '">' . $porcTxt . '</td>';
                    $rowsHtml .= '</tr>';
                }

                $tabla = '<span class="small"><strong>' . $label . '</strong></span>
            <table cellspacing="0" cellpadding="3">
              <thead>' . $thead . '</thead>
              <tbody>' . $rowsHtml . '</tbody>
            </table><br/>';
                $pdf->writeHTML($tabla, true, false, true, false, '');
            }
            // (Técnico sin Resumen PC)
        }

        // ===== 7) SALIDA =====
        $pdf->lastPage();
        $pdf->Output('reporte_calificaciones.pdf', 'I');
    }
}

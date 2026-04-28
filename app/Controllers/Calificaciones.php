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

    public function registro()
    {
        $schoolYearActual = $this->schoolYear->getEnCurso(); // devuelve array
        $idSchoolYear = null;
        if (!empty($schoolYearActual)) {
            // Toma el primero (más reciente)
            $idSchoolYear = $schoolYearActual[0]['id'];
        }
        helper('log');

        log_message('debug', '➡️ Entrando al método REGISTRO');

        $competencias = $this->competencias->findAll();
        $periodos     = $this->periodos->findAll();
        $distribucion = $this->distribucionasignaturas->findAll();
        $docentes     = $this->personal->where('funcion', 'Docente')->findAll();

        $estadoPeriodosDocente = [];

        $idUsuario    = session('personal_id');
        $tipoUsuario  = session('tipo_usuario');
        $funcion      = null;

        // Obtener función desde la tabla personal
        $registroPersonal = $this->personal->find($idUsuario);
        if ($registroPersonal && isset($registroPersonal['funcion'])) {
            $funcion = $registroPersonal['funcion'];
        }

        log_message('debug', ' Usuario autenticado: ID=' . $idUsuario . ' | Tipo=' . $tipoUsuario . ' | Función=' . $funcion);

        // 🔹 Si el usuario es docente
        if ($tipoUsuario == 3) {
            log_message('debug', '🎓 Usuario tipo docente. Buscando configuración por ID docente: ' . $idUsuario);

            $configuraciones = $this->configPeriodos
                ->select('p.nombre AS periodo, periodos_configuracion_usuario.bloqueado')
                ->join('periodos p', 'p.id = periodos_configuracion_usuario.id_periodo')
                ->where('periodos_configuracion_usuario.id_personal', $idUsuario)
                ->findAll();

            log_message('debug', '🔍 Configuraciones encontradas (docente): ' . json_encode($configuraciones));

            foreach ($configuraciones as $conf) {
                $estadoPeriodosDocente[$conf['periodo']] = [
                    'bloqueado' => $conf['bloqueado'] == '1' ? true : false
                ];
            }
        }

        // 🔹 Si es administrativo con función válida
        $funcionesPermitidas = ['Director', 'Secretaria', 'Digitador', 'Coordinador', 'Contable'];

        if ($tipoUsuario == 2 && in_array($funcion, $funcionesPermitidas)) {
            log_message('debug', '🛠 Usuario administrativo con función válida (' . $funcion . '). Buscando configuración global');

            $configuraciones = $this->configPeriodos
                ->select('p.nombre AS periodo, periodos_configuracion_usuario.bloqueado')
                ->join('periodos p', 'p.id = periodos_configuracion_usuario.id_periodo')
                ->where('periodos_configuracion_usuario.id_personal', $idUsuario)
                ->where('periodos_configuracion_usuario.id_distribucion_asignatura IS NULL')
                ->findAll();

            log_message('debug', '🔍 Configuraciones encontradas (admin-función): ' . json_encode($configuraciones));

            foreach ($configuraciones as $conf) {
                $estadoPeriodosDocente[$conf['periodo']] = [
                    'bloqueado' => $conf['bloqueado'] == '1' ? true : false
                ];
            }
        }

        // 🔒 Establecer por defecto si no hay datos
        foreach (['P1', 'P2', 'P3', 'P4'] as $p) {
            if (!isset($estadoPeriodosDocente[$p])) {
                log_message('debug', "⚠️ No se encontró configuración para $p. Se establecerá como bloqueado por defecto.");
                $estadoPeriodosDocente[$p] = ['bloqueado' => true];
            }
        }

        log_message('debug', '📦 Estado final de periodos: ' . json_encode($estadoPeriodosDocente));

        $data = [
            'titulo'                 => 'Gestión de Calificaciones',
            'competencias'          => $competencias,
            'periodos'              => $periodos,
            'asignaturas'           => $distribucion,
            'docentes'              => $docentes,
            'estadoPeriodosDocente' => $estadoPeriodosDocente,
            'funcion'               => $funcion,
            'id_schoolyear_actual' => $idSchoolYear
        ];

        echo view('header');
        echo view('calificaciones/registro', $data);
        echo view('footer');
    }
























    public function guardarNotas()
    {
        $request = service('request');
        $post = $request->getPost();

        log_message('debug', 'POST recibido en guardarNotas: ' . print_r($post, true));

        $idInscripciones = $post['id_inscripcion'] ?? [];
        $idDistribucion = $post['id_distribucion_asignatura'] ?? null;

        if (!$idDistribucion) {
            return redirect()->back()->with('error', 'Falta el ID de distribución de asignatura.');
        }

        // Mapa de periodos (ajusta si los IDs son diferentes en tu BD)
        $idPeriodos = [
            'P1' => 1,
            'P2' => 2,
            'P3' => 3,
            'P4' => 4,
        ];

        foreach ($idInscripciones as $i => $idInscripcion) {

            // Guardar calificaciones por competencia y periodo
            foreach (['c1', 'c2', 'c3', 'c4'] as $idx => $comp) {
                $idCompetencia = $idx + 1;

                $notasCompetencia = []; // para calcular promedio

                foreach ($idPeriodos as $label => $idPeriodo) {
                    $key = "{$comp}_{$label}";
                    $nota = $post[$key][$i] ?? null;

                    if ($nota !== null && $nota !== '') {
                        // Guardar en tabla calificaciones
                        $this->calificaciones->guardarOActualizar([
                            'id_inscripcion' => $idInscripcion,
                            'id_distribucion_asignatura' => $idDistribucion,
                            'id_competencia' => $idCompetencia,
                            'id_periodo' => $idPeriodo,
                            'nota' => $nota
                        ]);

                        $notasCompetencia[] = $nota;
                    }
                }

                // Calcular promedio solo si hay notas
                if (!empty($notasCompetencia)) {
                    $promedio = array_sum($notasCompetencia) / count($notasCompetencia);

                    // Guardar en promedio_competencias por periodo (promedio global de la competencia)
                    // Si quieres promedio por periodo individual, deberíamos iterar sobre $idPeriodos y guardar cada uno
                    $this->promedio_competencia->insertarOActualizar([
                        'id_inscripcion' => $idInscripcion,
                        'id_distribucion_asignatura' => $idDistribucion,
                        'id_competencia' => $idCompetencia,
                        'id_periodo' => 0, // 0 si es promedio global de la competencia, o puedes hacer un foreach para cada periodo
                        'promedio' => $promedio
                    ]);
                }
            }

            // Guardar calificación final
            $final = $post['final_area'][$i] ?? null;
            if ($final !== null && $final !== '') {
                $this->evaluaciones_finales->insertarOActualizar([
                    'id_inscripcion' => $idInscripcion,
                    'id_distribucion_asignatura' => $idDistribucion,
                    'calificacion_final' => $final,
                    'situacion_asignatura' => $final >= 70 ? 'A' : 'R'
                ]);
            }
        }

        return redirect()->back()->with('mensaje', 'Notas guardadas correctamente.');
    }



    public function completivo()
    {
        $data = ['titulo' => 'Registro de Completivo',];

        echo view('header');
        echo view('calificaciones/completivo', $data);
        echo view('footer');
    }

    public function extraordinario()
    {
        $data = ['titulo' => 'Registro de Extraordinario',];

        echo view('header');
        echo view('calificaciones/extraordinario', $data);
        echo view('footer');
    }



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
            $estudiantes = $this->inscripciones->getEstudiantesPorCurso($id_curso);


            return $this->response->setJSON($estudiantes);
        }
    }




    public function guardarConfiguracionPeriodos()
    {

        if (session('tipo_usuario') == 3) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No autorizado.'
            ]);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['error' => 'Método no permitido']);
        }

        $configuracion = $this->request->getPost('configuracion');
        $curso = $this->request->getPost('curso');
        $asignatura = $this->request->getPost('asignatura');
        $docente = $this->request->getPost('docente');

        $esGlobal = !$curso && !$asignatura && !$docente;
        $id_docente = null;
        $idDistribucion = null;

        // 🔹 Configuración específica: buscar distribución UNA sola vez
        if (!$esGlobal) {
            $dist = $this->distribucionasignaturas
                ->where('id_personal', $docente)
                ->where('id_curso', $curso)
                ->where('id_asignatura', $asignatura)
                ->first();

            if (!$dist) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se encontró la distribución para esa asignatura.'
                ]);
            }

            $id_docente = $docente;
            $idDistribucion = $dist['id'];
        }

        // 🔹 Configuración GLOBAL: aplicar a todas las distribuciones
        if ($esGlobal) {
            $distribuciones = $this->distribucionasignaturas->findAll();

            foreach ($configuracion as $nombrePeriodo => $valores) {
                $periodo = $this->periodos->where('nombre', $nombrePeriodo)->first();
                if (!$periodo) continue;

                foreach ($distribuciones as $dist) {
                    $data = [
                        'id_distribucion_asignatura' => $dist['id'],
                        'id_personal' => $dist['id_personal'],
                        'id_periodo' => $periodo['id'],
                        'bloqueado' => $valores['bloqueado'],
                        'fecha_configuracion' => date('Y-m-d H:i:s')
                    ];

                    $existe = $this->configPeriodos
                        ->where('id_distribucion_asignatura', $dist['id'])
                        ->where('id_personal', $dist['id_personal'])
                        ->where('id_periodo', $periodo['id'])
                        ->first();

                    if ($existe) {
                        $this->configPeriodos->update($existe['id'], $data);
                    } else {
                        $this->configPeriodos->insert($data);
                    }
                }
            }

            return $this->response->setJSON(['success' => true]);
        }

        // 🔹 Configuración ESPECÍFICA: aplicar solo al docente y distribución asignada
        foreach ($configuracion as $nombrePeriodo => $valores) {
            $periodo = $this->periodos->where('nombre', $nombrePeriodo)->first();
            if (!$periodo) continue;

            $dataInsert = [
                'id_personal' => $id_docente,
                'id_distribucion_asignatura' => $idDistribucion,
                'id_periodo' => $periodo['id'],
                'bloqueado' => $valores['bloqueado'],
                'fecha_configuracion' => date('Y-m-d H:i:s')
            ];

            $this->configPeriodos->updateOrInsert([
                'id_personal' => $id_docente,
                'id_distribucion_asignatura' => $idDistribucion,
                'id_periodo' => $periodo['id']
            ], $dataInsert);
        }

        return $this->response->setJSON(['success' => true]);
    }



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



    public function bloquearPeriodo()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Petición no válida.']);
        }

        $periodo   = $this->request->getPost('periodo');
        $bloquear  = $this->request->getPost('bloquear');
        $id_docente = $this->request->getPost('docente');
        $id_curso = $this->request->getPost('curso');
        $id_asignatura = $this->request->getPost('asignatura');

        $periodoRow = $this->periodos->where('nombre', $periodo)->first();
        if (!$periodoRow) {
            return $this->response->setJSON(['success' => false, 'message' => 'Periodo inválido.']);
        }

        // ⚙️ Cuando no se especifica curso ni asignatura, se asume configuración global para el docente
        if (empty($id_curso) || empty($id_asignatura)) {
            $data = [
                'id_personal' => $id_docente,
                'id_periodo' => $periodoRow['id'],
                'id_distribucion_asignatura' => null
            ];

            $existente = $this->configPeriodos
                ->where('id_personal', $id_docente)
                ->where('id_periodo', $periodoRow['id'])
                ->where('id_distribucion_asignatura IS NULL') // importante
                ->first();

            $data['bloqueado'] = $bloquear;
            $data['visible']   = $existente['visible'] ?? 1;

            if ($existente) {
                $this->configPeriodos->update($existente['id'], $data);
            } else {
                $this->configPeriodos->insert($data);
            }

            return $this->response->setJSON(['success' => true]);
        }

        // 🔍 Si hay curso y asignatura, buscar distribución específica
        $dist = $this->distribucionasignaturas
            ->where('id_personal', $id_docente)
            ->where('id_curso', $id_curso)
            ->where('id_asignatura', $id_asignatura)
            ->first();

        if (!$dist) {
            return $this->response->setJSON(['success' => false, 'message' => 'Distribución no encontrada.']);
        }

        $data = [
            'id_distribucion_asignatura' => $dist['id'],
            'id_personal' => $id_docente,
            'id_periodo' => $periodoRow['id'],
        ];

        $existente = $this->configPeriodos
            ->where('id_personal', $id_docente)
            ->where('id_periodo', $periodoRow['id'])
            ->where('id_distribucion_asignatura', $dist['id'])
            ->first();

        $data['bloqueado'] = $bloquear;
        $data['visible']   = $existente['visible'] ?? 1;

        if ($existente) {
            $this->configPeriodos->update($existente['id'], $data);
        } else {
            $this->configPeriodos->insert($data);
        }

        return $this->response->setJSON(['success' => true]);
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
        $schoolYear = $this->request->getGet('schoolYear'); // id del año escolar

        // Validar parámetros
        if (!$curso || !$asignatura || !$schoolYear) {
            log_message('error', "Parámetros inválidos: curso={$curso}, asignatura={$asignatura}, schoolYear={$schoolYear}");
            return $this->response->setJSON([]);
        }

        // Obtener inscripciones del curso
        $inscripciones = $this->db->table('inscripciones')
            ->select('id, id_estudiante, id_curso, id_grado')
            ->where('id_curso', $curso)
            ->get()
            ->getResultArray();

        if (empty($inscripciones)) {
            log_message('debug', "No se encontraron inscripciones para curso={$curso}");
            return $this->response->setJSON([]);
        }

        // Obtener distribución de asignaturas
        $distribucion = $this->db->table('distribucion_asignaturas')
            ->select('id, id_asignatura')
            ->where('id_asignatura', $asignatura)
            ->where('id_curso', $curso)
            ->where('id_schoolyear', $schoolYear)
            ->get()
            ->getResultArray();

        if (empty($distribucion)) {
            log_message('debug', "No se encontró distribución para asignatura={$asignatura}, curso={$curso}, schoolYear={$schoolYear}");
            return $this->response->setJSON([]);
        }

        // Obtener notas
        $distribucion_ids = array_column($distribucion, 'id');
        $inscripcion_ids  = array_column($inscripciones, 'id');

        $notas = $this->db->table('calificaciones')
            ->whereIn('id_inscripcion', $inscripcion_ids)
            ->whereIn('id_distribucion_asignatura', $distribucion_ids)
            ->get()
            ->getResultArray();

        log_message('debug', "Notas obtenidas: " . print_r($notas, true));

        return $this->response->setJSON($notas);
    }








    public function obtenerCalificacionesGuardadas()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['error' => 'Método no permitido']);
        }

        $idDistribucion = $this->request->getGet('id_distribucion_asignatura');
        $idCurso        = $this->request->getGet('id_curso');

        if (!$idDistribucion || !$idCurso) {
            return $this->response->setJSON(['error' => 'Faltan parámetros.']);
        }

        // 🔹 Obtener estudiantes del curso
        $estudiantes = $this->inscripciones->getEstudiantesPorCurso($idCurso);

        // 🔹 Obtener calificaciones guardadas (por competencia y periodo)
        $query = $this->db->table('calificaciones c')
            ->select('c.id_inscripcion, c.id_competencia, p.nombre AS periodo, c.nota')
            ->join('periodos p', 'p.id = c.id_periodo')
            ->where('c.id_distribucion_asignatura', $idDistribucion)
            ->get()
            ->getResultArray();

        // 🔹 Reorganizar en un arreglo fácil de usar desde JS
        $notas = [];
        foreach ($query as $row) {
            $notas[$row['id_inscripcion']][$row['id_competencia']][$row['periodo']] = $row['nota'];
        }

        // 🔹 Obtener notas finales (si existen)
        $finales = $this->db->table('evaluaciones_finales')
            ->select('id_inscripcion, calificacion_final')
            ->where('id_distribucion_asignatura', $idDistribucion)
            ->get()
            ->getResultArray();

        $notaFinal = [];
        foreach ($finales as $f) {
            $notaFinal[$f['id_inscripcion']] = $f['calificacion_final'];
        }

        // 🔹 Combinar estudiantes con notas
        foreach ($estudiantes as &$est) {
            $idIns = $est['id_inscripcion'];
            $est['notas'] = $notas[$idIns] ?? [];
            $est['final'] = $notaFinal[$idIns] ?? '';
        }

        return $this->response->setJSON($estudiantes);
    }










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

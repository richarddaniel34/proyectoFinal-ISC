<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DistribucionAsignaturasModel;
use App\Models\PersonalModel;
use App\Models\AsignaturaModel;
use App\Models\CursosModel;
use App\Models\SchoolYearModel;
use App\Models\EscuelaModel;

class DistribucionAsignaturas extends BaseController
{
    protected $distribucion;
    protected $docentes;
    protected $asignaturas;
    protected $cursos;
    protected $grados;
    protected $secciones;
    protected $periodos;
    protected $escuela;

    public function __construct()
    {
        $this->distribucion = new DistribucionAsignaturasModel();
        $this->docentes = new PersonalModel();
        $this->asignaturas = new AsignaturaModel();
        $this->cursos = new CursosModel();
        $this->periodos = new SchoolYearModel();
        $this->escuela = new EscuelaModel();
    }

    /**
     * Mostrar todas las asignaciones de docentes con detalles.
     */
    public function index()
    {
        // Obtener la lista de asignaciones con detalles
        $asignaciones = $this->distribucion->getDistribucionConDetalles();

        $data = [
            'titulo' => 'Gestión de Distribución de Asignaturas',
            'asignaciones' => $asignaciones
        ];

        echo view('header');
        echo view('distribucionasignaturas/distribucionasignaturas', $data);
        echo view('footer_distribucion');
    }

    /**
     * Cargar formulario de nueva asignación.
     */
    public function nuevo()
    {
        $idEscuela = session('id_escuela');
        $codigoGestion = session('codigo_gestion');
        $escuela = null;

        // 🔸 Cargar periodos académicos activos
        $periodosEnCurso = $this->periodos->getEnCurso();
        $periodoActual = !empty($periodosEnCurso) ? $periodosEnCurso[0] : null;

        // Validar que haya escuela seleccionada
        if (!$idEscuela && !$codigoGestion) {
            session()->setFlashdata('error_swal', [
                'titulo' => 'Escuela no seleccionada',
                'mensaje' => 'Por favor, seleccione una escuela para poder continuar.'
            ]);
            return redirect()->to(base_url('dashboard'));
        }

        // 🔄 Si solo hay código de gestión, buscar el ID
        if (!$idEscuela && $codigoGestion) {
            $escuela = $this->escuela->where('codigo_gestion', $codigoGestion)->first();
            if ($escuela) {
                $idEscuela = $escuela['id'];
                session()->set('id_escuela', $idEscuela);
            }
        } else {
            $escuela = $this->escuela->find($idEscuela);
        }

        // Si no se encuentra la escuela, detener
        if (!$escuela) {
            session()->setFlashdata('error_swal', [
                'titulo' => 'Escuela no encontrada',
                'mensaje' => 'No se pudo determinar la escuela actual.<br><strong>Por favor, seleccione una escuela.</strong>'
            ]);
            return redirect()->back();
        }

        //  Obtener cursos del año lectivo actual según la escuela
        $idEscuela = $escuela['id']; // asegurar que tenemos el ID actualizado
        $idSchoolYear = $periodoActual['id'] ?? null; // obtener año lectivo actual
        $cursos = $this->cursos->getCursosPorEscuela($idEscuela, $idSchoolYear);
        //dd($this->asignaturas->findAll());
        $personal = $this->docentes->getDocentesPorEscuela($idEscuela);


        $data = [
            'titulo' => 'Nueva Distribución de Asignaturas',
            'personal' => $personal,
            'asignaturas' => $this->asignaturas->findAll(),
            'cursos' => $cursos,
            'periodoActual' => $periodoActual
        ];

        echo view('header');
        echo view('distribucionasignaturas/nuevo', $data);
        echo view('footer');
    }







    /**
     *  Guardar nueva asignación.
     */
    public function insertar()
    {
        $json = $this->request->getPost('asignaciones');
        $asignaciones = json_decode($json, true);

        $idEscuela = session('id_escuela');

        if (!$asignaciones || !is_array($asignaciones)) {
            return redirect()->back()->with('error', 'Datos inválidos.');
        }

        $insertados = 0;
        $duplicados = [];

        foreach ($asignaciones as $asignacion) {
            // Verificar si ya existe el registro
            $existe = $this->distribucion
                ->where('id_personal', $asignacion['id_personal'])
                ->where('id_asignatura', $asignacion['id_asignatura'])
                ->where('id_curso', $asignacion['id_curso'])
                ->where('id_schoolyear', $asignacion['id_schoolyear'])
                ->where('id_escuela', $idEscuela)
                ->first();

            if ($existe) {
                // Obtener nombres legibles
                $docente = $this->docentes->find($asignacion['id_personal'])['nombre'] ?? 'Docente desconocido';
                $asignatura = $this->asignaturas->find($asignacion['id_asignatura'])['nombre'] ?? 'Asignatura desconocida';
                $duplicados[] = "{$docente} - {$asignatura}";
                continue;
            }

            // Insertar nuevo registro
            $this->distribucion->insert([
                'id_personal'   => $asignacion['id_personal'],
                'id_asignatura' => $asignacion['id_asignatura'],
                'id_curso'      => $asignacion['id_curso'],
                'id_schoolyear'    => $asignacion['id_schoolyear'],
                'id_escuela'    => $idEscuela
            ]);

            $insertados++;
        }

        // Preparar mensajes
        $mensaje = '';
        if ($insertados > 0) {
            $mensaje .= "Se han insertado {$insertados} asignaciones correctamente.";
        }
        if (!empty($duplicados)) {
            $mensaje .= " Los siguientes registros ya existen y no se insertaron: " . implode(', ', $duplicados) . ".";
        }

        // Retornar con mensaje según corresponda
        if ($insertados > 0 && empty($duplicados)) {
            return redirect()->to(base_url('/distribucionasignaturas/nuevo'))->with('success', $mensaje);
        } elseif ($insertados > 0 && !empty($duplicados)) {
            return redirect()->to(base_url('/distribucionasignaturas/nuevo'))->with('warning', $mensaje);
        } else {
            return redirect()->to(base_url('/distribucionasignaturas/nuevo'))->with('error', $mensaje);
        }
    }






    /**
     * Eliminar una asignación.
     */
    public function eliminar($id)
    {
        $this->distribucion->delete($id);
        return redirect()->to(base_url('/distribucionasignaturas'))->with('success', 'Asignación eliminada correctamente.');
    }




    public function getDocentesAjax()
    {
        $request = service('request');
        $term = trim($request->getGet('q') ?? '');
        $page = max(1, (int) ($request->getGet('page') ?? 1)); // Evita page < 1
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        // Modelo de docentes = PersonalModel
        $personalModel = new \App\Models\PersonalModel();

        // Obtener id_escuela desde la sesión
        $idEscuela = session()->get('id_escuela');

        // Si es admin y tiene código de gestión pero no id_escuela
        if (!$idEscuela && session()->get('codigo_gestion')) {
            $codigoGestion = session()->get('codigo_gestion');
            $escuelaModel = new \App\Models\EscuelaModel();
            $escuela = $escuelaModel->where('codigo_gestion', $codigoGestion)->first();
            if ($escuela) {
                $idEscuela = $escuela['id'];
            }
        }

        // Si existe una escuela válida
        if ($idEscuela) {
            $docentes = $personalModel->getDocentesPorEscuela($term, $idEscuela, $perPage, $offset);
            $totalResults = $personalModel->contarDocentesPorEscuela($term, $idEscuela);
        } else {
            $docentes = [];
            $totalResults = 0;
        }

        // Verificar si hay más páginas
        $more = ($page * $perPage) < $totalResults;

        return $this->response->setJSON([
            'items' => $docentes,
            'more'  => $more
        ]);
    }






    public function getAsignaturasAjax()
    {
        $q = $this->request->getGet('q') ?? '';

        $asignaturas = $this->asignaturas
            ->like('nombre', $q)
            ->where('activo', 1) // ✅ solo activas
            ->findAll();

        $results = [];
        foreach ($asignaturas as $asig) {
            $results[] = [
                'id' => $asig['id'],
                'text' => $asig['nombre']
            ];
        }

        return $this->response->setJSON(['items' => $results]);
    }

    public function getDistribucionesAjax()
    {
        $idEscuela = session('id_escuela');
        $idPeriodo = $this->periodos->getEnCurso()[0]['id'] ?? null;

        if (!$idEscuela || !$idPeriodo) {
            return $this->response->setJSON(['items' => []]);
        }

        $distribuciones = $this->distribucion
            ->select('d.id, e.nombre AS escuela, p.nombre AS docente, c.nombre_curso AS curso, per.nombre AS periodo')
            ->from('distribucion d')
            ->join('escuelas e', 'd.id_escuela = e.id')
            ->join('personal p', 'd.id_personal = p.id')
            ->join('cursos c', 'd.id_curso = c.id')
            ->join('schoolyear per', 'd.id_schoolyear = per.id')
            ->where('d.id_escuela', $idEscuela)
            ->where('d.id_schoolyear', $idPeriodo)
            ->findAll();

        return $this->response->setJSON(['items' => $distribuciones]);
    }

    public function actualizarDocente()
    {
        $request = service('request');
        $idDistribucion = $request->getPost('id');
        $idDocente = $request->getPost('id_docente');

        if (!$idDistribucion || !$idDocente) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Faltan datos para actualizar.'
            ]);
        }

        try {
            $distribucionModel = new \App\Models\DistribucionAsignaturasModel();

            // Actualizar la columna correcta
            $actualizado = $distribucionModel->update($idDistribucion, ['id_personal' => $idDocente]);

            if ($actualizado) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Docente actualizado correctamente.'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No se pudo actualizar el registro.'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}

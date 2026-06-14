<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DistribucionAsignaturasModel;
use App\Models\PersonalModel;
use App\Models\AsignaturaModel;
use App\Models\CursosModel;
use App\Models\SchoolYearModel;
use App\Models\EscuelaModel;

class DistribucionAcademica extends BaseController
{
    protected $distribucion;
    protected $docentes;
    protected $asignaturas;
    protected $cursos;
    protected $grados;
    protected $secciones;
    protected $schoolyear;
    protected $escuela;

    public function __construct()
    {
        $this->distribucion = new DistribucionAsignaturasModel();
        $this->docentes = new PersonalModel();
        $this->asignaturas = new AsignaturaModel();
        $this->cursos = new CursosModel();
        $this->schoolyear = new SchoolYearModel();
        $this->escuela = new EscuelaModel();
    }

    /**
     * Mostrar todas las asignaciones de docentes con detalles.
     */
    public function index()
    {
        $schoolYearActual = $this->schoolyear
            ->where('estado', 'En curso')
            ->first();

        $schoolYearAnterior = null;
        $asignaciones = [];
        $asignacionesAnterior = [];

        if ($schoolYearActual) {
            $asignaciones = $this->distribucion
                ->getDistribucionConDetalles($schoolYearActual['id']);

            $schoolYearAnterior = $this->schoolyear
                ->where('id <', $schoolYearActual['id'])
                ->orderBy('id', 'DESC')
                ->first();

            if ($schoolYearAnterior) {
                $asignacionesAnterior = $this->distribucion
                    ->getDistribucionConDetalles($schoolYearAnterior['id']);
            }
        }

        $data = [
            'titulo_1' => 'GESTIÓN ACADÉMICA',
            'titulo_2' => 'DISTRIBUCION ACÁDEMICA',
            'schoolYearActual' => $schoolYearActual,
            'schoolYearAnterior' => $schoolYearAnterior,
            'asignaciones' => $asignaciones,
            'asignacionesAnterior' => $asignacionesAnterior,
        ];

        echo view('header');
        echo view('distribucionacademica/distribucionacademica', $data);
        echo view('footer');
    }

    /**
     * Cargar formulario de nueva asignación.
     */
    public function nuevo()
    {
        $idEscuela = session('id_escuela');
        $codigoGestion = session('codigo_gestion');
        $escuela = null;

        // Cargar periodos académicos activos
        $periodosEnCurso = $this->schoolyear->getEnCurso();
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
            'titulo_1' => 'GESTIÓN ACADÉMICA',
            'titulo_2' => 'Nueva Distribución Académica',
            'personal' => $personal,
            'asignaturas' => $this->asignaturas->findAll(),
            'cursos' => $cursos,
            'schoolYearActual' => $periodoActual
        ];

        echo view('header');
        echo view('distribucionacademica/nuevo', $data);
        echo view('footer');
    }


    /**
     * Cargar datos del año anterior
     */
    public function copiarAnterior()
    {
        $schoolYearActual = $this->schoolyear
            ->where('estado', 'En curso')
            ->first();

        if (!$schoolYearActual) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No hay un año escolar en curso.'
            ]);
        }

        $schoolYearAnterior = $this->schoolyear
            ->where('id <', $schoolYearActual['id'])
            ->orderBy('id', 'DESC')
            ->first();

        if (!$schoolYearAnterior) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se encontró un año escolar anterior.'
            ]);
        }

        $resultado = $this->distribucion->copiarDesdeAnioAnterior(
            $schoolYearAnterior['id'],
            $schoolYearActual['id']
        );

        return $this->response->setJSON([
            'success' => true,
            'message' => "Distribución copiada correctamente. 
Registros nuevos: {$resultado['insertados']}. 
Duplicados omitidos: {$resultado['duplicados']}. 
Docentes inactivos omitidos: {$resultado['docentes_inactivos']}. 
Asignaturas inactivas omitidas: {$resultado['asignaturas_inactivas']}."
        ]);
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
            return redirect()->back()->with('error', 'Agregue al menos una asignacion.');
        }

        $insertados = 0;
        $duplicados = [];
        $errores = [];

        foreach ($asignaciones as $asignacion) {

            if (
                empty($asignacion['id_personal']) ||
                empty($asignacion['id_asignatura']) ||
                empty($asignacion['id_curso']) ||
                empty($asignacion['id_schoolyear'])
            ) {
                $errores[] = 'Una asignación llegó incompleta.';
                continue;
            }

            $existe = $this->distribucion
                ->where('id_asignatura', $asignacion['id_asignatura'])
                ->where('id_curso', $asignacion['id_curso'])
                ->where('id_schoolyear', $asignacion['id_schoolyear'])
                ->where('id_escuela', $idEscuela)
                ->first();

            if ($existe) {
                $asignatura = $this->asignaturas->find($asignacion['id_asignatura'])['nombre'] ?? 'Asignatura desconocida';
                $duplicados[] = "{$asignatura}";
                continue;
            }

            $this->distribucion->insert([
                'id_personal'   => $asignacion['id_personal'],
                'id_asignatura' => $asignacion['id_asignatura'],
                'id_curso'      => $asignacion['id_curso'],
                'id_schoolyear' => $asignacion['id_schoolyear'],
                'id_escuela'    => $idEscuela,
                'activo'        => 1
            ]);

            $insertados++;
        }

        $mensaje = '';

        if ($insertados > 0) {
            $mensaje .= "Se han insertado {$insertados} asignaciones correctamente.";
        }

        if (!empty($duplicados)) {
            $mensaje .= " Algunas asignaturas ya estaban asignadas a esos cursos y no se insertaron.";
        }

        if (!empty($errores)) {
            $mensaje .= " Algunas asignaciones llegaron incompletas y fueron omitidas.";
        }

        if ($insertados > 0 && empty($duplicados) && empty($errores)) {
            return redirect()->to(base_url('distribucion-academica/nuevo'))->with('success', $mensaje);
        }

        if ($insertados > 0) {
            return redirect()->to(base_url('distribucion-academica/nuevo'))->with('warning', $mensaje);
        }

        return redirect()->to(base_url('distribucion-academica/nuevo'))->with('error', $mensaje ?: 'No se insertó ninguna asignación.');
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
        $page = max(1, (int) ($request->getGet('page') ?? 1));
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $personalModel = new \App\Models\PersonalModel();

        $idEscuela = session()->get('id_escuela');

        if (!$idEscuela) {
            return $this->response->setJSON([
                'items' => [],
                'more'  => false
            ]);
        }

        $builder = $personalModel
            ->select("personal.id, CONCAT(personal.nombre, ' ', personal.apellido) AS nombre_completo")
            ->join('nombramiento', 'nombramiento.id = personal.funcion', 'left')
            ->where('personal.id_escuela', $idEscuela)
            ->where('personal.activo', 1)
            ->where('nombramiento.nombre', 'Docente')
            ->groupStart()
            ->like('personal.nombre', $term)
            ->orLike('personal.apellido', $term)
            ->orLike("CONCAT(personal.nombre, ' ', personal.apellido)", $term)
            ->groupEnd()
            ->orderBy('personal.nombre', 'ASC');

        /*
     * Si tu campo funcion guarda exactamente "Docente",
     * activa esta línea:
     */
        // $builder->where('funcion', 'Docente');

        $docentes = $builder
            ->limit($perPage, $offset)
            ->findAll();

        $totalBuilder = $personalModel
            ->join('nombramiento', 'nombramiento.id = personal.funcion', 'left')
            ->where('personal.id_escuela', $idEscuela)
            ->where('personal.activo', 1)
            ->where('nombramiento.nombre', 'Docente')
            ->groupStart()
            ->like('personal.nombre', $term)
            ->orLike('personal.apellido', $term)
            ->orLike("CONCAT(personal.nombre, ' ', personal.apellido)", $term)
            ->groupEnd();

        /*
     * Si activas el filtro de funcion arriba,
     * actívalo también aquí:
     */
        // $totalBuilder->where('funcion', 'Docente');

        $totalResults = $totalBuilder->countAllResults();

        return $this->response->setJSON([
            'items' => $docentes,
            'more'  => ($page * $perPage) < $totalResults
        ]);
    }


    public function getAsignaturasAjax()
    {
        $q = $this->request->getGet('q');

        $data = $this->asignaturas->buscar($q);

        $results = [];

        foreach ($data as $row) {
            $results[] = [
                'id' => $row['id'],
                'text' => $row['nombre'],
                'tipo_asignatura' => $row['tipo_asignatura']
            ];
        }

        return $this->response->setJSON([
            'items' => $results
        ]);
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

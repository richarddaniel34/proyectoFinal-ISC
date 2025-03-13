<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DistribucionAsignaturasModel;
use App\Models\PersonalModel;
use App\Models\AsignaturaModel;
use App\Models\CursosModel;
use App\Models\SchoolYearModel;

class DistribucionAsignaturasController extends BaseController
{
    protected $distribucion;
    protected $docentes;
    protected $asignaturas;
    protected $cursos;
    protected $grados;
    protected $secciones;
    protected $periodos;

    public function __construct()
    {
        $this->distribucion = new DistribucionAsignaturasModel();
        $this->docentes = new PersonalModel();
        $this->asignaturas = new AsignaturaModel();
        $this->cursos = new CursosModel();
        $this->periodos = new SchoolYearModel();
    }

    /**
     * 🔥 Mostrar todas las asignaciones de docentes con detalles.
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
        echo view('distribucion_asignaturas/distribucion_asignaturas', $data);
        echo view('footer');
    }

    /**
     * 🔥 Cargar formulario de nueva asignación.
     */
    public function nuevo()
    {
        $data = [
            'titulo' => 'Nueva Distribución de Asignaturas',
            'docentes' => $this->docentes->where('funcion', 'Docente')->findAll(),
            'asignaturas' => $this->asignaturas->findAll(),
            'cursos' => $this->cursos->findAll(),
            'grados' => $this->grados->findAll(),
            'secciones' => $this->secciones->findAll(),
            'periodos' => $this->periodos->findAll(),
        ];

        echo view('header');
        echo view('distribucion_asignaturas/nuevo', $data);
        echo view('footer');
    }

    /**
     * 🔥 Guardar nueva asignación.
     */
    public function insertar()
    {
        if (!$this->validate($this->distribucion->validationRules, $this->distribucion->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->distribucion->save([
            'id_docente'    => $this->request->getPost('id_docente'),
            'id_asignatura' => $this->request->getPost('id_asignatura'),
            'id_curso'      => $this->request->getPost('id_curso'),
            'id_grado'      => $this->request->getPost('id_grado'),
            'id_seccion'    => $this->request->getPost('id_seccion'),
            'id_periodo'    => $this->request->getPost('id_periodo'),
        ]);

        return redirect()->to(base_url('/distribucion_asignaturas'))->with('success', 'Asignación creada correctamente.');
    }

    /**
     * 🔥 Eliminar una asignación.
     */
    public function eliminar($id)
    {
        $this->distribucion->delete($id);
        return redirect()->to(base_url('/distribucion_asignaturas'))->with('success', 'Asignación eliminada correctamente.');
    }
}

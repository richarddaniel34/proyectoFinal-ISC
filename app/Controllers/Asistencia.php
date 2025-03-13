<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AsistenciaModel;
use App\Models\EstudiantesModel;
use App\Models\AsignaturaModel;
use App\Models\PersonalModel;

class Asistencia extends BaseController
{
    protected $asistencias;
    protected $estudiantes;
    protected $asignaturas;
    protected $docentes;
    protected $cursos;

    public function __construct()
    {
        $this->asistencias = new AsistenciaModel();
        $this->estudiantes = new EstudiantesModel();
        $this->asignaturas = new AsignaturaModel();
        $this->docentes = new PersonalModel();
        $this->cursos = new PersonalModel();
    }

    /**
     * 🔥 Mostrar todas las asistencias
     */
    public function index()
    {
        $asistencias = $this->asistencias->getAsistenciasConDetalles();

        $data = [
            'titulo' => 'Registro de Asistencias',
            'asistencias' => $asistencias
        ];

        echo view('header');
        echo view('asistencia/asistencia', $data);
        echo view('footer');
    }

    /**
     * 🔥 Mostrar formulario de registro de asistencia
     */
    public function nuevo()
    {
        $estudiantes = $this->estudiantes->findAll();
        $asignaturas = $this->asignaturas->findAll();
        $docentes = $this->docentes->findAll();
        $cursos = $this->cursos->findAll();

        $data = [
            'titulo' => 'Registrar Asistencia',
            'estudiantes' => $estudiantes,
            'asignaturas' => $asignaturas,
            'docentes' => $docentes,
            'cursos' => $cursos
        ];

        echo view('header');
        echo view('asistencia/nuevo', $data);
        echo view('footer');
    }

    /**
     * 🔥 Insertar nueva asistencia
     */
    public function insertar()
    {
        // Validar datos
        if (!$this->validate($this->asistencias->validationRules, $this->asistencias->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Guardar asistencia
        $this->asistencias->save([
            'id_estudiante' => $this->request->getPost('id_estudiante'),
            'id_asignatura' => $this->request->getPost('id_asignatura'),
            'id_docente'    => $this->request->getPost('id_docente'),
            'fecha'         => $this->request->getPost('fecha'),
            'estado'        => $this->request->getPost('estado'),
            'observaciones' => $this->request->getPost('observaciones')
        ]);

        return redirect()->to(base_url('asistencias'))->with('success', 'Asistencia registrada correctamente.');
    }

    /**
     * 🔥 Mostrar formulario de edición
     */
    public function editar($id)
    {
        $asistencia = $this->asistencias->find($id);
        $estudiantes = $this->estudiantes->findAll();
        $asignaturas = $this->asignaturas->findAll();
        $docentes = $this->docentes->findAll();

        if (!$asistencia) {
            return redirect()->to(base_url('asistencias'))->with('error', 'Registro de asistencia no encontrado.');
        }

        $data = [
            'titulo' => 'Editar Asistencia',
            'asistencia' => $asistencia,
            'estudiantes' => $estudiantes,
            'asignaturas' => $asignaturas,
            'docentes' => $docentes
        ];

        echo view('header');
        echo view('asistencias/editar', $data);
        echo view('footer');
    }

    /**
     * 🔥 Actualizar asistencia
     */
    public function actualizar()
    {
        $id = $this->request->getPost('id');

        if (!$this->validate($this->asistencias->validationRules, $this->asistencias->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->asistencias->update($id, [
            'id_estudiante' => $this->request->getPost('id_estudiante'),
            'id_asignatura' => $this->request->getPost('id_asignatura'),
            'id_docente'    => $this->request->getPost('id_docente'),
            'fecha'         => $this->request->getPost('fecha'),
            'estado'        => $this->request->getPost('estado'),
            'observaciones' => $this->request->getPost('observaciones')
        ]);

        return redirect()->to(base_url('asistencias'))->with('success', 'Asistencia actualizada correctamente.');
    }

    /**
     * 🔥 Eliminar asistencia
     */
    public function eliminar($id)
    {
        $this->asistencias->delete($id);
        return redirect()->to(base_url('asistencias'))->with('success', 'Registro de asistencia eliminado.');
    }
}

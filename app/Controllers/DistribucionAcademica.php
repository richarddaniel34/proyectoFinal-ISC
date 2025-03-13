<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DistribucionAcademicaModel;
use App\Models\EscuelaModel;
use App\Models\PersonalModel;
use App\Models\CursosModel;
use App\Models\SchoolYearModel;

class DistribucionAcademica extends BaseController
{
    protected $distribucion;
    protected $escuela;
    protected $personal;
    protected $cursos;
    protected $schoolyear;

    public function __construct()
    {
        $this->distribucion = new DistribucionAcademicaModel();
        $this->escuela = new EscuelaModel();
        $this->personal = new PersonalModel();
        $this->cursos = new CursosModel();
        $this->schoolyear = new SchoolYearModel();
    }

    /**
     * 🔥 Mostrar la distribución académica
     */
    public function index()
    {
        $data = [
            'titulo' => 'Distribución Académica',
            'datos' => $this->distribucion->getDistribucionConDetalles()
        ];

        echo view('header');
        echo view('distribucionacademica/distribucionacademica', $data);
        echo view('footer');
    }

    /**
     * 🔥 Mostrar formulario de nueva distribución académica
     */

    public function obtenerDocentesPorEscuela()
    {
        $idEscuela = $this->request->getPost('id_escuela');

        if (!$idEscuela) {
            return $this->response->setJSON([]);
        }

        $docentes = $this->personal->getDocentesPorEscuela($idEscuela);

        return $this->response->setJSON($docentes);
    }

    public function getCursos()
    {
        $idEscuelaSeleccionada = $this->request->getPost('id_escuela');

        if (!$idEscuelaSeleccionada) {
            return $this->response->setJSON(['error' => 'No se recibió el ID de la escuela.']);
        }

        // Obtener código de la escuela
        $escuela = $this->escuela->find($idEscuelaSeleccionada);
        if (!$escuela) {
            return $this->response->setJSON(['error' => 'Escuela no encontrada.']);
        }

        $codigoEscuela = $escuela['codigo_gestion'];

        // Obtener cursos filtrados según la escuela
        $cursos = $this->cursos->getCursosPorEscuela($codigoEscuela);

        return $this->response->setJSON($cursos);
    }




    public function nuevo()
    {
        $idEscuelaSeleccionada = $this->request->getPost('id_escuela') ?? null;
        $codigoEscuela = null;
        $docentes = [];
        $cursos = [];

        // 🔥 Buscar el código de la escuela seleccionada
        if ($idEscuelaSeleccionada) {
            $escuela = $this->escuela->find($idEscuelaSeleccionada);
            if ($escuela) {
                $codigoEscuela = $escuela['codigo_gestion']; // 🔥 Obtener el código de la escuela
            }

            // 🔥 Obtener docentes y cursos filtrados por escuela
            $docentes = $this->personal->getDocentesPorEscuela($idEscuelaSeleccionada);
            $cursos = $this->cursos->getCursosPorEscuela($codigoEscuela);
        }

        // 📌 Pasamos los datos a la vista
        $data = [
            'titulo'   => 'Nueva Distribución Académica',
            'escuelas' => $this->escuela->findAll(),
            'docentes' => $docentes,
            'cursos'   => $cursos,
            'periodos' => $this->schoolyear->findAll()
        ];

        echo view('header');
        echo view('distribucionacademica/nuevo', $data);
        echo view('footer');
    }











    /**
     * 🔥 Insertar una nueva distribución académica
     */
    public function insertar()
    {
        if (!$this->validate($this->distribucion->validationRules, $this->distribucion->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->distribucion->save([
            'id_escuela' => $this->request->getPost('id_escuela'),
            'id_personal' => $this->request->getPost('id_personal'),
            'id_curso' => $this->request->getPost('id_curso'),
            'id_periodo_academico' => $this->request->getPost('id_periodo_academico')
        ]);

        return redirect()->to(base_url('/distribucionacademica/nuevo'))->with('success', 'Distribución registrada correctamente.');
    }

    /**
     * 🔥 Mostrar formulario de edición
     */
    public function editar($id)
    {
        $data = [
            'titulo' => 'Editar Distribución Académica',
            'distribucion' => $this->distribucion->find($id),
            'escuelas' => $this->escuela->findAll(),
            'docentes' => $this->personal->where('nombramiento', 'Docente')->findAll(),
            'cursos' => $this->cursos->findAll(),
            'periodos' => $this->schoolyear->findAll()
        ];

        echo view('header');
        echo view('distribucion_academica/editar', $data);
        echo view('footer');
    }

    /**
     * 🔥 Actualizar la distribución académica
     */
    public function actualizar()
    {
        $id = $this->request->getPost('id');

        if (!$this->validate($this->distribucion->validationRules, $this->distribucion->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->distribucion->update($id, [
            'id_escuela' => $this->request->getPost('id_escuela'),
            'id_personal' => $this->request->getPost('id_personal'),
            'id_curso' => $this->request->getPost('id_curso'),
            'id_periodo_academico' => $this->request->getPost('id_periodo_academico'),
            'activo' => $this->request->getPost('activo')
        ]);

        return redirect()->to(base_url('/distribucion_academica'))->with('success', 'Distribución actualizada correctamente.');
    }

    /**
     * 🔥 Eliminar distribución académica
     */
    public function eliminar($id)
    {
        $this->distribucion->delete($id);
        return redirect()->to(base_url('/distribucion_academica'))->with('success', 'Distribución eliminada correctamente.');
    }
}

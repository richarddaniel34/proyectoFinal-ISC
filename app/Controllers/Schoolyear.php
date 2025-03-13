<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SchoolyearModel;

class Schoolyear extends BaseController
{
    protected $schoolyear;

    public function __construct()
    {
        $this->schoolyear = new SchoolyearModel();
    }

    public function index($activo = 1)
    {

        // SELECT * FROM schoolyear WHEN activo = $activo
        $schoolyear = $this->schoolyear->where('activo', $activo)->findAll();

        $data = ['titulo' => 'Período Académico', 'datos' => $schoolyear];

        echo view('header');
        echo view('schoolyear/schoolyear', $data);
        echo view('footer');
    }

    public function nuevo()
    {

        $data = ['titulo' => 'Registrar Período Académico'];

        echo view('header');
        echo view('schoolyear/nuevo', $data);
        echo view('footer');
    }




    public function insertar()
    {
        // Verifica si hay un año escolar sin fecha de término válida
        $añoSinTermino = $this->schoolyear
            ->where('fecha_termino', '0000-00-00')
            ->orWhere('fecha_termino', null)
            ->first(); // Busca el primer registro con fecha de término vacía

        if ($añoSinTermino) {
            // Si existe un año sin fecha de término, devuelve un error
            return redirect()->to(base_url() . '/schoolyear')
                ->with('error', 'No puedes registrar un nuevo año escolar mientras el anterior no tenga una fecha de término válida.');
        }

        // Si no hay conflictos, inserta el nuevo año escolar
        $this->schoolyear->save([
            'nombre' => $this->request->getPost('nombre'),
            'fecha_inicio' => $this->request->getPost('fecha_inicio'),
            'fecha_termino' => $this->request->getPost('fecha_termino'),
            'codigo' => $this->request->getPost('codigo')
        ]);

        return redirect()->to(base_url() . '/schoolyear')->with('success', 'Año escolar registrado con éxito.');
    }




    public function editar($id)
    {


        $schoolyear = $this->schoolyear->where('id', $id)->first();

        if (!$schoolyear) {
            return $this->response->setStatusCode(404)->setBody('Datos no encontrados.');
        }

        $data = ['titulo' => 'Editar/Finalizar año escolar', 'datos' => $schoolyear];


        // Devolver solo la vista parcial del formulario de edición para el modal
        return view('schoolyear/editar', $data);
    }




    public function actualizar($id)
    {

        // Buscar el año escolar en la base de datos
        $añoEscolar = $this->schoolyear->find($id);

        if (!$añoEscolar) {
            return redirect()->to(base_url() . '/schoolyear')->with('error', 'El año escolar no existe.');
        }

        // Obtener datos del formulario
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'fecha_inicio' => $this->request->getPost('fecha_inicio'),
            'fecha_termino' => $this->request->getPost('fecha_termino'),
            'codigo' => $this->request->getPost('codigo')
        ];

        // Permitir la edición sin restricciones
        $this->schoolyear->update($id, $data);

        return redirect()->to(base_url() . '/schoolyear')->with('success', 'Año escolar actualizado con éxito.');
    }




    public function eliminar($id) {}
    public function eliminados($id) {}
    public function restaurar($id) {}
    public function visualizar($id) {}
}

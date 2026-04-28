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
        log_message('debug', 'Iniciando inserción de año escolar.');

        // Verifica si ya existe un año escolar en espera
        $periodoEnEspera = $this->schoolyear
            ->where('estado', 'En espera')
            ->first();

        if ($periodoEnEspera) {
            log_message('debug', 'Ya existe un año escolar en estado "En espera" con ID: ' . $periodoEnEspera['id']);

            // Flashdata y redirección al index del módulo
            return redirect()->to(base_url('/schoolyear'))
                ->with('error', 'Ya existe un período escolar en estado "En espera". Finalízalo o actívalo antes de agregar uno nuevo.');
        }

        // No hay conflicto → insertar
        $save = $this->schoolyear->save([
            'nombre' => $this->request->getPost('nombre'),
            'fecha_inicio' => $this->request->getPost('fecha_inicio'),
            'fecha_termino' => $this->request->getPost('fecha_termino'),
            'codigo' => $this->request->getPost('codigo'),
            'estado' => 'En espera' // Insertar con estado fijo
        ]);

        if ($save) {
            log_message('debug', 'Año escolar insertado correctamente.');
        } else {
            log_message('error', 'Error al insertar el año escolar.');
        }

        return redirect()->to(base_url('/schoolyear'))
            ->with('success', 'Año escolar registrado correctamente con estado "En espera".');
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
        log_message('debug', "Actualizando año escolar con ID: $id");

        // Buscar el año escolar en la base de datos
        $añoEscolar = $this->schoolyear->find($id);

        if (!$añoEscolar) {
            return redirect()->to(base_url() . '/schoolyear')->with('error', 'El año escolar no existe.');
        }

        // Obtener datos del formulario
        $nuevoEstado = $this->request->getPost('estado');

        // Validación: si se intenta activar como "En curso"
        if ($nuevoEstado === 'En curso') {
            $enCursoExistente = $this->schoolyear
                ->where('estado', 'En curso')
                ->where('id !=', $id)
                ->first();

            if ($enCursoExistente) {
                return redirect()->to(base_url() . '/schoolyear')
                    ->with('error', 'Ya existe un período escolar en curso. Finalízalo antes de activar otro.');
            }
        }

        // Validación: si se intenta marcar como "Finalizado" pero no estaba en curso
        if ($nuevoEstado === 'Finalizado' && $añoEscolar['estado'] !== 'En curso') {
            return redirect()->to(base_url() . '/schoolyear')
                ->with('error', 'Solo se puede finalizar un período que esté en curso.');
        }

        // Preparar datos para actualizar
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'fecha_inicio' => $this->request->getPost('fecha_inicio'),
            'fecha_termino' => $this->request->getPost('fecha_termino'),
            'codigo' => $this->request->getPost('codigo'),
            'estado' => $nuevoEstado
        ];

        // Actualizar
        $this->schoolyear->update($id, $data);

        log_message('debug', "Año escolar con ID $id actualizado correctamente.");

        return redirect()->to(base_url() . '/schoolyear')
            ->with('success', 'Año escolar actualizado con éxito.');
    }




    public function eliminar($id) {}
    public function eliminados($id) {}
    public function restaurar($id) {}
    public function visualizar($id) {}
}

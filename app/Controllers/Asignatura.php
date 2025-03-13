<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AsignaturaModel;

class Asignatura extends BaseController
{
    protected $asignatura;

    public function __construct()
    {
        $this->asignatura = new AsignaturaModel();
    }

    public function index($activo = 1)
    {

        // Realiza un join entre la tabla 'asignatura' y 'atipo_asignatura'
        $asignatura = $this->asignatura->select('asignatura.*, tipo_asignatura.nombre AS tipo_asignatura_nombre')
            ->join('tipo_asignatura', 'asignatura.tipo_asignatura = tipo_asignatura.id')
            ->where('asignatura.activo', $activo)
            ->findAll();

        // Conexión a la base de datos
        $db = \Config\Database::connect();

        // Consulta para obtener las atipo_asignaturaes
        $query = $db->query("SELECT id, nombre FROM tipo_asignatura");

        // Almacenar el resultado en un arreglo
        $tipo_asignaturas = $query->getResultArray();

        $data = ['titulo' => 'Asignaturas', 'datos' => $asignatura, 'tipo_asignaturas' => $tipo_asignaturas];

        echo view('header');
        echo view('asignatura/asignatura', $data);
        echo view('footer');
    }

    public function nuevo()
    {
        // Conexión a la base de datos
        $db = \Config\Database::connect();

        // Consulta para obtener las tipo_asignaturas
        $query = $db->query("SELECT id, nombre FROM tipo_asignatura");

        // Almacenar el resultado en un arreglo
        $tipo_asignaturas = $query->getResultArray();

        // Pasar los datos de tipo_asignaturas a la vista junto con el título
        $data = ['titulo' => 'Registro de asignaturas', 'tipo_asignaturas' => $tipo_asignaturas];

        //$data = ['titulo' =>'Registro de datos del centro educativo'];

        echo view('header');
        echo view('asignatura/nuevo', $data);
        echo view('footer');
    }




    public function insertar()
    {
        // Reglas de validación
        $rules = [
            'nombre' => 'required',
            'codigo_asignatura' => 'required',
            'tipo_asignatura' => 'required',
        ];

        // Mensajes de error personalizados (opcional)
        $messages = [
            'nombre' => [
                'required' => 'El nombre es obligatorio.'
            ],
            'codigo_asignatura' => [
                'required' => 'El código de asignatura es obligatorio.',
            ],
            'tipo_asignatura' => [
                'required' => 'El tipo de asignatura es obligatorio.',
            ]
        ];

        // Validar los datos del formulario
        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Obtener los valores del formulario
        $nombre = $this->request->getPost('nombre');
        $codigo = $this->request->getPost('codigo_asignatura');
        $tipo = $this->request->getPost('tipo_asignatura');

        // Verificar si ya existe una asignatura con el mismo nombre
        $existeNombre = $this->asignatura->where('nombre', $nombre)->first();
        if ($existeNombre) {
            return redirect()->back()
                ->withInput()
                ->with('errors', [
                    'nombre' => 'Ya existe una asignatura con este nombre.'
                ]);
        }

        // Verificar si ya existe una asignatura con el mismo código
        $existeCodigo = $this->asignatura->where('codigo_asignatura', $codigo)->first();
        if ($existeCodigo) {
            return redirect()->back()
                ->withInput()
                ->with('errors', [
                    'codigo_asignatura' => 'Ya existe una asignatura con este código.'
                ]);
        }

        // Guardar los datos en la base de datos
        $this->asignatura->save([
            'nombre' => $nombre,
            'codigo_asignatura' => $codigo,
            'tipo_asignatura' => $tipo,
        ]);

        return redirect()->to(base_url() . '/asignatura')->with('success', 'Datos guardados correctamente.');
    }



    public function editar($id)
    {
        log_message('debug', 'Editando asignatura con ID: ' . $id);

        // Busca la asignatura por su ID
        $asignatura = $this->asignatura->where('id', $id)->first();

        if (!$asignatura) {
            return $this->response->setStatusCode(404)->setBody('Escuela no encontrada.');
        }

        // Conexión a la base de datos para obtener las tipo_asignaturas
        $db = \Config\Database::connect();
        $query = $db->query("SELECT id, nombre FROM tipo_asignatura");
        $tipo_asignaturas = $query->getResultArray();

        // Pasar las tipo_asignaturas a la vista junto con los datos de la asignatura
        $data = ['titulo' => 'Editar datos del centro educativo', 'datos' => $asignatura, 'tipo_asignaturas' => $tipo_asignaturas];

        // Devolver la vista
        // $data = ['titulo' => 'Editar datos del centro educativo', 'datos' => $asignatura];

        // Devolver solo la vista parcial del formulario de edición para el modal
        return view('asignatura/editar', $data);
    }







    public function actualizar($id) {
        // Reglas de validación
        $rules = [
            'nombre' => 'required',
            'codigo_asignatura' => 'required',
            'tipo_asignatura' => 'required'
        ];

        $messages = [
            'nombre' => [
                'required' => 'El nombre es obligatorio.'
            ],
            'codigo_asignatura' => [
                'required' => 'El código de asignatura es obligatorio.'
            ],
            'tipo_asignatura' => [
                'required' => 'El tipo de asignatura es obligatorio.'
            ]
        ];

        // Validar los datos del formulario
        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Obtener los datos actuales del registro
        $asignaturaActual = $this->asignatura->find($id);
        if (!$asignaturaActual) {
            return redirect()->back()->with('error', 'Registro no encontrado.');
        }

        // Obtener los valores del formulario
        $nombre = $this->request->getPost('nombre');
        $codigo = $this->request->getPost('codigo_asignatura');
        $tipo = $this->request->getPost('tipo_asignatura');

        // Verificar si ya existe otra asignatura con el mismo nombre (excluyendo la actual)
        $existeNombre = $this->asignatura->where('nombre', $nombre)->where('id !=', $id)->first();
        if ($existeNombre) {
            return redirect()->back()
                ->withInput()
                ->with('errors', [
                    'nombre' => 'Ya existe otra asignatura con este nombre.'
                ]);
        }

        // Verificar si ya existe otra asignatura con el mismo código (excluyendo la actual)
        $existeCodigo = $this->asignatura->where('codigo_asignatura', $codigo)->where('id !=', $id)->first();
        if ($existeCodigo) {
            return redirect()->back()
                ->withInput()
                ->with('errors', [
                    'codigo_asignatura' => 'Ya existe otra asignatura con este código.'
                ]);
        }

        // Crear un array con los datos actualizados
        $datosActualizados = [
            'nombre' => $nombre,
            'codigo_asignatura' => $codigo,
            'tipo_asignatura' => $tipo,
        ];

        // Actualizar el registro en la base de datos
        $this->asignatura->update($id, $datosActualizados);

        return redirect()->to(base_url() . '/asignatura')->with('success', 'Datos actualizados correctamente.');
    }




    public function eliminar($id) {

        $this->asignatura->update($id, ['activo' => 0]);
        return redirect()->to(base_url() . 'asignatura');
    }



    public function eliminados($activo = 0) {

        // Realiza un join entre la tabla 'asignatura' y 'atipo_asignatura'
        $asignatura = $this->asignatura->select('asignatura.*, tipo_asignatura.nombre AS tipo_asignatura_nombre')
            ->join('tipo_asignatura', 'asignatura.tipo_asignatura = tipo_asignatura.id')
            ->where('asignatura.activo', $activo)
            ->findAll();

        // Conexión a la base de datos
        $db = \Config\Database::connect();

        // Consulta para obtener las atipo_asignaturaes
        $query = $db->query("SELECT id, nombre FROM tipo_asignatura");

        // Almacenar el resultado en un arreglo
        $tipo_asignaturas = $query->getResultArray();

        $data = ['titulo' => 'Asignaturas', 'datos' => $asignatura, 'tipo_asignaturas' => $tipo_asignaturas];

        echo view('header');
        echo view('asignatura/eliminados', $data);
        echo view('footer');
    }

    public function restaurar($id) {

        $this->asignatura->update($id, ['activo' => 1]);
        return redirect()->to(base_url() . 'asignatura/eliminados');
    }


    public function visualizar($id) {}
}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\GradoModel;
use App\Models\CursosModel;

class Grados extends BaseController
{
    protected $grado;
    protected $curso;

    public function __construct()
    {
        $this->grado = new GradoModel();
        $this->curso = new CursosModel();
        $cursoModel = new CursosModel();
    }

    public function index($activo = 1)
    {
        $grado = $this->grado
            ->select('grados.id AS id_grado, grados.nombre AS grado_nombre, secciones.nombre AS seccion, cursos.nombreCurso AS curso, cursos.codigoCurso')
            ->join('cursos', 'cursos.id_grado = grados.id', 'inner') // Relación entre grados y cursos
            ->join('secciones', 'secciones.id = cursos.id_secciones', 'inner') // Relación entre cursos y secciones
            ->where('grados.activo', $activo)
            ->orderBy('grados.nombre', 'ASC') // Ordenar primero por grado
            ->orderBy('secciones.nombre', 'ASC') // Luego, por sección
            ->findAll();

        $data = ['titulo' => 'Grados y Secciones', 'datos' => $grado];

        echo view('header');
        echo view('grados/grados', $data);
        echo view('footer');
    }





    public function nuevo()
    {
        // Conexión a la base de datos
        $db = \Config\Database::connect();

        // Consulta para obtener las tipo_asignaturas
        $query = $db->query("SELECT id, nombre FROM secciones");

        // Almacenar el resultado en un arreglo
        $secciones = $query->getResultArray();

        // Pasar los datos de tipo_asignaturas a la vista junto con el título
        $data = ['titulo' => 'Registro de Grados y Secciones', 'secciones' => $secciones];

        //$data = ['titulo' =>'Registro de datos del centro educativo'];

        echo view('header');
        echo view('grados/nuevo', $data);
        echo view('footer');
    }

    public function nuevoCurso()
    {
        // Conexión a la base de datos
        $db = \Config\Database::connect();

        // Consulta para obtener las tipo_asignaturas
        // Consulta para obtener los grados
        $queryGrados = $db->query("SELECT id, nombre FROM grados");
        $grados = $queryGrados->getResultArray();

        // Consulta para obtener las secciones
        $querySecciones = $db->query("SELECT id, nombre FROM secciones");
        $secciones = $querySecciones->getResultArray();



        // Pasar los datos de tipo_asignaturas a la vista junto con el título
        $data = ['titulo' => 'Registro de Cursos', 'grados' => $grados, 'secciones' => $secciones];

        //$data = ['titulo' =>'Registro de datos del centro educativo'];

        echo view('header');
        echo view('grados/cursos', $data);
        echo view('footer');
    }

    public function insertarCurso()
{
    // Reglas de validación básicas
    $rules = [
        'nombreCurso'  => 'required',
        'id_grado'     => 'required|integer',
        'id_secciones' => 'required|integer',
        'codigoCurso'  => 'required'
    ];

    $messages = [
        'nombreCurso' => [
            'required' => 'El nombre del curso es obligatorio.'
        ],
        'id_grado' => [
            'required' => 'Debe seleccionar un grado.'
        ],
        'id_secciones' => [
            'required' => 'Debe seleccionar una sección.'
        ],
        'codigoCurso' => [
            'required' => 'El código del curso es obligatorio.'
        ]
    ];

    // 🔹 Validar los datos del formulario
    if (!$this->validate($rules, $messages)) {
        return redirect()->back()
            ->withInput()
            ->with('errors', $this->validator->getErrors());
    }

    // 🔹 Obtener los valores del formulario
    $id_grado     = $this->request->getPost('id_grado');
    $id_secciones = $this->request->getPost('id_secciones');
    $nombreCurso  = $this->request->getPost('nombreCurso');
    $codigoCurso  = $this->request->getPost('codigoCurso');

    // Obtener la sección seleccionada
    $db = \Config\Database::connect();
    $seccionQuery = $db->query("SELECT nombre FROM secciones WHERE id = ?", [$id_secciones]);
    $seccionActual = $seccionQuery->getRow();
    
    if ($seccionActual) {
        // Verificar si es una sección que requiere validación secuencial (B, C, D, etc.)
        if ($seccionActual->nombre != 'A' && $seccionActual->nombre != 'ÚNICA') {
            // Obtener la letra anterior en la secuencia
            $letraAnterior = chr(ord($seccionActual->nombre) - 1);
            
            // Verificar si existe un curso con la sección anterior para el mismo grado
            $seccionAnteriorQuery = $db->query(
                "SELECT c.id FROM cursos c 
                 JOIN secciones s ON c.id_secciones = s.id 
                 WHERE c.id_grado = ? AND s.nombre = ?", 
                [$id_grado, $letraAnterior]
            );
            
            $existeSeccionAnterior = $seccionAnteriorQuery->getRow();
            
            if (!$existeSeccionAnterior) {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', [
                        'id_secciones' => "No se puede agregar la sección {$seccionActual->nombre} sin antes tener la sección {$letraAnterior} para este grado."
                    ]);
            }
        }
    }

    // 🔥 Validar que no exista el mismo curso en el mismo grado y sección
    $cursoExistente = $this->curso
        ->where('id_grado', $id_grado)
        ->where('id_secciones', $id_secciones)
        ->first();

    if ($cursoExistente) {
        // Añadimos el error al array 'errors' en la clave específica
        return redirect()->back()
            ->withInput()
            ->with('errors', [
                'id_secciones' => 'Ya existe un curso para este grado y sección.'
            ]);
    }

    // 🔥 Validar que el código del curso sea único
    $codigoExistente = $this->curso
        ->where('codigoCurso', $codigoCurso)
        ->first();

    if ($codigoExistente) {
        return redirect()->back()
            ->withInput()
            ->with('errors', [
                'codigoCurso' => 'Ya existe un curso con el mismo código.'
            ]);
    }

    // 🔥 Validar que el nombre del curso sea único
    $nombreExistente = $this->curso
        ->where('nombreCurso', $nombreCurso)
        ->first();

    if ($nombreExistente) {
        return redirect()->back()
            ->withInput()
            ->with('errors', [
                'nombreCurso' => 'Ya existe un curso con el mismo nombre.'
            ]);
    }

    // 🔹 Preparar los datos para guardar
    $data = [
        'id_grado'     => $id_grado,
        'id_secciones' => $id_secciones,
        'nombreCurso'  => $nombreCurso,
        'codigoCurso'  => $codigoCurso,
        'activo'       => 1
    ];

    // 🔹 Guardar el curso
    if (!$this->curso->save($data)) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Error al guardar el curso.'); // Si es un fallo inesperado, mensaje general
    }

    // 🔹 Redirigir con mensaje de éxito
    return redirect()->to(base_url() . '/grados')
        ->with('success', 'Curso guardado correctamente.');
}



    public function insertar()
    {

        // Reglas de validación
        $rules = [
            'nombre' => 'required'
        ];

        // Mensajes de error personalizados (opcional)
        $messages = [
            'nombre' => [
                'required' => 'El nombre es obligatorio.'
            ]
        ];

        // Validar los datos del formulario
        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }


        // Guardar los datos en la base de datos
        $this->grado->save(['nombre' => $this->request->getPost('nombre')]);

        return redirect()->to(base_url() . '/grados')->with('success', 'Datos guardados correctamente.');
    }



    public function editar($id) {}
    public function actualizar($id) {}
    public function eliminar($id) {}
    public function eliminados($activo = 0) {}
    public function restaurar($id) {}
    public function visualizar($id) {}


   

}





   

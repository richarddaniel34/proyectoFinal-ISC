<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PersonalModel;
use App\Models\CondicionModel;
use App\Models\NombramientoModel;
use App\Models\GradoAcademicoModel;
use App\Models\UsuariosModel;
use App\Models\EscuelaModel;


class Personal extends BaseController
{
    protected $personal,
        $condicion,
        $nombramiento,
        $gradoAcademico,
        $usuarios,
        $escuela;


    public function __construct()
    {
        $this->personal = new PersonalModel();
        $this->condicion = new CondicionModel();
        $this->nombramiento = new NombramientoModel();
        $this->gradoAcademico = new GradoAcademicoModel();
        $this->usuarios = new UsuariosModel();
        $this->escuela = new EscuelaModel();
    }

    public function index($activo = 1)
    {

        // SELECT * FROM schoolyear WHEN activo = $activo
        $personal = $this->personal->where('activo', $activo)->findAll();

        $data = ['titulo' => 'Personal', 'datos' => $personal];

        echo view('header');
        echo view('personal/personal', $data);
        echo view('footer');
    }





    public function nuevo()
    {
        $escuelas = $this->escuela->findAll();

        $data = [
            'titulo' => 'Registro de Personal',
            'personal' => $this->personal,
            'condiciones' => $this->condicion->findAll(),
            'nombramientos' => $this->nombramiento->findAll(),
            'grados_academicos' => $this->gradoAcademico->findAll(),
            'tipos_usuarios' => $this->usuarios->getTiposUsuariosParaPersonal(),
            'sexoOpciones' => ['Masculino', 'Femenino'],
            'escuelas' => $escuelas

        ];

        echo view('header');
        echo view('personal/nuevo', $data);
        echo view('footer');
    }



    public function insertar()
    {
        $cedula = str_replace('-', '', trim($this->request->getPost('cedula')));

    // 🔥 Verificar si la cédula ya existe
    if ($this->personal->cedulaExiste($cedula)) {
        return redirect()->back()->withInput()->with('errors', ['cedula' => 'La cédula ya está registrada.']);
    }

    // 🔥 VALIDACIÓN (Sin imagen)
    if (!$this->validate($this->personal->validationRules, $this->personal->validationMessages)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // 🔥 PROCESAMIENTO DE IMAGEN (Nombre personalizado)
    $imagen = $this->request->getFile('foto');
    $imagenNombre = 'assets/img/users/personal/user_default.png'; // Valor por defecto

    if ($imagen && $imagen->isValid() && !$imagen->hasMoved()) {
        $nombre = $this->request->getPost('nombre');
        $apellido = $this->request->getPost('apellido');
        $inicial = strtoupper(substr($nombre, 0, 1));

        $nombreImagen = "{$inicial}-{$apellido}_{$cedula}." . $imagen->getExtension();
        $imagen->move('assets/img/users/personal', $nombreImagen);
        $imagenNombre = 'assets/img/users/personal/' . $nombreImagen;
    }

    // 🔥 INSERTAR PERSONAL
    $this->personal->save([
        'nombre' => $this->request->getPost('nombre'),
        'apellido' => $this->request->getPost('apellido'),
        'sexo' => $this->request->getPost('sexo'),
        'cedula' => $cedula,
        'telefono' => $this->request->getPost('telefono'),
        'email' => $this->request->getPost('email'),
        'direccion' => $this->request->getPost('direccion'),
        'fecha_nac' => $this->request->getPost('fecha_nac'),
        'condicion' => $this->request->getPost('condicion'),
        'nombramiento' => $this->request->getPost('nombramiento'),
        'funcion' => $this->request->getPost('funcion'),
        'grado_academico' => $this->request->getPost('grado_academico'),
        'foto' => $imagenNombre
    ]);

        // 🔥 OBTENER EL ID DEL PERSONAL RECIÉN CREADO
        $personalId = $this->personal->insertID();

        // 🔥 OBTENER ID DE LA ESCUELA
        $idEscuela = $this->request->getPost('id_escuela') ?: null;

        // 🔥 VERIFICAR SI SE DEBE CREAR USUARIO
        $funcion = strtolower(trim($this->request->getPost('funcion')));
        $excluirFunciones = ['conserje', 'jardinero', 'portero'];

        if (!in_array($funcion, $excluirFunciones, true)) {
            $usuario = trim($this->request->getPost('usuario'));
            $clavePlano = trim($this->request->getPost('clave'));
            $tipoUsuario = $this->request->getPost('tipo_usuario') ?: null;

            if (!empty($usuario) && !empty($clavePlano) && !empty($tipoUsuario)) {
                $contraseña = password_hash($clavePlano, PASSWORD_DEFAULT);

                $this->usuarios->save([
                    'usuario' => $usuario,
                    'clave' => $contraseña,
                    'id_tipo_usuario' => $tipoUsuario,
                    'cambio_clave' => true,
                    'personal_id' => $personalId,
                    'estudiante_id' => null,
                    'id_escuela' => $idEscuela
                ]);
            }
        }

        return redirect()->to(base_url() . '/personal')->with('success', 'Datos guardados correctamente.');
    }














    public function editar($id)
    {

        log_message('debug', 'Editando escuela con ID: ' . $id);

        // Busca la escuela por su ID
        $personal = $this->personal->where('id', $id)->first();

        if (!$personal) {
            return $this->response->setStatusCode(404)->setBody('Personal no encontrado.');
        }

        // Conexión a la base de datos para obtener las modalidades
        $db = \Config\Database::connect();

        // Consultas para obtener los datos de las tablas
        $condicion = $db->query("SELECT id, nombre FROM condicion")->getResultArray();
        $nombramiento = $db->query("SELECT id, nombre FROM nombramiento")->getResultArray();
        $tipo_trabajo = $db->query("SELECT id, nombre FROM tipo_trabajo")->getResultArray();
        $grado_academico = $db->query("SELECT id, grado_academico FROM grado_academico")->getResultArray();

        // Pasar las modalidades a la vista junto con los datos de la escuela
        // Pasar los datos a la vista junto con el título
        $data = [
            'titulo' => 'Registro de Personal',
            'personal' => $personal,
            'condiciones' => $condicion,
            'nombramientos' => $nombramiento,
            'tipo_trabajos' => $tipo_trabajo,
            'grados_academicos' => $grado_academico
        ];


        // Devolver solo la vista parcial del formulario de edición para el modal
        return view('personal/editar', $data);
    }


    public function actualizar($id)
    {
        // Reglas de validación
        $rules = [
            'nombre' => 'required',
            'apellido' => 'required',
            'cedula' => 'required',
            'telefono' => 'required',
            'email' => 'required|valid_email',
            'direccion' => 'required',
            'fecha_nac' => 'required',
            'condicion' => 'required',
            'nombramiento' => 'required',
            'funcion' => 'required',
            'grado_academico' => 'required',
            'foto' => 'permit_empty|uploaded[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]|max_size[foto,2048]'
        ];

        // Mensajes de error personalizados
        $messages = [
            'nombre' => [
                'required' => 'El nombre es obligatorio.'
            ],
            'apellido' => [
                'required' => 'El apellido es obligatorio.'
            ],
            'email' => [
                'required' => 'El email es obligatorio.',
                'valid_email' => 'Debe proporcionar un email válido.'
            ],
            'logo' => [
                'uploaded' => 'Debe subir un archivo de imagen.',
                'mime_in' => 'El logo debe ser una imagen en formato JPG, JPEG o PNG.',
                'max_size' => 'El logo no debe superar los 2MB.'
            ]
        ];

        // Validar los datos del formulario
        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Obtener los datos del formulario
        $nombre = $this->request->getPost('nombre');
        $apellido = $this->request->getPost('apellido');
        $cedula = $this->request->getPost('cedula');

        // Buscar el personal por ID
        $personal = $this->personal->find($id);

        if (!$personal) {
            return redirect()->to(base_url() . '/personal')->with('error', 'Personal no encontrado.');
        }

        // Obtener la imagen del formulario
        $imagen = $this->request->getFile('foto');
        $imagenNombre = $personal['foto']; // Usar la imagen actual por defecto

        // Si se subió una nueva imagen válida, moverla y obtener su nombre
        if ($imagen && $imagen->isValid() && !$imagen->hasMoved()) {
            // Extraer la primera inicial del nombre
            $inicial = strtoupper(substr($nombre, 0, 1));

            // Quitar los guiones de la cédula
            $cedulaLimpia = str_replace('-', '', $cedula);

            // Generar nombre optimizado
            $nombreImagen = "{$inicial}-{$apellido}_{$cedulaLimpia}." . $imagen->getExtension();

            // Mover la imagen a la carpeta destino
            $imagen->move('assets/img/users/personal', $nombreImagen);

            $imagenNombre = 'assets/img/users/personal/' . $nombreImagen; // Guardar nombre para la BD
        }

        // Actualizar los datos en la base de datos
        $this->personal->update($id, [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'cedula' => $cedula,
            'telefono' => $this->request->getPost('telefono'),
            'email' => $this->request->getPost('email'),
            'direccion' => $this->request->getPost('direccion'),
            'fecha_nac' => $this->request->getPost('fecha_nac'),
            'condicion' => $this->request->getPost('condicion'),
            'nombramiento' => $this->request->getPost('nombramiento'),
            'funcion' => $this->request->getPost('funcion'),
            'grado_academico' => $this->request->getPost('grado_academico'),
            'foto' => $imagenNombre
        ]);

        return redirect()->to(base_url() . '/personal')->with('success', 'Datos actualizados correctamente.');
    }









    public function eliminar($id)
    {
        $this->personal->update($id, ['activo' => 0]);
        return redirect()->to(base_url() . 'personal');
    }


    public function eliminados($activo = 0)
    {
        // SELECT * FROM schoolyear WHEN activo = $activo
        $personal = $this->personal->where('activo', $activo)->findAll();

        $data = ['titulo' => 'Personal Inactivo', 'datos' => $personal];

        echo view('header');
        echo view('personal/eliminados', $data);
        echo view('footer');
    }

    public function restaurar($id)
    {
        $this->personal->update($id, ['activo' => 1]);
        return redirect()->to(base_url() . 'personal/eliminados');
    }


    public function visualizar($id)
    {


        $personal = $this->personal->where('id', $id)->first();

        $data = ['titulo' => 'visualizar datos del centro educativo', 'datos' => $personal];

        return view('personal/visualizar', $data);
    }
}




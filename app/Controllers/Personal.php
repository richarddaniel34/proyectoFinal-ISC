<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PersonalModel;
use App\Models\CondicionModel;
use App\Models\NombramientoModel;
use App\Models\GradoAcademicoModel;
use App\Models\UsuariosModel;
use App\Models\EscuelaModel;
use App\Models\NacionalidadesModel;


class Personal extends BaseController
{
    protected $personal,
        $condicion,
        $nombramiento,
        $gradoAcademico,
        $usuarios,
        $escuela,
        $nacionalidad;


    public function __construct()
    {
        $this->personal = new PersonalModel();
        $this->condicion = new CondicionModel();
        $this->nombramiento = new NombramientoModel();
        $this->gradoAcademico = new GradoAcademicoModel();
        $this->usuarios = new UsuariosModel();
        $this->escuela = new EscuelaModel();
        $this->nacionalidad = new NacionalidadesModel();
    }

    public function index($activo = 1)
    {
        $session = session();
        $codigoGestion = $session->get('codigo_gestion'); // ← Obtener el código_gestion desde la sesión

        if (!$codigoGestion) {
            return redirect()->to('/login')->with('error', 'No se encontró un código de gestión en la sesión.');
        }

        // Buscar la escuela con ese código_gestion
        $escuela = $this->escuela->where('codigo_gestion', $codigoGestion)->first();

        if (!$escuela) {
            return redirect()->to('/login')->with('error', 'No se encontró una escuela asociada a ese código de gestión.');
        }

        $idEscuela = $escuela['id']; // ID real de la escuela

        // Filtrar por escuela e indicador de activo
        $personal = $this->personal
            ->where('activo', $activo)
            ->where('id_escuela', $idEscuela)
            ->findAll();

        $data = ['titulo1' => 'Registro/', 'titulo2' => 'Datos del Personal', 'datos' => $personal];

        echo view('header');
        echo view('personal/personal', $data);
        echo view('footer');
    }







    public function nuevo()
    {
        $escuelas = $this->escuela->findAll();

        // Traer nacionalidades
        $nacionalidades = $this->nacionalidad->findAll(); // o getAll() si tu modelo tiene ese método

        $data = [
            'titulo1' => 'Registro/',
            'titulo2' => 'Personal',
            'personal' => $this->personal,
            'condiciones' => $this->condicion->findAll(),
            'nombramientos' => $this->nombramiento->findAll(),
            'grados_academicos' => $this->gradoAcademico->findAll(),
            'tipos_usuarios' => $this->usuarios->getTiposUsuariosParaPersonal(),
            'sexoOpciones' => ['M', 'F'],
            'escuelas' => $escuelas,
            'nacionalidades' => $nacionalidades // <-- aquí pasamos las nacionalidades
        ];

        echo view('header');
        echo view('personal/nuevo', $data);
        echo view('footer');
    }




    public function insertar()
    {
        $cedula = str_replace('-', '', trim($this->request->getPost('cedula')));

        //  Verificar si la cédula ya existe
        if ($this->personal->cedulaExiste($cedula)) {
            return redirect()->back()->withInput()->with('errors', ['cedula' => 'La cédula ya está registrada.']);
        }

        //  VALIDACIÓN (Sin imagen)
        if (!$this->validate($this->personal->validationRules, $this->personal->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        //  PROCESAMIENTO DE IMAGEN (Nombre personalizado)
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

        log_message('debug', 'Insertando personal con id_escuela=' . session()->get('id_escuela'));


        // INSERTAR PERSONAL
        $this->personal->save([
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'sexo' => $this->request->getPost('sexo'),
            'cedula' => $cedula,
            'celular' => $this->request->getPost('celular'),
            'telefono' => $this->request->getPost('telefono'),
            'email' => $this->request->getPost('email'),
            'direccion' => $this->request->getPost('direccion'),
            'fecha_nac' => $this->request->getPost('fecha_nac'),
            'condicion' => $this->request->getPost('condicion'),
            'nombramiento' => $this->request->getPost('nombramiento'),
            'funcion' => $this->request->getPost('funcion'),
            'grado_academico' => $this->request->getPost('grado_academico'),
            'id_nacionalidad' => $this->request->getPost('id_nacionalidad'), // <-- agregado
            'foto' => $imagenNombre,
            'id_escuela' => session()->get('id_escuela')
        ]);


        //  OBTENER EL ID DEL PERSONAL RECIÉN CREADO
        $personalId = $this->personal->insertID();

        //  OBTENER ID DE LA ESCUELA
        $idEscuela = $this->request->getPost('id_escuela') ?: null;

        //  VERIFICAR SI SE DEBE CREAR USUARIO
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
                    'id_escuela' => session()->get('id_escuela'),

                ]);
            }
        }

        return redirect()->to(base_url() . '/personal')->with('success', 'Datos guardados correctamente.');
    }















    public function editar($id)
    {
        $personal = $this->personal->find($id);
        $nacionalidades = $this->nacionalidad->findAll(); // o getAll() si tu modelo tiene ese método

        if (!$personal) {
            return $this->response->setStatusCode(404)->setBody('Personal no encontrado.');
        }

        $data = [
            'titulo' => 'Editar Personal',
            'personal' => $personal,
            'condiciones' => $this->condicion->findAll(),
            'nombramientos' => $this->nombramiento->findAll(),
            'grados_academicos' => $this->gradoAcademico->findAll(),
            'tipos_usuarios' => $this->usuarios->getTiposUsuariosParaPersonal(),
            'sexoOpciones' => ['M', 'F'],
            'datos'     => $personal,
            'nacionalidades' => $nacionalidades // <-- aquí pasamos las nacionalidades
        ];

        echo view('header');
        echo view('personal/editar', $data);
        echo view('footer');
    }



    public function actualizar()
    {
        helper('filesystem');

        $id = $this->request->getPost('id');
        if (!$id) {
            return redirect()->to(base_url() . '/personal')->with('error', 'ID de personal no proporcionado.');
        }

        // Validación de campos
        $rules = [
            'nombre' => 'required',
            'apellido' => 'required',
            'cedula' => 'required',
            'celular' => 'required',
            'email' => 'valid_email',
            'direccion' => 'required',
            'fecha_nac' => 'required',
            'condicion' => 'required',
            'nombramiento' => 'required',
            'funcion' => 'required',
            'grado_academico' => 'required',
            'id_nacionalidad' => 'required',
            'foto' => 'permit_empty|uploaded[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]|max_size[foto,2048]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Verificar si la cédula ya está en uso por otro registro
        $cedula = $this->request->getPost('cedula');
        $existeCedula = $this->personal
            ->where('cedula', $cedula)
            ->where('id !=', $id)
            ->first();

        if ($existeCedula) {
            return redirect()->back()->withInput()->with('error', 'Ya existe otro registro con esta cédula.');
        }

        // Buscar el registro actual
        $personal = $this->personal->find($id);
        if (!$personal) {
            return redirect()->to(base_url() . '/personal')->with('error', 'Personal no encontrado.');
        }

        // Procesar imagen si se sube una nueva
        $imagen = $this->request->getFile('foto');
        $imagenNombre = $personal['foto'];
        if ($imagen && $imagen->isValid() && !$imagen->hasMoved()) {
            $inicial = strtoupper(substr($this->request->getPost('nombre'), 0, 1));
            $cedulaLimpia = str_replace('-', '', $cedula);
            $nombreImagen = "{$inicial}-{$this->request->getPost('apellido')}_{$cedulaLimpia}." . $imagen->getExtension();
            $imagen->move('assets/img/users/personal', $nombreImagen);
            $imagenNombre = 'assets/img/users/personal/' . $nombreImagen;
        }

        // Datos a actualizar
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'sexo' => $this->request->getPost('sexo'),
            'cedula' => $cedula,
            'celular' => $this->request->getPost('celular'),
            'telefono' => $this->request->getPost('telefono'),
            'email' => $this->request->getPost('email'),
            'direccion' => $this->request->getPost('direccion'),
            'fecha_nac' => $this->request->getPost('fecha_nac'),
            'condicion' => $this->request->getPost('condicion'),
            'nombramiento' => $this->request->getPost('nombramiento'),
            'funcion' => $this->request->getPost('funcion'),
            'grado_academico' => $this->request->getPost('grado_academico'),
            'id_nacionalidad' => $this->request->getPost('id_nacionalidad'),
            'foto' => $imagenNombre,
            'id_escuela' => session()->get('id_escuela'),
            'activo' => 1
        ];

        // Ejecutar actualización
        $result = $this->personal->update($id, $data);
        $filasAfectadas = $this->personal->db->affectedRows();

        // Mensaje según resultado
        if ($result && $filasAfectadas > 0) {
            return redirect()->to(base_url() . '/personal')->with('success', 'Datos actualizados correctamente.');
        } elseif ($result && $filasAfectadas === 0) {
            return redirect()->to(base_url() . '/personal')->with('info', 'No hubo cambios para actualizar.');
        } else {
            return redirect()->to(base_url() . '/personal')->with('error', 'No se pudo actualizar el personal.');
        }
    }













    public function eliminar($id)
    {
        // Conectar a la base de datos
        $db = \Config\Database::connect();

        // Tablas donde puede tener asignaciones
        $tablasAsignaciones = [
            'asistencia' => 'id_personal',
            'distribucion_asignaturas' => 'id_personal',
            'docentes_guia' => 'id_personal',
            'periodos_configuracion_usuario' => 'id_personal',
        ];

        // Revisar si hay asignaciones activas
        foreach ($tablasAsignaciones as $tabla => $campo) {
            $existe = $db->table($tabla)->where($campo, $id)->countAllResults();
            if ($existe > 0) {
                return redirect()->to(base_url('personal'))
                    ->with('error', 'El docente tiene asignaciones activas en ' . $tabla . '. Libere primero sus cursos o registros.');
            }
        }

        // Desactivar registro de personal
        $this->personal->update($id, ['activo' => 0]);

        // Desactivar usuario asociado si existe
        $usuario = $db->table('usuarios')->where('personal_id', $id)->get()->getRowArray();
        if ($usuario) {
            $db->table('usuarios')->where('id', $usuario['id'])->update(['activo' => 0]);
        }

        return redirect()->to(base_url('personal'))->with('success', 'Docente desactivado correctamente.');
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
        // Reactivar personal
        $this->personal->update($id, ['activo' => 1]);

        // Reactivar usuario asociado si existe
        $db = \Config\Database::connect();
        $usuario = $db->table('usuarios')->where('personal_id', $id)->get()->getRowArray();
        if ($usuario) {
            $db->table('usuarios')->where('id', $usuario['id'])->update(['activo' => 1]);
        }

        return redirect()->to(base_url('personal/eliminados'))
            ->with('success', 'Personal restaurado correctamente.');
    }



    public function visualizar($id)
    {


        $personal = $this->personal->where('id', $id)->first();

        $data = ['titulo' => 'visualizar datos del personal', 'datos' => $personal];

        return view('personal/visualizar', $data);
    }
}

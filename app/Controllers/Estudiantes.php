<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EstudiantesModel;
use App\Models\ResponsablesModel;
use App\Models\UsuariosModel;
use App\Models\EscuelaModel;
use App\Models\EstudiantesResponsablesModel;


class Estudiantes extends BaseController
{
    protected $estudiantes;
    protected $responsables;
    protected $usuarios;
    protected $escuela;
    protected $estudiantesResponsables;


    public function __construct()
    {
        $this->estudiantes = new EstudiantesModel();
        $this->responsables = new ResponsablesModel();
        $this->usuarios = new UsuariosModel();
        $this->escuela = new EscuelaModel();
        $this->estudiantesResponsables = new EstudiantesResponsablesModel();
    }

    public function index($activo = 1)
    {
        // SELECT * FROM schoolyear WHEN activo = $activo
        $estudiantes = $this->estudiantes->where('activo', $activo)->findAll();

        $data = ['titulo' => 'Estudiantes', 'datos' => $estudiantes];

        echo view('header');
        echo view('estudiantes/estudiantes', $data);
        echo view('footer');
    }

    public function nuevo()
    {
        $escuelas = $this->escuela->findAll();

        // Llamada a la función para obtener los ENUM del campo parentesco
        $parentescos = $this->estudiantesResponsables->getEnumValues('estudiantes_responsables', 'parentesco');

        $data = [
            'titulo' => 'Registro de Estudiantes',
            'matricula' => $this->generarMatricula(), //Generar matrícula antes de cargar la vista
            'tipos_usuarios' => $this->usuarios->getTiposUsuariosEstudiantes(),
            'escuelas' => $escuelas,
            'parentescos' => $parentescos
        ];

        echo view('header');
        echo view('estudiantes/nuevo', $data);
        echo view('footer');
    }



    public function generarMatricula()
    {
        $año = date('Y'); // Año actual
        $prefijoEscuela = "EDS"; // Prefijo del centro educativo

        //  Obtener el último estudiante registrado sin importar el año
        $ultimoEstudiante = $this->estudiantes
            ->orderBy("id", "DESC") //  Ordenamos por ID para garantizar el último registro
            ->first();

        //  Si hay un estudiante registrado, extraemos los últimos 4 dígitos de la matrícula
        if ($ultimoEstudiante) {
            $ultimoNumero = intval(substr($ultimoEstudiante['matricula'], -4));
            $nuevoNumero = str_pad($ultimoNumero + 1, 4, '0', STR_PAD_LEFT); // 🔥 Sumar y rellenar con ceros
        } else {
            $nuevoNumero = '0001'; //  Si no hay estudiantes, iniciar con "0001"
        }

        return "{$año}-{$prefijoEscuela}-{$nuevoNumero}";
    }







    public function insertar()
    {
        $session = session();

        // 🔥 VALIDACIÓN DIRECTAMENTE EN EL MODELO
        if (!$this->validate($this->estudiantes->validationRules, $this->estudiantes->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 🔥 OBTENER DATOS DEL FORMULARIO
        $nombre = trim($this->request->getPost('nombre'));
        $apellido = trim($this->request->getPost('apellido'));
        $sexo = trim($this->request->getPost('sexo'));
        $numeroIdentidad = trim($this->request->getPost('numero_identidad'));
        $sigerdId = trim($this->request->getPost('sigerd_id'));
        $matricula = trim($this->request->getPost('matricula'));
        $responsables = $this->request->getPost('responsables');

        // 🔥 VERIFICAR DUPLICADOS (EXTRA SEGURIDAD)
        if ($this->estudiantes->estudianteExiste($numeroIdentidad, $sigerdId, $matricula)) {
            return redirect()->back()->withInput()->with('errors', ['matricula' => 'El estudiante ya está registrado.']);
        }

        // 🔥 PROCESAMIENTO DE IMAGEN
        $imagen = $this->request->getFile('imagen');
        $imagenNombre = 'assets/img/estudiantes/user_default.png';

        if ($imagen && $imagen->isValid() && !$imagen->hasMoved()) {
            $nombreImagen = "{$nombre}-{$sigerdId}." . $imagen->getExtension();
            $imagen->move('assets/img/estudiantes', $nombreImagen);
            $imagenNombre = 'assets/img/estudiantes/' . $nombreImagen;
        }

        // 🔥 GENERAR MATRÍCULA SI NO SE PROPORCIONA UNA
        if (!$matricula) {
            $matricula = $this->generarMatricula();
        }

        // 🔥 GUARDAR ESTUDIANTE EN LA BASE DE DATOS
        $this->estudiantes->save([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'sexo' => $sexo,
            'lugar_nacimiento' => $this->request->getPost('lugar_nacimiento'),
            'provincia' => $this->request->getPost('provincia'),
            'fecha_nac' => $this->request->getPost('fecha_nac'),
            'numero_identidad' => $numeroIdentidad,
            'sigerd_id' => $sigerdId,
            'direccion' => $this->request->getPost('direccion'),
            'escuela_procedencia' => $this->request->getPost('escuela_procedencia'),
            'responsables' => $responsables,
            'estado_padres' => $this->request->getPost('estado_padres'),
            'casa_estudiante' => $this->request->getPost('casa_estudiante'),
            'alergias' => $this->request->getPost('alergias'),
            'condicion_medica' => $this->request->getPost('condicion_medica'),
            'medicamentos' => $this->request->getPost('medicamentos'),
            'tipo_sangre' => $this->request->getPost('tipo_sangre'),
            'imagen' => $imagenNombre,
            'matricula' => $matricula
        ]);

        // OBTENER ID DEL ESTUDIANTE RECIÉN CREADO
        $estudianteId = $this->estudiantes->insertID();

        //  OBTENER ID DE LA ESCUELA
        $idEscuela = $this->request->getPost('id_escuela') ?: null;

        // INSERTAR RELACIONES EN LA TABLA INTERMEDIA estudiantes-representantes

        // ID de responsables
        $padreId = $this->request->getPost('padre');
        $madreId = $this->request->getPost('madre');
        $tutorId = $this->request->getPost('tutor');

        // Observaciones por responsable
        $obsPadre = $this->request->getPost('Observaciones_padre');
        $obsMadre = $this->request->getPost('Observaciones_madre');
        $obsTutor = $this->request->getPost('Observaciones_tutor');

        // MODELO PARA TABLA INTERMEDIA (Asumo que tienes un modelo llamado EstudiantesResponsablesModel)
        $this->estudiantesResponsables->insert([
            'estudiante_id' => $estudianteId,
            'responsable_id' => $padreId,
            'parentesco' => 'Padre',
            'observaciones' => $obsPadre
        ]);


        $this->estudiantesResponsables->insert([
            'estudiante_id' => $estudianteId,
            'responsable_id' => $madreId,
            'parentesco' => 'Madre',
            'observaciones' => $obsMadre
        ]);

        if (!empty($tutorId)) {
            $this->estudiantesResponsables->insert([
                'estudiante_id' => $estudianteId,
                'responsable_id' => $tutorId,
                'parentesco' => 'Tutor',
                'observaciones' => $obsTutor
            ]);
        }

        // ⚡⚡ CREAR USUARIO PARA EL ESTUDIANTE USANDO SU MATRÍCULA
        $usuario = $matricula; //  Usuario = matrícula
        $contraseña = password_hash($matricula, PASSWORD_DEFAULT); // Clave = matrícula encriptada

        // Verificar si el usuario ya existe
        $usuarioExiste = $this->usuarios->where('usuario', $usuario)->first();

        if (!$usuarioExiste) {
            $this->usuarios->save([
                'usuario' => $usuario,
                'clave' => $contraseña,
                'id_tipo_usuario' => $this->request->getPost('tipo_usuario'), // 🔥 ID de tipo "Estudiante"
                'cambio_clave' => true,
                'personal_id' => null,
                'estudiantes_id' => $estudianteId,
                'id_escuela' => $idEscuela
            ]);
        }

        return redirect()->to(base_url('/estudiantes'))->with('success', 'Estudiante registrado correctamente.');
    }












    public function editar($id) {}







    public function actualizar($id) {}




    public function eliminar($id)
    {
        $this->estudiantes->update($id, ['activo' => 0]);
        return redirect()->to(base_url() . '/escuela');
    }



    public function eliminados($activo = 0) {}

    public function restaurar($id)
    {
        $this->estudiantes->update($id, ['activo' => 1]);
        return redirect()->to(base_url() . 'escuela/eliminados');
    }


    public function visualizar($id) {}
}

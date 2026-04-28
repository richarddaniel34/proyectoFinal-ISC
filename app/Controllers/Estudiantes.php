<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EstudiantesModel;
use App\Models\ResponsablesModel;
use App\Models\UsuariosModel;
use App\Models\EscuelaModel;
use App\Models\EstudiantesResponsablesModel;
use App\Models\GradosModel;
use App\Models\NacionalidadesModel;

class Estudiantes extends BaseController
{
    protected $estudiantes;
    protected $responsables;
    protected $usuarios;
    protected $escuela;
    protected $grado;
    protected $estudiantesResponsables;
    protected $nacionalidad;


    public function __construct()
    {
        $this->estudiantes = new EstudiantesModel();
        $this->responsables = new ResponsablesModel();
        $this->usuarios = new UsuariosModel();
        $this->escuela = new EscuelaModel();
        $this->estudiantesResponsables = new EstudiantesResponsablesModel();
        $this->grado = new GradosModel();
        $this->nacionalidad = new NacionalidadesModel();
    }

    
    /**rederizar home del modulo estudiantes */
    public function index($activo = 1)
    {
        $estudiantes = $this->estudiantes->where('activo', $activo)->findAll();

        foreach ($estudiantes as &$estudiante) {
            if (!empty($estudiante['fecha_nac'])) {
                $fechaNacimiento = new \DateTime($estudiante['fecha_nac']);
                $hoy = new \DateTime();
                $edad = $hoy->diff($fechaNacimiento);

                $estudiante['edad'] = $edad->y; // años
            } else {
                $estudiante['edad'] = null;
            }
        }

        $data = [
            'titulo' => 'Estudiantes',
            'datos' => $estudiantes
        ];

        echo view('header');
        echo view('estudiantes/estudiantes', $data);
        echo view('footer');
    }

    /**rederizar vista de registro de estudiantes */
    public function nuevo()
    {
        // Obtener el id_escuela del usuario desde la sesión
        $idEscuela = session()->get('id_escuela');

        // Obtener el registro de la escuela
        $escuela = $this->escuela->find($idEscuela);
        $idNivelEscuela = $escuela['id_nivel']; // Nivel de la escuela (Primaria o Secundaria)
        $nacionalidades = $this->nacionalidad->findAll(); // o getAll() si tu modelo tiene ese método

        // Traer solo los grados activos que correspondan al nivel de la escuela
        $grados = $this->grado
            ->join('grados_niveles gn', 'gn.id_grado = grados.id')
            ->where('grados.activo', 1)
            ->where('gn.id_nivel', $idNivelEscuela)
            ->findAll();

        // Llamada a la función para obtener los ENUM del campo parentesco
        $parentescos = $this->estudiantesResponsables->getEnumValues('estudiantes_responsables', 'parentesco');

        $data = [
            'titulo' => 'Registro de Estudiantes',
            'matricula' => $this->generarMatricula(),
            'tipos_usuarios' => $this->usuarios->getTiposUsuariosEstudiantes(),
            'parentescos' => $parentescos,
            'grados' => $grados,
            'id_escuela' => $idEscuela, // Para usarlo como input hidden en la vista
            'nacionalidades' => $nacionalidades
        ];

        echo view('header');
        echo view('estudiantes/nuevo', $data);
        echo view('footer');
    }




/**Generar matricula de forma Automatica */
    public function generarMatricula()
    {
        $año = date('Y'); // Año actual
        //$prefijoEscuela = "EDS"; // Prefijo del centro educativo

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

        return "{$año}-{$nuevoNumero}";
        /**return "{$año}-{$prefijoEscuela}-{$nuevoNumero}"; */
    }





/**Insertar los nuevos registros */
    public function insertar()
    {
        $session = session();

        // VALIDACIÓN DIRECTAMENTE EN EL MODELO
        if (!$this->validate($this->estudiantes->validationRules, $this->estudiantes->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // OBTENER DATOS DEL FORMULARIO
        $nombre = trim($this->request->getPost('nombre'));
        $apellido = trim($this->request->getPost('apellido'));
        $sexo = trim($this->request->getPost('sexo'));
        $numeroIdentidad = trim($this->request->getPost('numero_identidad'));
        $sigerdId = trim($this->request->getPost('sigerd_id'));
        $matricula = trim($this->request->getPost('matricula'));
        $responsables = $this->request->getPost('responsables');

        // VERIFICAR DUPLICADOS
        if ($this->estudiantes->estudianteExiste($numeroIdentidad, $sigerdId, $matricula)) {
            return redirect()->back()->withInput()->with('errors', ['matricula' => 'El estudiante ya está registrado.']);
        }

        // PROCESAR IMAGEN
        $imagen = $this->request->getFile('imagen');
        $imagenNombre = 'assets/img/estudiantes/user_default.png';
        if ($imagen && $imagen->isValid() && !$imagen->hasMoved()) {
            $nombreImagen = "{$nombre}-{$sigerdId}." . $imagen->getExtension();
            $imagen->move('assets/img/estudiantes', $nombreImagen);
            $imagenNombre = 'assets/img/estudiantes/' . $nombreImagen;
        }

        // GENERAR MATRÍCULA SI NO SE PROPORCIONA
        if (!$matricula) {
            $matricula = $this->generarMatricula();
        }

        // OBTENER CAMPOS RELACIONADOS
        $idEscuela = $this->request->getPost('id_escuela') ?: null;

        // Aquí recibimos el id del select (que viene de grados_niveles)
        $idGradoNivel = $this->request->getPost('id_grado') ?: null;

        // Opcional: si quieres el id del grado real de la tabla 'grados':
        // $idGrado = $this->gradosNiveles->where('id', $idGradoNivel)->first()['grado_id'] ?? null;

        $idNacionalidad = $this->request->getPost('id_nacionalidad') ?: null;
        log_message('debug', 'Valor recibido para id_nacionalidad: ' . $idNacionalidad);
        log_message('debug', 'Valor recibido para id_grado_nivel: ' . $idGradoNivel);

        // GUARDAR ESTUDIANTE EN LA BASE DE DATOS
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
            'matricula' => $matricula,
            'id_escuela' => $idEscuela,
            'id_grado' => $idGradoNivel, // Guardamos directamente el id de grados_niveles
            'id_nacionalidad' => $idNacionalidad,
            'activo' => 1
        ]);

        // OBTENER ID DEL ESTUDIANTE RECIÉN CREADO
        $estudianteId = $this->estudiantes->insertID();

        // INSERTAR RELACIONES EN TABLA INTERMEDIA
        $padreId = $this->request->getPost('padre');
        $madreId = $this->request->getPost('madre');
        $tutorId = $this->request->getPost('tutor');

        $obsPadre = $this->request->getPost('Observaciones_padre');
        $obsMadre = $this->request->getPost('Observaciones_madre');
        $obsTutor = $this->request->getPost('Observaciones_tutor');

        if (!empty($padreId)) {
            $this->estudiantesResponsables->insert([
                'estudiante_id' => $estudianteId,
                'responsable_id' => $padreId,
                'parentesco' => 'Padre',
                'observaciones' => $obsPadre
            ]);
        }

        if (!empty($madreId)) {
            $this->estudiantesResponsables->insert([
                'estudiante_id' => $estudianteId,
                'responsable_id' => $madreId,
                'parentesco' => 'Madre',
                'observaciones' => $obsMadre
            ]);
        }

        if (!empty($tutorId)) {
            $this->estudiantesResponsables->insert([
                'estudiante_id' => $estudianteId,
                'responsable_id' => $tutorId,
                'parentesco' => 'Tutor',
                'observaciones' => $obsTutor
            ]);
        }

        // CREAR USUARIO PARA EL ESTUDIANTE
        $usuario = $matricula;
        $contraseña = password_hash($matricula, PASSWORD_DEFAULT);

        $usuarioExiste = $this->usuarios->where('usuario', $usuario)->first();

        if (!$usuarioExiste) {
            $this->usuarios->save([
                'usuario' => $usuario,
                'clave' => $contraseña,
                'id_tipo_usuario' => $this->request->getPost('tipo_usuario'),
                'cambio_clave' => true,
                'estudiante_id' => null,
                'estudiantes_id' => $estudianteId,
                'id_escuela' => $idEscuela
            ]);
        }

        return redirect()->to(base_url('/estudiantes'))->with('success', 'Estudiante registrado correctamente.');
    }




/**rederizar vista de edicion de registro de estudiantes */
    public function editar($id)
    {
        $idEscuela = session()->get('id_escuela');

        $estudiante = $this->estudiantes->find($id);

        if (!$estudiante) {
            return $this->response->setStatusCode(404)->setBody('Estudiante no encontrado.');
        }

        // Escuela y nivel
        $escuela = $this->escuela->find($idEscuela);
        $idNivelEscuela = $escuela['id_nivel'] ?? null;

        // Catálogos
        $nacionalidades = $this->nacionalidad->findAll();

        $grados = $this->grado
            ->join('grados_niveles gn', 'gn.id_grado = grados.id')
            ->where('grados.activo', 1)
            ->where('gn.id_nivel', $idNivelEscuela)
            ->findAll();

        $parentescos = $this->estudiantesResponsables
            ->getEnumValues('estudiantes_responsables', 'parentesco');

        // Responsables vinculados al estudiante
        $responsablesRelacionados = $this->estudiantesResponsables
            ->where('estudiante_id', $id)
            ->findAll();

        $padre = null;
        $madre = null;
        $tutor = null;

        $parentescoPadre = null;
        $parentescoMadre = null;
        $parentescoTutor = null;

        $obsPadre = null;
        $obsMadre = null;
        $obsTutor = null;

        foreach ($responsablesRelacionados as $relacion) {
            if ($relacion['parentesco'] === 'Padre') {
                $padre = $relacion['responsable_id'];
                $parentescoPadre = $relacion['parentesco'];
                $obsPadre = $relacion['observaciones'] ?? null;
            }

            if ($relacion['parentesco'] === 'Madre') {
                $madre = $relacion['responsable_id'];
                $parentescoMadre = $relacion['parentesco'];
                $obsMadre = $relacion['observaciones'] ?? null;
            }

            if ($relacion['parentesco'] === 'Tutor') {
                $tutor = $relacion['responsable_id'];
                $parentescoTutor = $relacion['parentesco'];
                $obsTutor = $relacion['observaciones'] ?? null;
            }
        }

        // Datos completos de responsables para precargar Select2
        $padreData = $padre ? $this->responsables->find($padre) : null;
        $madreData = $madre ? $this->responsables->find($madre) : null;
        $tutorData = $tutor ? $this->responsables->find($tutor) : null;

        $data = [
            'titulo' => 'Editar Estudiante',
            'datos' => $estudiante,
            'estudiante' => $estudiante,
            'matricula' => $estudiante['matricula'],
            'tipos_usuarios' => $this->usuarios->getTiposUsuariosEstudiantes(),
            'parentescos' => $parentescos,
            'grados' => $grados,
            'id_escuela' => $idEscuela,
            'nacionalidades' => $nacionalidades,

            // IDs seleccionados
            'padreSeleccionado' => $padre,
            'madreSeleccionada' => $madre,
            'tutorSeleccionado' => $tutor,

            // Datos completos para mostrar en select2
            'padreData' => $padreData,
            'madreData' => $madreData,
            'tutorData' => $tutorData,

            // Parentescos y observaciones
            'parentescoPadre' => $parentescoPadre,
            'parentescoMadre' => $parentescoMadre,
            'parentescoTutor' => $parentescoTutor,
            'observacionesPadre' => $obsPadre,
            'observacionesMadre' => $obsMadre,
            'observacionesTutor' => $obsTutor,
            'estadosPadres' => [
                'casados y viven juntos' => 'Casados y Viven Juntos',
                'casados y no viven juntos' => 'Casados y no viven juntos',
                'separados' => 'Separados',
                'divorciados' => 'Divorciados',
                'union libre' => 'Unión Libre',
                'familia en tramites viaje' => 'Familia en trámites de viaje',
            ],
            'convivenciaEstudiante' => [
                'con ambos padres' => 'Con ambos Padres',
                'padre' => 'Padre',
                'madre' => 'Madre',
                'tutor' => 'Tutor',
            ],
            'tipoSangreEstudiante' => [
                'A+' => 'A+',
                'A-' => 'A-',
                'B+' => 'B+',
                'B-' => 'B-',
                'O+' => 'O+',
                'O-' => 'O-',
                'AB+' => 'AB+',
                'AB-' => 'AB-'
            ]
        ];

        echo view('header');
        echo view('estudiantes/editar', $data);
        echo view('footer');
    }





/**Insertar los cambios realizados en registro de un estudiante */
    public function actualizar()
    {
        $id = $this->request->getPost('id');

        if (!$id) {
            return redirect()->to(base_url('/estudiantes'))
                ->with('error', 'ID de estudiante no válido.');
        }

        $estudiante = $this->estudiantes->find($id);

        if (!$estudiante) {
            return redirect()->to(base_url('/estudiantes'))
                ->with('error', 'Estudiante no encontrado.');
        }

        // Reglas específicas para edición
        $rules = [
            'nombre' => 'required|min_length[2]',
            'apellido' => 'required|min_length[2]',
            'sexo' => 'required',
            'fecha_nac' => 'required',
            'lugar_nacimiento' => 'required',
            'provincia' => 'required',
            'direccion' => 'required',
            'id_grado' => 'required',
            'id_nacionalidad' => 'required',

            // Ignorar el mismo registro actual
            'numero_identidad' => "permit_empty|is_unique[estudiantes.numero_identidad,id,{$id}]",
            'sigerd_id' => "permit_empty|is_unique[estudiantes.sigerd_id,id,{$id}]"
        ];

        $messages = [
            'nombre' => [
                'required' => 'El nombre es obligatorio.'
            ],
            'apellido' => [
                'required' => 'El apellido es obligatorio.'
            ],
            'sexo' => [
                'required' => 'Debe seleccionar el sexo.'
            ],
            'fecha_nac' => [
                'required' => 'La fecha de nacimiento es obligatoria.'
            ],
            'lugar_nacimiento' => [
                'required' => 'El lugar de nacimiento es obligatorio.'
            ],
            'provincia' => [
                'required' => 'La provincia es obligatoria.'
            ],
            'direccion' => [
                'required' => 'La dirección es obligatoria.'
            ],
            'id_grado' => [
                'required' => 'Debe seleccionar el grado.'
            ],
            'id_nacionalidad' => [
                'required' => 'Debe seleccionar la nacionalidad.'
            ],
            'numero_identidad' => [
                'is_unique' => 'Este número de identidad ya está registrado.'
            ],
            'sigerd_id' => [
                'is_unique' => 'El SIGERD ID ya está en uso.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Datos del formulario
        $nombre = trim((string) $this->request->getPost('nombre'));
        $apellido = trim((string) $this->request->getPost('apellido'));
        $sexo = trim((string) $this->request->getPost('sexo'));
        $numeroIdentidad = trim((string) $this->request->getPost('numero_identidad'));
        $sigerdId = trim((string) $this->request->getPost('sigerd_id'));

        $idEscuela = $this->request->getPost('id_escuela') ?: null;
        $idGradoNivel = $this->request->getPost('id_grado') ?: null;
        $idNacionalidad = $this->request->getPost('id_nacionalidad') ?: null;

        /*
     * FOTO:
     * Asegúrate de que el input del formulario sea name="foto"
     */
        $archivoFoto = $this->request->getFile('foto');

        // Mantener la imagen actual por defecto
        $imagenNombre = $estudiante['imagen'] ?? 'assets/img/estudiantes/user_default.png';

        if ($archivoFoto && $archivoFoto->isValid() && !$archivoFoto->hasMoved()) {
            $extension = $archivoFoto->getExtension();
            $nombreArchivo = 'estudiante_' . $id . '.' . $extension;
            $rutaDestino = FCPATH . 'assets/img/estudiantes/';

            // Eliminar archivo anterior personalizado, sin tocar el default
            foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
                $archivoExistente = $rutaDestino . 'estudiante_' . $id . '.' . $ext;
                if (is_file($archivoExistente)) {
                    @unlink($archivoExistente);
                }
            }

            $archivoFoto->move($rutaDestino, $nombreArchivo, true);
            $imagenNombre = 'assets/img/estudiantes/' . $nombreArchivo;
        }

        $datosActualizar = [
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
            'estado_padres' => $this->request->getPost('estado_padres'),
            'casa_estudiante' => $this->request->getPost('casa_estudiante'),
            'alergias' => $this->request->getPost('alergias'),
            'condicion_medica' => $this->request->getPost('condicion_medica'),
            'medicamentos' => $this->request->getPost('medicamentos'),
            'tipo_sangre' => $this->request->getPost('tipo_sangre'),
            'imagen' => $imagenNombre,
            'id_escuela' => $idEscuela,
            'id_grado' => $idGradoNivel,
            'id_nacionalidad' => $idNacionalidad
        ];

        if (!$this->estudiantes->skipValidation(true)->update($id, $datosActualizar)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', ['general' => 'No se pudo actualizar el estudiante.']);
        }

        if (!$this->estudiantes->skipValidation(true)->update($id, $datosActualizar)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', ['general' => 'No se pudo actualizar el estudiante.']);
        }

        // Actualizar relaciones de responsables
        $padreId = $this->request->getPost('padre');
        $madreId = $this->request->getPost('madre');
        $tutorId = $this->request->getPost('tutor');

        $obsPadre = $this->request->getPost('observaciones_padre');
        $obsMadre = $this->request->getPost('observaciones_madre');
        $obsTutor = $this->request->getPost('observaciones_tutor');

        // Borrar relaciones anteriores y volver a insertar las actuales
        $this->estudiantesResponsables->where('estudiante_id', $id)->delete();

        if (!empty($padreId)) {
            $this->estudiantesResponsables->insert([
                'estudiante_id' => $id,
                'responsable_id' => $padreId,
                'parentesco' => 'Padre',
                'observaciones' => $obsPadre
            ]);
        }

        if (!empty($madreId)) {
            $this->estudiantesResponsables->insert([
                'estudiante_id' => $id,
                'responsable_id' => $madreId,
                'parentesco' => 'Madre',
                'observaciones' => $obsMadre
            ]);
        }

        if (!empty($tutorId)) {
            $this->estudiantesResponsables->insert([
                'estudiante_id' => $id,
                'responsable_id' => $tutorId,
                'parentesco' => 'Tutor',
                'observaciones' => $obsTutor
            ]);
        }

        return redirect()->to(base_url('/estudiantes'))
            ->with('success', 'Estudiante actualizado correctamente.');
    }











    //public function eliminar($id){}



    public function eliminados($activo = 0) {}

    public function restaurar($id)
    {
        $this->estudiantes->update($id, ['activo' => 1]);
        return redirect()->to(base_url() . 'escuela/eliminados');
    }


    public function visualizar($id) {}
}

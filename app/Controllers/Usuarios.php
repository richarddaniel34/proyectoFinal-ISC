<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;
use App\Models\EscuelaModel;
use App\Models\PersonalModel;
use App\Models\EstudiantesModel;

use CodeIgniter\HTTP\RedirectResponse;

class Usuarios extends BaseController
{
    protected $usuarios;
    protected $escuela;
    protected $personal;
    protected $estudiantes;

    public function __construct()
    {
        $this->usuarios = new UsuariosModel();
        $this->escuela = new EscuelaModel();
        $this->personal = new PersonalModel();
        $this->estudiantes = new EstudiantesModel();
    }

    public function login()
    {
        return view('auth/login'); // Asegúrate de que tienes el archivo login.php en views/
    }






    public function verificarLogin()
    {
        $usuario = $this->request->getPost('usuario');
        $clave = $this->request->getPost('clave');

        // Paso 1: Buscar usuario básico
        $datosUsuario = $this->usuarios->verificarCredenciales($usuario);

        if (!$datosUsuario) {
            return redirect()->to(base_url('login'))->with('error', 'Usuario o contraseña incorrectos');
        }

        // Paso 2: Verificar contraseña
        if (!password_verify($clave, $datosUsuario['clave'])) {
            return redirect()->to(base_url('login'))->with('error', 'Usuario o contraseña incorrectos');
        }

        // Paso 3: Obtener datos extendidos
        $datosUsuarioBase = $this->usuarios->getUsuarioCompleto($datosUsuario['id']);




        // Paso 4: Preparar sesión
        $session = session();
        session_regenerate_id(true);

        log_message('debug', 'Datos usuario: ' . print_r($datosUsuario, true));


        // Paso 4.5: Si es personal, guardar nombre completo
        if (!empty($datosUsuario['personal_id'])) {
            $personal = $this->personal->find($datosUsuario['personal_id']);

            if ($personal) {
                $nombreCompleto = $personal['nombre'] . ' ' . $personal['apellido'];
            }
        }

        $tipoUsuarioNombre = 'usuario';
        if ($datosUsuario['id_tipo_usuario'] == 5) {
            $tipoUsuarioNombre = 'administrador';
        } elseif ($datosUsuarioBase['tipo_usuario'] == 'administrativo' && !empty($datosUsuarioBase['personal_funcion'])) {
            $tipoUsuarioNombre = strtolower($datosUsuarioBase['personal_funcion']);
        } else {
            $tipoUsuarioNombre = strtolower($datosUsuarioBase['tipo_usuario']);
        }

        $sessionData = [
            'usuario'             => $datosUsuario['usuario'],
            'usuario_id'          => $datosUsuario['id'],
            'personal_id'         => $datosUsuario['personal_id'] ?? null,
            'tipo_usuario'        => $datosUsuario['id_tipo_usuario'],
            'tipo_usuario_nombre' => $tipoUsuarioNombre,
            'funcion'             => $datosUsuarioBase['personal_funcion'] ?? null,
            'cambio_clave'        => $datosUsuario['cambio_clave'],
            'nombre_completo'     => $nombreCompleto ?? null,
            'isLoggedIn'          => true,
        ];

        // Si el usuario es estudiante, asignar la foto desde el campo estudiante_foto
        if (isset($datosUsuarioBase['estudiante_foto'])) {
            $sessionData['foto'] = $datosUsuarioBase['estudiante_foto'];
        }

        $session->set($sessionData);



        // Paso 5: Datos de escuela
        if (!empty($datosUsuario['id_escuela'])) {
            $escuela = $this->escuela->find($datosUsuario['id_escuela']);
            $session->set([
                'id_escuela'      => $datosUsuario['id_escuela'],
                'codigo_gestion'  => $escuela['codigo_gestion'] ?? 'N/A',
                'nombre_escuela'  => $escuela['nombre'] ?? 'Sin nombre',
            ]);
        } else {
            $session->set([
                'codigo_gestion'  => 'N/A',
                'nombre_escuela'  => 'Administrador',
            ]);
        }

        // Paso 6: Redirigir a cambio de clave si aplica
        if ($datosUsuario['cambio_clave'] == 1) {
            return redirect()->to(base_url('usuarios/cambio_clave'));
        }

        // Paso 7: Foto del usuario
        $foto = null;
        if ($datosUsuario['id_tipo_usuario'] == 5) {
            $foto = base_url('assets/img/users/admin/default-avatar.png');
        } elseif (!empty($datosUsuario['personal_id'])) {
            $personal = $this->personal->find($datosUsuario['personal_id']);
            $foto = isset($personal['foto']) && strpos($personal['foto'], 'assets/img/users/personal/') === false
                ? base_url('assets/img/users/personal/' . $personal['foto'])
                : base_url($personal['foto'] ?? '');
        } elseif (!empty($datosUsuario['estudiantes_id'])) {
            $estudiante = $this->estudiantes->find($datosUsuario['estudiantes_id']);
            $foto = isset($estudiante['imagen']) && strpos($estudiante['imagen'], 'assets/img/estudiantes/') === false
                ? base_url('assets/img/estudiantes/' . $estudiante['imagen'])
                : base_url($estudiante['imagen'] ?? '');
        }

        $session->set('foto', $foto);

        // Paso 8: Redirigir a inicio
        return redirect()->to(base_url('home'));
    }





    public function cambioClave()
    {
        log_message('debug', 'Accediendo a la vista de cambio de clave');
        return view('usuarios/cambio_clave');
    }


    public function listarUsuarios()
    {
        // Definir si el usuario está activo o no
        $activo = 1; // O 0 dependiendo de si quieres solo usuarios activos o no

        // Obtener los usuarios filtrados por activo
        $usuarios = $this->usuarios->where('activo', $activo)->findAll();

        // Pasar los datos a la vista
        $data = [
            'titulo' => 'Lista de Usuarios', // Título de la vista
            'usuarios' => $usuarios // Los datos de los usuarios
        ];

        // Cargar las vistas
        echo view('header');
        echo view('usuarios/usuarios', $data); // Vista con la lista de usuarios
        echo view('footer');
    }








    public function validarUsuario()
    {
        $nombre = trim($this->request->getPost('nombre'));
        $apellido = trim($this->request->getPost('apellido'));

        if (empty($nombre) || empty($apellido)) {
            return $this->response->setJSON(['error' => 'Nombre y apellido son obligatorios']);
        }

        // Separar nombres y apellidos correctamente
        $nombreParts = explode(" ", preg_replace('/\s+/', ' ', $nombre)); // Evitar múltiples espacios
        $apellidoParts = explode(" ", preg_replace('/\s+/', ' ', $apellido));

        $usuarioBase = "";

        if (count($nombreParts) >= 2) {
            $usuarioBase = strtolower(substr($this->removerAcentos($nombreParts[0]), 0, 1)) .
                strtolower(substr($this->removerAcentos($nombreParts[1]), 0, 1));
        } else {
            $usuarioBase = strtolower(substr($this->removerAcentos($nombreParts[0]), 0, 2));
        }

        $primerApellido = count($apellidoParts) > 0 ? strtolower($this->removerAcentos($apellidoParts[0])) : "";
        $segundoApellidoLetra = count($apellidoParts) > 1 ? strtolower(substr($this->removerAcentos($apellidoParts[1]), 0, 1)) : "";

        $usuarioBase .= $primerApellido . $segundoApellidoLetra;

        //  Verificar si el usuario ya existe en la BD y agregar número incremental si es necesario
        $contador = 1;
        $usuarioFinal = $usuarioBase;

        while ($this->usuarios->where('usuario', $usuarioFinal)->countAllResults() > 0) {
            $usuarioFinal = $usuarioBase . $contador;
            $contador++;
        }

        return $this->response->setJSON(['usuario' => $usuarioFinal]);
    }


    public function docentes()
    {

        $data = [
            'titulo' => 'Usuarios Docentes'
        ];

        echo view('header');
        echo view('usuarios/docentes', $data); // Asegúrate que esta es la ruta correcta de tu vista
        echo view('footer');
    }



    public function studentUser() {}

    public function administrativoUser() {}


    public function homeAdmin()
    {
        // Verificar si el usuario está autenticado
        //if (!isset($_SESSION['usuario_id'])) {
        // return redirect()->to('/login'); // Si no está logueado, redirigir al login
        // }

        // Obtener el ID del usuario
        //  $usuario_id = $_SESSION['usuario_id'];

        // Obtener la información completa del usuario, incluyendo el tipo de usuario
        // $usuario = $this->usuarios->getUsuarioCompleto($usuario_id); // Método en el modelo

        // Pasar la información del usuario a la vista
        // return view('usuarios/admin/home', ['usuario' => $usuario]);
    }


    /**
     * Remover acentos para evitar problemas con caracteres especiales.
     */
    private function removerAcentos($cadena)
    {
        return iconv('UTF-8', 'ASCII//TRANSLIT', $cadena);
    }


    // Controlador Usuarios.php
    public function actualizarClave()
    {
        log_message('debug', ' Entrando al método actualizarClave');

        $session = session();
        $usuario_id = $session->get('usuario_id');

        log_message('debug', ' usuario_id en sesión: ' . $usuario_id);

        if (!$usuario_id) {
            log_message('error', ' Usuario no autenticado, redirigiendo al login');
            return redirect()->to('/login');
        }

        // Detectar si es POST de forma robusta
        $metodo = strtoupper($this->request->getMethod());
        $metodo_real = strtoupper($this->request->getServer('REQUEST_METHOD'));

        log_message('debug', 'Método recibido: ' . $metodo);
        log_message('debug', 'REQUEST_METHOD real: ' . $metodo_real);

        if ($metodo !== 'POST' || $metodo_real !== 'POST') {
            log_message('warning', 'No es una solicitud POST, redirigiendo al formulario');
            return redirect()->to('/usuarios/cambio_clave');
        }

        // Recoger contraseñas del formulario
        $nuevo_password     = $this->request->getPost('nuevo_password');
        $confirmar_password = $this->request->getPost('confirmar_password');

        log_message('debug', 'Nueva contraseña: ' . $nuevo_password);
        log_message('debug', 'Confirmar contraseña: ' . $confirmar_password);

        if (empty($nuevo_password) || empty($confirmar_password)) {
            log_message('error', ' Campos vacíos');
            return redirect()->back()->with('error', 'Todos los campos son obligatorios');
        }

        if ($nuevo_password !== $confirmar_password) {
            log_message('error', ' Las contraseñas no coinciden');
            return redirect()->back()->with('error', 'Las contraseñas no coinciden');
        }

        // Preparar datos para actualización
        $hashPassword = password_hash($nuevo_password, PASSWORD_DEFAULT);

        $datos = [
            'clave' => $hashPassword,
            'cambio_clave' => 0
        ];

        log_message('debug', ' ID usuario: ' . $usuario_id);
        log_message('debug', ' Datos a actualizar: ' . print_r($datos, true));

        if ($this->usuarios->update($usuario_id, $datos)) {
            log_message('info', ' Contraseña actualizada exitosamente en la BD');
            $session->set('cambio_clave', 0);
            return redirect()->to('/home')->with('success', 'Contraseña actualizada correctamente');
        } else {
            log_message('error', ' Falló la actualización de la contraseña en la base de datos');
            return redirect()->back()->with('error', 'Hubo un problema al actualizar la contraseña');
        }
    }






    public function logout()
    {
        session()->destroy(); // Destruir la sesión
        return redirect()->to(base_url('login')); // Redirigir al login
    }








    public function cambiarEscuela()
    {
        // Verificar si el usuario es administrador
        if (session('tipo_usuario') != '5') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No autorizado']);
        }

        $escuelaId = $this->request->getPost('id_escuela');
        $codigoGestion = $this->request->getPost('codigo_gestion');
        $nombreEscuela = $this->request->getPost('nombre_escuela');

        // Si se envió el ID pero no el código o nombre, buscar la escuela
        if ($escuelaId && (empty($codigoGestion) || empty($nombreEscuela))) {
            $escuela = $this->escuela->find($escuelaId);
            if ($escuela) {
                $codigoGestion = $escuela['codigo_gestion'];
                $nombreEscuela = $escuela['nombre'];
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Escuela no encontrada']);
            }
        }

        // Actualizar datos de sesión
        session()->set([
            'id_escuela' => $escuelaId,
            'codigo_gestion' => $codigoGestion,
            'nombre_escuela' => $nombreEscuela
        ]);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Escuela cambiada correctamente']);
    }



    public function getEscuelas()
    {
        // Verificar si el usuario es administrador
        if (session('tipo_usuario') != '5') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No autorizado']);
        }

        $escuelas = $this->escuela->findAll();

        return $this->response->setJSON($escuelas);
    }
}

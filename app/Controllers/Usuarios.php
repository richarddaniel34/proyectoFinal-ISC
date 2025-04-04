<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;
use CodeIgniter\HTTP\RedirectResponse;

class Usuarios extends BaseController
{
    protected $usuarios;

    public function __construct()
    {
        $this->usuarios = new UsuariosModel();
    }

    public function login()
    {
        return view('auth/login'); // Asegúrate de que tienes el archivo login.php en views/
    }



    public function verificarLogin()
    {
        // Capturar los datos del formulario
        $usuario = $this->request->getPost('usuario');
        $clave = $this->request->getPost('clave');

        log_message('debug', 'Usuario recibido: ' . $usuario);

        // Buscar el usuario en la base de datos
        $datosUsuario = $this->usuarios->where('usuario', $usuario)->first();

        if (!$datosUsuario) {
            log_message('error', 'Usuario no encontrado: ' . $usuario);
            return redirect()->to(base_url('login'))->with('error', 'Usuario o contraseña incorrectos');
        }

        log_message('debug', 'Usuario encontrado en BD: ' . $datosUsuario['usuario']);

        // Verificar contraseña
        if (password_verify($clave, $datosUsuario['clave'])) {

            $session = session();
            session_regenerate_id(true);

            $session->set([
                'usuario' => $datosUsuario['usuario'],
                'usuario_id' => $datosUsuario['id'],   // ESTE DEBE EXISTIR
                'tipo_usuario' => $datosUsuario['id_tipo_usuario'],
                'cambio_clave' => $datosUsuario['cambio_clave'],
                'isLoggedIn'   => true // Asegúrate que coincida con el filtro
            ]);

            log_message('debug', 'Sesión iniciada para el usuario: ' . $datosUsuario['usuario']);

            if ($datosUsuario['cambio_clave'] == 1) {
                log_message('debug', 'Redirigiendo a cambio de clave');
                return redirect()->to(base_url('usuarios/cambio_clave'));
            }

            log_message('debug', 'Redirigiendo al dashboard home');
            return redirect()->to(base_url('home')); // o ->route('home') si tienes alias
        }

        log_message('error', 'Contraseña incorrecta para el usuario: ' . $usuario);
        return redirect()->to(base_url('login'))->with('error', 'Usuario o contraseña incorrectos');
    }

    public function cambioClave()
    {
        log_message('debug', 'Accediendo a la vista de cambio de clave');
        return view('usuarios/cambio_clave');
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

        // 🔥 Verificar si el usuario ya existe en la BD y agregar número incremental si es necesario
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
        if (!isset($_SESSION['usuario_id'])) {
            return redirect()->to('/login'); // Si no está logueado, redirigir al login
        }

        // Obtener el ID del usuario
        $usuario_id = $_SESSION['usuario_id'];

        // Obtener la información completa del usuario, incluyendo el tipo de usuario
        $usuario = $this->usuarios->getUsuarioCompleto($usuario_id); // Método en el modelo

        // Pasar la información del usuario a la vista
        return view('usuarios/admin/home', ['usuario' => $usuario]);
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
        $session = session();

        // Verificar si el usuario está autenticado
        if (!$session->get('usuario_id')) {
            return redirect()->to('/login');
        }

        // Si es un post
        if ($this->request->getMethod() === 'post') {

            // Recoger las contraseñas desde el formulario
            $nuevo_password = $this->request->getPost('nuevo_password');
            $confirmar_password = $this->request->getPost('confirmar_password');

            // Validaciones básicas
            if (empty($nuevo_password) || empty($confirmar_password)) {
                return redirect()->back()->with('error', 'Todos los campos son obligatorios');
            }

            if ($nuevo_password !== $confirmar_password) {
                return redirect()->back()->with('error', 'Las contraseñas no coinciden');
            }

            // Hashear la contraseña
            $hashPassword = password_hash($nuevo_password, PASSWORD_DEFAULT);

            // Preparar los datos para el update
            $usuario_id = $session->get('usuario_id');

            $datos = [
                'clave' => $hashPassword,
                'cambio_clave' => 0 // Ya no debe cambiar la clave obligatoriamente
            ];

            log_message('debug', 'ID usuario: ' . $usuario_id);
            log_message('debug', 'Datos actualizados: ' . print_r($datos, true));

            // Actualizar la contraseña en la base de datos
            if ($this->usuarios->update($usuario_id, $datos)) {

                // Actualizar también en la sesión el estado de cambio de clave
                $session->set('cambio_clave', 0);

                return redirect()->to('/home')->with('success', 'Contraseña actualizada correctamente');
            } else {
                return redirect()->back()->with('error', 'Hubo un problema al actualizar la contraseña');
            }
        }

        // Si no es POST, redirige al formulario de cambio de clave
        return redirect()->to('/usuarios/cambio_clave');
    }


    public function logout()
    {
        session()->destroy(); // Destruir la sesión
        return redirect()->to('/login'); // Redirigir al login
    }
}

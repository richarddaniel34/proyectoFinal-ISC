<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;

class Login extends BaseController
{
    protected $usuarios;

    public function __construct()
    {
        $this->usuarios = new UsuariosModel();
    }

    /**
     * Página de Login
     */
   

    /**
     * 🔑 Procesar el Inicio de Sesión
     */
    public function procesarLogin()
    {
        $session = session();
        $usuario = trim($this->request->getPost('usuario'));
        $clave = trim($this->request->getPost('clave'));

        //  Validaciones básicas
        if (empty($usuario) || empty($clave)) {
            return redirect()->back()->withInput()->with('errors', ['usuario' => 'Debe ingresar usuario y contraseña']);
        }

        // 🔍 Verificar si el usuario existe y está activo
        $usuarioData = $this->usuarios->verificarCredenciales($usuario);

        if (!$usuarioData || !password_verify($clave, $usuarioData['clave'])) {
            return redirect()->back()->withInput()->with('errors', ['usuario' => 'Usuario o contraseña incorrectos']);
        }

        //  Obtener detalles adicionales
        $usuarioDetalles = $this->usuarios->getUsuarioConDetalles($usuarioData['id']);

        // ✅ Crear sesión del usuario
        $session->set([
            'usuario_id' => $usuarioData['id'],
            'usuario' => $usuarioData['usuario'],
            'tipo_usuario' => $usuarioDetalles['tipo_usuario'],
            'nombre' => $usuarioDetalles['personal_nombre'] ?? $usuarioDetalles['estudiante_nombre'],
            'apellido' => $usuarioDetalles['personal_apellido'] ?? $usuarioDetalles['estudiante_apellido'],
            'isLoggedIn' => true
        ]);

        // 🔀 Redirigir según el tipo de usuario
        switch ($usuarioData['id_tipo_usuario']) {
            case 1: return redirect()->to(base_url('/administrativo')); // Administrativo
            case 3: return redirect()->to(base_url('/docente')); // Docente
            case 4: return redirect()->to(base_url('/estudiante')); // Estudiante
            case 5: return redirect()->to(base_url('/admin')); // ADMIN
            default: return redirect()->to(base_url('/dashboard'));
        }
    }

    /**
     * 🔴 Cerrar sesión
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/login'))->with('success', 'Sesión cerrada correctamente.');
    }
}

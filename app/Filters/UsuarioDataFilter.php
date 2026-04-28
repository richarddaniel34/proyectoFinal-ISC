<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\UsuariosModel;

class UsuarioDataFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Obtener el usuario de la sesión
        $session = session();
        $usuarioId = $session->get('usuario_id');

        if ($usuarioId) {
            // Obtener datos del usuario
            $usuariosModel = new UsuariosModel();
            $usuarioData = $usuariosModel->getUsuarioCompleto($usuarioId);

            // Pasar los datos del usuario a la vista
            // Asignar en la sesión para que esté disponible en todas las vistas
            $session->set('usuario_data', $usuarioData);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se necesita nada después de la ejecución de la solicitud
    }
}

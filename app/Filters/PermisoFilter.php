<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class PermisoFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        
        $usuario = session()->get('usuario_data');
        

if (!$usuario) {
    return redirect()->to('/login');
}

$tipo = $usuario['tipo_usuario'] ?? null;
$funcion = $usuario['funcion'] ?? null;

$tieneAcceso = (
    $tipo === 'ADMIN' ||
    $tipo === 'S-ADMIN' ||
    (
        $tipo === 'Administrativo' &&
        in_array($funcion, ['Digitador/a', 'Secretaria/o'])
    )
);

if (!$tieneAcceso) {
    return redirect()->to('/home')->with('error', 'No tienes permisos para acceder.');
}
        log_message('error', 'Permiso ejecutado para: ' . $tipo . ' - ' . $funcion);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nada aquí
    }
}
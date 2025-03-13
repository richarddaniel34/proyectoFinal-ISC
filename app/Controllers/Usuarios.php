<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;

class Usuarios extends BaseController
{
    protected $usuarios;

    public function __construct()
    {
        $this->usuarios = new UsuariosModel();
    }

    public function validarUsuario()
{
    $nombre = trim($this->request->getPost('nombre'));
    $apellido = trim($this->request->getPost('apellido'));

    if (empty($nombre) || empty($apellido)) {
        return $this->response->setJSON(['error' => 'Nombre y apellido son obligatorios']);
    }

    // 🔥 Separar nombres y apellidos correctamente
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

/**
 * 🔥 Remover acentos para evitar problemas con caracteres especiales.
 */
private function removerAcentos($cadena)
{
    return iconv('UTF-8', 'ASCII//TRANSLIT', $cadena);
}

}


?>


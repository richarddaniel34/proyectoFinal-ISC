<?php

namespace App\Models;

use CodeIgniter\Model;

class TipoUsuarioModel extends Model
{
    protected $table = 'tipo_usuario';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['nombre'];

    // Relación con la tabla usuarios
    public function usuarios()
    {
        return $this->hasMany(UsuariosModel::class, 'id_tipo_usuario', 'id');
    }
}

<?php

namespace App\Models;
use CodeIgniter\Model;

class NivelesModel extends Model
{
    protected $table = 'niveles';
    protected $primaryKey = 'id';
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = ['nombre', 'descripcion', 'activo'];
    
    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';
    protected $deletedField = 'deleted_at';
    
    protected $validationRules = [
        'nombre' => 'required|is_unique[niveles.nombre]|alpha_space',
        'descripcion' => 'permit_empty|string|max_length[255]',
        'activo' => 'required|in_list[0,1]'
    ];
    
    protected $validationMessages = [
        'nombre' => [
            'required' => 'ESte campo es obligatorio.',
            'is_unique' => 'Este nivel educativo ya está registrado.',
            'alpha_space' => 'El nombre solo puede contener letras y espacios.'
        ],
        'descripcion' => [
            'max_length' => 'La descripción no puede superar los 255 caracteres.'
        ],
        'activo' => [
            'required' => 'El estado del nivel es obligatorio.',
            'in_list' => 'El estado debe ser 0 (inactivo) o 1 (activo).'
        ]
    ];
    
    protected $skipValidation = false;
}

?>

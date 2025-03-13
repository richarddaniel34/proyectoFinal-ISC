<?php

namespace App\Models;
use CodeIgniter\Model;

class ModalidadModel extends Model
{
    protected $table = 'modalidad'; // Nombre de la tabla
    protected $primaryKey = 'id'; // Clave primaria
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false; // Si no usas eliminación lógica, ponlo en false
    
    protected $allowedFields = ['nombre', 'descripcion', 'activo']; // Campos permitidos para inserción/actualización
    
    protected $useTimestamps = true; // Activar timestamps automáticos
    protected $createdField = 'fecha_alta'; // Campo de fecha de creación
    protected $updatedField = 'fecha_edit'; // Campo de fecha de modificación
    protected $deletedField = 'deleted_at'; // No se usa si `useSoftDeletes = false`
    
    // 🔹 Reglas de validación
    protected $validationRules = [
        'nombre' => 'required|is_unique[modalidades.nombre]|alpha_space|min_length[3]|max_length[100]',
        'descripcion' => 'permit_empty|max_length[255]',
        'activo' => 'required|in_list[0,1]'
    ];

    // 🔹 Mensajes personalizados para validaciones
    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre de la modalidad es obligatorio.',
            'is_unique' => 'Esta modalidad ya está registrada.',
            'alpha_space' => 'El nombre solo puede contener letras y espacios.',
            'min_length' => 'El nombre debe tener al menos 3 caracteres.',
            'max_length' => 'El nombre no puede superar los 100 caracteres.'
        ],
        'descripcion' => [
            'max_length' => 'La descripción no puede superar los 255 caracteres.'
        ],
        'activo' => [
            'required' => 'El estado de la modalidad es obligatorio.',
            'in_list' => 'El estado debe ser 0 (inactivo) o 1 (activo).'
        ]
    ];
    
    protected $skipValidation = false;
}

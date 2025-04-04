<?php

namespace App\Models;
use CodeIgniter\Model;

class ResponsablesModel extends Model
{
    protected $table = 'responsables';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'nombre',
        'apellido',
        'cedula',
        'celular',
        'telefono',
        'direccion',
        'trabajo',
        'telefono_trabajo',
        'contacto_emergencia',
        'activo'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';
    protected $deletedField = 'deleted_at';

    
    protected $validationRules = [
        'nombre'  => 'permit_empty|min_length[3]|max_length[100]',
        'apellido' => 'permit_empty|min_length[3]|max_length[100]',
        // Cedula: Permitido vacío, o si no es vacío, debe ser única y cumplir el formato.
        'cedula' => 'permit_empty|regex_match[/^[0-9\-]+$/]|is_unique[responsables.cedula,id,{id}]',
        'celular' => 'permit_empty|regex_match[/^[0-9\-\(\)\s]+$/]',
        'telefono' => 'permit_empty|regex_match[/^[0-9\-\(\)\s]+$/]',
        'direccion' => 'permit_empty|max_length[255]',
        'trabajo' => 'permit_empty|max_length[255]',
        'telefono_trabajo' => 'permit_empty|regex_match[/^[0-9\-\(\)\s]+$/]',
        'contacto_emergencia' => 'permit_empty|max_length[255]',
    ];

    protected $validationMessages = [
        'cedula' => [
            'regex_match' => 'La cédula solo puede contener números y guiones.',
            'is_unique' => 'Ya existe un responsable con esa cédula registrada.',
        ],
        'celular' => [
            'regex_match' => 'El celular solo puede contener números, espacios, guiones y paréntesis.',
        ],
    ];

    protected $skipValidation = false;
}

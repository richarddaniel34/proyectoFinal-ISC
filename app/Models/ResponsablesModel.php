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
        'id_nacionalidad',
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
        'nombre' => 'permit_empty|min_length[3]|max_length[100]',
        'apellido' => 'permit_empty|min_length[3]|max_length[100]',
        'cedula' => 'permit_empty|regex_match[/^[0-9\-]+$/]',  // Quitamos is_unique
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

    // Agregar método para verificar cédula única
    public function isCedulaUnique($cedula, $excludeId = null)
    {
        $builder = $this->where('cedula', $cedula);
        
        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->countAllResults() === 0;
    }

    protected $skipValidation = false;
}

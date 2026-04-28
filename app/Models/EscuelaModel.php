<?php

namespace App\Models;

use CodeIgniter\Model;

class EscuelaModel extends Model
{
    protected $table = 'escuela'; // Nombre de la tabla en la BD
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'nombre',
        'id_nivel',
        'id_modalidad',
        'codigo_gestion',
        'codigo_plantel',
        'rnc',
        'distrito_educativo',
        'email',
        'telefono',
        'redes',
        'direccion',
        'web',
        'logo',
        'activo',
        'tanda',     // agregado
        'tipo'       // agregado
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';
    protected $deletedField = 'deleted_at';

    // Reglas de Validación
    protected $validationRules = [
        'nombre' => 'required|min_length[3]|max_length[100]',
        'id_nivel' => 'required|integer',
        'id_modalidad' => 'required|integer',
        'email' => 'permit_empty|valid_email|max_length[50]',
        'telefono' => 'required|regex_match[/^[0-9+\-() ]{7,20}$/]',
        'direccion' => 'required|max_length[100]',
        'web' => 'permit_empty|valid_url|max_length[50]',
        'logo' => 'permit_empty|max_length[150]',
        'tanda' => 'required|in_list[Matutina,Vespertina,Jornada Escolar Extendida (J.E.E.),Nocturno]',
        'tipo' => 'required|in_list[Privado,Publico]'
    ];

    // Mensajes Personalizados
    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre de la escuela es obligatorio.',
            'min_length' => 'El nombre debe tener al menos 3 caracteres.',
            'max_length' => 'El nombre no puede superar los 100 caracteres.'
        ],
        'codigo_gestion' => [
            'required' => 'El código SIGERD es obligatorio.',
            'is_unique' => 'Este código Gestion ya está registrado.',
            'alpha_numeric' => 'El código SIGERD solo puede contener números.',
            'max_length' => 'El código SIGERD no puede superar los 10 caracteres.'
        ],
        'codigo_plantel' => [
            'required' => 'El código del plantel es obligatorio.',
            'is_unique' => 'Este código de plantel ya está registrado.',
            'alpha_numeric' => 'El código del plantel solo puede contener letras y números.',
            'max_length' => 'El código de plantel no puede superar los 10 caracteres.'
        ],
        'rnc' => [
            'required' => 'El RNC es obligatorio.',
            'is_unique' => 'Este RNC ya está registrado.',
            'numeric' => 'El RNC solo puede contener números.',
            'min_length' => 'El RNC debe tener al menos 9 dígitos.',
            'max_length' => 'El RNC no puede superar los 11 dígitos.'
        ],
        'email' => [
            'permit_empty' => 'El correo electrónico es obligatorio.',
            'valid_email' => 'Debe ingresar un correo válido.',
            'max_length' => 'El correo no puede superar los 50 caracteres.'
        ],
        'telefono' => [
            'required' => 'El teléfono es obligatorio.',
            'regex_match' => 'Debe ingresar un número de teléfono válido.'
        ],
        'tanda' => [
            'required' => 'La tanda es obligatoria.',
            'in_list' => 'La tanda seleccionada no es válida.'
        ],
        'tipo' => [
            'required' => 'El tipo es obligatorio.',
            'in_list' => 'El tipo seleccionado no es válido.'
        ]
    ];

    protected $skipValidation = false;

    // Métodos existentes ...

    public function getEscuelasConDetalles($activo = null)
    {
        $query = $this->select('escuela.*, niveles.nombre AS nivel, modalidad.nombre AS modalidad')
            ->join('niveles', 'niveles.id = escuela.id_nivel', 'left')
            ->join('modalidad', 'modalidad.id = escuela.id_modalidad', 'left');

        if (!is_null($activo)) {
            $query->where('escuela.activo', $activo);
        }

        return $query->findAll();
    }

    public function getEscuelaByCodigoPlantel($codigo)
    {
        return $this->where('codigo_plantel', $codigo)->first();
    }

    public function getNivelNombreByEscuela($idEscuela)
    {
        $escuela = $this->select('niveles.nombre AS nivel')
            ->join('niveles', 'niveles.id = escuela.id_nivel')
            ->where('escuela.id', $idEscuela)
            ->first();

        return $escuela['nivel'] ?? '';
    }
}

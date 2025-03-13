<?php

namespace App\Models;

use CodeIgniter\Model;

class EstudiantesModel extends Model
{
    protected $table = 'estudiantes';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'nombre', 'apellido', 'sexo', 'lugar_nacimiento', 'provincia', 'fecha_nac', 
        'numero_identidad', 'sigerd_id', 'direccion', 'escuela_procedencia', 
        'responsables', 'estado_padres', 'casa_estudiante', 'alergias', 
        'condicion_medica', 'medicamentos', 'tipo_sangre', 'imagen', 
        'matricula', 'activo'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';
    protected $deletedField = 'deleted_at';

    // 🔥 VALIDACIONES DIRECTAMENTE EN EL MODELO (SIN VALIDAR IMAGEN AQUÍ)
    protected $validationRules = [
        'nombre' => 'required',
        'apellido' => 'required',
        'sexo' => 'required',
        'lugar_nacimiento' => 'required',
        'provincia' => 'required',
        'fecha_nac' => 'required|valid_date',
        'numero_identidad' => 'required|is_unique[estudiantes.numero_identidad]',
        'sigerd_id' => 'required|is_unique[estudiantes.sigerd_id]',
        'direccion' => 'required',
        'escuela_procedencia' => 'required',
        'responsables' => 'required',
        'estado_padres' => 'required',
        'casa_estudiante' => 'required',
        'alergias' => 'permit_empty',
        'condicion_medica' => 'permit_empty',
        'medicamentos' => 'permit_empty',
        'tipo_sangre' => 'required',
        'matricula' => 'required|is_unique[estudiantes.matricula]',
    ];

    protected $validationMessages = [
        'numero_identidad' => ['is_unique' => 'Este número de identidad ya está registrado.'],
        'sigerd_id' => ['is_unique' => 'El SIGERD ID ya está en uso.'],
        'matricula' => ['is_unique' => 'Esta matrícula ya está registrada.'],
    ];

    /**
     * 🔥 Verifica si un estudiante ya está registrado por identidad, SIGERD ID o matrícula
     */
    public function estudianteExiste($numeroIdentidad, $sigerdId, $matricula)
    {
        return $this->where('numero_identidad', $numeroIdentidad)
            ->orWhere('sigerd_id', $sigerdId)
            ->orWhere('matricula', $matricula)
            ->first();
    }
}


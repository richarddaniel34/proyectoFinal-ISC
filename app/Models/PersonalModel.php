<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonalModel extends Model
{
    protected $table = 'personal';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'nombre',
        'apellido',
        'sexo',
        'cedula',
        'telefono',
        'email',
        'direccion',
        'fecha_nac',
        'condicion',
        'nombramiento',
        'funcion',
        'grado_academico',
        'foto',
        'activo'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';
    protected $deletedField = 'deleted_at';

    // 🔥 VALIDACIONES DIRECTAMENTE EN EL MODELO
    protected $validationRules = [
        'nombre' => 'required',
        'apellido' => 'required',
        'sexo' => 'required',
        'cedula' => 'required|is_unique[personal.cedula]',
        'telefono' => 'required',
        'email' => 'required|valid_email',
        'direccion' => 'required',
        'fecha_nac' => 'required',
        'condicion' => 'required',
        'nombramiento' => 'required',
        'funcion' => 'required',
        'grado_academico' => 'required',
        'foto' => 'permit_empty'
    ];

    protected $validationMessages = [
        'cedula' => ['is_unique' => 'La cédula ya está registrada.'],
        'email' => ['valid_email' => 'Debe ingresar un correo válido.'],
        'foto' => [
            'is_image' => 'El archivo debe ser una imagen válida.',
            'mime_in' => 'La imagen debe estar en formato JPG, JPEG o PNG.',
            'max_size' => 'La imagen no debe superar los 2MB.'
        ]
    ];

    /**
     * 🔥 Obtiene el personal con sus relaciones (Condición, Nombramiento, Grado Académico)
     */
    public function getPersonalConDetalles()
    {
        return $this->select('
            personal.*, 
            condicion.nombre as condicion_nombre, 
            nombramiento.nombre as nombramiento_nombre, 
            grado_academico.grado_academico as grado_academico_nombre
        ')
            ->join('condicion', 'condicion.id = personal.condicion', 'left')
            ->join('nombramiento', 'nombramiento.id = personal.nombramiento', 'left')
            ->join('grado_academico', 'grado_academico.id = personal.grado_academico', 'left')
            ->findAll();
    }

    /**
     * 🔥 Verifica si una cédula ya está registrada
     */
    public function cedulaExiste($cedula)
    {
        return $this->where('cedula', str_replace('-', '', trim($cedula)))->first();
    }



    public function getDocentesPorEscuela($idEscuela)
{
    return $this->select('personal.id, CONCAT(personal.nombre, " ", personal.apellido) AS nombre_completo, usuarios.id_escuela, nombramiento.nombre AS nombramiento')
        ->join('usuarios', 'usuarios.personal_id = personal.id', 'inner') // Relacionamos con usuarios
        ->join('nombramiento', 'nombramiento.id = personal.nombramiento', 'inner') // Relacionamos con nombramiento
        ->where('usuarios.id_escuela IS NOT NULL') // Aseguramos que tiene escuela asignada
        ->where('usuarios.id_escuela', $idEscuela) // Filtra por la escuela del usuario
        ->where('nombramiento.nombre', 'Docente') // Filtra solo los docentes
        ->where('personal.activo', 1) // Solo docentes activos
        ->findAll();
}

}

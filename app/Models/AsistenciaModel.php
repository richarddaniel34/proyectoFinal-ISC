<?php

namespace App\Models;

use CodeIgniter\Model;

class AsistenciaModel extends Model
{
    protected $table = 'asistencia';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id_estudiante',
        'id_asignatura',
        'id_personal',
        'id_curso',
        'id_periodo',
        'mes',
        'fecha',
        'estado',
        'observaciones'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';
    protected $deletedField = 'deleted_at';

    // 🔥 Reglas de validación
    protected $validationRules = [
        'id_estudiante' => 'required|integer',
        'id_asignatura' => 'required|integer',
        'id_personal'    => 'required|integer',
        'fecha'         => 'required|valid_date',
        'estado'        => 'required|in_list[Presente,Tarde,Ausente,Justificado]',
        'observaciones' => 'permit_empty|max_length[255]'
    ];

    protected $validationMessages = [
        'estado' => ['in_list' => 'Estado de asistencia inválido.']
    ];

    /**
     * 🔥 Obtener asistencia con información adicional (estudiante, asignatura y personal)
     */
    public function getAsistenciasConDetalles()
    {
        return $this->select('
                asistencia.*, 
                CONCAT(estudiantes.nombre, " ", estudiantes.apellido) AS estudiante,
                asignatura.nombre AS asignatura,
                CONCAT(personal.nombre, " ", personal.apellido) AS personal
            ')
            ->join('estudiantes', 'estudiantes.id = asistencia.id_estudiante', 'left')
            ->join('asignatura', 'asignatura.id = asistencia.id_asignatura', 'left')
            ->join('personal', 'personal.id = asistencia.id_personal', 'left')
            ->orderBy('asistencia.fecha', 'DESC')
            ->findAll();
    }

    /**
     * 🔥 Obtener asistencia por estudiante
     */
    public function getAsistenciasPorEstudiante($idEstudiante)
    {
        return $this->where('id_estudiante', $idEstudiante)->orderBy('fecha', 'DESC')->findAll();
    }

    /**
     * 🔥 Obtener asistencia por asignatura
     */
    public function getAsistenciasPorAsignatura($idAsignatura)
    {
        return $this->where('id_asignatura', $idAsignatura)->orderBy('fecha', 'DESC')->findAll();
    }
}

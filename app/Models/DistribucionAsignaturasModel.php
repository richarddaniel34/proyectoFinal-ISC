<?php

namespace App\Models;

use CodeIgniter\Model;

class DistribucionAsignaturasModel extends Model
{
    protected $table = 'distribucion_asignaturas';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id_docente',
        'id_asignatura',
        'id_curso',
        'id_grado',
        'id_seccion',
        'id_periodo',
        'fecha_asignacion'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_asignacion';
    protected $updatedField = 'fecha_edit';
    protected $deletedField = 'deleted_at';

    // 🔥 VALIDACIONES DIRECTAMENTE EN EL MODELO
    protected $validationRules = [
        'id_docente'    => 'required|integer',
        'id_asignatura' => 'required|integer',
        'id_curso'      => 'required|integer',
        'id_grado'      => 'required|integer',
        'id_seccion'    => 'required|integer',
        'id_periodo'    => 'required|integer',
    ];

    protected $validationMessages = [
        'id_docente'    => ['required' => 'Debe seleccionar un docente.'],
        'id_asignatura' => ['required' => 'Debe seleccionar una asignatura.'],
        'id_curso'      => ['required' => 'Debe seleccionar un curso.'],
        'id_grado'      => ['required' => 'Debe seleccionar un grado.'],
        'id_seccion'    => ['required' => 'Debe seleccionar una sección.'],
        'id_periodo'    => ['required' => 'Debe seleccionar un período escolar.']
    ];

    /**
     * 🔥 Obtener todas las asignaciones con detalles (docente, asignatura, curso, grado, sección, período)
     */
    public function getDistribucionConDetalles()
    {
        return $this->select('
                distribucion_asignaturas.*, 
                CONCAT(personal.nombre, " ", personal.apellido) AS docente, 
                asignatura.nombre AS asignatura, 
                cursos.nombre AS curso, 
                grados.nombre AS grado, 
                secciones.nombre AS seccion,
                schoolyear.nombre AS periodo
            ')
            ->join('personal', 'personal.id = distribucion_asignaturas.id_docente', 'left')
            ->join('asignatura', 'asignatura.id = distribucion_asignaturas.id_asignatura', 'left')
            ->join('cursos', 'cursos.id = distribucion_asignaturas.id_curso', 'left')
            ->join('grados', 'grados.id = distribucion_asignaturas.id_grado', 'left')
            ->join('secciones', 'secciones.id = distribucion_asignaturas.id_seccion', 'left')
            ->join('schoolyear', 'schoolyear.id = distribucion_asignaturas.id_periodo', 'left')
            ->orderBy('distribucion_asignaturas.fecha_asignacion', 'DESC')
            ->findAll();
    }
}

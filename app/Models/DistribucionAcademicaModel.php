<?php

namespace App\Models;

use CodeIgniter\Model;

class DistribucionAcademicaModel extends Model
{
    protected $table = 'distribucion_academica';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id_escuela',
        'id_personal',
        'id_curso',
        'id_periodo_academico',
        'activo'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';
    protected $deletedField = 'deleted_at';

    // 🔥 VALIDACIONES DIRECTAMENTE EN EL MODELO
    protected $validationRules = [
        'id_escuela'          => 'required|integer',
        'id_personal'         => 'required|integer',
        'id_curso'            => 'required|integer',
        'id_periodo_academico' => 'required|integer'
    ];

    protected $validationMessages = [
        'id_escuela' => ['required' => 'Debe seleccionar una escuela.'],
        'id_personal' => ['required' => 'Debe seleccionar un docente.'],
        'id_curso' => ['required' => 'Debe seleccionar un curso.'],
        'id_periodo_academico' => ['required' => 'Debe seleccionar un período académico.']
    ];

    /**
     * 🔥 Obtener la distribución académica con detalles (docente, curso, escuela y período académico)
     */
    public function getDistribucionConDetalles()
    {
        return $this->select('
                distribucion_academica.*, 
                CONCAT(escuela.codigo_gestion, " - ", escuela.nombre) AS escuela_nombre, 
                CONCAT(personal.nombre, " ", personal.apellido) AS docente,
                cursos.nombreCurso AS curso_nombre,
                schoolyear.codigo AS periodo_academico
            ')
            ->join('escuela', 'escuela.id = distribucion_academica.id_escuela', 'left')
            ->join('personal', 'personal.id = distribucion_academica.id_personal', 'left')
            ->join('cursos', 'cursos.id = distribucion_academica.id_curso', 'left')
            ->join('schoolyear', 'schoolyear.id = distribucion_academica.id_periodo_academico', 'left')
            ->orderBy('schoolyear.codigo', 'DESC')
            ->findAll();
    }

   
}

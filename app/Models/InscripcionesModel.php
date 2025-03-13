<?php

namespace App\Models;

use CodeIgniter\Model;

class InscripcionesModel extends Model
{
    protected $table = 'inscripciones';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id_distribucion_academica',
        'id_estudiante',
        'id_schoolYear',
        'id_pago',
        'condicion_inicial',
        'estado',
        'Condicion_final',
        'activo'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';
    protected $deletedField = 'deleted_at';

    // Validaciones
    protected $validationRules = [
        'id_distribucion_academica' => 'required|integer',
        'id_estudiante' => 'required|integer',
        'id_schoolYear' => 'required|integer',

        // Estado ahora es opcional
        'estado' => 'permit_empty|in_list[Pre-Matricula]'
    ];


    protected $validationMessages = [
        'id_estudiante' => ['required' => 'Debe seleccionar un estudiante.'],
        'id_distribucion_academica' => ['required' => 'Debe seleccionar un curso.'],
        'id_schoolYear' => ['required' => 'Debe seleccionar un año escolar.'],
    ];

    
    //Verifica si un estudiante ya está inscrito en el año actual
    public function estudianteYaInscrito($idEstudiante, $idSchoolYear)
    {
        return $this->where('id_estudiante', $idEstudiante)
            ->where('id_schoolYear', $idSchoolYear)
            ->first();
    }

    
     //Obtener inscripciones con detalles (estudiante, curso, año escolar, estado)
    public function getInscripcionesConDetalles()
    {
        return $this->select('
            inscripciones.*, 
            CONCAT(estudiantes.nombre, " ", estudiantes.apellido) AS estudiante,
            cursos.nombreCurso AS curso,
            schoolyear.codigo AS año_escolar,
            inscripciones.estado AS estado_inscripcion
        ')
            ->join('estudiantes', 'estudiantes.id = inscripciones.id_estudiante', 'left')
            ->join('distribucion_academica', 'distribucion_academica.id = inscripciones.id_distribucion_academica', 'left')
            ->join('cursos', 'cursos.id = distribucion_academica.id_curso', 'left')
            ->join('schoolyear', 'schoolyear.id = inscripciones.id_schoolYear', 'left')
            ->orderBy('schoolyear.codigo', 'DESC')
            ->findAll();
    }


    public function getEstudiantesInscritosPorResponsable($id_responsable, $id_schoolYear)
    {
        return $this->select('
            estudiantes.id,
            estudiantes.nombre,
            estudiantes.apellido,
            cursos.nombreCurso AS curso
        ')
            ->join('estudiantes', 'estudiantes.id = inscripciones.id_estudiante')
            ->join('distribucion_academica', 'distribucion_academica.id = inscripciones.id_distribucion_academica')
            ->join('cursos', 'cursos.id = distribucion_academica.id_curso')
            ->join('pagos', 'pagos.id_estudiante = estudiantes.id')
            ->where('pagos.id_responsable', $id_responsable) // Aquí filtramos por responsable
            ->where('inscripciones.id_schoolYear', $id_schoolYear)
            ->where('inscripciones.activo', 1)
            ->groupBy('estudiantes.id')
            ->findAll();
    }
}

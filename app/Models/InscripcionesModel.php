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
        'id_escuela',
        'id_grado',
        'id_curso',
        'id_estudiante',
        'id_schoolYear',
        'id_pago',
        'condicion_inicial',
        'estado',
        'condicion_final',
        'activo'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';
    protected $deletedField = 'deleted_at';

    // Validaciones
    protected $validationRules = [
        'id_grado' => 'required|integer',
        'id_estudiante' => 'required|integer',
        'id_schoolYear' => 'required|integer',
        'estado' => 'permit_empty|in_list[Normal, Prematricula, Pendiente de Pago]' // agrega todos los estados posibles
    ];



    protected $validationMessages = [
        'id_estudiante' => ['required' => 'Debe seleccionar un estudiante.'],
        'id_curso' => ['required' => 'Debe seleccionar un curso.'],
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
            cursos_base.nombre_curso AS curso
        ')
            ->join('estudiantes', 'estudiantes.id = inscripciones.id_estudiante')
            ->join('cursos', 'cursos.id = inscripciones.id_curso')
            ->join('cursos_base', 'cursos_base.id = cursos.id_cursos_base', 'left')
            ->where('inscripciones.id_schoolYear', $id_schoolYear)
            ->where('inscripciones.activo', 1)
            ->where("EXISTS (
            SELECT 1 FROM pagos 
            WHERE pagos.id_estudiante = estudiantes.id 
            AND pagos.id_responsable = {$id_responsable}
        )", null, false)
            ->orderBy('inscripciones.id', 'DESC')
            ->findAll();
    }


    /*public function getEstudiantesPorCurso($id_curso)
    {
        return $this->select('estudiantes.id, estudiantes.nombre, estudiantes.apellido')
            ->join('estudiantes', 'estudiantes.id = inscripciones.id_estudiante')
            ->join('distribucion_asignaturas', 'distribucion_asignaturas.id = inscripciones.id_distribucion_academica')
            ->where('distribucion_asignaturas.id_curso', $id_curso)
            ->where('inscripciones.activo', 1)
            ->orderBy('estudiantes.apellido', 'ASC') // Puedes cambiar a nombre si quieres
            ->findAll();
    }
*/



    public function getEstudiantesPorCurso($id_curso)
    {
        return $this->select('
            inscripciones.id AS id_inscripcion, 
            estudiantes.nombre, 
            estudiantes.apellido
        ')
            ->join('estudiantes', 'estudiantes.id = inscripciones.id_estudiante')
            ->join('cursos', 'cursos.id = inscripciones.id_curso') // ← ahora va directo
            ->where('cursos.id', $id_curso)
            ->where('inscripciones.activo', 1)
            ->orderBy('estudiantes.apellido', 'ASC')
            ->findAll();
    }



    public function contarEstudiantesPorEscuela($idEscuela)
    {
        return $this->select("
            COUNT(inscripciones.id_estudiante) AS total,
            SUM(CASE WHEN estudiantes.sexo = 'M' THEN 1 ELSE 0 END) AS total_masculino,
            SUM(CASE WHEN estudiantes.sexo = 'F' THEN 1 ELSE 0 END) AS total_femenino
        ")
            ->join('estudiantes', 'estudiantes.id = inscripciones.id_estudiante')
            ->join('schoolyear', 'schoolyear.id = inscripciones.id_schoolYear')
            ->where('inscripciones.id_escuela', $idEscuela)
            ->where('inscripciones.activo', 1)
            ->where('schoolyear.estado', 'En curso')
            ->get()
            ->getRowArray();
    }


    public function contarFamiliasPorEscuela($idEscuela)
    {
        return $this->db->table('inscripciones i')
            ->join('estudiantes e', 'e.id = i.id_estudiante')
            ->join('estudiantes_responsables er', 'er.estudiante_id = e.id')
            ->join('schoolyear sy', 'sy.id = i.id_schoolYear')
            ->where('i.id_escuela', $idEscuela)
            ->where('i.activo', 1)
            ->where('sy.estado', 'En curso')
            ->select('COUNT(DISTINCT er.responsable_id) AS total_familias')
            ->get()
            ->getRowArray();
    }
}

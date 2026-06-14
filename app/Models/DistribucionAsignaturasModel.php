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
        'id_personal',
        'id_asignatura',
        'id_curso',
        'id_schoolyear',
        'id_escuela'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';
    protected $deletedField = 'deleted_at';

    //  VALIDACIONES DIRECTAMENTE EN EL MODELO
    protected $validationRules = [
        'id_personal'   => 'required|integer',
        'id_asignatura' => 'required|integer',
        'id_curso'      => 'required|integer',
        'id_schoolyear'    => 'required|integer',
        'id_escuela'    => 'required|integer' // ✅ AGREGAR ESTO TAMBIÉN
    ];

    protected $validationMessages = [
        'id_personal'   => ['required' => 'Debe seleccionar un docente.'],
        'id_asignatura' => ['required' => 'Debe seleccionar una asignatura.'],
        'id_curso'      => ['required' => 'Debe seleccionar un curso.'],
        'id_schoolyear'    => ['required' => 'Debe seleccionar un período escolar.'],
        'id_escuela'    => ['required' => 'La escuela es obligatoria.'] // ✅
    ];

    /**
     *  Obtener todas las asignaciones con detalles (docente, asignatura, curso, grado, sección, período)
     */
    public function getDistribucionConDetalles($idSchoolYear = null)
    {
        $builder = $this->select('
        distribucion_asignaturas.*, 
        distribucion_asignaturas.id_personal AS id_docente,
        CONCAT(personal.nombre, " ", personal.apellido) AS docente, 
        asignatura.nombre AS asignatura, 
        cursos_base.nombre_curso AS curso, 
        schoolyear.nombre AS periodo
    ')
            ->join('personal', 'personal.id = distribucion_asignaturas.id_personal', 'left')
            ->join('asignatura', 'asignatura.id = distribucion_asignaturas.id_asignatura', 'left')
            ->join('cursos', 'cursos.id = distribucion_asignaturas.id_curso', 'left')
            ->join('cursos_base', 'cursos_base.id = cursos.id_cursos_base', 'left')
            ->join('schoolyear', 'schoolyear.id = distribucion_asignaturas.id_schoolyear', 'left');

        if (!empty($idSchoolYear)) {
            $builder->where('distribucion_asignaturas.id_schoolyear', $idSchoolYear);
        }

        return $builder
            ->orderBy('cursos_base.nombre_curso', 'ASC')
            ->orderBy('asignatura.nombre', 'ASC')
            ->findAll();
    }




    // Obtener cursos únicos para un docente
    public function getCursosPorDocente($id_docente)
    {
        return $this->select([
            'cursos.id',
            "cursos_base.nombre_curso AS text"
        ])
            ->join('cursos', 'cursos.id = distribucion_asignaturas.id_curso')
            ->join('cursos_base', 'cursos_base.id = cursos.id_cursos_base')
            ->where('distribucion_asignaturas.id_personal', $id_docente)
            ->groupBy('cursos.id')
            ->findAll();
    }

    //==========> TRAE LOS REGISTROS DEL AÑO ANTERIOR
    public function copiarDesdeAnioAnterior($idAnterior, $idActual)
    {
        $registrosAnterior = $this
            ->where('id_schoolyear', $idAnterior)
            ->where('activo', 1)
            ->findAll();

        $personalModel = new \App\Models\PersonalModel();
        $asignaturaModel = new \App\Models\AsignaturaModel();

        $insertados = 0;
        $duplicados = 0;
        $docentesInactivos = 0;
        $asignaturasInactivas = 0;

        foreach ($registrosAnterior as $registro) {

            $docente = $personalModel
                ->where('id', $registro['id_personal'])
                ->where('activo', 1)
                ->first();

            if (!$docente) {
                $docentesInactivos++;
                continue;
            }

            $asignatura = $asignaturaModel
                ->where('id', $registro['id_asignatura'])
                ->where('activo', 1)
                ->first();

            if (!$asignatura) {
                $asignaturasInactivas++;
                continue;
            }

            $existe = $this
                ->where('id_asignatura', $registro['id_asignatura'])
                ->where('id_curso', $registro['id_curso'])
                ->where('id_schoolyear', $idActual)
                ->where('id_escuela', $registro['id_escuela'])
                ->first();

            if ($existe) {
                $duplicados++;
                continue;
            }

            $this->insert([
                'id_personal'   => $registro['id_personal'],
                'id_asignatura' => $registro['id_asignatura'],
                'id_curso'      => $registro['id_curso'],
                'id_schoolyear' => $idActual,
                'id_escuela'    => $registro['id_escuela'],
                'activo'        => 1
            ]);

            $insertados++;
        }

        return [
            'insertados' => $insertados,
            'duplicados' => $duplicados,
            'docentes_inactivos' => $docentesInactivos,
            'asignaturas_inactivas' => $asignaturasInactivas
        ];
    }



    //    


    /*
    public function getAsignaturasPorDocenteCurso($id_docente, $id_curso)
    {
        return $this->select('asignatura.id, asignatura.nombre AS text')
            ->join('asignatura', 'asignatura.id = distribucion_asignaturas.id_asignatura')
            ->where('distribucion_asignaturas.id_personal', $id_docente)
            ->where('distribucion_asignaturas.id_curso', $id_curso)
            ->groupBy('asignatura.id')
            ->findAll();
    }*/


    public function getAsignaturasPorDocenteCurso($id_docente, $id_curso)
    {
        return $this->db->table('distribucion_asignaturas AS da')
            ->select("
            a.id,
            a.nombre AS text,
            a.tipo_asignatura,
            (a.tipo_asignatura = 2) AS es_tecnica,
            da.id AS id_distribucion_asignatura
        ", false)
            ->join('asignatura AS a', 'a.id = da.id_asignatura') // usa 'asignaturas' si tu tabla es en plural
            ->where('da.id_personal', $id_docente)               // o da.id_docente si así se llama en tu esquema
            ->where('da.id_curso',   $id_curso)
            //->where('a.activo', 1)
            ->groupBy('a.id, a.nombre, a.tipo_asignatura, da.id')
            ->orderBy('a.nombre', 'ASC')
            ->get()
            ->getResultArray();
    }





    // Obtener asignaturas por docente y curso
    /*public function getAsignaturasPorDocenteCurso($id_docente, $id_curso)
{
    return $this->select('asignatura.id, asignatura.nombre')
        ->join('asignatura', 'asignatura.id = distribucion_asignaturas.id_asignatura')
        ->where('distribucion_asignaturas.id_personal', $id_docente)
        ->where('distribucion_asignaturas.id_curso', $id_curso)
        ->groupBy('asignatura.id')
        ->findAll();
}


public function getDatosPorDocente($id_docente)
{
    return $this->select('
                distribucion_asignaturas.id AS id_distribucion,
                cursos.id AS id_curso, cursos.nombreCurso,
                asignatura.id AS id_asignatura, asignatura.nombre AS nombre_asignatura
            ')
        ->join('cursos', 'cursos.id = distribucion_asignaturas.id_curso')
        ->join('asignatura', 'asignatura.id = distribucion_asignaturas.id_asignatura')
        ->where('distribucion_asignaturas.id_personal', $id_docente)
        ->findAll();
}*/
}

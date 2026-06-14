<?php

namespace App\Models;

use CodeIgniter\Model;

class CursosModel extends Model
{
    protected $table = 'cursos';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id_cursos_base', 'id_schoolyear', 'capacidad', 'tipo_aula', 'id_servicio', 'activo'];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function getCursosPorEscuela($idEscuela, $idSchoolYear = null, $idGrado = null)
    {
        $db = $this->db;

        // 1️ Obtener los servicios activos de la escuela
        $servicios = $db->table('escuelas_servicios')
            ->select('id_servicio')
            ->where('id_escuela', $idEscuela)
            ->where('activo', 1)
            ->get()
            ->getResultArray();

        if (empty($servicios)) {
            return [];
        }

        $idsServicios = array_column($servicios, 'id_servicio');

        // 2️ Obtener cursos activos de esos servicios y del año lectivo solicitado
        $builder = $db->table('cursos')
            ->select('
            cursos.id,
            cursos.id_cursos_base,
            cursos_base.nombre_curso,
            cursos.capacidad,
            cursos.id_servicio,
            COUNT(inscripciones.id) AS inscritos
        ')
            ->join('cursos_base', 'cursos_base.id = cursos.id_cursos_base', 'inner')
            ->join('inscripciones', 'inscripciones.id_curso = cursos.id AND inscripciones.activo = 1', 'left')
            ->whereIn('cursos.id_servicio', $idsServicios)
            ->where('cursos.activo', 1)
            ->groupBy('cursos.id');

        // Filtrar por año lectivo
        if ($idSchoolYear) {
            $builder->where('cursos.id_schoolyear', $idSchoolYear);
        }

        // Filtrar por grado, si se proporciona
        if ($idGrado) {
            $builder->where('cursos_base.id_grado', $idGrado);
        }

        // Solo cursos que aún tengan cupo
        $builder->having('inscritos < cursos.capacidad');

        return $builder->get()->getResultArray();
    }

    public function getCursosParaDocenteGuia($idEscuela, $idSchoolYear = null, $idGrado = null)
    {
        $db = $this->db;

        // Servicios activos
        $servicios = $db->table('escuelas_servicios')
            ->select('id_servicio')
            ->where('id_escuela', $idEscuela)
            ->where('activo', 1)
            ->get()
            ->getResultArray();

        if (empty($servicios)) {
            return [];
        }

        $idsServicios = array_column($servicios, 'id_servicio');

        // Cursos + información de docente guía (si ya existe)
        $builder = $db->table('cursos')
            ->select('
            cursos.id,
            cursos.id_cursos_base,
            cursos_base.nombre_curso,
            cursos.capacidad,
            cursos.id_servicio,
            docentes_guia.id AS id_docente_guia,
            docentes_guia.id_personal
        ')
            ->join('cursos_base', 'cursos_base.id = cursos.id_cursos_base', 'inner')
            ->join('docentes_guia', 'docentes_guia.id_curso = cursos.id AND docentes_guia.activo = 1', 'left')
            ->whereIn('cursos.id_servicio', $idsServicios)
            ->where('cursos.activo', 1);

        if ($idSchoolYear) {
            $builder->where('cursos.id_schoolyear', $idSchoolYear);
        }

        if ($idGrado) {
            $builder->where('cursos_base.id_grado', $idGrado);
        }

        return $builder->get()->getResultArray();
    }


    public function contarCursosDisponibles($id_escuela, $id_schoolyear)
    {
        return $this->db->table('cursos')
            ->join('cursos_base', 'cursos_base.id = cursos.id_cursos_base')
            ->where('cursos.id_schoolyear', $id_schoolyear)
            ->where('cursos.activo', 1)
            ->where('cursos_base.id_escuela', $id_escuela)
            ->countAllResults();
    }


    public function obtenerCursoDisponible($id_grado, $id_servicio, $id_schoolyear)
    {
        $cursos = $this->select('cursos.*, cursos_base.nombre_curso')
            ->join('cursos_base', 'cursos_base.id = cursos.id_cursos_base')
            ->where('id_grado', $id_grado)
            ->where('id_servicio', $id_servicio)
            ->where('id_schoolyear', $id_schoolyear)
            ->where('activo', 1)
            ->orderBy('id_cursos_base', 'ASC')
            ->findAll();

        $resultado = [];
        foreach ($cursos as $curso) {
            $inscritos = $this->db->table('inscripciones')
                ->where('id_curso', $curso['id'])
                ->where('activo', 1)
                ->where('id_schoolyear', $id_schoolyear)
                ->countAllResults();

            $curso['disponibilidad'] = $curso['capacidad'] - $inscritos;
            $resultado[] = $curso;
        }

        return $resultado; // ahora la vista puede filtrar
    }

//CONSULTA QUE TRAE LOS CURSOS YA CONFIGURADOS
    public function obtenerCursosConfigurados($id_escuela, $id_schoolyear, $id_servicio = null, $nombre_salida = null)
    {
        $builder = $this->db->table('cursos');

        $builder->select('
        cursos.*,
        cursos_base.nombre_curso,
        schoolyear.nombre AS schoolyear
    ');

        $builder->join('cursos_base', 'cursos_base.id = cursos.id_cursos_base');
        $builder->join('schoolyear', 'schoolyear.id = cursos.id_schoolyear');

        $builder->where('cursos_base.id_escuela', $id_escuela);
        $builder->where('cursos.id_schoolyear', $id_schoolyear);

        if (!empty($id_servicio)) {
            $builder->where('cursos_base.id_servicio', $id_servicio);
        }

        if (!empty($nombre_salida)) {
            $builder->like('cursos_base.nombre_curso', $nombre_salida);
        }

        return $builder
            ->orderBy('cursos.id', 'ASC')
            ->get()
            ->getResultArray();
    }
}

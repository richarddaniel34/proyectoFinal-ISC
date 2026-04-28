<?php

namespace App\Models;

use CodeIgniter\Model;

class CursosBaseModel extends Model
{
    protected $table = 'cursos_base';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id_grado', 'id_seccion', 'id_escuela', 'id_servicio', 'nombre_curso', 'codigo_curso', 'activo'];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';
    //protected $deletedField = 'deleted_at';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function obtenerCursosBasePorServicio($id_escuela, $id_servicio)
    {
        return $this->select('
        cursos_base.id,
        cursos_base.id_grado,
        cursos_base.id_seccion,
        cursos_base.id_escuela,
        cursos_base.id_servicio,
        cursos_base.nombre_curso,
        cursos_base.codigo_curso,
        cursos_base.activo,
        grados.nombre AS grado,
        secciones.letra AS seccion,
        escuela.nombre AS escuela')
            ->join('grados', 'grados.id = cursos_base.id_grado')
            ->join('secciones', 'secciones.id = cursos_base.id_seccion')
            ->join('escuela', 'escuela.id = cursos_base.id_escuela')
            ->where('cursos_base.id_escuela', $id_escuela)
            ->where('cursos_base.id_servicio', $id_servicio)
            ->orderBy('cursos_base.id_grado', 'ASC')
            ->orderBy('cursos_base.id_seccion', 'ASC')
            ->findAll();
    }





    public function buscarPorGradoYSeccion($id_grado, $id_seccion)
    {
        return $this->where('id_grado', $id_grado)
            ->where('id_seccion', $id_seccion)
            ->findAll();
    }

    public function cursosPorEscuela($id_escuela)
    {
        return $this->where('id_escuela', $id_escuela)
            ->orderBy('id_grado', 'ASC')
            ->findAll();
    }


    //Obtener todos los cursos con datos relacionados
    public function obtenerCursosDetallado()
    {
        return $this->select('cursos_base.*, grados.nombre AS grado, secciones.letra AS seccion, escuela.nombre AS escuela')
            ->join('grados', 'grados.id = cursos_base.id_grado')
            ->join('secciones', 'secciones.id = cursos_base.id_seccion')
            ->join('escuela', 'escuela.id = cursos_base.id_escuela')
            ->orderBy('id_grado', 'ASC')
            ->findAll();
    }


    //VERIFICAR SI EXISTE UN CURSO (EVITAR DUPLICADOS)
    public function existeCurso($id_grado, $id_seccion, $id_escuela)
    {
        return $this->where([
            'id_grado' => $id_grado,
            'id_seccion' => $id_seccion,
            'id_escuela' => $id_escuela,
            'activo' => 1
        ])->first();
    }

    //obtener los cursos base por escuela
    public function obtenerCursosBasePorEscuela($id_escuela)
    {
        return $this->select('cursos_base.*, grados.nombre AS grado, secciones.letra AS seccion, escuela.nombre AS escuela')
            ->join('grados_niveles', 'grados_niveles.id = cursos_base.id_grado')
            ->join('grados', 'grados.id = grados_niveles.id_grado') // join a tabla grados
            ->join('secciones', 'secciones.id = cursos_base.id_seccion')
            ->join('escuela', 'escuela.id = cursos_base.id_escuela')
            ->where('cursos_base.id_escuela', $id_escuela)
            ->orderBy('grados_niveles.id_grado', 'ASC')
            ->findAll();
    }


    public function contarSeccionesPorEscuela($id_escuela)
    {
        return $this->where('id_escuela', $id_escuela)->countAllResults();
    }
}

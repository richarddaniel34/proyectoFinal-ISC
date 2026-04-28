<?php

namespace App\Models;

use CodeIgniter\Model;

class DocenteGuiaModel extends Model
{
    protected $table = 'docentes_guia';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id_curso',
        'id_personal',
        'id_schoolyear',
        'activo'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';

    // Validaciones ajustadas a los campos reales
    protected $validationRules = [
        'id_personal'   => 'integer',
        'id_curso'      => 'integer',
        'id_schoolyear' => 'integer',
    ];

    protected $validationMessages = [];


    // En DocenteGuiaModel.php
    public function getAsignacionesConDetalles($idSchoolYear = null)
    {
        $builder = $this->distinct()
            ->select('
            dg.id,
            dg.id_personal,
            dg.id_curso,
            dg.id_schoolyear,
            CONCAT(p.nombre, " ", p.apellido) as nombre_personal,
            cb.nombre_curso,
            s.nombre as nombre_schoolyear,
            e.nombre as nombre_escuela')
            ->from('docentes_guia dg')
            ->join('personal p', 'p.id = dg.id_personal')
            ->join('escuela e', 'e.id = p.id_escuela')
            ->join('cursos c', 'c.id = dg.id_curso')
            ->join('cursos_base cb', 'cb.id = c.id_cursos_base')
            ->join('schoolyear s', 's.id = dg.id_schoolyear')
            ->orderBy('e.nombre, cb.nombre_curso');

        if ($idSchoolYear !== null) {
            $builder->where('dg.id_schoolyear', $idSchoolYear);
        }

        return $builder->findAll();
    }

    public function getAsignacionConDetallesPorId($id)
    {
        return $this->db->table('docentes_guia dg')
            ->select('
            dg.id,
            dg.id_personal,
            dg.id_curso,
            dg.id_schoolyear,
            CONCAT(p.nombre, " ", p.apellido) as nombre_personal,
            cb.nombre_curso,
            s.nombre as nombre_schoolyear,
            e.nombre as nombre_escuela
        ')
            ->join('personal p', 'p.id = dg.id_personal')
            ->join('escuela e', 'e.id = p.id_escuela')
            ->join('cursos c', 'c.id = dg.id_curso')
            ->join('cursos_base cb', 'cb.id = c.id_cursos_base')
            ->join('schoolyear s', 's.id = dg.id_schoolyear')
            ->where('dg.id', $id)
            ->get()
            ->getRowArray();
    }
}

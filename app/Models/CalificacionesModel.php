<?php

namespace App\Models;

use CodeIgniter\Model;

// Modelo de Calificaciones
class CalificacionesModel extends Model
{
    protected $table = 'calificaciones';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'id_inscripcion',
        'id_distribucion_asignatura',
        'id_competencia',
        'id_periodo',
        'nota'
    ];

    protected $validationRules = [
        'id_inscripcion' => 'required|integer',
        'id_distribucion_asignatura'  => 'required|integer',
        'id_competencia' => 'required|integer',
        'id_periodo'     => 'required|integer',
        'nota'           => 'required|decimal'
    ];


    public function guardarOActualizar($data)
    {
        $builder = $this->db->table('calificaciones');

        // Verifica si ya existe un registro para esa inscripción, asignatura, competencia y periodo
        $registro = $builder
            ->where('id_inscripcion', $data['id_inscripcion'])
            ->where('id_distribucion_asignatura', $data['id_distribucion_asignatura'])
            ->where('id_competencia', $data['id_competencia'])
            ->where('id_periodo', $data['id_periodo'])
            ->get()
            ->getRow();

        if ($registro) {
            $builder
                ->where('id_inscripcion', $data['id_inscripcion'])
                ->where('id_distribucion_asignatura', $data['id_distribucion_asignatura'])
                ->where('id_competencia', $data['id_competencia'])
                ->where('id_periodo', $data['id_periodo'])
                ->update($data);
        } else {
            $builder->insert($data);
        }
    }
}

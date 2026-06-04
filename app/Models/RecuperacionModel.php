<?php

namespace App\Models;

use CodeIgniter\Model;

// Modelo de Calificaciones
class RecuperacionModel extends Model
{
    protected $table = 'recuperaciones_pedagogicas';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'id_inscripcion',
        'id_distribucion_asignatura',
        'id_periodo',
        'id_competencia',
        'nota_rp'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';


    protected $validationRules = [
        'id_inscripcion' => 'required|integer',
        'id_distribucion_asignatura'  => 'required|integer',
        'id_competencia' => 'required|integer',
        'id_periodo'     => 'required|integer',
        'nota_rp'           => 'required|decimal'
    ];


    public function guardarOActualizar($data)
    {
        $builder = $this->db->table($this->table);

        $registro = $builder
            ->where('id_inscripcion', $data['id_inscripcion'])
            ->where('id_distribucion_asignatura', $data['id_distribucion_asignatura'])
            ->where('id_competencia', $data['id_competencia'])
            ->where('id_periodo', $data['id_periodo'])
            ->get()
            ->getRowArray();

        if ($registro) {
            return $builder
                ->where('id', $registro['id'])
                ->update($data);
        }

        return $builder->insert($data);
    }
}

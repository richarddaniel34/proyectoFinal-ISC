<?php

namespace App\Models;

use CodeIgniter\Model;

class EvaluacionesFinalesTecnicasModel extends Model
{
    protected $table            = 'evaluaciones_finales_tecnicas';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'id_inscripcion',
        'id_distribucion_asignatura',
        'id_schoolyear',
        'total',
        'aprobado_especial',
        'aprobado',
        'reprobado',
        'updated_by',
        'fecha_alta',
        'fecha_edit',
    ];

    protected $useTimestamps = false;

    public function buscarResumen($idInscripcion, $idDistribucion, $idSchoolYear)
    {
        return $this->where('id_inscripcion', $idInscripcion)
            ->where('id_distribucion_asignatura', $idDistribucion)
            ->where('id_schoolyear', $idSchoolYear)
            ->first();
    }

    public function guardarOActualizar($data)
    {
        $existente = $this->buscarResumen(
            $data['id_inscripcion'],
            $data['id_distribucion_asignatura'],
            $data['id_schoolyear']
        );

        if ($existente) {
            $data['fecha_edit'] = date('Y-m-d H:i:s');
            return $this->update($existente['id'], $data);
        }

        $data['fecha_alta'] = date('Y-m-d H:i:s');
        return $this->insert($data);
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class CalificacionesTecnicasModel extends Model
{
    protected $table            = 'calificaciones_tecnicas';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'id_inscripcion',
        'id_distribucion_asignatura',
        'id_schoolyear',
        'id_periodo',
        'id_ra_configuracion',
        'cra',
        'rp1',
        'rp2',
        'updated_by',
        'fecha_alta',
        'fecha_edit',
    ];

    protected $useTimestamps = false;

    public function getNotasPorDistribucion($idDistribucion, $idSchoolYear, $idPeriodo)
    {
        return $this->where('id_distribucion_asignatura', $idDistribucion)
            ->where('id_schoolyear', $idSchoolYear)
            ->where('id_periodo', $idPeriodo)
            ->findAll();
    }

    public function buscarNota($idInscripcion, $idDistribucion, $idSchoolYear, $idPeriodo, $idRaConfiguracion)
    {
        return $this->where('id_inscripcion', $idInscripcion)
            ->where('id_distribucion_asignatura', $idDistribucion)
            ->where('id_schoolyear', $idSchoolYear)
            ->where('id_periodo', $idPeriodo)
            ->where('id_ra_configuracion', $idRaConfiguracion)
            ->first();
    }

    public function guardarOActualizar($data)
    {
        $existente = $this->buscarNota(
            $data['id_inscripcion'],
            $data['id_distribucion_asignatura'],
            $data['id_schoolyear'],
            $data['id_periodo'],
            $data['id_ra_configuracion']
        );

        if ($existente) {
            $data['fecha_edit'] = date('Y-m-d H:i:s');
            return $this->update($existente['id'], $data);
        }

        $data['fecha_alta'] = date('Y-m-d H:i:s');
        return $this->insert($data);
    }


    public function getNotasPeriodo(
        $idDistribucion,
        $idSchoolYear,
        $idPeriodo
    ) {
        return $this->where('id_distribucion_asignatura', $idDistribucion)
            ->where('id_schoolyear', $idSchoolYear)
            ->where('id_periodo', $idPeriodo)
            ->findAll();
    }
}

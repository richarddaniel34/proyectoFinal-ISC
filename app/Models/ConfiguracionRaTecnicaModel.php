<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfiguracionRaTecnicaModel extends Model
{
    protected $table            = 'configuracion_ra_tecnica';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'id_distribucion_asignatura',
        'id_schoolyear',
        'numero_ra',
        'valor_ra',
        'minimo_ra',
        'activo',
        'created_by',
        'updated_by',
        'fecha_alta',
        'fecha_edit',
    ];

    protected $useTimestamps = false;

    public function getRasConfigurados($idDistribucion, $idSchoolYear)
    {
        return $this->where('id_distribucion_asignatura', $idDistribucion)
            ->where('id_schoolyear', $idSchoolYear)
            ->where('activo', 1)
            ->orderBy('numero_ra', 'ASC')
            ->findAll();
    }

    public function guardarConfiguracionRa($data)
    {
        return $this->db->table($this->table)->upsert($data);
    }

    public function existeRa($idDistribucion, $idSchoolYear, $numeroRa)
    {
        return $this->where('id_distribucion_asignatura', $idDistribucion)
            ->where('id_schoolyear', $idSchoolYear)
            ->where('numero_ra', $numeroRa)
            ->first();
    }
}

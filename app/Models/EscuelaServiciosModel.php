<?php

namespace App\Models;

use CodeIgniter\Model;

class EscuelaServiciosModel extends Model
{
    protected $table = 'escuelas_servicios';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id_escuela', 'id_servicio', 'activo'];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;


    public function getServiciosPorEscuela_activos($id_escuela)
    {
        return $this->select('
            servicios.id,
            servicios.nombre AS servicio_nombre,
            salidas_tecnicas.nombre AS salida_tecnica')
            ->join('servicios', 'servicios.id = escuelas_servicios.id_servicio')
            ->join('salidas_tecnicas', 'salidas_tecnicas.id_servicio = servicios.id', 'left')
            ->where('escuelas_servicios.id_escuela', $id_escuela)
            ->where('escuelas_servicios.activo', 1)
            ->findAll();
    }
}

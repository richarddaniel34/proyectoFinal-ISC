<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfigPeriodosModel extends Model
{
    protected $table = 'periodos_configuracion_usuario';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_distribucion_asignatura',
        'id_personal',
        'id_periodo',
        'bloqueado',
        'fecha_configuracion'
    ];

    protected $useTimestamps = false;

    // Método personalizado
    public function updateOrInsert(array $where, array $data)
    {
        $registro = $this->where($where)->first();

        if ($registro) {
            return $this->update($registro[$this->primaryKey], $data);
        } else {
            return $this->insert(array_merge($where, $data));
        }
    }
}

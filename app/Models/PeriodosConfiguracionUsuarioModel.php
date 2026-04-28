<?php

namespace App\Models;

use CodeIgniter\Model;

// Archivo: PeriodosConfiguracionUsuarioModel.php

namespace App\Models;

use CodeIgniter\Model;

class PeriodosConfiguracionUsuarioModel extends Model
{
    protected $table            = 'periodos_configuracion_usuario';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'id_distribucion_asignatura',
        'id_personal',
        'id_periodo',
        'visible',
        'bloqueado',
        'fecha_configuracion'
    ];
    protected $useTimestamps    = false;
    protected $returnType       = 'array';

    public function updateOrInsert(array $where, array $data)
    {
        $existing = $this->where($where)->first();
        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            return $this->insert(array_merge($where, $data));
        }
    }
}

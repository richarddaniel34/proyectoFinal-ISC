<?php

namespace App\Models;

use CodeIgniter\Model;

class ConceptoPagosConfigModel extends Model
{
    protected $table = 'concepto_pagos_config';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id_concepto',
        'id_schoolYear',
        'mes',
        'monto',
        'mora',
        'descuento',
        'estado'
    ];


    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';
    protected $deletedField = 'deleted_at';

   
}

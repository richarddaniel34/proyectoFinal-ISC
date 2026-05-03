<?php

namespace App\Models;

use CodeIgniter\Model;

class MesesModel extends Model
{
    protected $table = 'meses';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'nombre',
        'numero',
        'orden',
        'estado'
    ];


    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';
    protected $deletedField = 'deleted_at';

   
}

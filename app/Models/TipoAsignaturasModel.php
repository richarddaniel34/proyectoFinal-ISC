<?php

namespace App\Models;

use CodeIgniter\Model;

class DistribucionAsignaturasModel extends Model
{
    protected $table = 'tipo_asignatura';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'nombre',
        
    ];

   // protected $useTimestamps = true;
   // protected $createdField = 'fecha_alta';
   // protected $updatedField = 'fecha_edit';
   // protected $deletedField = 'deleted_at';

    
}

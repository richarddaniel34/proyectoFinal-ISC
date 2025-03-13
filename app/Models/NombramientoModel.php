<?php

namespace App\Models;

use CodeIgniter\Model;

class NombramientoModel extends Model
{
    protected $table = 'nombramiento';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['nombre'];
}

?>
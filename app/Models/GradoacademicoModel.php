<?php

namespace App\Models;

use CodeIgniter\Model;

class GradoacademicoModel extends Model
{
    protected $table = 'grado_academico';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['grado_academico'];
}

?>

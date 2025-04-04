<?php

namespace App\Models;

use CodeIgniter\Model;

// Modelo de Periodos
class PeriodosModel extends Model
{
    protected $table = 'periodos';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['nombre'];
    
    protected $validationRules = [
        'nombre' => 'required|min_length[3]'
    ];
}

?>
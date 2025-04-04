<?php

namespace App\Models;

use CodeIgniter\Model;

// Modelo de Competencias
class CompetenciasModel extends Model
{
    protected $table = 'competencias';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['nombre'];
    
    protected $validationRules = [
        'nombre' => 'required|min_length[3]'
    ];
}

?>
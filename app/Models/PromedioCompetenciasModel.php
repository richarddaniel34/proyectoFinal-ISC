<?php

namespace App\Models;

use CodeIgniter\Model;

// Modelo de Promedio por Competencias
class PromedioCompetenciasModel extends Model
{
    protected $table = 'promedio_competencias';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'id_inscripcion',
        'id_distribucuion_asignatura',
        'id_periodo',
        'promedio'
    ];
    
    protected $validationRules = [
        'id_inscripcion' => 'required|integer',
        'id_distribucuion_asignatura'  => 'required|integer',
        'id_periodo'     => 'required|integer',
        'promedio'       => 'required|decimal'
    ];
}


?>
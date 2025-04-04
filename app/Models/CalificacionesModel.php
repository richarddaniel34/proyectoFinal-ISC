<?php

namespace App\Models;

use CodeIgniter\Model;

// Modelo de Calificaciones
class CalificacionesModel extends Model
{
    protected $table = 'calificaciones';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'id_inscripcion',
        'id_distribucion_asignatura',
        'id_competencia',
        'id_periodo',
        'nota'
    ];
    
    protected $validationRules = [
        'id_inscripcion' => 'required|integer',
        'id_distribucion_asignatura'  => 'required|integer',
        'id_competencia' => 'required|integer',
        'id_periodo'     => 'required|integer',
        'nota'           => 'required|decimal'
    ];
}


?>
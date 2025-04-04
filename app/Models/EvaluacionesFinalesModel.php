<?php

namespace App\Models;

use CodeIgniter\Model;

// Modelo de Evaluaciones Finales
class EvaluacionesFinalesModel extends Model
{
    protected $table = 'evaluaciones_finales';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'id_inscripcion',
        'id_distribucion_asignatura',
        'calificacion_final',
        'calif_completiva',
        'calif_extraordinaria',
        'calif_especial',
        'situacion_asignatura'
    ];
    
    protected $validationRules = [
        'id_inscripcion'       => 'required|integer',
        'id_distribucion_asignatura'        => 'required|integer',
        'calificacion_final'   => 'permit_empty|decimal',
        'calif_completiva'     => 'permit_empty|decimal',
        'calif_extraordinaria' => 'permit_empty|decimal',
        'calif_especial'       => 'permit_empty|decimal',
        'situacion_asignatura' => 'required|in_list[A,R]'
    ];
}


?>
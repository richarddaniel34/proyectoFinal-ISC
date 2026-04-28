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
        'id_distribucion_asignatura',
        'id_periodo',
        'id_competencia',
        'promedio'
    ];

    protected $validationRules = [
        'id_inscripcion' => 'required|integer',
        'id_distribucion_asignatura'  => 'required|integer',
        'id_periodo'     => 'required|integer',
        'promedio'       => 'required|decimal'
    ];





    public function insertarOActualizar($data)
    {
        log_message('debug', 'insertarOActualizar data: ' . print_r($data, true));

        $registroExistente = $this->where([
            'id_inscripcion'             => $data['id_inscripcion'],
            'id_distribucion_asignatura' => $data['id_distribucion_asignatura'],
            'id_periodo'                 => $data['id_periodo'],
            'id_competencia'             => $data['id_competencia']
        ])->first();

        if ($registroExistente) {
            log_message('debug', 'Actualizando promedio_competencia ID: ' . $registroExistente['id']);
            return $this->update($registroExistente['id'], $data);
        } else {
            log_message('debug', 'Insertando nuevo promedio_competencia');
            return $this->insert($data);
        }
    }
}

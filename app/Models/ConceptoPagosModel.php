<?php

namespace App\Models;

use CodeIgniter\Model;

class ConceptoPagosModel extends Model
{
    protected $table = 'concepto_pagos';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $allowedFields = ['nombre', 'monto', 'activo'];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'nombre' => 'required|min_length[3]',
        'monto'  => 'required|decimal',
    ];

    protected $validationMessages = [
        'nombre' => ['required' => 'El nombre del concepto es obligatorio.'],
        'monto'  => ['required' => 'Debe ingresar un monto válido.'],
    ];

    // 🔥 Devuelve solo los conceptos activos
    public function getConceptosActivos()
    {
        return $this->where('activo', 1)->findAll();
    }


    // 🔥 Devuelve el monto de un concepto específico
    public function getMontoPorConcepto($id)
    {
        return $this->select('monto')->where('id', $id)->first();
    }

    
}

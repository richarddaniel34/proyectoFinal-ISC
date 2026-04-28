<?php

namespace App\Models;

use CodeIgniter\Model;

class SchoolyearModel extends Model
{
    protected $table = 'schoolyear';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nombre', 'fecha_inicio', 'fecha_termino', 'codigo', 'estado', 'activo'];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;


    public function getEnCurso()
    {
        return $this->where('estado', 'En curso')->orderBy('fecha_inicio', 'DESC')->findAll();
    }

    public function getEnEspera()
    {
        return $this->where('estado', 'En espera')->orderBy('fecha_inicio', 'ASC')->findAll();
    }

    public function getFinalizados()
    {
        return $this->where('estado', 'Finalizado')->orderBy('fecha_termino', 'DESC')->findAll();
    }

    public function getTodosActivos()
    {
        return $this->where('activo', 1)->orderBy('fecha_inicio', 'DESC')->findAll();
    }
}

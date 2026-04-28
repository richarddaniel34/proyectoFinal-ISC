<?php

namespace App\Models;

use CodeIgniter\Model;

class SeccionesModel extends Model
{
    protected $table = 'secciones';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['letra', 'activo'];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function obtenerActivas()
    {
        return $this->where('activo', 1)->orderBy('letra', 'ASC')->findAll();
    }
}

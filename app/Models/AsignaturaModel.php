<?php

    namespace App\Models;
    use CodeIgniter\Model;
    
    class AsignaturaModel extends Model
    {
        protected $table = 'asignatura';
        protected $primaryKey = 'id';
        
        protected $returnType = 'array';
        protected $useSoftDeletes = false;
        
        protected $allowedFields = ['nombre', 'codigo_asignatura', 'tipo_asignatura', 'activo'];
        
        protected $useTimestamps = true;
        protected $createdField = 'fecha_alta';
        protected $updatedField = 'fecha_edit';
        protected $deletedField = 'deleted_at';
        
        protected $validationRules = [];
        protected $validationMessages = [];
        protected $skipValidation = false;
    }
 
?>
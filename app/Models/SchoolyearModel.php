<?php

    namespace App\Models;
    use CodeIgniter\Model;
    
    class SchoolyearModel extends Model
    {
        protected $table = 'schoolyear';
        protected $primaryKey = 'id';
        
        protected $returnType = 'array';
        protected $useSoftDeletes = false;
        
        protected $allowedFields = ['nombre','fecha_inicio','fecha_termino','codigo', 'activo'];
        
        protected $useTimestamps = true;
        protected $createdField = 'fecha_alta';
        protected $updatedField = 'fecha_edit';
        protected $deletedField = 'deleted_at';
        
        protected $validationRules = [];
        protected $validationMessages = [];
        protected $skipValidation = false;
    }
 
?>
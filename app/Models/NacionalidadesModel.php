<?php

    namespace App\Models;
    use CodeIgniter\Model;
    
    class NacionalidadesModel extends Model
    {
        protected $table = 'nacionalidades';
        protected $primaryKey = 'id';
        
        protected $returnType = 'array';
        protected $useSoftDeletes = false;
        
        protected $allowedFields = ['pais', 'gentilicio', 'iso'];
        
        protected $useTimestamps = true;
        protected $createdField = 'fecha_alta';
        protected $updatedField = 'fecha_edit';
        protected $deletedField = 'deleted_at';
        
        protected $validationRules = [];
        protected $validationMessages = [];
        protected $skipValidation = false;
    }
 
?>
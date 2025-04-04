<?php

    namespace App\Models;
    use CodeIgniter\Model;
    
    class ProvinciasModel extends Model
    {
        protected $table = 'provincias';
        protected $primaryKey = 'provincia_id';
        
        protected $returnType = 'array';
        protected $useSoftDeletes = false;
        
        protected $allowedFields = ['nombre'];
        
        protected $useTimestamps = true;
        protected $createdField = 'fecha_alta';
        protected $updatedField = 'fecha_edit';
        protected $deletedField = 'deleted_at';
        
        protected $validationRules = [];
        protected $validationMessages = [];
        protected $skipValidation = false;
    }
 
?>
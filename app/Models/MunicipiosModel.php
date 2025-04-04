<?php

    namespace App\Models;
    use CodeIgniter\Model;
    
    class MunicipiosModel extends Model
    {
        protected $table = 'municipios';
        protected $primaryKey = 'municipio_id';
        
        protected $returnType = 'array';
        protected $useSoftDeletes = false;
        
        protected $allowedFields = ['nombre','provincia_id'];
        
        protected $useTimestamps = true;
        protected $createdField = 'fecha_alta';
        protected $updatedField = 'fecha_edit';
        protected $deletedField = 'deleted_at';
        
        protected $validationRules = [];
        protected $validationMessages = [];
        protected $skipValidation = false;
    }
 
?>
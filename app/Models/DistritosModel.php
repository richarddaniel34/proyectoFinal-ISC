<?php

    namespace App\Models;
    use CodeIgniter\Model;
    
    class DistritosModel extends Model
    {
        protected $table = 'distritos_municipales';
        protected $primaryKey = 'distrito_id';
        
        protected $returnType = 'array';
        protected $useSoftDeletes = false;
        
        protected $allowedFields = ['nombre','municipio_id'];
        
        protected $useTimestamps = true;
        protected $createdField = 'fecha_alta';
        protected $updatedField = 'fecha_edit';
        protected $deletedField = 'deleted_at';
        
        protected $validationRules = [];
        protected $validationMessages = [];
        protected $skipValidation = false;
    }
 
?>
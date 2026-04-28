<?php

    namespace App\Models;
    use CodeIgniter\Model;
    
    class SalidasTecnicasModel extends Model
    {
        protected $table = 'salidas_tecnicas';
        protected $primaryKey = 'id';
        
        protected $returnType = 'array';
        protected $useSoftDeletes = false;
        
        protected $allowedFields = ['id_servicio', 'nombre', 'familia', 'activo'];
        
        protected $useTimestamps = true;
        protected $createdField = 'fecha_alta';
        protected $updatedField = 'fecha_edit';
        protected $deletedField = 'deleted_at';
        
        protected $validationRules = [];
        protected $validationMessages = [];
        protected $skipValidation = false;
    }
 
?>
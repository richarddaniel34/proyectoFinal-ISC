<?php

    namespace App\Models;
    use CodeIgniter\Model;
    
    class DistritoEducativoModel extends Model
    {
        protected $table = 'distrito_educativo';
        protected $primaryKey = 'id';
        
        protected $returnType = 'array';
        protected $useSoftDeletes = false;
        
        protected $allowedFields = ['regional_educacion', 'distrito_educativo', 'director_distrito', 'tecnico_acreditacion','telefono'];
        
        protected $useTimestamps = true;
        protected $createdField = 'fecha_alta';
        protected $updatedField = 'fecha_edit';
        protected $deletedField = 'deleted_at';
        
        protected $validationRules = [];
        protected $validationMessages = [];
        protected $skipValidation = false;
    }
 
?>
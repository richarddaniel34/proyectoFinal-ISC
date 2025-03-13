<?php

    namespace App\Models;
    use CodeIgniter\Model;
    
    class ResponsablesModel extends Model
    {
        protected $table = 'responsables';
        protected $primaryKey = 'id';
        
        protected $returnType = 'array';
        protected $useSoftDeletes = false;
        
        protected $allowedFields = ['nombre_padre','apellido_padre','cedula_padre','telefono_padre','direccion_padre','trabajo_padre','telefono_trabajo_padre',
                                    'nombre_madre','apellido_madre','cedula_madre','telefono_madre','direccion_madre','trabajo_madre','telefono_trabajo_madre',
                                    'nombre_tutor','apellido_tutor','cedula_tutor','telefono_tutor','direccion_tutor','trabajo_tutor','telefono_trabajo_tutor', 'activo'];
        
        protected $useTimestamps = true;
        protected $createdField = 'fecha_alta';
        protected $updatedField = 'fecha_edit';
        protected $deletedField = 'deleted_at';
        
        protected $validationRules = [];
        protected $validationMessages = [];
        protected $skipValidation = false;
    }
 
?>
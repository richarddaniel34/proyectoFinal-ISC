<?php

    namespace App\Models;
    use CodeIgniter\Model;
    
    class CursosModel extends Model
    {
        protected $table = 'cursos';
        protected $primaryKey = 'id';
        
        protected $returnType = 'array';
        protected $useSoftDeletes = false;
        
        protected $allowedFields = ['id_grado','id_secciones','curso','nombreCurso','codigoCurso', 'activo'];
        
        protected $useTimestamps = true;
        protected $createdField = 'fecha_alta';
        protected $updatedField = 'fecha_edit';
        protected $deletedField = 'deleted_at';
        
        protected $validationRules = [];
        protected $validationMessages = [];
        protected $skipValidation = false;

        public function getCursosPorEscuela($codigoEscuela)
        {
            if ($codigoEscuela === '07243') {
                // 🔥 Si el código de la escuela es 07243 (Primaria), solo traer cursos que contengan "Primaria"
                return $this->where("nombreCurso LIKE '%Primaria%'")->findAll();
            } elseif ($codigoEscuela === '15661') {
                // 🔥 Si el código de la escuela es 15661 (Secundaria), solo traer cursos que contengan "Secundaria"
                return $this->where("nombreCurso LIKE '%Secundaria%'")->findAll();
            } else {
                // 🔥 Si no se reconoce el código, no devolver cursos
                return [];
            }
        }




    }



 
?>
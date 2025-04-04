<?php

namespace App\Models;

use CodeIgniter\Model;

class EstudiantesResponsablesModel extends Model
{
    protected $table = 'estudiantes_responsables';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['estudiante_id', 'responsable_id', 'parentesco'];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;



    public function getEnumValues($table, $field)
    {
        $query = $this->db->query("SHOW COLUMNS FROM `$table` LIKE '$field'");
        $row = $query->getRow();

        // Verifica que exista y que sea ENUM
        if ($row && preg_match("/^enum\('(.*)'\)$/", $row->Type, $matches)) {
            $enum = explode("','", $matches[1]);
            return $enum;
        }

        // Devuelve array vacío si no encuentra ENUM o error
        return [];
    }
}

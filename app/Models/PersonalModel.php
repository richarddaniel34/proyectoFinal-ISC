<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonalModel extends Model
{
    protected $table = 'personal';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'nombre',
        'apellido',
        'sexo',
        'id_nacionalidad',
        'cedula',
        'celular',
        'telefono',
        'email',
        'direccion',
        'fecha_nac',
        'condicion',
        'nombramiento',
        'funcion',
        'grado_academico',
        'foto',
        'activo',
        'id_escuela'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';
    protected $deletedField = 'deleted_at';

    //  VALIDACIONES DIRECTAMENTE EN EL MODELO
    protected $validationRules = [
        'nombre' => 'required',
        'apellido' => 'required',
        'sexo' => 'required',
        'id_nacionalidad' => 'required',
        'cedula' => 'required',
        'direccion' => 'required',
        'fecha_nac' => 'required',
        'condicion' => 'required',
        'nombramiento' => 'required',
        'funcion' => 'required',
        'grado_academico' => 'required',
        'foto' => 'permit_empty'
    ];

    protected $validationMessages = [
        'cedula' => ['is_unique' => 'La cédula ya está registrada.'],
        'foto' => [
            'is_image' => 'El archivo debe ser una imagen válida.',
            'mime_in' => 'La imagen debe estar en formato JPG, JPEG o PNG.',
            'max_size' => 'La imagen no debe superar los 2MB.'
        ]
    ];

    /**
     *  Obtiene el personal con sus relaciones (Condición, Nombramiento, Grado Académico)
     */
    public function getPersonalConDetalles()
    {
        return $this->select('
            personal.*, 
            condicion.nombre as condicion_nombre, 
            nombramiento.nombre as nombramiento_nombre, 
            grado_academico.grado_academico as grado_academico_nombre
        ')
            ->join('condicion', 'condicion.id = personal.condicion', 'left')
            ->join('nombramiento', 'nombramiento.id = personal.nombramiento', 'left')
            ->join('grado_academico', 'grado_academico.id = personal.grado_academico', 'left')
            ->findAll();
    }

    /**
     * Verifica si una cédula ya está registrada
     */
    public function cedulaExiste($cedula)
    {
        return $this->where('cedula', str_replace('-', '', trim($cedula)))->first();
    }


//======>OBLIGATORIA
    public function getDocentesPorEscuela($idEscuela)
    {
        return $this->select('personal.id, CONCAT(personal.nombre, " ", personal.apellido) AS nombre_completo, nombramiento.nombre AS nombramiento')
            ->join('nombramiento', 'nombramiento.id = personal.funcion', 'inner') // Relacionamos con nombramiento
            ->where('personal.id_escuela', $idEscuela) // Filtra por la escuela del personal
            ->where('nombramiento.nombre', 'Docente') // Filtra solo los docentes
            ->where('personal.activo', 1) // Solo docentes activos
            ->findAll();
    }



/*
    public function getDocentes($idEscuela = null)
    {
        $this->select('personal.id, CONCAT(personal.nombre, " ", personal.apellido) AS nombre_completo')
            ->where('personal.funcion', 6)
            ->where('personal.activo', 1)
            ->orderBy('personal.nombre', 'ASC');

        if ($idEscuela !== null) {
            $this->join('usuarios', 'usuarios.personal_id = personal.id', 'inner');
            $this->where('usuarios.id_escuela', $idEscuela);
        }

        return $this->findAll();
    }
*/


    public function buscarDocentesPorEscuela($term = '', $idEscuela, $limit = 10, $offset = 0)
    {
        // Evitar offset negativo
        if ($offset < 0) {
            $offset = 0;
        }

        return $this->select('personal.id, CONCAT(personal.nombre, " ", personal.apellido) AS nombre_completo')
            ->join('usuarios', 'usuarios.personal_id = personal.id', 'inner')
            ->join('nombramiento', 'nombramiento.id = personal.nombramiento', 'inner')
            ->where('usuarios.id_escuela', $idEscuela)
            ->where('nombramiento.nombre', 'Docente')
            ->where('personal.activo', 1)
            ->groupStart()
            ->like('personal.nombre', $term)
            ->orLike('personal.apellido', $term)
            ->groupEnd()
            ->orderBy('personal.nombre', 'ASC')
            ->findAll($limit, $offset);
    }








    
    public function contarDocentesPorEscuela($idEscuela)
    {
        return $this->join('nombramiento AS n_oficial', 'n_oficial.id = personal.nombramiento', 'left')
            ->join('nombramiento AS n_funcion', 'n_funcion.id = personal.funcion', 'left')
            ->groupStart()
            ->where('n_oficial.nombre', 'Docente')
            ->orWhere('n_funcion.nombre', 'Docente')
            ->groupEnd()
            ->where('personal.id_escuela', $idEscuela)
            ->where('personal.activo', 1)
            ->countAllResults();
    }


    public function contarPersonalPorEscuela($idEscuela)
    {
        return $this->where('personal.id_escuela', $idEscuela)
            ->where('personal.activo', 1)
            ->countAllResults();
    }

    public function getListadoConAsignaturas($id_escuela = null, $id_periodo = null)
    {
        $builder = $this->db->table('personal p');
        $builder->select('p.nombre, p.apellido, p.sexo, p.id_nacionalidad, p.cedula, p.telefono, p.email, p.direccion, p.fecha_nac, p.condicion, p.nombramiento, p.funcion, p.grado_academico, a.nombre AS asignatura');
        $builder->join('distribucion_asignaturas da', 'da.id_personal = p.id', 'left');
        $builder->join('asignatura a', 'a.id = da.id_asignatura', 'left');
        $builder->where('p.activo', 1);

        if ($id_escuela) {
            $builder->where('p.id_escuela', $id_escuela);
        }
        if ($id_periodo) {
            $builder->where('da.id_periodo', $id_periodo);
        }

        $builder->groupBy('p.id, a.id'); // evita duplicar asignaturas iguales

        $query = $builder->get();
        return $query->getResultArray();
    }
}

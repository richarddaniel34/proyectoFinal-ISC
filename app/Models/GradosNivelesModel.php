<?php

namespace App\Models;

use CodeIgniter\Model;

class GradosNivelesModel extends Model
{
    protected $table = 'grados_niveles';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id_grado', 'id_nivel', 'activo'];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;



    public function obtenerActivos()
    {
        return $this->where('activo', 1)->orderBy('orden', 'ASC')->findAll();
    }

    public function obtenerInactivos()
    {
        return $this->where('activo', 0)->orderBy('orden', 'ASC')->findAll();
    }

    public function buscarPorNombre($nombre)
    {
        return $this->like('nombre', $nombre)->findAll();
    }

    public function getGradosPorEscuela_activos($id_escuela)
    {
        // Instanciar modelo Escuela
        $escuelaModel = new \App\Models\EscuelaModel();
        $escuela = $escuelaModel->find($id_escuela);

        if (!$escuela) {
            return []; // No existe la escuela
        }

        $id_nivel = $escuela['id_nivel'];

        // Hacer join con la tabla 'grados' para obtener nombre y orden
        return $this->select('grados_niveles.*, grados.nombre as nombre_grado, grados.orden')
            ->join('grados', 'grados.id = grados_niveles.id_grado')
            ->where('grados_niveles.activo', 1)
            ->where('grados_niveles.id_nivel', $id_nivel)
            ->orderBy('grados.orden', 'ASC')
            ->findAll();
    }


    public function getCantidadGradosActivos($id_escuela)
    {
        $escuelaModel = new \App\Models\EscuelaModel();
        $escuela = $escuelaModel->find($id_escuela);

        if (!$escuela) {
            return 0; // No existe la escuela
        }

        $id_nivel = $escuela['id_nivel'];

        return $this->where('activo', 1)
            ->where('id_nivel', $id_nivel)
            ->countAllResults();
    }



    


    public function getGradosPorEscuela_inactivos($id_escuela)
    {
        // Instanciar modelo Escuela
        $escuelaModel = new \App\Models\EscuelaModel();
        $escuela = $escuelaModel->find($id_escuela);

        if (!$escuela) {
            return []; // No existe la escuela
        }

        $id_nivel = $escuela['id_nivel'];

        // Hacer join con la tabla 'grados' para obtener nombre y orden
        return $this->select('grados_niveles.*, grados.nombre as nombre_grado, grados.orden')
            ->join('grados', 'grados.id = grados_niveles.id_grado')
            ->where('grados_niveles.activo', 0)
            ->where('grados_niveles.id_nivel', $id_nivel)
            ->orderBy('grados.orden', 'ASC')
            ->findAll();
    }
}

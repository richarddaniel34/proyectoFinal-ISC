<?php

namespace App\Models;

use CodeIgniter\Model;

class GradosModel extends Model
{
    protected $table = 'grados';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nombre', 'orden', 'activo'];

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


    public function obtenerTodosLosGrados()
    {
        // Obtenemos todos los grados activos junto con su nivel
        $grados = $this
            ->select('grados.id, grados.nombre, grados.orden, niveles.nombre as nivel')
            ->join('grados_niveles', 'grados_niveles.id_grado = grados.id')
            ->join('niveles', 'niveles.id = grados_niveles.id_nivel')
            ->where('grados.activo', 1)
            ->orderBy('grados.orden', 'ASC')
            ->findAll();

        // Estructuramos el resultado en el mismo formato que espera tu lógica
        $resultado = [];
        foreach ($grados as $g) {
            $resultado[] = [
                'id' => $g['id'],
                'nombre' => $g['nombre'],
                'orden' => $g['orden'],
                'niveles' => [$g['nivel']] // mantenemos un array para compatibilidad
            ];
        }

        return $resultado;
    }
}

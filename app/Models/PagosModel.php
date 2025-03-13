<?php

namespace App\Models;

use CodeIgniter\Model;

class PagosModel extends Model
{
    protected $table = 'pagos';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id_concepto',
        'id_responsable',
        'id_estudiante',
        'monto',
        'fecha_pago',
        'metodo_pago',
        'estado',
        'id_factura'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';
    protected $deletedField = 'deleted_at';

    // 🔥 VALIDACIONES PARA EVITAR ERRORES
    protected $validationRules = [
        'id_concepto'   => 'required|integer',
        'id_responsable' => 'required|integer',
        'id_estudiante' => 'required|integer',
        'monto'         => 'required|decimal',
        'fecha_pago'    => 'required|valid_date',
        'metodo_pago'   => 'required|in_list[Efectivo,Transferencia,Tarjeta]',
        'estado'        => 'required|in_list[Pagado,Pendiente,Anulado]',
        'id_factura'    => 'permit_empty|integer',
    ];

    protected $validationMessages = [
        'monto' => ['decimal' => 'El monto debe ser un número válido.'],
        'fecha_pago' => ['valid_date' => 'Debe ingresar una fecha válida.'],
        'metodo_pago' => ['in_list' => 'Método de pago inválido.'],
        'estado' => ['in_list' => 'Estado inválido.'],
        'id_factura' => ['integer' => 'El ID de factura debe ser un número entero.'],
    ];

    /**
     * 🔥 Obtener pagos con información adicional (concepto, estudiante y responsable).
     */
    public function getPagosConDetalles()
    {
        return $this->select('
                pagos.*, 
                concepto_pagos.nombre AS concepto_pago,
                CONCAT(responsables.nombre_padre, " ", responsables.apellido_padre) AS responsable,
                CONCAT(estudiantes.nombre, " ", estudiantes.apellido) AS estudiante
            ')
            ->join('concepto_pagos', 'concepto_pagos.id = pagos.id_concepto', 'left')
            ->join('responsables', 'responsables.id = pagos.id_responsable', 'left')
            ->join('estudiantes', 'estudiantes.id = pagos.id_estudiante', 'left')
            ->orderBy('pagos.fecha_pago', 'DESC')
            ->findAll();
    }

    /**
     * 🔥 Filtrar pagos por estado (Pagado, Pendiente, Anulado).
     */
    public function getPagosPorEstado($estado)
    {
        return $this->where('estado', $estado)->orderBy('fecha_pago', 'DESC')->findAll();
    }


    private function obtenerNombreMes($numeroMes)
    {
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        return isset($meses[$numeroMes]) ? $meses[$numeroMes] : 'Mes ' . $numeroMes;
    }

    public function getMesesPendientes($id_estudiante, $id_schoolYear)
{
    // Meses 1 a 12
    $todosLosMeses = range(1, 12);

    // Buscar meses que ya tienen pago registrado en la tabla pagos
    $pagosRealizados = $this->select('mes')
        ->where('id_estudiante', $id_estudiante)
        ->where('id_schoolYear', $id_schoolYear)
        ->where('id_concepto', 3) // Concepto de Mensualidad (ajusta si usas otro id)
        ->findAll();

    // Si no se encuentra el campo mes en la tabla, te tocará agregarlo (ya lo hablamos)
    $mesesPagados = array_column($pagosRealizados, 'mes');

    // Filtrar meses pendientes
    $mesesPendientes = array_diff($todosLosMeses, $mesesPagados);

    $resultado = [];

    // Obtener el monto de la mensualidad
    $conceptoMensualidad = $this->db->table('concepto_pagos')->where('nombre', 'Mensualidad')->get()->getRowArray();
    $montoMensual = $conceptoMensualidad ? $conceptoMensualidad['monto'] : 0;

    // Recorrer meses pendientes y devolverlos en el formato que tu controlador espera
    foreach ($mesesPendientes as $numeroMes) {
        $resultado[] = [
            'numero' => $numeroMes,
            'nombre' => $this->obtenerNombreMes($numeroMes), // El método que agregaste antes
            'monto'  => $montoMensual
        ];
    }

    return $resultado;
}

}

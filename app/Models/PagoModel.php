/**
 * Obtiene los meses pendientes de pago para un estudiante en un año escolar
 */
public function getMesesPendientes($id_estudiante, $id_schoolYear)
{
    // Obtener los meses ya pagados
    $mesesPagados = $this->select('mes')
                         ->where('id_estudiante', $id_estudiante)
                         ->where('id_concepto', 2) // ID del concepto de mensualidad
                         ->where('estado', 'Pagado')
                         ->findAll();
    
    // Convertir a un array simple de números de mes
    $mesesPagadosArray = [];
    foreach ($mesesPagados as $pago) {
        if (!empty($pago['mes'])) {
            $mesesPagadosArray[] = $pago['mes'];
        }
    }
    
    // Obtener el concepto de mensualidad para saber el monto
    $db = \Config\Database::connect();
    $conceptoModel = $db->table('conceptos');
    $conceptoMensualidad = $conceptoModel->where('nombre', 'Mensualidad')->get()->getRowArray();
    
    $monto = $conceptoMensualidad ? $conceptoMensualidad['monto'] : 0;
    
    // Crear array con todos los meses del año escolar
    $mesesPendientes = [];
    for ($i = 1; $i <= 12; $i++) {
        if (!in_array($i, $mesesPagadosArray)) {
            $mesesPendientes[] = [
                'numero' => $i,
                'nombre' => $this->getNombreMes($i),
                'monto' => $monto
            ];
        }
    }
    
    return $mesesPendientes;
}

/**
 * Obtiene el nombre del mes según su número
 */
private function getNombreMes($numeroMes)
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
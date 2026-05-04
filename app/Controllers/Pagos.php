<?php

namespace App\Controllers;

use App\Models\InscripcionesModel;
use App\Models\EstudiantesModel;
use App\Models\ResponsablesModel;
use App\Models\GradosNivelesModel;
use App\Models\SchoolYearModel;
use App\Models\PagosModel;
use App\Models\ConceptoPagosModel;
use App\Models\CursosModel;
use App\Models\EscuelaServiciosModel;
use App\Models\ServiciosModel;
use App\Models\ConceptoPagosConfigModel;
use App\Models\MesesModel;
// Añadir la importación de TCPDF
use TCPDF;

class Pagos extends BaseController
{
    protected $inscripciones;
    protected $estudiantes;
    protected $responsables;
    protected $schoolYear;
    protected $pagos;
    protected $conceptoPagos;
    protected $cursos;
    protected $gradosNiveles;
    protected $escuelasServicios;
    protected $servicios;
    protected $conceptoConfig;
    protected $meses;

    public function __construct()
    {
        $this->inscripciones = new InscripcionesModel();
        $this->estudiantes = new EstudiantesModel();
        $this->responsables = new ResponsablesModel();
        $this->schoolYear = new SchoolYearModel();
        $this->pagos = new PagosModel();
        $this->conceptoPagos = new ConceptoPagosModel();
        $this->cursos = new CursosModel();
        $this->gradosNiveles = new GradosNivelesModel();
        $this->escuelasServicios = new EscuelaServiciosModel();
        $this->servicios = new ServiciosModel();
        $this->conceptoConfig = new ConceptoPagosConfigModel();
        $this->meses = new MesesModel();
    }

    public function index()
    {
        // Obtener todos los pagos con detalles
        $pagos = $this->pagos->getPagosConDetalles();

        // Pasamos los datos a la vista
        $data = [
            'titulo' => 'Gestión de Pagos',
            'pagos' => $pagos
        ];

        // Renderiza las vistas
        echo view('header');
        echo view('pagos/gestion_pagos', $data); // Asegúrate que esta es la ruta correcta de tu vista
        echo view('footer');
    }


    //Muestra el formulario de inscripción
    public function nueva_inscripcion()
    {
        // 🔹 Obtener responsables y año escolar activo
        $responsables = $this->responsables->findAll();
        $schoolYearActivo = $this->schoolYear->where('estado', 'En curso')->first();
        $id_schoolYear = $schoolYearActivo ? $schoolYearActivo['id'] : null;

        // 🔹 Obtener conceptos de pago
        $conceptoInscripcion = $this->conceptoPagos->where('nombre', 'Inscripción')->first();
        $conceptoMensualidad = $this->conceptoPagos->where('nombre', 'Mensualidad')->first();
        $cantidadMensualidades = 12;

        // 🔹 Obtener responsable y sus estudiantes
        $id_responsable = $this->request->getGet('id_responsable') ?? null;

        $estudiantes = [];
        $grados = [];
        $servicios = [];
        $cursos = [];

        if ($id_responsable) {
            $estudiantesResponsable = $this->estudiantes
                ->where('responsables', $id_responsable)
                ->findAll();

            $estudiantes = $estudiantesResponsable;

            $id_escuela = $estudiantesResponsable[0]['id_escuela'] ?? null;

            if ($id_escuela) {
                // 🔹 Servicios activos
                $servicios = $this->escuelasServicios
                    ->select('servicios.id, servicios.nombre')
                    ->join('servicios', 'servicios.id = escuelas_servicios.id_servicio')
                    ->where('escuelas_servicios.id_escuela', $id_escuela)
                    ->where('escuelas_servicios.activo', 1)
                    ->findAll();

                // 🔹 Grados disponibles
                $grados = $this->gradosNiveles
                    ->where('id_escuela', $id_escuela)
                    ->orderBy('orden', 'ASC')
                    ->findAll();
            }

            // 🔹 Precargar cursos según primer estudiante
            if (!empty($estudiantesResponsable) && $id_schoolYear) {
                $id_grado = $estudiantesResponsable[0]['id_grado'];

                if (!empty($servicios)) {
                    $id_servicio = $servicios[0]['id']; // Primer servicio disponible

                    // 🔹 Obtener cursos filtrando por grado, servicio y año
                    $cursos = $this->cursos
                        ->select('cursos.id, cursos_base.nombre_curso AS nombre_curso, cursos.capacidad, cursos.tipo_aula, salidas_tecnicas.nombre AS salida_tecnica')
                        ->join('cursos_base', 'cursos_base.id = cursos.id_cursos_base')
                        ->join('salidas_tecnicas', 'salidas_tecnicas.id_servicio = cursos.id_servicio', 'left')
                        ->where('cursos.id_schoolyear', $id_schoolYear)
                        ->where('cursos.id_grado', $id_grado)
                        ->where('cursos.id_servicio', $id_servicio)
                        ->where('cursos.activo', 1)
                        ->orderBy('cursos_base.nombre_curso', 'ASC')
                        ->findAll();
                }
            }
        }

        $data = [
            'titulo' => 'Nueva Inscripción',
            'responsables' => $responsables,
            'schoolYearActivo' => $schoolYearActivo,
            'estudiantes' => $estudiantes,
            'grados' => $grados,
            'servicios' => $servicios,
            'cursos' => $cursos,
            'concepto_inscripcion' => $conceptoInscripcion,
            'concepto_mensualidad' => $conceptoMensualidad,
            'cantidad_mensualidades' => $cantidadMensualidades,
            'id_responsable' => $id_responsable,
        ];

        echo view('header');
        echo view('pagos/nueva_inscripcion', $data);
        echo view('footer');
    }












    // Carga los estudiantes según el responsable seleccionado
    public function obtenerEstudiantes()
    {
        $idResponsable = $this->request->getGet('id_responsable');
        log_message('info', "obtenerEstudiantes -> idResponsable recibido: {$idResponsable}");

        if (!$idResponsable) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'ID del responsable no proporcionado.'
            ]);
        }

        // SELECT sin comentarios inline
        $estudiantes = $this->estudiantes
            ->select([
                'estudiantes.id',
                'estudiantes.nombre',
                'estudiantes.apellido',
                'estudiantes.matricula',
                'estudiantes.id_grado AS id_grado_nivel', // <-- sin comentario SQL
                'estudiantes.id_escuela',
                'grados.nombre  AS grado_nombre',
                'niveles.nombre AS nivel_nombre',
            ])
            ->join('estudiantes_responsables', 'estudiantes.id = estudiantes_responsables.estudiante_id')
            ->join('grados_niveles', 'grados_niveles.id = estudiantes.id_grado')
            ->join('grados', 'grados.id = grados_niveles.id_grado')
            ->join('niveles', 'niveles.id = grados_niveles.id_nivel')
            ->where('estudiantes_responsables.responsable_id', $idResponsable)
            ->findAll();

        log_message('info', 'obtenerEstudiantes -> Estudiantes crudos obtenidos: ' . json_encode($estudiantes));

        if (empty($estudiantes)) {
            return $this->response->setJSON([
                'status'  => 'empty',
                'message' => 'No se encontraron estudiantes para este responsable.'
            ]);
        }

        $db = \Config\Database::connect();

        foreach ($estudiantes as &$estudiante) {
            log_message('info', 'Procesando estudiante: ' . json_encode($estudiante));

            $estudiante['grado_nivel'] = $estudiante['grado_nombre'] . ' - ' . $estudiante['nivel_nombre'];

            // Inscripción actual (ajusta si tienes schoolYear en juego aquí)
            $inscripcion = $this->inscripciones
                ->where('id_estudiante', $estudiante['id'])
                ->where('condicion_inicial', 'Inscrito')
                ->first();
            $estudiante['inscrito'] = $inscripcion ? true : false;

            // Servicios de la escuela + salidas técnicas
            $rows = $db->table('escuelas_servicios esv')
                ->select('
                s.id AS id_servicio,
                s.nombre AS servicio_nombre,
                s.activo AS servicio_activo,
                st.id AS salida_id,
                st.nombre AS salida_nombre
            ')
                ->join('servicios s', 's.id = esv.id_servicio')
                ->join('salidas_tecnicas st', 'st.id_servicio = s.id', 'left')
                ->where('esv.id_escuela', $estudiante['id_escuela'])
                ->where('esv.activo', 1)
                ->where('s.activo', 1)
                ->orderBy('s.nombre', 'ASC')
                ->orderBy('st.nombre', 'ASC')
                ->get()->getResultArray();

            log_message('info', "Servicios+salidas crudos (escuela {$estudiante['id_escuela']}): " . json_encode($rows));

            $serviciosOpciones = [];
            foreach ($rows as $r) {
                $esTecnico = stripos($r['servicio_nombre'], 'técnico') !== false || stripos($r['servicio_nombre'], 'tecnico') !== false;
                $tieneSalida = !empty($r['salida_id']);

                if ($esTecnico && $tieneSalida) {
                    $serviciosOpciones[] = [
                        'id_servicio'   => (int) $r['id_servicio'],
                        'nombre'        => $r['servicio_nombre'] . ' - ' . $r['salida_nombre'],
                        'salida_id'     => (int) $r['salida_id'],
                        'salida_nombre' => $r['salida_nombre'],
                        'es_tecnico'    => 1,
                    ];
                } elseif (!$esTecnico) {
                    $serviciosOpciones[] = [
                        'id_servicio'   => (int) $r['id_servicio'],
                        'nombre'        => $r['servicio_nombre'],
                        'salida_id'     => null,
                        'salida_nombre' => null,
                        'es_tecnico'    => 0,
                    ];
                }
            }

            // quita duplicados (por si el LEFT repite no técnicos)
            $serviciosOpciones = array_values(array_unique($serviciosOpciones, SORT_REGULAR));
            $estudiante['servicios'] = $serviciosOpciones;

            log_message('info', "Estudiante {$estudiante['id']} -> servicios mapeados: " . json_encode($serviciosOpciones));
        }

        log_message('info', 'obtenerEstudiantes -> Estudiantes procesados: ' . json_encode($estudiantes));

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $estudiantes
        ]);
    }




    //Registra la inscripción y el pago si aplica
    public function registrar_inscripcion()
    {
        $id_responsable = $this->request->getPost('id_responsable');
        $metodo_pago    = $this->request->getPost('metodo_pago');
        $pagoCompleto   = $this->request->getPost('pago_completo');
        $id_schoolYear  = $this->request->getPost('id_schoolYear');

        $estudiantes = $this->request->getPost('estudiantes');

        if (empty($estudiantes)) {
            return redirect()->back()->with('error', 'No hay datos de estudiantes.');
        }

        // 🔹 Conceptos reales desde BD (NO frontend)
        $conceptoInscripcion = $this->conceptoPagos->where('nombre', 'Inscripción')->first();
        $conceptoMensualidad = $this->conceptoPagos->where('nombre', 'Mensualidad')->first();

        if (!$conceptoInscripcion) {
            return redirect()->back()->with('error', 'Concepto inscripción no configurado.');
        }

        $configInscripcion = $this->conceptoConfig
            ->where('id_concepto', $conceptoInscripcion['id'])
            ->where('id_schoolYear', $id_schoolYear)
            ->first();

        $configMensualidad = $this->conceptoConfig
            ->where('id_concepto', $conceptoMensualidad['id'])
            ->where('id_schoolYear', $id_schoolYear)
            ->first();

        if (!$configInscripcion) {
            return redirect()->back()->with('error', 'Config de inscripción no definida para este año.');
        }

        $monto_inscripcion = $configInscripcion['monto'];
        $monto_mensualidad = $configMensualidad['monto'] ?? 0;

        $db = \Config\Database::connect();
        $db->transStart();

        $pagosParaFactura = [];
        $totalFactura = 0;
        $detallesFactura = [];

        foreach ($estudiantes as $id_estudiante => $data) {

            //  SOLO procesar los marcados
            if (empty($data['inscribir'])) {
                continue;
            }

            $id_grado   = $data['id_grado'] ?? null;
            $id_escuela = $data['id_escuela'] ?? null;
            $id_curso   = $data['id_curso'] ?? null;

            if (!$id_grado || !$id_escuela || !$id_curso) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Datos incompletos en la inscripción.');
            }

            //  Validar duplicado
            $existe = $this->inscripciones
                ->where('id_estudiante', $id_estudiante)
                ->where('id_grado', $id_grado)
                ->where('id_schoolYear', $id_schoolYear)
                ->where('activo', 1)
                ->first();

            if ($existe) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Uno de los estudiantes ya está inscrito.');
            }

            //  VALIDAR CURSO REAL (ANTI-MANIPULACIÓN)
            $curso = $this->cursos->find($id_curso);

            if (!$curso) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Curso inválido.');
            }

            // VALIDAR CUPO (CRÍTICO)
            $capacidad = $curso['capacidad'];
            $ocupados = $this->inscripciones
                ->where('id_curso', $id_curso)
                ->where('activo', 1)
                ->countAllResults();

            if ($ocupados >= $capacidad) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Uno de los cursos ya no tiene cupo.');
            }

            //  Registrar pago inscripción
            $pagoInscripcion = [
                'id_concepto'    => $conceptoInscripcion['id'],
                'id_responsable' => $id_responsable,
                'id_estudiante'  => $id_estudiante,
                'id_schoolYear'  => $id_schoolYear,
                'monto'          => $monto_inscripcion,
                'metodo_pago'    => $metodo_pago,
                'estado'         => 'Pago',
                'fecha_pago'     => date('Y-m-d')
            ];

            if (!$this->pagos->save($pagoInscripcion)) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Error al registrar pago.');
            }

            $id_pago = $this->pagos->getInsertID();

            // 🔹 Registrar inscripción
            $inscripcion = [
                'id_estudiante' => $id_estudiante,
                'id_grado'      => $id_grado,
                'id_curso'      => $id_curso,
                'id_escuela'    => $id_escuela,
                'id_schoolYear' => $id_schoolYear,
                'id_pago'       => $id_pago,
                'activo'        => 1
            ];

            if (!$this->inscripciones->save($inscripcion)) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Error al registrar inscripción.');
            }

            // Factura
            $estudiante = $this->estudiantes->find($id_estudiante);

            $pagosParaFactura[] = $id_pago;
            $totalFactura += $monto_inscripcion;

            $detallesFactura[] = [
                'concepto'   => 'Inscripción',
                'estudiante' => $estudiante['nombre'] . ' ' . $estudiante['apellido'],
                'monto'      => $monto_inscripcion
            ];

            //  Pago completo (mensualidades)
            if ($pagoCompleto) {

                //  Obtener meses desde BD (ordenados)
                $meses = $this->meses
                    ->orderBy('orden', 'ASC')
                    ->findAll();

                log_message('debug', '📅 Meses desde BD: ' . json_encode($meses));

                foreach ($meses as $mesData) {

                    $mes = ucfirst(strtolower($mesData['nombre'])); //  normalización elegante

                    log_message('debug', "   📌 Procesando mes: $mes");

                    $pagoMensual = [
                        'id_concepto'    => $conceptoMensualidad['id'],
                        'id_responsable' => $id_responsable,
                        'id_estudiante'  => $id_estudiante,
                        'id_schoolYear'  => $id_schoolYear,
                        'monto'          => $monto_mensualidad,
                        'metodo_pago'    => $metodo_pago,
                        'estado'         => 'Pago',
                        'fecha_pago'     => date('Y-m-d'),
                        'mes'            => $mes
                    ];

                    if (!$this->pagos->save($pagoMensual)) {
                        log_message('error', '❌ Error guardando mensualidad: ' . json_encode($this->pagos->errors()));
                        $db->transRollback();
                        return redirect()->back()->with('error', 'Error en mensualidades.');
                    }

                    $id_pago_m = $this->pagos->getInsertID();

                    log_message('debug', "   ✅ Pago mensual guardado ID: $id_pago_m");

                    $pagosParaFactura[] = $id_pago_m;
                    $totalFactura += $monto_mensualidad;

                    $detallesFactura[] = [
                        'concepto'   => "Mensualidad ($mes)",
                        'estudiante' => $estudiante['nombre'] . ' ' . $estudiante['apellido'],
                        'monto'      => $monto_mensualidad
                    ];
                }
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Error en la transacción.');
        }

        if (!empty($pagosParaFactura)) {
            $id_factura = $this->generarFactura($id_responsable, $pagosParaFactura, $totalFactura, $detallesFactura);

            return redirect()->to(site_url('pagos/verFactura/' . $id_factura))
                ->with('success', 'Inscripción registrada correctamente.');
        }

        return redirect()->back()->with('error', 'No se procesaron estudiantes.');
    }
























    //Genera una factura para los pagos realizados
    private function generarFactura($id_responsable, $pagos, $total, $detalles)
    {
        log_message('debug', '--- Generando nueva factura ---');
        log_message('debug', "Responsable ID: $id_responsable");
        log_message('debug', 'Pagos: ' . json_encode($pagos));
        log_message('debug', 'Total: ' . $total);
        log_message('debug', 'Detalles: ' . json_encode($detalles));

        $facturaModel = new \App\Models\FacturaModel();
        $responsable = $this->responsables->find($id_responsable);

        if (!$responsable) {
            log_message('error', "❌ No se encontró el responsable con ID: $id_responsable");
            return false;
        }

        $numeroFactura = 'FAC-' . date('Ymd') . '-' . uniqid();

        $facturaData = [
            'numero_factura' => $numeroFactura,
            'id_responsable' => $id_responsable,
            'nombre_responsable' => $responsable['nombre'] . ' ' . $responsable['apellido'],
            'fecha_emision' => date('Y-m-d'),
            'total' => $total,
            'estado' => 'Pago',
            'detalles' => json_encode($detalles),
            'pagos_relacionados' => json_encode($pagos)
        ];

        log_message('debug', 'Datos de factura a guardar: ' . json_encode($facturaData));

        if (!$facturaModel->save($facturaData)) {
            log_message('error', '❌ Error guardando factura: ' . json_encode($facturaModel->errors()));
            return false;
        }

        $id_factura = $facturaModel->getInsertID();
        log_message('debug', "✅ Factura guardada correctamente. ID generado: $id_factura");

        // Actualizar los pagos con el ID de la factura
        foreach ($pagos as $id_pago) {
            $this->pagos->update($id_pago, ['id_factura' => $id_factura]);
            log_message('debug', "🧾 Pago $id_pago vinculado a factura $id_factura");
        }

        log_message('debug', '--- Fin del proceso de generación de factura ---');
        return $id_factura;
    }



    //Genera e imprime un PDF de la factura
    public function imprimirFactura($id_factura)
    {
        $facturaModel = new \App\Models\FacturaModel();
        $factura = $facturaModel->find($id_factura);

        if (!$factura) {
            return redirect()->back()->with('error', 'Factura no encontrada.');
        }

        $detalles = json_decode($factura['detalles'], true);
        $nombreEscuela = session('nombre_escuela') ?? 'Centro Educativo';

        // 📄 MEDIA CARTA MÁS COMPACTA
        $pdf = new \TCPDF('P', 'mm', [216, 120], true, 'UTF-8', false);

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(true, 8);

        $pdf->AddPage();

        // 🧾 HEADER
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 6, strtoupper($nombreEscuela), 0, 1, 'C');

        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 5, 'Factura #' . $factura['numero_factura'], 0, 1, 'C');
        $pdf->Cell(0, 5, date('d/m/Y', strtotime($factura['fecha_emision'])), 0, 1, 'C');

        $pdf->Ln(4);

        // 👤 CLIENTE
        $pdf->Cell(0, 5, 'Cliente: ' . $factura['nombre_responsable'], 0, 1);

        // 🔥 línea separadora
        $pdf->Ln(2);
        $pdf->Cell(0, 0, '', 'T', 1);
        $pdf->Ln(2);

        // 🔥 AGRUPAR POR ESTUDIANTE
        $estudiantes = [];

        foreach ($detalles as $d) {

            $est = $d['estudiante'];

            if (!isset($estudiantes[$est])) {
                $estudiantes[$est] = [
                    'inscripcion' => 0,
                    'mensualidad' => 0,
                    'count' => 0
                ];
            }

            if (strpos($d['concepto'], 'Mensualidad') !== false) {
                $estudiantes[$est]['mensualidad'] += $d['monto'];
                $estudiantes[$est]['count']++;
            } else {
                $estudiantes[$est]['inscripcion'] += $d['monto'];
            }
        }

        // 🧾 IMPRIMIR BONITO
        foreach ($estudiantes as $nombre => $data) {

            // 👤 Nombre estudiante
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 5, $nombre, 0, 1);

            $pdf->SetFont('helvetica', '', 9);

            // 📌 Inscripción
            if ($data['inscripcion'] > 0) {
                $pdf->Cell(120, 5, '  Inscripción', 0, 0);
                $pdf->Cell(60, 5, 'RD$' . number_format($data['inscripcion'], 2), 0, 1, 'R');
            }

            // 📌 Mensualidades
            if ($data['mensualidad'] > 0) {
                $texto = '  Mensualidades (' . $data['count'] . ' meses)';
                $pdf->Cell(120, 5, $texto, 0, 0);
                $pdf->Cell(60, 5, 'RD$' . number_format($data['mensualidad'], 2), 0, 1, 'R');
            }

            // 🔥 separador
            $pdf->Ln(1);
            $pdf->Cell(0, 0, '', 'T', 1);
            $pdf->Ln(2);
        }

        // 💰 TOTAL
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(120, 6, 'TOTAL', 0, 0);
        $pdf->Cell(60, 6, 'RD$' . number_format($factura['total'], 2), 0, 1, 'R');

        // 🧠 FOOTER
        $pdf->Ln(4);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(0, 5, 'Gracias por su pago.', 0, 1, 'C');

        // 🔥 limpiar buffer
        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="Factura_' . $factura['numero_factura'] . '.pdf"');

        $pdf->Output('Factura_' . $factura['numero_factura'] . '.pdf', 'I');
        exit;
    }



    //Muestra los detalles de una factura con opción para imprimir
    public function verFactura($id_factura)
    {
        // Cargar el modelo de facturas
        $facturaModel = new \App\Models\FacturaModel();

        // Obtener los datos de la factura
        $factura = $facturaModel->find($id_factura);

        if (!$factura) {
            return redirect()->to(base_url('/pagos'))->with('error', 'Factura no encontrada.');
        }

        // Decodificar los detalles de la factura
        $detalles = json_decode($factura['detalles'], true);

        $data = [
            'titulo' => 'Detalles de Factura',
            'factura' => $factura,
            'detalles' => $detalles
        ];

        echo view('header');
        echo view('pagos/ver_factura', $data);
        echo view('footer');
    }


    //Obtiene las mensualidades pendientes de los estudiantes de un responsable
    public function obtenerMensualidadesPendientes()
    {
        $id_responsable = $this->request->getGet('id_responsable');
        $id_schoolYear  = $this->request->getGet('id_schoolYear');

        if (!$id_responsable || !$id_schoolYear) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Faltan parámetros requeridos.'
            ]);
        }

        try {
            $estudiantes = $this->inscripciones
                ->getEstudiantesInscritosPorResponsable($id_responsable, $id_schoolYear);

            if (empty($estudiantes)) {
                return $this->response->setJSON([
                    'status'  => 'empty',
                    'message' => 'No hay estudiantes inscritos.'
                ]);
            }

            //  eliminar duplicados
            $estudiantesUnicos = [];
            foreach ($estudiantes as $e) {
                $estudiantesUnicos[$e['id']] = $e;
            }
            $estudiantes = array_values($estudiantesUnicos);

            $resultado = [];

            foreach ($estudiantes as $estudiante) {

                log_message('debug', "👤 Estudiante {$estudiante['id']}");

                //  PAGOS REALMENTE REALIZADOS
                $pagos = $this->pagos
                    ->select('mes')
                    ->where('id_estudiante', $estudiante['id'])
                    ->where('id_schoolYear', $id_schoolYear)
                    ->where('estado', 'Pago')
                    ->findAll();

                //  normalizar meses pagados
                $mesesPagados = array_map(function ($p) {
                    return strtolower(trim($p['mes']));
                }, $pagos);

                log_message('debug', "💰 Meses pagados: " . json_encode($mesesPagados));

                //  meses pendientes desde tu lógica actual
                $mesesPendientes = $this->pagos
                    ->getMesesPendientes($estudiante['id'], $id_schoolYear);

                log_message('debug', "📊 Pendientes: " . json_encode($mesesPendientes));

                if (!empty($mesesPendientes)) {
                    $resultado[] = [
                        'id'               => $estudiante['id'],
                        'nombre'           => $estudiante['nombre'],
                        'apellido'         => $estudiante['apellido'],
                        'curso'            => $estudiante['curso'],

                        //  nuevos datos para el front
                        'meses_pendientes' => $mesesPendientes,
                        'meses_pagados'    => $mesesPagados
                    ];
                }
            }

            if (empty($resultado)) {
                return $this->response->setJSON([
                    'status'  => 'empty',
                    'message' => 'No hay mensualidades pendientes.'
                ]);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'data'   => $resultado
            ]);
        } catch (\Exception $e) {
            log_message('error', '❌ ERROR: ' . $e->getMessage());

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Error inesperado.'
            ]);
        }
    }

    //Registra el pago de mensualidades
    public function registrarPagoMensualidad()
    {
        log_message('debug', '=== 🚀 INICIO registrarPagoMensualidad ===');

        $id_responsable = $this->request->getPost('id_responsable');
        $id_schoolYear  = $this->request->getPost('id_schoolYear');
        $metodo_pago    = $this->request->getPost('metodo_pago');
        $meses          = $this->request->getPost('meses');

        $estudiantes = array_keys($meses ?? []);

        log_message('debug', '📥 RAW INPUT: ' . json_encode([
            'id_responsable' => $id_responsable,
            'id_schoolYear'  => $id_schoolYear,
            'metodo_pago'    => $metodo_pago,
            'meses'          => $meses
        ]));

        $estudiantes = array_values(array_unique($estudiantes));

        if (!$id_responsable || !$metodo_pago || empty($meses)) {
            return redirect()->back()->with('error', 'Datos incompletos.');
        }

        $responsable = $this->responsables->find($id_responsable);
        if (!$responsable) {
            return redirect()->back()->with('error', 'Responsable no encontrado.');
        }

        // AQUÍ ESTÁ EL CAMBIO IMPORTANTE
        $conceptoMensualidad = $this->conceptoPagos
            ->where('nombre', 'Mensualidad')
            ->first();

        $configMensualidad = $this->conceptoConfig
            ->where('id_schoolYear', $id_schoolYear)
            ->where('id_concepto', $conceptoMensualidad['id'])
            ->first();

        if (!$configMensualidad) {
            return redirect()->back()->with('error', 'Mensualidad no configurada para este año.');
        }

        $montoMensualidad = $configMensualidad['monto'];
        $idConcepto       = $configMensualidad['id_concepto'];

        $db = \Config\Database::connect();
        $db->transStart();

        $pagosParaFactura = [];
        $totalFactura = 0;
        $detallesFactura = [];

        foreach ($estudiantes as $id_estudiante) {

            if (empty($meses[$id_estudiante])) continue;

            $estudiante = $this->estudiantes->find($id_estudiante);
            if (!$estudiante) continue;

            foreach ($meses[$id_estudiante] as $mesTexto) {

                //  NORMALIZACIÓN GOD MODE
                $mes = ucfirst(strtolower(trim($mesTexto)));

                if (empty($mes)) continue;

                log_message('debug', "📅 Procesando: $mes");

                // 🔍 VALIDAR DUPLICADO
                $existePago = $this->pagos
                    ->where('id_estudiante', $id_estudiante)
                    ->where('mes', $mes)
                    ->where('id_schoolYear', $id_schoolYear)
                    ->where('id_concepto', $idConcepto)
                    ->where('estado', 'Pago')
                    ->first();

                if ($existePago) {
                    log_message('warning', "⚠️ YA EXISTE: Est $id_estudiante Mes $mes");
                    continue;
                }

                $dataPago = [
                    'id_concepto'    => $idConcepto,
                    'id_responsable' => $id_responsable,
                    'id_estudiante'  => $id_estudiante,
                    'id_schoolYear'  => $id_schoolYear,
                    'monto'          => $montoMensualidad, //  desde config
                    'metodo_pago'    => $metodo_pago,
                    'estado'         => 'Pago',
                    'fecha_pago'     => date('Y-m-d'),
                    'mes'            => $mes
                ];

                if (!$this->pagos->save($dataPago)) {
                    $db->transRollback();
                    return redirect()->back()->with('error', 'Error guardando pago.');
                }

                $id_pago = $this->pagos->getInsertID();

                $pagosParaFactura[] = $id_pago;
                $totalFactura += $montoMensualidad;

                $detallesFactura[] = [
                    'concepto'   => 'Mensualidad - ' . $mes,
                    'estudiante' => $estudiante['nombre'] . ' ' . $estudiante['apellido'],
                    'monto'      => $montoMensualidad
                ];
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Error en la transacción.');
        }

        if (empty($pagosParaFactura)) {
            return redirect()->back()->with('error', 'No se registraron pagos.');
        }

        $id_factura = $this->generarFactura(
            $id_responsable,
            $pagosParaFactura,
            $totalFactura,
            $detallesFactura
        );

        return redirect()->to(base_url('/pagos/verFactura/' . $id_factura))
            ->with('success', 'Pagos registrados correctamente.');
    }






    //Muestra la página para pagar mensualidades
    public function otros_pagos()
    {
        $data = [
            'titulo' => 'Pago de Mensualidades',
            'responsables' => $this->responsables->findAll(),
            'schoolYears' => $this->schoolYear->findAll()
        ];

        echo view('header');
        echo view('pagos/otros_pagos', $data);
        echo view('footer');
    }
}

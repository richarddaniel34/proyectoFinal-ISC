<?php

namespace App\Controllers;

use App\Models\InscripcionesModel;
use App\Models\EstudiantesModel;
use App\Models\ResponsablesModel;
use App\Models\DistribucionAcademicaModel;
use App\Models\SchoolYearModel;
use App\Models\PagosModel;
use App\Models\ConceptoPagosModel;
// Añadir la importación de TCPDF
use TCPDF;

class Inscripciones extends BaseController
{
    protected $inscripciones;
    protected $estudiantes;
    protected $responsables;
    protected $distribucionAcademica;
    protected $schoolYear;
    protected $pagos;
    protected $conceptoPagos;

    public function __construct()
    {
        $this->inscripciones = new InscripcionesModel();
        $this->estudiantes = new EstudiantesModel();
        $this->responsables = new ResponsablesModel();
        $this->distribucionAcademica = new DistribucionAcademicaModel();
        $this->schoolYear = new SchoolYearModel();
        $this->pagos = new PagosModel();
        $this->conceptoPagos = new ConceptoPagosModel();
    }

    public function index()
    {
        // 🔥 Obtener todos los pagos con detalles
        $pagos = $this->pagos->getPagosConDetalles();

        // 📌 Pasamos los datos a la vista
        $data = [
            'titulo' => 'Gestión de Pagos',
            'pagos' => $pagos
        ];

        // Renderiza las vistas
        echo view('header');
        echo view('inscripciones/inscripciones', $data); // Asegúrate que esta es la ruta correcta de tu vista
        echo view('footer');
    }


    //Muestra el formulario de inscripción
    public function nuevo()
    {
        $responsables = $this->responsables->findAll();
        $schoolYears = $this->schoolYear->findAll();


        // 🔥 Obtener monto de inscripción y mensualidad
        $conceptoInscripcion = $this->conceptoPagos->where('nombre', 'Inscripción')->first();
        $conceptoMensualidad = $this->conceptoPagos->where('nombre', 'Mensualidad')->first();

        $cantidadMensualidades = 12; // O el número real en tu sistema

        // 🔹 Obtener estudiantes según responsable (opcional para que cargue al inicio)
        $id_responsable = $this->request->getGet('id_responsable') ?? null;

        $estudiantes = [];
        if ($id_responsable) {
            $estudiantes = $this->estudiantes
                ->where('responsables', $id_responsable)
                ->findAll();
        }

        $cursos = $this->distribucionAcademica
            ->select('distribucion_academica.id, cursos.nombreCurso, escuela.codigo_gestion')
            ->join('cursos', 'cursos.id = distribucion_academica.id_curso', 'left')
            ->join('escuela', 'escuela.id = distribucion_academica.id_escuela', 'left')
            ->findAll();

        $data = [
            'titulo' => 'Nueva Inscripción',
            'responsables' => $responsables,
            'schoolYears' => $schoolYears,
            'estudiantes' => $estudiantes,
            'cursos' => $cursos,
            'concepto_inscripcion' => $conceptoInscripcion,
            'concepto_mensualidad' => $conceptoMensualidad,
            'cantidad_mensualidades' => $cantidadMensualidades,
            'id_responsable' => $id_responsable
        ];

        echo view('header');
        echo view('inscripciones/nuevo', $data);
        echo view('footer');
    }


    // Carga los estudiantes según el responsable seleccionado
    public function obtenerEstudiantes()
    {
        $idResponsable = $this->request->getGet('id_responsable'); // ✅ GET en lugar de POST

        if (!$idResponsable) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ID del responsable no proporcionado.'
            ]);
        }

        $estudiantes = $this->estudiantes
            ->where('responsables', $idResponsable)
            ->findAll();

        if (empty($estudiantes)) {
            return $this->response->setJSON([
                'status' => 'empty',
                'message' => 'No se encontraron estudiantes para este responsable.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $estudiantes
        ]);
    }



    //Carga los cursos según la escuela seleccionada
    public function obtenerCursosPorEscuela()
    {
        $idEscuela = $this->request->getPost('id_escuela');

        $cursos = $this->distribucionAcademica
            ->where('id_escuela', $idEscuela)
            ->findAll();

        return $this->response->setJSON($cursos);
    }




    //Registra la inscripción y el pago si aplica
    public function registrar()
    {
        $id_responsable = $this->request->getPost('id_responsable');
        $metodo_pago = $this->request->getPost('metodo_pago');

        // Comentado hasta que se defina su uso (validación o registro)
        // $total_pago = $this->request->getPost('total_pago'); // ➡️ Se puede usar para validar el monto total más adelante

        $inscribir = $this->request->getPost('inscribir');
        $cursos = $this->request->getPost('id_curso');
        $montos = $this->request->getPost('monto');
        $pagoCompleto = $this->request->getPost('pago_completo');
        $id_schoolYear = $this->request->getPost('id_schoolYear');

        if (!$inscribir) {
            return redirect()->back()->with('error', 'Debe seleccionar al menos un estudiante.');
        }

        // 🔹 Traer el monto de mensualidad dinámico desde la BD
        $conceptoMensualidad = $this->conceptoPagos->where('nombre', 'Mensualidad')->first();
        $monto_mensualidad = $conceptoMensualidad['monto'];

        // 🔥 Crear un array para almacenar los IDs de pagos para la factura
        $pagosParaFactura = [];
        $totalFactura = 0;
        $detallesFactura = [];

        foreach ($inscribir as $index => $id_estudiante) {

            $id_distribucion_academica = $cursos[$index] ?? null;

            $monto_pago_inscripcion_raw = $montos[$index] ?? '0';
            $monto_pago_inscripcion = floatval(str_replace(',', '', $monto_pago_inscripcion_raw));

            log_message('debug', "➡️ Procesando estudiante: $id_estudiante | Curso: $id_distribucion_academica | Monto Inscripción: $monto_pago_inscripcion");

            if (!$id_distribucion_academica) {
                log_message('error', "❌ No se envió el id_distribucion_academica para el estudiante $id_estudiante");
                continue;
            }

            // Obtener datos del estudiante para la factura
            $estudiante = $this->estudiantes->find($id_estudiante);

            // Registrar el pago de inscripción
            $pagoInscripcionData = [
                'id_concepto' => 1,
                'id_responsable' => $id_responsable,
                'id_estudiante' => $id_estudiante,
                'monto' => $monto_pago_inscripcion,
                'metodo_pago' => $metodo_pago,
                'estado' => 'Pago',
                'fecha_pago' => date('Y-m-d')
            ];

            if (!$this->pagos->save($pagoInscripcionData)) {
                log_message('error', '❌ Error guardando pago inscripción: ' . json_encode($this->pagos->errors()));
                return redirect()->back()->with('error', 'Error al registrar el pago de inscripción.');
            }

            $id_pago_inscripcion = $this->pagos->getInsertID();
            log_message('debug', "✅ Pago inscripción guardado, ID: $id_pago_inscripcion");

            // 🔥 Añadir pago a la lista para factura
            $pagosParaFactura[] = $id_pago_inscripcion;
            $totalFactura += $monto_pago_inscripcion;
            $detallesFactura[] = [
                'concepto' => 'Inscripción',
                'estudiante' => $estudiante['nombre'] . ' ' . $estudiante['apellido'],
                'monto' => $monto_pago_inscripcion
            ];

            // Registrar la inscripción
            $inscripcionData = [
                'id_estudiante' => $id_estudiante,
                'id_distribucion_academica' => $id_distribucion_academica,
                'id_schoolYear' => $id_schoolYear,
                'id_pago' => $id_pago_inscripcion,
                'condicion_inicial' => 'Inscrito',
                'Condicion_final' => null,
                'activo' => 1
            ];

            if (!$this->inscripciones->save($inscripcionData)) {
                log_message('error', '❌ Error guardando inscripción: ' . json_encode($this->inscripciones->errors()));
                return redirect()->back()->with('error', 'Error al registrar la inscripción.');
            }

            log_message('debug', "✅ Inscripción guardada para estudiante: $id_estudiante");

            // Registrar mensualidades si paga el año completo
            if ($pagoCompleto) {
                log_message('info', "➡️ Registrando mensualidades para estudiante: $id_estudiante");

                for ($mes = 1; $mes <= 12; $mes++) {
                    $pagoMensualidadData = [
                        'id_concepto' => $conceptoMensualidad['id'],
                        'id_responsable' => $id_responsable,
                        'id_estudiante' => $id_estudiante,
                        'monto' => $monto_mensualidad,
                        'metodo_pago' => $metodo_pago,
                        'estado' => 'Pagado',
                        'fecha_pago' => date('Y-m-d')
                    ];

                    if (!$this->pagos->save($pagoMensualidadData)) {
                        log_message('error', '❌ Error guardando pago mensualidad: ' . json_encode($this->pagos->errors()));
                        return redirect()->back()->with('error', 'Error al registrar las mensualidades.');
                    }

                    $id_pago_mensualidad = $this->pagos->getInsertID();

                    // 🔥 Añadir pago mensualidad a la lista para factura
                    $pagosParaFactura[] = $id_pago_mensualidad;
                    $totalFactura += $monto_mensualidad;
                    $detallesFactura[] = [
                        'concepto' => 'Mensualidad - Mes ' . $mes,
                        'estudiante' => $estudiante['nombre'] . ' ' . $estudiante['apellido'],
                        'monto' => $monto_mensualidad
                    ];
                }

                log_message('debug', "✅ Mensualidades completas registradas para estudiante: $id_estudiante");
            }
        }

        // 🔥 Generar la factura con todos los pagos
        if (!empty($pagosParaFactura)) {
            $id_factura = $this->generarFactura($id_responsable, $pagosParaFactura, $totalFactura, $detallesFactura);

            // Redirigir a una página que muestre la factura con opción de imprimir
            if ($id_factura) {
                return redirect()->to(base_url('/inscripciones/verFactura/' . $id_factura))
                    ->with('success', 'Inscripción y pagos registrados correctamente. Puede imprimir la factura ahora.');
            }
        }

        log_message('info', '✅ Inscripción, pagos y factura registrados correctamente.');

        return redirect()->to(base_url('/inscripciones'))->with('success', 'Inscripción y pagos registrados correctamente.');
    }


    //Genera una factura para los pagos realizados
    private function generarFactura($id_responsable, $pagos, $total, $detalles)
    {
        // Cargar el modelo de facturas
        $facturaModel = new \App\Models\FacturaModel();

        // Obtener datos del responsable
        $responsable = $this->responsables->find($id_responsable);

        // Generar número de factura único
        $numeroFactura = 'FAC-' . date('Ymd') . '-' . uniqid();

        // Datos de la factura
        $facturaData = [
            'numero_factura' => $numeroFactura,
            'id_responsable' => $id_responsable,
            'nombre_responsable' => $responsable['nombre_padre'] . ' ' . $responsable['apellido_padre'],
            'fecha_emision' => date('Y-m-d'),
            'total' => $total,
            'estado' => 'Pagado',
            'detalles' => json_encode($detalles),
            'pagos_relacionados' => json_encode($pagos)
        ];

        // Guardar la factura
        if (!$facturaModel->save($facturaData)) {
            log_message('error', '❌ Error generando factura: ' . json_encode($facturaModel->errors()));
            return false;
        }

        $id_factura = $facturaModel->getInsertID();
        log_message('debug', "✅ Factura generada correctamente, ID: $id_factura");

        // Actualizar los pagos con el ID de la factura
        foreach ($pagos as $id_pago) {
            $this->pagos->update($id_pago, ['id_factura' => $id_factura]);
        }

        return $id_factura;
    }


    //Genera e imprime un PDF de la factura
    public function imprimirFactura($id_factura)
    {
        // Cargar el modelo de facturas
        $facturaModel = new \App\Models\FacturaModel();

        // Obtener los datos de la factura
        $factura = $facturaModel->find($id_factura);

        if (!$factura) {
            return redirect()->back()->with('error', 'Factura no encontrada.');
        }

        // Decodificar los detalles de la factura
        $detalles = json_decode($factura['detalles'], true);

        // Crear una instancia de TCPDF con valores explícitos en lugar de constantes
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Establecer información del documento
        $pdf->SetCreator('EDSN Sistema');
        $pdf->SetAuthor('Sistema EDSN');
        $pdf->SetTitle('Factura #' . $factura['numero_factura']);
        $pdf->SetSubject('Factura');
        $pdf->SetKeywords('Factura, Pago, EDSN');

        // Eliminar cabecera y pie de página predeterminados
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Establecer márgenes
        $pdf->SetMargins(15, 15, 15);

        // Establecer saltos de página automáticos
        $pdf->SetAutoPageBreak(true, 15);

        // Establecer la fuente
        $pdf->SetFont('helvetica', '', 10);

        // Añadir una página
        $pdf->AddPage();

        // Logo de la escuela (ajusta la ruta según donde esté tu logo)
        $logoPath = FCPATH . 'assets/img/logo.png';
        if (file_exists($logoPath)) {
            $pdf->Image($logoPath, 15, 15, 30, 0, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        }

        // Título de la factura
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'FACTURA', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 6, 'Nº: ' . $factura['numero_factura'], 0, 1, 'C');
        $pdf->Ln(10);

        // Información de la escuela
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 6, 'ESCUELA EDSN', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 6, 'Dirección: Calle Principal #123', 0, 1, 'L');
        $pdf->Cell(0, 6, 'Teléfono: (123) 456-7890', 0, 1, 'L');
        $pdf->Cell(0, 6, 'Email: info@edsn.edu', 0, 1, 'L');
        $pdf->Ln(5);

        // Información del cliente
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 6, 'DATOS DEL CLIENTE', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 6, 'Responsable: ' . $factura['nombre_responsable'], 0, 1, 'L');
        $pdf->Cell(0, 6, 'Fecha de emisión: ' . date('d/m/Y', strtotime($factura['fecha_emision'])), 0, 1, 'L');
        $pdf->Ln(5);

        // Tabla de detalles
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 6, 'DETALLES DE LA FACTURA', 0, 1, 'L');
        $pdf->Ln(2);

        // Cabecera de la tabla
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(90, 7, 'Concepto', 1, 0, 'C', true);
        $pdf->Cell(60, 7, 'Estudiante', 1, 0, 'C', true);
        $pdf->Cell(30, 7, 'Monto', 1, 1, 'C', true);

        // Agrupar detalles por concepto y estudiante
        $detallesAgrupados = [];
        foreach ($detalles as $detalle) {
            // Si es mensualidad, extraemos el estudiante para agrupar
            if (strpos($detalle['concepto'], 'Mensualidad') !== false) {
                $key = 'Mensualidad-' . $detalle['estudiante'];
                if (!isset($detallesAgrupados[$key])) {
                    $detallesAgrupados[$key] = [
                        'concepto' => 'Mensualidades (Anual)',
                        'estudiante' => $detalle['estudiante'],
                        'monto' => 0,
                        'count' => 0
                    ];
                }
                $detallesAgrupados[$key]['monto'] += $detalle['monto'];
                $detallesAgrupados[$key]['count']++;
            } else {
                // Para otros conceptos como inscripción, los mantenemos individuales
                $key = $detalle['concepto'] . '-' . $detalle['estudiante'] . '-' . uniqid();
                $detallesAgrupados[$key] = $detalle;
            }
        }

        // Contenido de la tabla
        $pdf->SetFont('helvetica', '', 10);
        foreach ($detallesAgrupados as $detalle) {
            $concepto = $detalle['concepto'];
            // Si es mensualidad agrupada, añadimos el número de meses
            if (isset($detalle['count']) && $detalle['count'] > 0) {
                $concepto = 'Mensualidades (' . $detalle['count'] . ' meses)';
            }

            $pdf->Cell(90, 7, $concepto, 1, 0, 'L');
            $pdf->Cell(60, 7, $detalle['estudiante'], 1, 0, 'L');
            $pdf->Cell(30, 7, '$' . number_format($detalle['monto'], 2), 1, 1, 'R');
        }

        // Total
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(150, 7, 'TOTAL', 1, 0, 'R', true);
        $pdf->Cell(30, 7, '$' . number_format($factura['total'], 2), 1, 1, 'R', true);

        // Pie de página con términos y condiciones
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(0, 6, 'TÉRMINOS Y CONDICIONES', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->MultiCell(0, 5, 'Esta factura es un comprobante de pago por los servicios educativos prestados. Los pagos realizados no son reembolsables. Para cualquier consulta, por favor contacte a la administración de la escuela.', 0, 'L', false);

        // Firma
        $pdf->Ln(15);
        $pdf->Line(15, $pdf->GetY(), 80, $pdf->GetY());
        $pdf->Cell(65, 6, 'Firma Autorizada', 0, 0, 'C');

        // Generar el PDF
        $pdfName = 'Factura_' . $factura['numero_factura'] . '.pdf';
        $pdf->Output($pdfName, 'D'); // 'D' para forzar la descarga
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
            return redirect()->to(base_url('/inscripciones'))->with('error', 'Factura no encontrada.');
        }

        // Decodificar los detalles de la factura
        $detalles = json_decode($factura['detalles'], true);

        $data = [
            'titulo' => 'Detalles de Factura',
            'factura' => $factura,
            'detalles' => $detalles
        ];

        echo view('header');
        echo view('inscripciones/ver_factura', $data);
        echo view('footer');
    }

    
    //Obtiene las mensualidades pendientes de los estudiantes de un responsable
    public function obtenerMensualidadesPendientes()
    {
        $id_responsable = $this->request->getGet('id_responsable');
        $id_schoolYear = $this->request->getGet('id_schoolYear');

        // 🔹 Validar parámetros obligatorios
        if (!$id_responsable || !$id_schoolYear) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Faltan parámetros requeridos (responsable o año escolar).'
            ]);
        }

        try {
            // 🔹 Obtener estudiantes inscritos del responsable en el año escolar actual
            //$estudiantes = $this->inscripciones->getEstudiantesInscritos($id_responsable, $id_schoolYear);
            $estudiantes = $this->inscripciones->getEstudiantesInscritosPorResponsable($id_responsable, $id_schoolYear);


            if (empty($estudiantes)) {
                return $this->response->setJSON([
                    'status'  => 'empty',
                    'message' => 'No hay estudiantes inscritos para este responsable en el año escolar seleccionado.'
                ]);
            }

            // 🔹 Recorremos los estudiantes para obtener sus meses pendientes
            $resultado = [];
            foreach ($estudiantes as $estudiante) {
                $mesesPendientes = $this->pagos->getMesesPendientes($estudiante['id'], $id_schoolYear);

                if (!empty($mesesPendientes)) {
                    $resultado[] = [
                        'id'               => $estudiante['id'],
                        'nombre'           => $estudiante['nombre'],
                        'apellido'         => $estudiante['apellido'],
                        'curso'            => $estudiante['curso'], // 👈 Asegúrate que venga en la consulta de inscripciones
                        'meses_pendientes' => $mesesPendientes
                    ];
                }
            }

            if (empty($resultado)) {
                return $this->response->setJSON([
                    'status'  => 'empty',
                    'message' => 'No hay mensualidades pendientes para los estudiantes de este responsable.'
                ]);
            }

            // ✅ Respuesta exitosa
            return $this->response->setJSON([
                'status' => 'success',
                'data'   => $resultado
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener mensualidades pendientes: ' . $e->getMessage());

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Ha ocurrido un error inesperado. Intente nuevamente más tarde.'
            ]);
        }
    }

    //Registra el pago de mensualidades
    public function registrarPagoMensualidad()
    {
        $id_responsable = $this->request->getPost('id_responsable');
        $id_schoolYear = $this->request->getPost('id_schoolYear');
        $metodo_pago = $this->request->getPost('metodo_pago');
        $estudiantes = $this->request->getPost('estudiantes');
        $meses = $this->request->getPost('meses');

        if (!$id_responsable || !$metodo_pago || !$estudiantes || !$meses) {
            return redirect()->back()->with('error', 'Faltan datos requeridos para procesar el pago.');
        }

        $responsable = $this->responsables->find($id_responsable);
        if (!$responsable) {
            return redirect()->back()->with('error', 'Responsable no encontrado.');
        }

        $pagosParaFactura = [];
        $totalFactura = 0;
        $detallesFactura = [];

        foreach ($estudiantes as $id_estudiante) {

            if (!isset($meses[$id_estudiante]) || empty($meses[$id_estudiante])) {
                continue;
            }

            $estudiante = $this->estudiantes->find($id_estudiante);
            if (!$estudiante) {
                continue;
            }

            $conceptoMensualidad = $this->conceptoPagos->where('nombre', 'Mensualidad')->first();
            if (!$conceptoMensualidad) {
                return redirect()->back()->with('error', 'Concepto de mensualidad no encontrado.');
            }

            foreach ($meses[$id_estudiante] as $mes) {

                $pagoMensualidadData = [
                    'id_concepto'    => $conceptoMensualidad['id'],
                    'id_responsable' => $id_responsable,
                    'id_estudiante'  => $id_estudiante,
                    'id_schoolYear'  => $id_schoolYear, // ⬅️ Aquí agregas el año escolar al pago
                    'monto'          => $conceptoMensualidad['monto'],
                    'metodo_pago'    => $metodo_pago,
                    'estado'         => 'Pagado',
                    'fecha_pago'     => date('Y-m-d'),
                    'mes'            => $mes
                ];


                if (!$this->pagos->save($pagoMensualidadData)) {
                    log_message('error', '❌ Error guardando pago mensualidad: ' . json_encode($this->pagos->errors()));
                    return redirect()->back()->with('error', 'Error al registrar el pago de mensualidad.');
                }

                $id_pago_mensualidad = $this->pagos->getInsertID();

                $pagosParaFactura[] = $id_pago_mensualidad;
                $totalFactura += $conceptoMensualidad['monto'];

                $nombreMes = $this->obtenerNombreMes($mes);

                $detallesFactura[] = [
                    'concepto' => 'Mensualidad - ' . $nombreMes,
                    'estudiante' => $estudiante['nombre'] . ' ' . $estudiante['apellido'],
                    'monto' => $conceptoMensualidad['monto']
                ];
            }
        }

        if (!empty($pagosParaFactura)) {
            $id_factura = $this->generarFactura($id_responsable, $pagosParaFactura, $totalFactura, $detallesFactura);

            if ($id_factura) {
                return redirect()->to(base_url('/inscripciones/verFactura/' . $id_factura))
                    ->with('success', 'Pagos de mensualidades registrados correctamente. Puede imprimir la factura ahora.');
            }
        }

        return redirect()->to(base_url('/inscripciones/mensualidades'))
            ->with('success', 'Pagos de mensualidades registrados correctamente.');
    }


    //Obtiene el nombre del mes según su número
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

    //Muestra la página para pagar mensualidades
    public function mensualidades()
    {
        $data = [
            'titulo' => 'Pago de Mensualidades',
            'responsables' => $this->responsables->findAll(),
            'schoolYears' => $this->schoolYear->findAll()
        ];

        echo view('header');
        echo view('inscripciones/pagar_mensualidad', $data);
        echo view('footer');
    }
}
        // El resto del código permanece igual

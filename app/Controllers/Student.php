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
use App\Models\UsuariosModel;

class Student extends BaseController
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
    protected $usuarios;

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
        $this->usuarios = new UsuariosModel();
    }




    public function perfil()
    {
        $data = ['titulo' => 'Mi Perfil'];

        echo view('header');
        echo view('usuarios/students/perfil', $data);
        echo view('footer');
    }

    //====> VISTA MIS PAGOS
    public function mis_pagos()
    {
        $usuario = session()->get('usuario_id');

        $usuarioData = $this->usuarios->getUsuarioCompleto($usuario);

        if (!isset($usuarioData['estudiantes_id'])) {
            return redirect()->to(base_url('home'))
                ->with('error', 'Estudiante no encontrado.');
        }

        $idEstudiante = $usuarioData['estudiantes_id'];

        $schoolYear = $this->schoolYear
            ->where('estado', 'En curso')
            ->first();

        $estudiante = $this->estudiantes
            ->find($idEstudiante);

        // Inscripción actual
        $inscripcion = $this->inscripciones
            ->select('
            inscripciones.*,
            cursos_base.nombre_curso
        ')
            ->join('cursos', 'cursos.id = inscripciones.id_curso')
            ->join('cursos_base', 'cursos_base.id = cursos.id_cursos_base')
            ->where('inscripciones.id_estudiante', $idEstudiante)
            ->where('inscripciones.id_schoolYear', $schoolYear['id'])
            ->where('inscripciones.activo', 1)
            ->first();

        $estudiante['curso'] = $inscripcion['nombre_curso'] ?? 'No disponible';

        // Configuración mensualidad
        $conceptoMensualidad = $this->conceptoPagos
            ->where('nombre', 'Mensualidad')
            ->first();

        $configMensualidad = null;
        $montoMensualidad = 0;

        if ($conceptoMensualidad) {

            $configMensualidad = $this->conceptoConfig
                ->where('id_concepto', $conceptoMensualidad['id'])
                ->where('id_schoolYear', $schoolYear['id'])
                ->first();

            $montoMensualidad = $configMensualidad['monto'] ?? 0;
        }

        // Meses configurados
        $meses = $this->meses
            ->orderBy('orden', 'ASC')
            ->findAll();

        // Pagos realizados
        $pagosPorMes = [];

        if ($conceptoMensualidad) {

            $pagos = $this->pagos
                ->where('id_estudiante', $idEstudiante)
                ->where('id_schoolYear', $schoolYear['id'])
                ->where('id_concepto', $conceptoMensualidad['id'])
                ->where('estado', 'Pago')
                ->findAll();

            foreach ($pagos as $pago) {

                $pagosPorMes[$pago['mes']] = [
                    'fecha_pago' => $pago['fecha_pago'],
                    'id_pago'    => $pago['id']
                ];
            }
        }

        $data = [
            'titulo_1' => 'ESTUDIANTE',
            'titulo_2' => 'MIS PAGOS',

            'estudiante' => $estudiante,
            'schoolYear' => $schoolYear,

            'monto_mensualidad' => $montoMensualidad,

            'meses' => $meses,
            'pagosPorMes' => $pagosPorMes
        ];

        echo view('header');
        echo view('usuarios/students/mis_pagos', $data);
        echo view('footer');
    }

    public function calificaciones()
    {
        $data['titulo'] = 'Mis Calificaciones';

        echo view('header');
        echo view('usuarios/students/calificaciones', $data);
        echo view('footer');
    }

    public function asistencia()
    {
        $data['titulo'] = 'Mi Asistencia';

        echo view('header');
        echo view('usuarios/students/asistencia', $data);
        echo view('footer');
    }

    public function pagos()
    {
        $data['titulo'] = 'Mis Pagos';

        echo view('header');
        echo view('usuarios/students/pagos', $data);
        echo view('footer');
    }
}

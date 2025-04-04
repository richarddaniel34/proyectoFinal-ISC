<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\CompetenciasModel;
use App\Models\PeriodosModel;
use App\Models\DistribucionAsignaturasModel;
use App\Models\InscripcionesModel;
use App\Models\CalificacionesModel;

class Calificaciones extends BaseController
{
    protected $competencias;
    protected $periodos;
    protected $distribucionasignaturas;
    protected $inscripciones;
    protected $calificaciones;

    public function __construct()
    {
        $this->competencias = new CompetenciasModel();
        $this->periodos = new PeriodosModel();
        $this->distribucionasignaturas = new DistribucionAsignaturasModel();
        $this->inscripciones = new InscripcionesModel();
        $this->calificaciones = new CalificacionesModel();
    }

    /**
     * Vista principal del módulo de calificaciones.
     */
    public function index()
    {
        // 🔹 Ejemplo de datos básicos para la vista
        $competencias = $this->competencias->findAll();
        $periodos = $this->periodos->findAll();
        $distribucionasignaturas = $this->distribucionasignaturas->findAll();
        
        $data = [
            'titulo'       => 'Gestión de Calificaciones',
            'competencias' => $competencias,
            'periodos'     => $periodos,
            'asignaturas'  => $distribucionasignaturas,
            // Puedes agregar más data si quieres cargar estudiantes o calificaciones directamente
        ];

        // Carga las vistas con header y footer (opcional)
        echo view('header');
        echo view('calificaciones/calificaciones', $data);
        echo view('footer');
    }
}

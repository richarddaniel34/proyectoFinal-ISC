<?php

namespace App\Controllers;

use App\Models\PersonalModel;

class Home extends BaseController
{
    protected $personal;

    public function __construct()
    {
        $this->personal = new PersonalModel();
    }


    public function index()
    {
        // 🔥 Definir las categorías según el nombramiento
        $personalAdministrativo = [
            'Director/a',
            'Coordinador/a',
            'Secretaria/o',
            'Digitador/a',
            'Enfermera',
            'Contable',
            'Administrador/a',
            'Recepcionista'
        ];
    
        $personalDocente = ['Docente', 'Maestro/a Auxiliar'];
        $personalApoyo = ['Conserje', 'Jardinero', 'Portero'];
        
        // 🔥 Definir la condición exacta como está en la base de datos
        $condicionPagado = "Pagado por la escuela"; // Asegúrate de que sea exacto
    
        // 🔥 Contar administrativos activos
        $totalAdministrativos = $this->personal
            ->join('nombramiento', 'nombramiento.id = personal.nombramiento')
            ->where('personal.activo', 1)
            ->whereIn('nombramiento.nombre', $personalAdministrativo)
            ->countAllResults();
    
        // 🔥 Contar docentes activos
        $totalDocentes = $this->personal
            ->join('nombramiento', 'nombramiento.id = personal.nombramiento')
            ->where('personal.activo', 1)
            ->whereIn('nombramiento.nombre', $personalDocente)
            ->countAllResults();
    
        // 🔥 Contar personal de apoyo activos
        $totalApoyo = $this->personal
            ->join('nombramiento', 'nombramiento.id = personal.nombramiento')
            ->where('personal.activo', 1)
            ->whereIn('nombramiento.nombre', $personalApoyo)
            ->countAllResults();
    
        // 🔥 Contar personal "Pagado por la escuela"
        $totalPagadoCentro = $this->personal
            ->join('condicion', 'condicion.id = personal.condicion')
            ->where('personal.activo', 1)
            ->where('condicion.nombre', $condicionPagado) // Comparación exacta
            ->countAllResults();
    
        // 🔥 Pasar los datos a la vista
        $data = [
            'titulo' => 'Dashboard',
            'totalAdministrativos' => $totalAdministrativos,
            'totalDocentes' => $totalDocentes,
            'totalApoyo' => $totalApoyo,
            'totalPagadoCentro' => $totalPagadoCentro // Asegúrate de enviarlo correctamente
        ];
    
        echo view('header');
        echo view('home', $data);
        echo view('footer');
    }
    




}

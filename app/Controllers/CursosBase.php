<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\GradosModel;
use App\Models\CursosbaseModel;
use App\Models\SeccionesModel;
use App\Models\EscuelaModel;



class cursosbase extends BaseController
{
    protected $grado;
    protected $cursosBase;
    protected $secciones;
    protected $escuela;

    public function __construct()
    {
        $this->grado = new GradosModel();
        $this->cursosBase = new CursosBaseModel();
        $this->secciones = new SeccionesModel();
        $this->escuela = new EscuelaModel();
    }


    //Metodo que rederiza la vista principal del modulo
    public function index()
    {
        $id_escuela = session('id_escuela'); // Obtiene el ID de la escuela desde la sesión

        $data = [
            'titulo'  => 'Grados y Secciones',
            'cursos'  => $this->cursosBase->obtenerCursosBasePorEscuela($id_escuela) // Lo pasas al modelo
        ];

        echo view('header');
        echo view('cursosbase/cursosbase', $data);
        echo view('footer');
    }


    private function obtenerTodosLosGrados()
    {
        // Solo cargar grados activos desde la base de datos
        $gradosBD = $this->grado
            ->where('activo', 1)
            ->orderBy('orden', 'ASC')
            ->findAll();

        // Niveles permitidos según el nombre del grado
        $nivelesPorNombre = [
            'Maternal'      => ['Inicial', 'Primaria'],
            'Kinder'        => ['Inicial', 'Primaria'],
            'Pre-Primario'  => ['Inicial', 'Primaria'],
            'Primero'       => ['Primaria', 'Secundaria'],
            'Segundo'       => ['Primaria', 'Secundaria'],
            'Tercero'       => ['Primaria', 'Secundaria'],
            'Cuarto'        => ['Primaria', 'Secundaria'],
            'Quinto'        => ['Primaria', 'Secundaria'],
            'Sexto'         => ['Primaria', 'Secundaria'],
            // Puedes agregar aquí más en el futuro si lo deseas
        ];

        // Añadir el campo 'niveles' a cada grado desde el arreglo
        foreach ($gradosBD as &$grado) {
            $grado['niveles'] = $nivelesPorNombre[$grado['nombre']] ?? [];
        }

        return $gradosBD;
    }





    //Metodo que renderiza la vista para agregar nuevos registros
    


    







    public function editar($id) {}
    public function actualizar($id) {}
}

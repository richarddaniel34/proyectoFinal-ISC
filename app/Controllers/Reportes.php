<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AsignaturaModel;
use App\Models\PersonalModel;

use TCPDF;

class Reportes extends BaseController
{
    protected $personal;

    public function __construct()
    {
        $this->personal = new PersonalModel();
    }

    public function index()
    {

        $data = ['titulo' => 'Reportes'];

        echo view('header');
        echo view('reportes/reportes', $data);
        echo view('footer');
    }


    /* 
    BLOQUE DE ESTADISTICAS:
    */


    public function estadisticas()
    {

        $data = ['titulo' => 'Estadisticas'];

        echo view('header');
        echo view('reportes/estadisticas/estadisticas', $data);
        echo view('footer');
    }

    public function estadistica_personal()
    {

        $data = ['titulo' => 'Personal'];

        echo view('header');
        echo view('reportes/estadisticas/estadistica_personal', $data);
        echo view('footer');
    }

    public function estadistica_estudiantes()
    {

        $data = ['titulo' => 'Personal'];

        echo view('header');
        echo view('reportes/estadisticas/estadistica_personal', $data);
        echo view('footer');
    }




    /* 
    BLOQUE DE LISTADOS:
    */

    public function listados()
    {

        $data = ['titulo' => 'listados'];

        echo view('header');
        echo view('reportes/listados/listados', $data);
        echo view('footer');
    }

    /*
    public function listado_personal()
    {
        $personal = $this->personal->getListadoConAsignaturas();
        $data = [
            'personal' => $personal,
            'titulo' => 'Listado de Personal'
        ];

        // Solo la tabla, sin header ni footer
        echo view('reportes/listados/listado_personal', $data);
    }
*/
    public function listado_personal_pdf()
    {
        $personal = $this->personal->getListadoConAsignaturas();

        $html = '<h2>Listado de Personal</h2>';
        $html .= '<style>
        table { border-collapse: collapse; width: 100%; font-size: 10pt; }
        th, td { border: 1px solid #000; padding: 4px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>';
        $html .= '<table>';
        $html .= '<tr>
            <th>Nombre</th><th>Apellido</th><th>Cédula</th>
            <th>Fecha Nac.</th><th>Sexo</th><th>Condición</th>
            <th>Nombramiento</th><th>Función</th><th>Grado Académico</th>
            <th>Asignatura</th>
          </tr>';

        foreach ($personal as $p) {
            $html .= '<tr>
                <td>' . $p['nombre'] . '</td>
                <td>' . $p['apellido'] . '</td>
                <td>' . $p['cedula'] . '</td>
                <td>' . $p['fecha_nac'] . '</td>
                <td>' . $p['sexo'] . '</td>
                <td>' . $p['condicion'] . '</td>
                <td>' . $p['nombramiento'] . '</td>
                <td>' . $p['funcion'] . '</td>
                <td>' . $p['grado_academico'] . '</td>
                <td>' . ($p['asignatura'] ?? '-') . '</td>
              </tr>';
        }
        $html .= '</table>';

        if (ob_get_length()) ob_end_clean(); // limpiar buffer

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="Listado_Personal.pdf"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        $pdf = new \TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('Listado_Personal.pdf', 'I');
    }
}

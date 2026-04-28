<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\DistritoEducativoModel;

class distritoEducativo extends BaseController
{
    protected $distritoEducativo;

    public function __construct()
    {

        $this->distritoEducativo = new DistritoEducativoModel();
    }



    public function index()
    {
        $distrito = $this->distritoEducativo->first();

        $data = [
            'titulo' => 'Datos Distrito Educativo',
            'distrito' => $distrito
        ];

        echo view('header');
        echo view('distritoeducativo/distritoeducativo', $data);
        echo view('footer');
    }


    public function nuevo()
    {


        echo view('header');
        echo view('grados/nuevo', /*$data*/);
        echo view('footer');
    }


    public function guardar()
    {
        // Validar campos requeridos (puedes ajustarlo según tus reglas)
        $validacion = $this->validate([
            'telefono' => 'required|min_length[10]',
            'director_distrito' => 'required|min_length[3]',
            'tecnico_acreditacion' => 'required|min_length[3]'
        ]);

        if (!$validacion) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Capturar datos del formulario
        $data = [
            'telefono' => $this->request->getPost('telefono'),
            'director_distrito' => $this->request->getPost('director_distrito'),
            'tecnico_acreditacion' => $this->request->getPost('tecnico_acreditacion')
        ];

        // Verificar si ya hay un registro (se asume que solo debe haber uno)
        $distrito = $this->distritoEducativo->first();

        if ($distrito) {
            // Actualizar el único registro existente
            $this->distritoEducativo->update($distrito['id'], $data);
        } else {
            // Insertar nuevo registro (solo si aún no existe)
            // Incluye regional y distrito como están en el formulario readonly
            $data['regional'] = $this->request->getPost('regional');
            $data['distrito'] = $this->request->getPost('distrito');
            $this->distritoEducativo->insert($data);
        }

        return redirect()->to('/distritoeducativo')->with('success', 'Guardado correctamente');

    }












    /*Maria Elizabeth Rosario Tiburcio, M.A. */
}

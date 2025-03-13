<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ResponsablesModel;

class Responsables extends BaseController
{
    protected $responsables;

    public function __construct()
    {
        $this->responsables = new ResponsablesModel();
    }

    public function index($activo = 1)
    {
        // SELECT * FROM schoolyear WHEN activo = $activo
        $responsables = $this->responsables->where('activo', $activo)->findAll();

        $data = ['titulo' => 'Padres/Madres/tutores', 'datos' => $responsables];

        echo view('header');
        echo view('responsables/responsables', $data);
        echo view('footer');
    }

    public function nuevo()
    {

        $data = ['titulo' => 'Registro de Padres/Madres/tutores',];

        echo view('header');
        echo view('responsables/nuevo', $data);
        echo view('footer');
    }




    public function insertar()
    {

        $this->responsables->save([
            'nombre_padre' => $this->request->getPost('nombre_padre'),
            'apellido_padre' => $this->request->getPost('apellido_padre'),
            'cedula_padre' => $this->request->getPost('cedula_padre'),
            'telefono_padre' => $this->request->getPost('telefono_padre'),
            'direccion_padre' => $this->request->getPost('direccion_padre'),
            'trabajo_padre' => $this->request->getPost('trabajo_padre'),
            'telefono_trabajo_padre' => $this->request->getPost('telefono_trabajo_padre'),
            'nombre_madre' => $this->request->getPost('nombre_madre'),
            'apellido_madre' => $this->request->getPost('apellido_madre'),
            'cedula_madre' => $this->request->getPost('cedula_madre'),
            'telefono_madre' => $this->request->getPost('telefono_madre'),
            'direccion_madre' => $this->request->getPost('direccion_madre'),
            'trabajo_madre' => $this->request->getPost('trabajo_madre'),
            'telefono_trabajo_madre' => $this->request->getPost('telefono_trabajo_madre'),
            'nombre_tutor' => $this->request->getPost('nombre_tutor'),
            'apellido_tutor' => $this->request->getPost('apellido_tutor'),
            'cedula_tutor' => $this->request->getPost('cedula_tutor'),
            'telefono_tutor' => $this->request->getPost('telefono_tutor'),
            'direccion_tutor' => $this->request->getPost('direccion_tutor'),
            'trabajo_tutor' => $this->request->getPost('trabajo_tutor'),
            'telefono_trabajo_tutor' => $this->request->getPost('telefono_trabajo_tutor')
        ]);

        return redirect()->to(base_url() . '/responsables');
    }








    public function editar($id)
    {

        // Buscar el responsable en la base de datos
        $responsable = $this->responsables->find($id);

        // Verificar si el responsable existe
        if (!$responsable) {
            return redirect()->to(base_url() . '/responsables')->with('error', 'El responsable no existe.');
        }

        // Pasar los datos a la vista
        $data = [
            'titulo' => 'Edición de Padres/Madres/Tutores',
            'responsable' => $responsable
        ];

        echo view('header');
        echo view('responsables/editar', $data);
        echo view('footer');
    }







    public function actualizar($id)
    {
        // Asegurar que se recibió el ID
        if (!$id) {
            return redirect()->to(base_url() . '/responsables')->with('error', 'ID no válido.');
        }

        // Obtener los datos del formulario
        $datos = [
            'id' => $id, // Asegurar que se actualiza el registro correcto
            'nombre_padre' => $this->request->getPost('nombre_padre'),
            'apellido_padre' => $this->request->getPost('apellido_padre'),
            'cedula_padre' => $this->request->getPost('cedula_padre'),
            'telefono_padre' => $this->request->getPost('telefono_padre'),
            'direccion_padre' => $this->request->getPost('direccion_padre'),
            'trabajo_padre' => $this->request->getPost('trabajo_padre'),
            'telefono_trabajo_padre' => $this->request->getPost('telefono_trabajo_padre'),
            'nombre_madre' => $this->request->getPost('nombre_madre'),
            'apellido_madre' => $this->request->getPost('apellido_madre'),
            'cedula_madre' => $this->request->getPost('cedula_madre'),
            'telefono_madre' => $this->request->getPost('telefono_madre'),
            'direccion_madre' => $this->request->getPost('direccion_madre'),
            'trabajo_madre' => $this->request->getPost('trabajo_madre'),
            'telefono_trabajo_madre' => $this->request->getPost('telefono_trabajo_madre'),
            'nombre_tutor' => $this->request->getPost('nombre_tutor'),
            'apellido_tutor' => $this->request->getPost('apellido_tutor'),
            'cedula_tutor' => $this->request->getPost('cedula_tutor'),
            'telefono_tutor' => $this->request->getPost('telefono_tutor'),
            'direccion_tutor' => $this->request->getPost('direccion_tutor'),
            'trabajo_tutor' => $this->request->getPost('trabajo_tutor'),
            'telefono_trabajo_tutor' => $this->request->getPost('telefono_trabajo_tutor')
        ];

        // Actualizar en la base de datos
        $this->responsables->update($id, $datos);

        return redirect()->to(base_url() . '/responsables')->with('success', 'Responsable actualizado con éxito.');
    }









    public function eliminar($id)
    {
        $this->responsables->update($id, ['activo' => 0]);
        return redirect()->to(base_url() . '/responsables');
    }



    public function eliminados($activo = 0)
    {

        // SELECT * FROM schoolyear WHEN activo = $activo
        $responsables = $this->responsables->where('activo', $activo)->findAll();

        $data = ['titulo' => 'Padres/Madres/tutores', 'datos' => $responsables];

        echo view('header');
        echo view('responsables/eliminados', $data);
        echo view('footer');
    }

    public function restaurar($id)
    {
        $this->responsables->update($id, ['activo' => 1]);
        return redirect()->to(base_url() . 'responsables/eliminados');
    }


    public function visualizar($id) {}


    public function buscar()
    {
        $search = $this->request->getGet('search');

        // Si el usuario no escribió nada, obtenemos todos los responsables
        if (empty($search)) {
            $responsables = $this->responsables->findAll(); // 🔥 Traer todos los registros
        } else {
            // Si el usuario escribió algo, filtrar con LIKE
            $responsables = $this->responsables
                ->like('nombre_padre', $search)
                ->orLike('apellido_padre', $search)
                ->orLike('nombre_madre', $search)
                ->orLike('apellido_madre', $search)
                ->findAll(10);  // Limitar a 10 resultados para búsquedas
        }

        //  Formatear los datos para Select2
        $data = [];
        foreach ($responsables as $responsable) {
            $data[] = [
                'id' => $responsable['id'],
                'text' => "{$responsable['nombre_padre']} {$responsable['apellido_padre']} / {$responsable['nombre_madre']} {$responsable['apellido_madre']}"
            ];
        }

        return $this->response->setJSON($data);
    }
}

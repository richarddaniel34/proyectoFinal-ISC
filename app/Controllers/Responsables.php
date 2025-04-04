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
        
        $data = [
            'titulo' => 'Registro de Padres/Madres/Tutores',
            'responsables' => []  // Aquí se pasa la variable de responsables (vacía en este caso)
        ];

        // Cargar las vistas con los datos
        echo view('header');
        echo view('responsables/nuevo', $data);
        echo view('footer');
    }






    public function insertar()
    {
        $responsables = $this->request->getPost('responsables'); // Recibe arreglo de responsables

        // Filtrar responsables vacíos (donde todos los campos clave están vacíos)
        $responsables = array_filter($responsables, function($responsable) {
            return !empty(trim($responsable['nombre'])) || !empty(trim($responsable['apellido'])) || !empty(trim($responsable['cedula']));
        });

        // Verificar si se quedó algún responsable válido
        if (empty($responsables)) {
            return redirect()->back()->withInput()->with('error', 'Debe agregar al menos un responsable válido.');
        }

        // Validar si la madre está completa
        $madreCompleta = false;
        foreach ($responsables as $responsable) {
            if (isset($responsable['tipo_responsable']) && $responsable['tipo_responsable'] == 'madre') {
                if (
                    !empty(trim($responsable['nombre'])) &&
                    !empty(trim($responsable['apellido'])) &&
                    !empty(trim($responsable['cedula'])) &&
                    !empty(trim($responsable['celular'])) &&
                    !empty(trim($responsable['direccion'])) &&
                    !empty(trim($responsable['contacto_emergencia']))
                ) {
                    $madreCompleta = true;
                    break;
                }
            }
        }

        // Si la madre no está completa, mostrar un error
        if (!$madreCompleta) {
            $errors = [];
            if (isset($responsables[1])) {
                if (empty(trim($responsables[1]['nombre']))) {
                    $errors['responsables.1.nombre'] = 'El nombre de la madre es obligatorio';
                }
                if (empty(trim($responsables[1]['apellido']))) {
                    $errors['responsables.1.apellido'] = 'El apellido de la madre es obligatorio';
                }
                if (empty(trim($responsables[1]['cedula']))) {
                    $errors['responsables.1.cedula'] = 'La cédula de la madre es obligatoria';
                }
                if (empty(trim($responsables[1]['celular']))) {
                    $errors['responsables.1.celular'] = 'El celular de la madre es obligatorio';
                }
                if (empty(trim($responsables[1]['direccion']))) {
                    $errors['responsables.1.direccion'] = 'La dirección de la madre es obligatoria';
                }
                if (empty(trim($responsables[1]['contacto_emergencia']))) {
                    $errors['responsables.1.contacto_emergencia'] = 'El contacto de emergencia de la madre es obligatorio';
                }
            }
            
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        // Verificar cédulas duplicadas antes de intentar guardar
        foreach ($responsables as $index => $responsable) {
            if (!empty($responsable['cedula'])) {
                $existingResponsable = $this->responsables->where('cedula', $responsable['cedula'])->first();
                if ($existingResponsable) {
                    $errors = [];
                    $errors["responsables.{$index}.cedula"] = 'Esta cédula ya está registrada en el sistema';
                    return redirect()->back()->withInput()->with('errors', $errors);
                }
            }
        }

        // Insertar responsables
        $errores = [];
        foreach ($responsables as $index => $responsable) {
            // Intentar guardar cada responsable
            if (!$this->responsables->save($responsable)) {
                // Capturar errores de validación del modelo
                foreach ($this->responsables->errors() as $campo => $mensaje) {
                    $errores["responsables.{$index}.{$campo}"] = $mensaje;
                }
            }
        }

        // Si hay errores, redirigir con los errores
        if (!empty($errores)) {
            return redirect()->back()->withInput()->with('errors', $errores);
        }

        // Redirigir a la página de responsables con mensaje de éxito
        return redirect()->to(base_url() . '/responsables')->with('success', 'Responsables registrados correctamente.');
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
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'cedula' => $this->request->getPost('cedula'),
            'telefono' => $this->request->getPost('telefono'),
            'direccion' => $this->request->getPost('direccion'),
            'trabajo' => $this->request->getPost('trabajo'),
            'telefono_trabajo' => $this->request->getPost('telefono_trabajo')
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
            $responsables = $this->responsables->findAll(); // Traer todos los registros
        } else {
            // Si el usuario escribió algo, filtrar con LIKE
            $responsables = $this->responsables
                ->like('nombre', $search)
                ->orLike('apellido', $search)
                ->orLike('nombre', $search)
                ->orLike('apellido', $search)
                ->findAll(10);  // Limitar a 10 resultados para búsquedas
        }

        //  Formatear los datos para Select2
        $data = [];
        foreach ($responsables as $responsable) {
            $data[] = [
                'id' => $responsable['id'],
                'text' => "{$responsable['nombre']} 
                           {$responsable['apellido']} - 
                           {$responsable['cedula']}"
            ];
        }

        return $this->response->setJSON($data);
    }
}

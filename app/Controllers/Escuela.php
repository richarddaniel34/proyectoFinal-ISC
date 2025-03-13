<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EscuelaModel;
use App\Models\NivelesModel;
use App\Models\ModalidadModel;

class Escuela extends BaseController
{
    protected $escuela;
    protected $nivel;
    protected $modalidades;

    public function __construct()
    {
        $this->escuela = new EscuelaModel();
        $this->nivel = new NivelesModel();
        $this->modalidades = new ModalidadModel();
    }

    public function index($activo = 1)
    {
        $escuelas = $this->escuela->getEscuelasConDetalles($activo);



        $data = ['titulo' => 'Datos del Centro', 'datos' => $escuelas];

        echo view('header');
        echo view('escuela/escuela', $data);
        echo view('footer');
    }



    public function nuevo()
    {
        // Obtener las modalidad usando el modelo
        $modalidad = $this->modalidades->findAll();
        $niveles = $this->nivel->findAll();

        // Pasar los datos a la vista
        $data = [
            'titulo' => 'Registro de datos del centro educativo',
            'modalidad' => $modalidad,
            'niveles' => $niveles
        ];

        echo view('header');
        echo view('escuela/nuevo', $data);
        echo view('footer');
    }





    public function insertar()
    {
        $session = session();

        // Obtener datos del formulario
        $datos = [
            'nombre' => trim($this->request->getPost('nombre')),
            'id_nivel' => trim($this->request->getPost('id_nivel')),
            'id_modalidad' => trim($this->request->getPost('modalidad')),
            'codigo_gestion' => trim($this->request->getPost('codigo_gestion')),
            'codigo_plantel' => trim($this->request->getPost('codigo_plantel')),
            'rnc' => trim($this->request->getPost('rnc')),
            'regional_educacion' => trim($this->request->getPost('regional_educacion')),
            'distrito_educativo' => trim($this->request->getPost('distrito_educativo')),
            'email' => trim($this->request->getPost('email')),
            'telefono' => trim($this->request->getPost('telefono')),
            'redes' => trim($this->request->getPost('redes')),
            'direccion' => trim($this->request->getPost('direccion')),
            'web' => trim($this->request->getPost('web')),
            'logo' => 'assets/img/logo/default.png'
        ];

        // 🔥 Intentar guardar y validar automáticamente
        if (!$this->escuela->insert($datos)) {
            return redirect()->back()->withInput()->with('errors', $this->escuela->errors());
        }

        $session->setFlashdata('success', 'Escuela registrada correctamente.');
        return redirect()->to(base_url() . '/escuela');
    }













    public function editar($id)
    {

        log_message('debug', 'Editando escuela con ID: ' . $id);

        // Busca la escuela por su ID
        $escuela = $this->escuela->where('id', $id)->first();

        if (!$escuela) {
            return $this->response->setStatusCode(404)->setBody('Escuela no encontrada.');
        }

        // Conexión a la base de datos para obtener las modalidad
        $db = \Config\Database::connect();
        $query = $db->query("SELECT id, nombre FROM modalidad");
        $modalidad = $query->getResultArray();

        // Pasar las modalidad a la vista junto con los datos de la escuela
        $data = ['titulo' => 'Editar datos del centro educativo', 'datos' => $escuela, 'modalidad' => $modalidad];

        // Devolver la vista
        // $data = ['titulo' => 'Editar datos del centro educativo', 'datos' => $escuela];

        // Devolver solo la vista parcial del formulario de edición para el modal
        return view('escuela/editar', $data);
    }







    public function actualizar($id)
    {
        // Validar los datos
        $rules = [
            'nombre' => 'required',
            'modalidad' => 'required',
            'codigo-sigerd' => 'required',
            'codigo-plantel' => 'required',
            'rnc' => 'required',
            'regional' => 'required',
            'distrito' => 'required',
            'email' => 'required|valid_email',
            'telefono' => 'required',
            'direccion' => 'required',
            'web' => 'permit_empty|valid_url'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Obtener los datos actuales del registro
        $escuelaActual = $this->escuela->find($id);
        if (!$escuelaActual) {
            return redirect()->back()->with('error', 'Registro no encontrado.');
        }

        // Obtener datos del formulario
        $nombre = trim($this->request->getPost('nombre'));
        $codigoSigerd = trim($this->request->getPost('codigo-sigerd'));
        $codigoPlantel = trim($this->request->getPost('codigo-plantel'));
        $rnc = trim($this->request->getPost('rnc'));

        // Validar duplicados correctamente
        $duplicados = $this->escuela
            ->where('id !=', $id) // Excluir el mismo ID
            ->groupStart()
            ->where('codigo-sigerd', $codigoSigerd)
            ->orWhere('codigo-plantel', $codigoPlantel)
            ->orWhere('rnc', $rnc)
            ->groupEnd()
            ->findAll();

        $errores = [];

        foreach ($duplicados as $escuela) {
            if ($escuela['codigo-sigerd'] === $codigoSigerd && $escuela['id'] != $id) {
                if ($escuelaActual['codigo-sigerd'] === $codigoSigerd) {
                    //El usuario intentó cambiarlo por el mismo valor ya existente
                    $errores['codigo-sigerd'] = 'Este código ya está registrado y no se puede actualizar.';
                } else {
                    $errores['codigo-sigerd'] = 'El código SIGERD ya está registrado en otra escuela.';
                }
            }

            if ($escuela['codigo-plantel'] === $codigoPlantel && $escuela['id'] != $id) {
                $errores['codigo-plantel'] = 'El código del plantel ya está registrado en otra escuela.';
            }
            if ($escuela['rnc'] === $rnc && $escuela['id'] != $id) {
                if ($escuelaActual['rnc'] === $rnc) {
                    $errores['rnc'] = 'Este RNC ya está registrado y no se puede actualizar.';
                } else {
                    $errores['rnc'] = 'El RNC ya está registrado en otra escuela.';
                }
            }
        }

        error_log("Errores guardados en sesión: " . print_r($errores, true));

        // Agregar una verificación para que si NO hay errores, se limpien los mensajes previos
        if (!empty($errores)) {
            session()->set('errors', $errores);
            session()->set('error_id', $id);
            session()->set('codigo-sigerd', $codigoSigerd); // 🔥 Mantener el valor ingresado
            return redirect()->back()->withInput();
        } else {
            session()->remove('errors');
            session()->remove('codigo-sigerd'); // 🔥 Limpiar si no hay errores
        }


        // Mostrar en consola los valores antes de actualizar
        error_log("Antes de actualizar: ID={$id}, Codigo SIGERD={$codigoSigerd}, Codigo Plantel={$codigoPlantel}, RNC={$rnc}");


        // Verificar si los datos cambiaron antes de actualizar
        $datosActualizados = [
            'codigo-sigerd' => $codigoSigerd,
            'codigo-plantel' => $codigoPlantel,
            'rnc' => $rnc
        ];

        if ($this->escuela->update($id, $datosActualizados)) {
            session()->setFlashdata('success', 'Datos actualizados correctamente.');
        } else {
            session()->setFlashdata('error', 'No se realizaron cambios en los datos.');
        }

        return redirect()->to(base_url('/escuela'));
    }









    public function eliminar($id)
    {
        $this->escuela->update($id, ['activo' => 0]);
        return redirect()->to(base_url() . '/escuela');
    }



    public function eliminados($activo = 0)
    {
        $escuelas = $this->escuela->getEscuelasConDetalles($activo);

        $data = ['titulo' => 'Datos del Centro', 'datos' => $escuelas];

        echo view('header');
        echo view('escuela/eliminados', $data);
        echo view('footer');
    }

    public function restaurar($id)
    {
        $this->escuela->update($id, ['activo' => 1]);
        return redirect()->to(base_url() . 'escuela/eliminados');
    }


    public function visualizar($id)
    {

        log_message('debug', 'Editando escuela con ID: ' . $id);

        // Busca la escuela por su ID
        $escuela = $this->escuela->where('id', $id)->first();

        if (!$escuela) {
            return $this->response->setStatusCode(404)->setBody('Escuela no encontrada.');
        }

        // Conexión a la base de datos para obtener las modalidad
        $db = \Config\Database::connect();
        $query = $db->query("SELECT id, nombre FROM modalidad");
        $modalidad = $query->getResultArray();

        // Pasar las modalidad a la vista junto con los datos de la escuela
        $data = ['titulo' => 'visualizar datos del centro educativo', 'datos' => $escuela, 'modalidad' => $modalidad];

        // Devolver la vista
        // $data = ['titulo' => 'Editar datos del centro educativo', 'datos' => $escuela];

        // Devolver solo la vista parcial del formulario de edición para el modal
        return view('escuela/visualizar', $data);
    }
}

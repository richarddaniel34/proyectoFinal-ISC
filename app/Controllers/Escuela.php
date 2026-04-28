<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EscuelaModel;
use App\Models\NivelesModel;
use App\Models\ModalidadModel;
use App\Models\DistritoEducativoModel;

class Escuela extends BaseController
{
    protected $escuela;
    protected $nivel;
    protected $modalidades;
    protected $distrito_educativo;

    public function __construct()
    {
        $this->escuela = new EscuelaModel();
        $this->nivel = new NivelesModel();
        $this->modalidades = new ModalidadModel();
        $this->distrito_educativo = new DistritoEducativoModel();
    }


    public function index()
    {
        $data = ['titulo' => 'GESTION DE ESCUELA'];

        echo view('header');
        echo view('escuela/escuela', $data);
        echo view('footer');
    }

    public function info_escuela($activo = 1)
    {
        $escuelas = $this->escuela->getEscuelasConDetalles($activo);



        $data = ['titulo' => 'Datos del Centro', 'datos' => $escuelas];

        echo view('header');
        echo view('escuela/info-escuela', $data);
        echo view('footer');
    }



    public function nuevo()
    {
        // Obtener las modalidad usando el modelo
        $modalidad = $this->modalidades->findAll();
        $niveles = $this->nivel->findAll();
        $distritoEducativo = $this->distrito_educativo->first();


        // Definir valores de ENUM para tanda y tipo
        $tandas = [
            'Matutina' => 'Matutina',
            'Vespertina' => 'Vespertina',
            'Jornada Escolar Extendida (J.E.E.)' => 'Jornada Escolar Extendida (J.E.E.)',
            'Nocturno' => 'Nocturno'
        ];

        $tipos = [
            'Privado' => 'Privado',
            'Publico' => 'Publico'
        ];

        // Pasar los datos a la vista
        $data = [
            'titulo' => 'Registro de datos del centro educativo',
            'modalidad' => $modalidad,
            'niveles' => $niveles,
            'distritoEducativo' => $distritoEducativo,
            'tandas' => $tandas,
            'tipos' => $tipos
        ];

        //dd($distritoEducativo);

        echo view('header');
        echo view('escuela/nuevo', $data);
        echo view('footer');
    }






    public function insertar()
    {
        $session = session();

        // Validación estructural (sin reglas únicas)
        $rules = [
            'nombre' => 'required|min_length[3]|max_length[100]',
            'id_nivel' => 'required|integer',
            'id_modalidad' => 'required|integer',
            'codigo_gestion' => 'required|max_length[10]',
            'codigo_plantel' => 'required|alpha_numeric|max_length[10]',
            'rnc' => 'required|numeric|min_length[9]|max_length[11]',
            'distrito_educativo' => 'required|integer',
            'email' => 'permit_empty|valid_email|max_length[50]',
            'telefono' => 'required|regex_match[/^[0-9+\-() ]{7,20}$/]',
            'direccion' => 'required|max_length[100]',
            'web' => 'permit_empty|valid_url|max_length[50]',
            'tanda' => 'required|in_list[Matutina,Vespertina,Jornada Escolar Extendida (J.E.E.),Nocturno]',
            'tipo' => 'required|in_list[Privado,Publico]',
            'logo' => 'permit_empty|is_image[logo]|max_size[logo,1024]' // ✅ Validación del logo
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Recolectar y sanitizar datos
        $datos = [
            'nombre' => trim($this->request->getPost('nombre')),
            'id_nivel' => trim($this->request->getPost('id_nivel')),
            'id_modalidad' => trim($this->request->getPost('id_modalidad')),
            'codigo_gestion' => trim($this->request->getPost('codigo_gestion')),
            'codigo_plantel' => trim($this->request->getPost('codigo_plantel')),
            'rnc' => trim($this->request->getPost('rnc')),
            'distrito_educativo' => trim($this->request->getPost('distrito_educativo')),
            'email' => trim($this->request->getPost('email')),
            'telefono' => trim($this->request->getPost('telefono')),
            'redes' => trim($this->request->getPost('redes')),
            'direccion' => trim($this->request->getPost('direccion')),
            'web' => trim($this->request->getPost('web')),
            'tanda' => trim($this->request->getPost('tanda')),
            'tipo' => trim($this->request->getPost('tipo')),
        ];

        // Manejo del logo (si se sube)
        $logo = $this->request->getFile('logo');
        $rutaLogo = 'assets/img/logo/default.png';

        if ($logo && $logo->isValid() && !$logo->hasMoved()) {
            $nuevoNombre = $logo->getRandomName();
            $logo->move(ROOTPATH . 'public/assets/img/logo/', $nuevoNombre);
            $rutaLogo = 'assets/img/logo/' . $nuevoNombre;
        }

        $datos['logo'] = $rutaLogo;

        // Verificación de duplicados
        $duplicados = $this->escuela
            ->groupStart()
            ->where('codigo_gestion', $datos['codigo_gestion'])
            ->orWhere('codigo_plantel', $datos['codigo_plantel'])
            ->orWhere('rnc', $datos['rnc'])
            ->groupEnd()
            ->findAll();

        $errores = [];

        foreach ($duplicados as $escuela) {
            if ($escuela['codigo_gestion'] === $datos['codigo_gestion']) {
                $errores['codigo_gestion'] = 'El código SIGERD ya está registrado en otra escuela.';
            }
            if ($escuela['codigo_plantel'] === $datos['codigo_plantel']) {
                $errores['codigo_plantel'] = 'El código del plantel ya está registrado en otra escuela.';
            }
            if ($escuela['rnc'] === $datos['rnc']) {
                $errores['rnc'] = 'El RNC ya está registrado en otra escuela.';
            }
        }

        if (!empty($errores)) {
            return redirect()->back()->withInput()->with('errors', $errores);
        }

        // Insertar
        if (!$this->escuela->insert($datos)) {
            return redirect()->back()->withInput()->with('errors', $this->escuela->errors());
        }

        $session->setFlashdata('success', 'Escuela registrada correctamente.');
        return redirect()->to(base_url() . '/escuela');
    }





    public function editar($id)
    {
        log_message('debug', 'Editando escuela con ID: ' . $id);

        // Buscar la escuela por ID
        $escuela = $this->escuela->where('id', $id)->first();

        if (!$escuela) {
            return $this->response->setStatusCode(404)->setBody('Escuela no encontrada.');
        }

        // Cargar todos los datos necesarios
        $modalidades = $this->modalidades->findAll();
        $niveles = $this->nivel->findAll();
        //$distritos = $this->distrito_educativo->findAll();
        $distritoEducativo = $this->distrito_educativo->first();

        // ENUMs
        $tandas = [
            'Matutina' => 'Matutina',
            'Vespertina' => 'Vespertina',
            'Jornada Escolar Extendida (J.E.E.)' => 'Jornada Escolar Extendida (J.E.E.)',
            'Nocturno' => 'Nocturno'
        ];

        $tipos = [
            'Privado' => 'Privado',
            'Publico' => 'Publico'
        ];

        // Preparar datos para la vista
        $data = [
            'titulo' => 'Editar datos del centro educativo',
            'datos' => $escuela,
            'modalidad' => $modalidades,
            'niveles' => $niveles,
            'distritoEducativo' => $distritoEducativo,
            'tandas' => $tandas,
            'tipos' => $tipos
        ];

        // Cargar la vista del formulario de edición (puede ser parcial si es modal)
        //dd($distritoEducativo);
        echo view('header');
        echo view('escuela/editar', $data);
        echo view('footer');
    }








    public function actualizar()
    {
        $id = $this->request->getPost('id');

        if (!$id) {
            return redirect()->back()->with('error', 'ID no recibido.');
        }

        // Validación general
        $rules = [
            'nombre' => 'required',
            'id_modalidad' => 'required',
            'id_nivel' => 'required',
            'codigo_gestion' => 'required',
            'codigo_plantel' => 'required',
            'rnc' => 'required',
            'distrito_educativo' => 'required',
            'email' => 'required|valid_email',
            'telefono' => 'required',
            'direccion' => 'required',
            'web' => 'permit_empty|valid_url',
            'logo' => 'permit_empty|is_image[logo]|max_size[logo,1024]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $escuelaActual = $this->escuela->find($id);
        if (!$escuelaActual) {
            return redirect()->back()->with('error', 'Registro no encontrado.');
        }

        // Validación de duplicados
        $codigoSigerd = trim($this->request->getPost('codigo_gestion'));
        $codigoPlantel = trim($this->request->getPost('codigo_plantel'));
        $rnc = trim($this->request->getPost('rnc'));

        $duplicados = $this->escuela
            ->where('id !=', $id)
            ->groupStart()
            ->where('codigo_gestion', $codigoSigerd)
            ->orWhere('codigo_plantel', $codigoPlantel)
            ->orWhere('rnc', $rnc)
            ->groupEnd()
            ->findAll();

        $errores = [];

        foreach ($duplicados as $escuela) {
            if ($escuela['codigo_gestion'] === $codigoSigerd) {
                $errores['codigo_gestion'] = 'El código SIGERD ya está registrado en otra escuela.';
            }
            if ($escuela['codigo_plantel'] === $codigoPlantel) {
                $errores['codigo_plantel'] = 'El código del plantel ya está registrado en otra escuela.';
            }
            if ($escuela['rnc'] === $rnc) {
                $errores['rnc'] = 'El RNC ya está registrado en otra escuela.';
            }
        }

        if (!empty($errores)) {
            return redirect()->back()->withInput()->with('errors', $errores);
        }

        // Recolectar datos
        $datosActualizados = [
            'nombre' => trim($this->request->getPost('nombre')),
            'id_modalidad' => trim($this->request->getPost('id_modalidad')),
            'id_nivel' => trim($this->request->getPost('id_nivel')),
            'codigo_gestion' => $codigoSigerd,
            'codigo_plantel' => $codigoPlantel,
            'rnc' => $rnc,
            'distrito_educativo' => trim($this->request->getPost('distrito_educativo')),
            'email' => trim($this->request->getPost('email')),
            'telefono' => trim($this->request->getPost('telefono')),
            'direccion' => trim($this->request->getPost('direccion')),
            'web' => trim($this->request->getPost('web')),
            'redes' => trim($this->request->getPost('redes')),
            'tanda' => trim($this->request->getPost('tanda')),
            'tipo' => trim($this->request->getPost('tipo')),
        ];

        // Manejo del logo nuevo
        $logo = $this->request->getFile('logo');
        if ($logo && $logo->isValid() && !$logo->hasMoved()) {
            $nuevoNombre = $logo->getRandomName();
            $rutaDestino = ROOTPATH . 'public/assets/img/logo/';
            $logo->move($rutaDestino, $nuevoNombre);
            $rutaLogo = 'assets/img/logo/' . $nuevoNombre;

            // Eliminar logo anterior si no es el default
            if (!empty($escuelaActual['logo']) && $escuelaActual['logo'] !== 'assets/img/logo/default.png') {
                $logoAnterior = ROOTPATH . 'public/' . $escuelaActual['logo'];
                if (file_exists($logoAnterior)) {
                    @unlink($logoAnterior);
                }
            }

            $datosActualizados['logo'] = $rutaLogo;
        }

        log_message('debug', 'Intentando actualizar la escuela con ID: ' . $id);
        log_message('debug', 'Datos recibidos: ' . print_r($datosActualizados, true));

        if (!$this->escuela->update($id, $datosActualizados)) {
            log_message('error', 'Error al actualizar: ' . print_r($this->escuela->errors(), true));
            session()->setFlashdata('error', 'Error al actualizar: ' . implode(', ', $this->escuela->errors()));
            return redirect()->back()->withInput();
        }

        log_message('debug', 'Resultado de update: éxito');
        session()->setFlashdata('success', 'Datos actualizados correctamente.');

        return redirect()->to(base_url('/escuela/info_escuela'));
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


    public function visualizar($idEscuela = null)
{
    if (!$idEscuela) {
        $idEscuelaSesion = session()->get('id_escuela');

        if (!$idEscuelaSesion) {
            return $this->response->setStatusCode(400)->setBody('No se pudo identificar la escuela.');
        }

        $idEscuela = $idEscuelaSesion;
        log_message('debug', 'Visualizando escuela desde sesión con ID: ' . $idEscuela);
    } else {
        log_message('debug', 'Visualizando escuela por ID directo (admin): ' . $idEscuela);
    }

    $escuela = $this->escuela
        ->select('escuela.*, distrito_educativo.regional_educacion,distrito_educativo.distrito_educativo ,distrito_educativo.director_distrito, distrito_educativo.tecnico_acreditacion')
        ->join('distrito_educativo', 'distrito_educativo.id = escuela.distrito_educativo', 'left')
        ->where('escuela.id', $idEscuela)
        ->first();

    if (!$escuela) {
        return $this->response->setStatusCode(404)->setBody('Escuela no encontrada.');
    }

    $db = \Config\Database::connect();
    $modalidad = $db->query("SELECT id, nombre FROM modalidad")->getResultArray();

    $data = [
        'titulo'    => 'Visualizar datos del centro educativo',
        'datos'     => $escuela,
        'modalidad' => $modalidad,
    ];

    echo view('header');
    echo view('escuela/visualizar', $data);
    echo view('footer');
}

}

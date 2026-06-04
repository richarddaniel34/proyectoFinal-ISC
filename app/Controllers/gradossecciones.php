<?php

namespace App\Controllers;

use App\Controllers\BaseController;
//use App\Models\GradosModel;
use App\Models\CursosbaseModel;
use App\Models\CursosModel;
use App\Models\SeccionesModel;
use App\Models\EscuelaModel;
use App\Models\SchoolyearModel;
use \App\Models\GradosNivelesModel;
use \App\Models\EscuelaServiciosModel;
use \App\Models\SalidasTecnicasModel;
use \App\Models\ServiciosModel;


class GradosSecciones extends BaseController
{
    //protected $grado;
    protected $cursosBase;
    protected $cursos;
    protected $secciones;
    protected $escuela;
    protected $schoolyear;
    protected $gradoniveles;
    protected $escuelaservicios;
    protected $salidasTecnicas;
    protected $servicios;

    public function __construct()
    {
        //$this->grado = new GradosModel();
        $this->cursosBase = new CursosBaseModel();
        $this->cursos = new CursosModel();
        $this->secciones = new SeccionesModel();
        $this->escuela = new EscuelaModel();
        $this->schoolyear = new SchoolyearModel();
        $this->gradoniveles = new GradosNivelesModel();
        $this->escuelaservicios = new EscuelaServiciosModel();
        $this->salidasTecnicas = new SalidasTecnicasModel();
        $this->servicios = new ServiciosModel();
    }


    //Metodo que renderiza la vista principal del modulo

    public function index()
    {
        $id_escuela = session('id_escuela'); // Obtiene el ID de la escuela desde la sesión
        $schoolYear = $this->schoolyear->where('estado', 'En curso')->first();

        // Obtener cantidad de grados activos
        $cantidadGrados = $this->gradoniveles->getCantidadGradosActivos($id_escuela);
        $cantidadSecciones = $this->cursosBase->contarSeccionesPorEscuela($id_escuela);
        $cursosDisponibles = $schoolYear ? $this->cursos->contarCursosDisponibles($id_escuela, $schoolYear['id']) : 0;

        $data = [
            'titulo'          => 'Grados y Secciones',
            'cantidadGrados'  => $cantidadGrados,
            'cantidadSecciones' => $cantidadSecciones,
            'cursosDisponibles' => $cursosDisponibles
            // 'cursos' => $this->cursosBase->obtenerCursosBasePorEscuela($id_escuela)
        ];

        echo view('header');
        echo view('grados-secciones/grados-y-secciones', $data);
        echo view('footer');
    }


    /**
     * ============================================================
     * BLOQUE:  Grados
     * ------------------------------------------------------------
     * Este bloque maneja todas las operaciones relacionadas con los grados:
     *  - Crear nuevos grados
     *  - Editar información de grados existentes
     *  - Consultar lista o detalles de grados
     *  - Eliminar grados del sistema
     * ============================================================
     */


    //METODO QUE RENDERIZA LA VISTA DE GRADOS
    public function grados()
    {
        // Obtener el id_escuela desde la sesión
        $idEscuela = session()->get('id_escuela');

        // Usar la instancia del modelo Escuela
        $escuela = $this->escuela->find($idEscuela);

        // Usar la instancia del modelo GradosNiveles para obtener los grados activos de la escuela
        $grados = $this->gradoniveles->getGradosPorEscuela_activos($idEscuela);

        // Preparar los datos para la vista
        $data = [
            'titulo' => 'Grados',
            'grados' => $grados,
            'escuela' => $escuela
        ];

        echo view('header');
        echo view('grados-secciones/grados/grados', $data);
        echo view('footer');
    }








    public function inactivar_grado($id)
    {
        try {
            $this->gradoniveles->update($id, ['activo' => 0]);
            session()->setFlashdata('success', 'Grado inactivado correctamente.');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Error al inactivar el grado.');
        }

        return redirect()->to(base_url('grados-y-secciones/grados'));
    }

    public function restaurar_grado($id)
    {
        try {
            $this->gradoniveles->update($id, ['activo' => 1]);
            session()->setFlashdata('success', 'Grado restaurado correctamente.');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Error al restaurar el grado.');
        }

        return redirect()->to(base_url('grados-y-secciones/grados'));
    }

    //METODO QUE RENDERIZA LA VISTA DE GRADOS INACTIVOS
    public function grados_inactivos()
    {

        // Obtener el id_escuela desde la sesión
        $idEscuela = session()->get('id_escuela');

        // Usar la instancia del modelo Escuela
        $escuela = $this->escuela->find($idEscuela);

        // Usar la instancia del modelo GradosNiveles para obtener los grados activos de la escuela
        $grados = $this->gradoniveles->getGradosPorEscuela_inactivos($idEscuela);

        // Preparar los datos para la vista
        $data = [
            'titulo' => 'Grados',
            'grados' => $grados,
            'escuela' => $escuela
        ];


        echo view('header');
        echo view('grados-secciones/grados/inactivos', $data);
        echo view('footer');
    }



    //$id_escuela = session('id_escuela'); // Obtiene el ID de la escuela desde la sesión
    //'cursos'  => $this->cursosBase->obtenerCursosBasePorEscuela($id_escuela) // Lo pasas al modelo






    /**
     * ============================================================
     * BLOQUE:  Cursos
     * ------------------------------------------------------------
     * Este bloque maneja todas las operaciones relacionadas con los cursos:
     *  - Crear nuevos cursos
     *  - Editar información de cursos existentes
     *  - Consultar lista o detalles de cursos
     *  - Eliminar cursos del sistema
     *  - Establecer el servicio y la salida tecnica de un curso
     * ============================================================
     */


    public function cursos()
    {
        $id_escuela = session('id_escuela'); // Obtiene el ID de la escuela desde la sesión

        $data = [
            'titulo'  => 'Grados y Secciones',
            'cursos'  => $this->cursosBase->obtenerCursosBasePorEscuela($id_escuela) // Lo pasas al modelo
        ];

        echo view('header');
        echo view('grados-secciones/cursos/cursos', $data);
        echo view('footer');
    }


    public function curso_nuevo()
    {
        $id_escuela = session('id_escuela');
        $escuela = $this->escuela->find($id_escuela);

        if (!$escuela) {
            return redirect()->back()->with('error', 'No se encontró la escuela seleccionada.');
        }

        // 1️ Obtener los grados activos asociados al nivel de la escuela
        $grados = $this->gradoniveles->getGradosPorEscuela_activos($id_escuela);

        // 2️ Obtener los servicios activos de la escuela
        $servicios = $this->escuelaservicios->getServiciosPorEscuela_activos($id_escuela);





        // 3️ Preparar los datos para la vista
        $data = [
            'titulo'     => 'Registro de Cursos',
            'grados'     => $grados,
            'secciones'  => $this->secciones->findAll(),
            'servicios'  => $servicios,
            'nivel'      => $this->escuela->getNivelNombreByEscuela($id_escuela)
        ];

        echo view('header');
        echo view('grados-secciones/cursos/nuevo', $data);
        echo view('footer');
    }



    public function guardar_cursos()
    {
        log_message('info', 'Request Method: ' . $this->request->getMethod());
        log_message('info', 'Post data: ' . json_encode($this->request->getPost()));

        if ($this->request->getMethod() !== 'POST') {
            log_message('notice', 'Método guardar_cursos() llamado sin POST');
            return redirect()->to(base_url('cursosbase'));
        }

        // Recoger datos del formulario
        $id_grado      = $this->request->getPost('id_grado');
        $id_seccion    = $this->request->getPost('id_seccion');
        $id_escuela    = $this->request->getPost('id_escuela');
        $id_servicio   = $this->request->getPost('id_servicio');
        $nombre_curso  = $this->request->getPost('nombre_curso');
        $codigo_curso  = $this->request->getPost('codigo_curso');

        // Validación simple
        if (empty($id_grado) || empty($id_seccion) || empty($id_escuela) || empty($nombre_curso)) {
            log_message('error', 'Campos obligatorios vacíos');
            return redirect()->back()->with('error', 'Todos los campos son obligatorios.')->withInput();
        }

        // Verificar duplicado exacto (grado + sección + escuela + nombre)
        $existe = $this->cursosBase
            ->where('id_grado', $id_grado)
            ->where('id_seccion', $id_seccion)
            ->where('id_escuela', $id_escuela)
            ->where('nombre_curso', $nombre_curso)
            ->first();

        if ($existe) {
            return redirect()->back()
                ->with('error', 'Este curso ya está registrado en la misma sección y escuela.')
                ->withInput();
        }

        // Validación de secuencias de secciones
        $letraActual = $this->secciones->find($id_seccion)['letra'];
        $letrasOrdenadas = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        $indiceActual = array_search($letraActual, $letrasOrdenadas);

        // Validar que todas las secciones anteriores existan
        for ($i = 0; $i < $indiceActual; $i++) {
            $letraPrev = $letrasOrdenadas[$i];
            $seccionPrev = $this->secciones->where('letra', $letraPrev)->first();

            if ($seccionPrev) {
                $existePrev = $this->cursosBase
                    ->where('id_grado', $id_grado)
                    ->where('id_seccion', $seccionPrev['id'])
                    ->where('id_escuela', $id_escuela)
                    ->first();

                if (!$existePrev) {
                    return redirect()->back()
                        ->with('error', "No puedes registrar la sección $letraActual si no existen todas las secciones anteriores ($letraPrev) en este grado.")
                        ->withInput();
                }
            }
        }

        // Validación de salto de grados
        $todosLosGrados = $this->gradoniveles->getGradosPorEscuela_activos($id_escuela);
        $nivelNombre = $this->escuela->getNivelNombreByEscuela($id_escuela);

        $gradosFiltrados = array_filter($todosLosGrados, function ($g) use ($id_grado) {
            return $g['id'] == $id_grado;
        });
        $gradoActual = reset($gradosFiltrados);

        if ($gradoActual && $gradoActual['orden'] > 1) {
            $ordenAnterior = $gradoActual['orden'] - 1;
            $gradosAnteriores = array_filter($todosLosGrados, function ($g) use ($ordenAnterior) {
                return $g['orden'] == $ordenAnterior;
            });
            $gradoAnterior = reset($gradosAnteriores);

            if ($gradoAnterior) {
                $cursosEscuela = $this->cursosBase->where('id_escuela', $id_escuela)->findAll();
                $gradoAnteriorRegistrado = array_filter($cursosEscuela, fn($curso) => $curso['id_grado'] == $gradoAnterior['id']);

                if (empty($gradoAnteriorRegistrado)) {
                    return redirect()->back()
                        ->with('error', "No puedes registrar {$gradoActual['nombre']} si no existe {$gradoAnterior['nombre']} en esta escuela.")
                        ->withInput();
                }
            }
        }

        // Preparar datos del curso
        $dataCurso = [
            'id_grado'     => $id_grado,
            'id_seccion'   => $id_seccion,
            'id_escuela'   => $id_escuela,
            'id_servicio'  => $id_servicio,
            'nombre_curso' => $nombre_curso,
            'codigo_curso' => $codigo_curso,
            'activo'       => 1
        ];

        // Insertar curso
        if ($this->cursosBase->insert($dataCurso)) {
            $idCurso = $this->cursosBase->getInsertID();
            log_message('info', "Curso $idCurso insertado correctamente");
            return redirect()->to(base_url('grados-y-secciones/cursos'))->with('success', 'Curso registrado correctamente');
        } else {
            log_message('error', 'Fallo al insertar curso: ' . json_encode($this->cursosBase->errors()));
            return redirect()->back()->with('error', 'Error al registrar el curso')->withInput();
        }
    }


























    public function configurar_cursos()
    {
        $id_escuela = session('id_escuela'); // ID de la escuela desde la sesión

        // Obtener el año escolar en curso
        $schoolYear = $this->schoolyear
            ->where('estado', 'En curso')
            ->orderBy('id', 'DESC')
            ->first();

        $titulo = 'Configuración de Cursos para el ' . ($schoolYear['nombre'] ?? '(No definido)');

        // Cargar servicios disponibles para la escuela
        $servicios = $this->escuelaservicios
            ->select('
            escuelas_servicios.id_servicio,
            servicios.nombre AS nombre_servicio,
            salidas_tecnicas.nombre AS nombre_salida
        ')
            ->join('servicios', 'servicios.id = escuelas_servicios.id_servicio')
            ->join('salidas_tecnicas', 'servicios.id = salidas_tecnicas.id_servicio', 'left')
            ->where('escuelas_servicios.id_escuela', $id_escuela)
            ->findAll();

        // Cursos configurados para este año escolar
        $cursosQuery = $this->cursos
            ->select('cursos.*, cursos_base.nombre_curso, schoolyear.nombre as schoolyear')
            ->join('cursos_base', 'cursos_base.id = cursos.id_cursos_base')
            ->join('schoolyear', 'schoolyear.id = cursos.id_schoolyear')
            ->where('cursos_base.id_escuela', $id_escuela);

        // Filtrar por servicio y salida si vienen por POST (opcional)
        $id_servicio = $this->request->getPost('id_servicio');
        if ($id_servicio) {
            // Para cursos con salida técnica, filtramos por nombre concatenado
            $servicioData = explode('-', $id_servicio); // en caso de que envíes "idServicio-idSalida"
            $idServ = $servicioData[0];
            $nombreSalida = $servicioData[1] ?? null;

            $cursosQuery->where('cursos_base.id_servicio', $idServ);

            if ($nombreSalida) {
                $cursosQuery->like('cursos_base.nombre_curso', $nombreSalida);
            }
        }

        $cursos = $cursosQuery->orderBy('cursos.id', 'ASC')->findAll();

        // Datos para la vista
        $data = [
            'titulo'      => $titulo,
            'schoolYear'  => $schoolYear,
            'servicios'   => $servicios,
            'cursos'      => $cursos
        ];

        echo view('header');
        echo view('grados-secciones/configurar-cursos/configurar_cursos', $data);
        echo view('footer');
    }




    public function obtenerCursosPorServicio($id_servicio = null)
    {
        $salida = $this->request->getGet('salida');

        if (!$id_servicio) {
            return $this->response->setJSON([]);
        }

        $cursosQuery = $this->cursosBase
            ->where('id_servicio', intval($id_servicio));

        if (!empty($salida)) {
            $cursosQuery->like('nombre_curso', $salida);
        }

        $cursos = $cursosQuery
            ->orderBy('nombre_curso', 'ASC')
            ->findAll();

        return $this->response->setJSON($cursos);
    }


    public function obtenerCursosPorServicioInscripcion()
    {
        $idGrado      = (int) $this->request->getGet('id_grado');       // grados_niveles.id
        $idSchoolYear = (int) $this->request->getGet('id_schoolyear');
        $idEscuela    = (int) ($this->request->getGet('id_escuela') ?? 0);

        $svcComp    = (string) ($this->request->getGet('servicio_compuesto') ?? '');
        $idServicio = $this->request->getGet('id_servicio'); // compat
        $idSalida   = null;

        if (!$idGrado || !$idSchoolYear || (!$svcComp && !$idServicio)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Parámetros incompletos.']);
        }

        if ($svcComp) {
            if (strpos($svcComp, '|') !== false) {
                [$idServicio, $idSalida] = array_map('intval', explode('|', $svcComp, 2));
            } else {
                $idServicio = (int) $svcComp;
            }
        } else {
            $idServicio = (int) $idServicio;
        }

        $db = \Config\Database::connect();

        // ---------- SQL crudo garantizando nombre_curso ----------
        $sql = "
        SELECT
            c.id,
            cb.nombre_curso AS nombre_curso,
            c.capacidad,
            SUM(CASE WHEN i.id IS NOT NULL
                     AND i.id_schoolYear = ?
                     AND i.estado <> 'ANULADA'
                     THEN 1 ELSE 0 END) AS ocupados
        FROM cursos c
        JOIN cursos_base cb ON cb.id = c.id_cursos_base
        LEFT JOIN inscripciones i ON i.id_curso = c.id
        WHERE c.activo = 1
          AND c.id_schoolyear = ?
          AND cb.id_grado = ?
          AND cb.id_servicio = ?
          " . ($idEscuela ? " AND cb.id_escuela = ? " : "") . "
          " . ($idSalida   ? " AND cb.id_salida_tecnica = ? " : "") . "
        GROUP BY c.id, cb.nombre_curso, c.capacidad
        ORDER BY cb.nombre_curso ASC
    ";

        // Bind dinámico según filtros opcionales
        $binds = [$idSchoolYear, $idSchoolYear, $idGrado, $idServicio];
        if ($idEscuela) $binds[] = $idEscuela;
        if ($idSalida)  $binds[] = $idSalida;

        log_message('info', '[INSCRIP] (FORZADO SQL) binds=' . json_encode($binds));
        log_message('info', "[INSCRIP] (FORZADO SQL) \n{$sql}");

        $rows = $db->query($sql, $binds)->getResultArray();

        // Post-proceso para el front
        foreach ($rows as &$r) {
            $r['ocupados'] = (int) ($r['ocupados'] ?? 0);
            $r['capacidad'] = (int) $r['capacidad'];
            $r['disponibilidad'] = $r['ocupados'] . '/' . $r['capacidad'];
            $r['disponibilidad_num'] = $r['capacidad'] - $r['ocupados'];
        }

        log_message('info', '[INSCRIP] (FORZADO SQL) rows=' . json_encode($rows));

        // Filtra por cupo > 0
        $conCupo = array_values(array_filter($rows, fn($r) => $r['disponibilidad_num'] > 0));

        if (!$conCupo) {
            return $this->response->setJSON(['status' => 'empty', 'message' => 'No hay cursos disponibles con cupo.']);
        }

        // 🔙 Devuelve con nombre_curso garantizado
        return $this->response->setJSON(['status' => 'success', 'data' => $conCupo]);
    }



















    public function obtenerCursoUnico()
    {
        $id_servicio = $this->request->getGet('id_servicio');
        $salida = $this->request->getGet('salida');
        $id_grado = $this->request->getGet('id_grado');

        if (!$id_servicio || !$id_grado) {
            return $this->response->setJSON([]);
        }

        $curso = $this->cursosBase
            ->where('id_servicio', intval($id_servicio))
            ->where('id_grado_nivel', intval($id_grado));

        if (!empty($salida)) {
            $curso->like('nombre_curso', $salida);
        }

        $curso = $curso->first();

        return $this->response->setJSON($curso ?: []);
    }







    public function guardar_configuracion_cursos()
    {
        // 1️⃣ Verificar que la petición sea POST
        if (! $this->request->is('post')) {
            log_message('error', 'Método no permitido en guardar_configuracion_cursos');
            return redirect()->back()->with('error', 'Método no permitido');
        }

        // 2️⃣ Capturar los datos recibidos
        $postData = $this->request->getPost();
        log_message('debug', 'Datos recibidos en guardar_configuracion_cursos: ' . json_encode($postData));

        // 3️⃣ Validar los datos del formulario
        $validationRules = [
            'id_servicio'     => 'required|integer',
            'id_cursos_base'  => 'required|integer',
            'capacidad'       => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[60]',
            'tipo_aula'       => 'required|string',
        ];

        if (! $this->validate($validationRules)) {
            // Registrar los errores de validación
            $validationErrors = $this->validator->getErrors();
            log_message('error', 'Error de validación en guardar_configuracion_cursos: ' . json_encode($validationErrors));

            return redirect()->back()
                ->withInput()
                ->with('error', 'Por favor complete correctamente los campos requeridos.');
        }

        // 4️⃣ Obtener datos del formulario
        $id_schoolyear  = (int) $this->request->getPost('id_schoolyear');
        $id_servicio    = (int) $this->request->getPost('id_servicio');
        $id_cursos_base = (int) $this->request->getPost('id_cursos_base');
        $capacidad      = (int) $this->request->getPost('capacidad');
        $tipo_aula      = $this->request->getPost('tipo_aula');

        log_message('debug', "Campos procesados: id_schoolyear=$id_schoolyear, id_servicio=$id_servicio, id_cursos_base=$id_cursos_base, capacidad=$capacidad, tipo_aula=$tipo_aula");

        // 5️⃣ Verificar si el curso ya está registrado
        $existe = $this->cursos
            ->where('id_schoolyear', $id_schoolyear)
            ->where('id_cursos_base', $id_cursos_base)
            ->first();

        if ($existe) {
            log_message('error', 'Intento de guardar curso duplicado: ' . json_encode($existe));
            return redirect()->back()
                ->withInput()
                ->with('error', 'Este curso ya está registrado para el año escolar seleccionado.');
        }

        // 6️⃣ Preparar datos para guardar
        $data = [
            'id_schoolyear'   => $id_schoolyear,
            'id_servicio'     => $id_servicio,
            'id_cursos_base'  => $id_cursos_base,
            'capacidad'       => $capacidad,
            'tipo_aula'       => $tipo_aula,
            'activo'          => 1,
            'fecha_alta'      => date('Y-m-d H:i:s'),
        ];

        log_message('debug', 'Datos a insertar en cursos: ' . json_encode($data));

        // 7️⃣ Guardar en la base de datos
        try {
            if ($this->cursos->insert($data)) {
                log_message('info', "Curso guardado correctamente: id_cursos_base=$id_cursos_base, id_schoolyear=$id_schoolyear");
                return redirect()->to(base_url('grados-y-secciones/configurar_cursos'))
                    ->with('success', 'La configuración del curso se guardó correctamente.');
            } else {
                log_message('error', 'No se pudo guardar la configuración del curso.');
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'No se pudo guardar la configuración. Intente nuevamente.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Excepción al guardar curso: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error: ' . $e->getMessage());
        }
    }

    public function actualizar_curso($id)
    {
        // Recibir los datos enviados por JS (JSON)
        $data = $this->request->getJSON(true); // { capacidad, tipo_aula }

        // Actualizar solo en la tabla cursos
        $actualizado = $this->cursos->update($id, $data);

        return $this->response->setJSON([
            'success' => (bool)$actualizado
        ]);
    }





















    //Metodo que renderiza la vista para agregar nuevos registros
    public function nuevo()
    {


        $data = [
            'titulo'     => 'Registro de Cursos para el año escolar',
            // 'grados'     => $gradosFiltrados,
            'secciones'  => $this->secciones->findAll(),
            // 'nivel'      => $nivelNombre
        ];

        echo view('header');
        echo view('cursos/nuevo', $data);
        echo view('footer');
    }


    //Metodo para evitar saltos de secciones
    private function letraAnterior($letra) {}


    //Metodo que realiza la inserción de datos a la Base de Datos
    public function insertar() {}
    public function editar($id) {}
    public function actualizar($id) {}
}

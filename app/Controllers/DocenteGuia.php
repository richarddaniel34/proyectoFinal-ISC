<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DocenteGuiaModel;
use App\Models\EscuelaModel;
use App\Models\PersonalModel;
use App\Models\CursosModel;
use App\Models\SchoolYearModel;

class DocenteGuia extends BaseController
{
    protected $docente_guia;
    protected $escuela;
    protected $personal;
    protected $cursos;
    protected $schoolyear;

    public function __construct()
    {
        $this->docente_guia = new DocenteGuiaModel();
        $this->escuela = new EscuelaModel();
        $this->personal = new PersonalModel();
        $this->cursos = new CursosModel();
        $this->schoolyear = new SchoolYearModel();
    }

    /**
     * Mostrar la distribución académica
     */
    public function index()
    {
        // Obtener el año escolar en curso
        $anioActual = $this->schoolyear->getEnCurso();
        $idSchoolYear = !empty($anioActual) ? $anioActual[0]['id'] : null;

        // Traer solo las asignaciones de ese año
        $asignaciones = $this->docente_guia->getAsignacionesConDetalles($idSchoolYear);

        $data = [
            'titulo' => 'Distribución Académica',
            'asignaciones' => $asignaciones
        ];

        echo view('header');
        echo view('docenteguia/docenteguia', $data);
        echo view('footer');
    }




    /**
     * Mostrar formulario de nueva distribución académica
     */



    /**
     * Metodo que renderiza la vista para agregra doncente guia a un curso en especifico
     */

    public function nuevo()
    {
        $idEscuela = session()->get('id_escuela');

        // Obtener el año escolar "en curso"
        $anioActual = $this->schoolyear->getEnCurso();
        $idSchoolYear = !empty($anioActual) ? $anioActual[0]['id'] : null;

        // Obtener los cursos para asignación de docente guía
        $cursos = $this->cursos->getCursosParaDocenteGuia($idEscuela, $idSchoolYear);

        // Obtener los docentes de la escuela
        $docentes = $this->personal->getDocentesPorEscuela($idEscuela);

        // Pasar los cursos y docentes a la vista
        $data = [
            'titulo' => 'Distribución Académica',
            'cursos' => $cursos,
            'docentes' => $docentes,
            'anioEscolar' => $anioActual[0] ?? null
        ];

        // Renderizar la vista
        echo view('header');
        echo view('docenteguia/nuevo', $data);
        echo view('footer');
    }













    /**
     *  Insertar una nueva distribución académica
     */

    public function insertar()
    {
        $asignacionesJson = $this->request->getPost('asignaciones');

        // Log del JSON recibido
        log_message('debug', "🔹 asignacionesJson: " . $asignacionesJson);

        $asignaciones = json_decode($asignacionesJson, true);

        // Log de la variable decodificada
        log_message('debug', "🔹 asignaciones decodificadas: " . print_r($asignaciones, true));

        if (!$asignaciones || !is_array($asignaciones)) {
            log_message('error', "❌ No se encontraron asignaciones válidas.");
            return redirect()->back()
                ->withInput()
                ->with('error', 'No se encontraron asignaciones válidas.');
        }

        $idEscuela = session()->get('id_escuela');
        $errores = [];

        foreach ($asignaciones as $index => $a) {
            log_message('debug', "🔹 Procesando asignación #{$index}: " . print_r($a, true));

            // Verificar que existan las claves necesarias
            if (!isset($a['id_personal'], $a['id_curso'], $a['schoolyear'])) {
                log_message('error', "❌ Claves faltantes en asignación #{$index}");
                $errores[] = "Asignación #{$index} inválida, faltan datos.";
                continue;
            }

            $idPersonal = $a['id_personal'];
            $idCurso = $a['id_curso'];
            $idSchoolyear = $a['schoolyear'];

            // Verificar que el curso no tenga ya otro docente asignado
            $cursoExistente = $this->docente_guia
                ->where('id_curso', $idCurso)
                ->where('id_schoolyear', $idSchoolyear)
                ->first();

            // Verificar que el docente no tenga ya otro curso en el mismo año escolar
            $docenteExistente = $this->docente_guia
                ->where('id_personal', $idPersonal)
                ->where('id_schoolyear', $idSchoolyear)
                ->first();

            if ($cursoExistente || $docenteExistente) {
                $errores[] = "El curso " . ($a['nombre_curso'] ?? $idCurso) . " y/o el docente ya tienen asignación en este año escolar.";
                continue;
            }

            // Guardar la asignación
            $this->docente_guia->save([
                'id_escuela' => $idEscuela,
                'id_personal' => $idPersonal,
                'id_curso' => $idCurso,
                'id_schoolyear' => $idSchoolyear,
                'fecha_alta' => date('Y-m-d H:i:s'),
                'activo' => 1
            ]);
        }

        if (!empty($errores)) {
            log_message('error', "❌ Errores durante la inserción: " . implode('; ', $errores));
            return redirect()->back()
                ->withInput()
                ->with('error', implode('<br>', $errores));
        }

        log_message('debug', "✅ Distribución registrada correctamente.");
        return redirect()->to(base_url('/docenteguia/nuevo'))
            ->with('success', 'Distribución registrada correctamente.');
    }



    public function obtenerAsignacion($id = null)
    {
        if ($id === null) {
            return $this->response->setJSON(['error' => 'ID no especificado']);
        }

        $asignacion = $this->docente_guia->getAsignacionConDetallesPorId($id);

        if ($asignacion) {
            return $this->response->setJSON($asignacion);
        } else {
            return $this->response->setJSON(['error' => 'No se encontró la asignación.']);
        }
    }












    /**
     * Mostrar formulario de edición
     */
    public function editar($id)
    {
        $idEscuela = session()->get('id_escuela');

        $asignacion = $this->docente_guia->getAsignacionConDetallesPorId($id);

        if (!$asignacion) {
            return $this->response->setStatusCode(404)->setBody('Asignación no encontrada.');
        }

        $docentes = $this->personal->getDocentesPorEscuela($idEscuela);

        return view('docenteguia/editar', [
            'datos' => $asignacion,
            'docentes' => $docentes
        ]);
    }



    /**
     * Actualizar la distribución académica
     */
    public function actualizar()
    {
        $id = $this->request->getPost('id_asignacion');
        $id_personal = $this->request->getPost('id_personal');
        $id_curso = $this->request->getPost('id_curso');
        $id_schoolyear = $this->request->getPost('id_schoolyear');

        // Validaciones básicas
        if (!$id || !$id_personal || !$id_curso || !$id_schoolyear) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Faltan datos requeridos para actualizar la asignación.');
        }

        // Verificar que el curso no tenga ya otro docente asignado (excluyendo este registro)
        $cursoExistente = $this->docente_guia
            ->where('id_curso', $id_curso)
            ->where('id_schoolyear', $id_schoolyear)
            ->where('id !=', $id)
            ->first();

        // Verificar que el docente no tenga ya otro curso en el mismo año escolar (excluyendo este registro)
        $docenteExistente = $this->docente_guia
            ->where('id_personal', $id_personal)
            ->where('id_schoolyear', $id_schoolyear)
            ->where('id !=', $id)
            ->first();

        $errores = [];
        if ($cursoExistente) {
            $errores[] = "El curso seleccionado ya tiene un docente asignado.";
        }
        if ($docenteExistente) {
            $errores[] = "El docente seleccionado ya tiene un curso asignado en este año escolar.";
        }

        if (!empty($errores)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode('<br>', $errores));
        }

        // Actualizar la asignación
        $this->docente_guia->update($id, [
            'id_personal' => $id_personal,
            'id_curso' => $id_curso,
            'id_schoolyear' => $id_schoolyear
        ]);

        return redirect()->to(base_url('/docenteguia'))
            ->with('success', 'Asignación actualizada correctamente.');
    }
















    /**
     * 🔥 Eliminar distribución académica
     */
    public function eliminar($id)
    {
        $this->docente_guia->delete($id);
        return redirect()->to(base_url('/docente_guia_academica'))->with('success', 'Distribución eliminada correctamente.');
    }
}

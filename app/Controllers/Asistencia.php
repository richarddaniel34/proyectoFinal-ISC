<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AsistenciaModel;
use App\Models\EstudiantesModel;
use App\Models\AsignaturaModel;
use App\Models\PersonalModel;
use App\Models\DistribucionAsignaturasModel;
use App\Models\InscripcionesModel;

use App\Models\SchoolyearModel;

class Asistencia extends BaseController
{
    protected $asistencias;
    protected $estudiantes;
    protected $asignaturas;
    protected $personal;
    protected $cursos;
    protected $inscripciones;

    protected $distribucionasignaturas;

    protected $schoolYear;

    public function __construct()
    {
        $this->asistencias = new AsistenciaModel();
        $this->estudiantes = new EstudiantesModel();
        $this->asignaturas = new AsignaturaModel();
        $this->personal = new PersonalModel();
        $this->cursos = new PersonalModel();
        $this->distribucionasignaturas = new DistribucionAsignaturasModel();
        $this->inscripciones = new InscripcionesModel();

        $this->schoolYear = new SchoolyearModel();
    }

    /**
     *  Mostrar todas las asistencias
     */
    public function index()
    {
        $asistencias = $this->asistencias->getAsistenciasConDetalles();

        $data = [
            'titulo_1' => 'aSISTENCIA',
            'titulo_2' => 'REGISTRO DE ASISTENCIA',
            'asistencias' => $asistencias
        ];

        echo view('header');
        echo view('asistencia/asistencia', $data);
        echo view('footer');
    }

    /**
     *  Mostrar formulario de registro de asistencia
     */
    public function nuevo()
    {
        $schoolYearActual = $this->schoolYear->getEnCurso();

        $idSchoolYear = null;
        if (!empty($schoolYearActual)) {
            $idSchoolYear = $schoolYearActual[0]['id'];
        }

        $idUsuario   = session('personal_id');
        $tipoUsuario = session('tipo_usuario');
        $funcion     = null;

        $registroPersonal = $this->personal->find($idUsuario);

        if ($registroPersonal && isset($registroPersonal['funcion'])) {
            $funcion = $registroPersonal['funcion'];
        }

        $data = [
            'titulo_1' => 'ASISTENCIA',
            'titulo_2' => 'REGISTRO DE ASISTENCIA',
            'funcion'               => $funcion,
            'id_schoolyear_actual'  => $idSchoolYear,
            'usuario_actual'        => $registroPersonal,
            'tipo_usuario'          => $tipoUsuario,
        ];

        echo view('header');
        echo view('asistencia/nuevo', $data);
        echo view('footer');
    }

    /**
     *  Insertar nueva asistencia
     */
    public function insertar()
    {
        // Validar datos
        if (!$this->validate($this->asistencias->validationRules, $this->asistencias->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Guardar asistencia
        $this->asistencias->save([
            'id_estudiante' => $this->request->getPost('id_estudiante'),
            'id_asignatura' => $this->request->getPost('id_asignatura'),
            'id_docente'    => $this->request->getPost('id_docente'),
            'fecha'         => $this->request->getPost('fecha'),
            'estado'        => $this->request->getPost('estado'),
            'observaciones' => $this->request->getPost('observaciones')
        ]);

        return redirect()->to(base_url('asistencias'))->with('success', 'Asistencia registrada correctamente.');
    }



    // Buscar docente:
    public function buscarCursos($id_docente)
    {
        if ($this->request->isAJAX()) {
            $cursos = $this->distribucionasignaturas->getCursosPorDocente($id_docente);
            return $this->response->setJSON($cursos);
        }
    }


    //Buscar Asignatura:
    public function buscarAsignaturas($id_docente, $id_curso)
    {
        if ($this->request->isAJAX()) {
            $asignaturas = $this->distribucionasignaturas->getAsignaturasPorDocenteCurso($id_docente, $id_curso);
            return $this->response->setJSON($asignaturas);
        }
    }


    //Estudiantes por curso:
    public function estudiantesPorCurso($id_curso)
    {
        if ($this->request->isAJAX()) {

            $id_schoolyear = $this->request->getGet('id_schoolyear');

            $estudiantes = $this->inscripciones
                ->getEstudiantesPorCurso($id_curso, $id_schoolyear);

            return $this->response->setJSON($estudiantes);
        }
    }


    /*
    public function estudiantesPorCurso($id_curso)
    {
        if ($this->request->isAJAX()) {
            $estudiantes = $this->inscripciones->getEstudiantesPorCurso($id_curso);


            return $this->response->setJSON($estudiantes);
        }
    }

*/

    //Buscar Docentes:
    public function buscarDocentes()
    {
        $idEscuela = session()->get('id_escuela');

        $search = $this->request->getGet('search');

        $builder = $this->personal
            ->select('personal.id, CONCAT(personal.nombre, " ", personal.apellido) AS text')
            ->join('nombramiento', 'nombramiento.id = personal.funcion', 'left')
            ->where('nombramiento.nombre', 'Docente')
            ->where('personal.id_escuela', $idEscuela); // <-- filtro por escuela

        if (!empty($search)) {
            $builder->like('personal.nombre', $search)
                ->orLike('personal.apellido', $search);
        }

        $docentes = $builder->findAll(10);

        return $this->response->setJSON($docentes);
    }
}

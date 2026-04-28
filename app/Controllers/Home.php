<?php

namespace App\Controllers;

use App\Models\PersonalModel;
use App\Models\UsuariosModel;
use App\Models\InscripcionesModel;

class Home extends BaseController
{
    protected $personal;
    protected $usuarios;
    protected $inscripciones;

    public function __construct()
    {
        $this->personal = new PersonalModel();
        $this->usuarios = new UsuariosModel();
        $this->inscripciones = new InscripcionesModel();
    }

    public function index()
    {
        $usuario = session()->get('usuario_id');
        $usuarioData = $this->usuarios->getUsuarioCompleto($usuario);

        // Verifica si el usuario es un administrador
        if ($usuarioData['id_tipo_usuario'] == 5) {
            $data['titulo'] = 'Vista Administrador';
            echo view('header');
            echo view('usuarios/admin/admin_home', $data);
            echo view('footer');
        }
        // Verifica si el usuario es un estudiante
        else if (isset($usuarioData['estudiantes_id'])) {
            $data['titulo'] = 'Vista Estudiante';
            echo view('header');
            echo view('usuarios/students/estudiantes_home', $data);
            echo view('footer');
        }
        // Verifica si el usuario es un docente
        else if (isset($usuarioData['personal_id']) && $usuarioData['tipo_usuario'] == 'Docente') {
            $data['titulo'] = 'Vista Docente';
            echo view('header');
            echo view('usuarios/teachers/docentes_home', $data);
            echo view('footer');
        }
        // Verifica si el usuario es administrativo
        else if (isset($usuarioData['personal_id']) && $usuarioData['tipo_usuario'] == 'Administrativo') {

            $funcion = $usuarioData['personal_funcion'];
            if (empty($funcion)) {
                log_message('error', 'Función vacía para el usuario: ' . $usuario);
                $funcion = 'Administrativo';
            }

            $data['titulo'] = 'Vista ' . $funcion;

            // === Nuevo código ===
            $idEscuela = $usuarioData['id_escuela'];

            // Personal
            $data['total_personal']       = $this->personal->contarPersonalPorEscuela($idEscuela);
            $data['total_docentes']       = $this->personal->contarDocentesPorEscuela($idEscuela);
            $data['total_administrativos'] = $this->personal
                ->join('usuarios', 'usuarios.personal_id = personal.id', 'inner')
                ->where('usuarios.id_escuela', $idEscuela)
                ->where('usuarios.id_tipo_usuario', 1) // o el ID real de "Administrativo"
                ->where('personal.activo', 1)
                ->countAllResults();

            // Total de apoyo = total_personal - (docentes + administrativos)
            $data['total_apoyo'] = $data['total_personal'] - ($data['total_docentes'] + $data['total_administrativos']);

            // ====== Estadísticas de Inscripciones ======
            $estadisticasEstudiantes = $this->inscripciones->contarEstudiantesPorEscuela($idEscuela);
            $estadisticasFamilia = $this->inscripciones->contarFamiliasPorEscuela($idEscuela);

            $data['total_estudiantes'] = $estadisticasEstudiantes['total'] ?? 0;
            $data['total_masculinos'] = $estadisticasEstudiantes['total_masculino'] ?? 0;
            $data['total_femeninos']  = $estadisticasEstudiantes['total_femenino'] ?? 0;
            $data['total_familia'] = $estadisticasFamilia['total_familias'] ?? 0;
            // ============================================

            $vistas = [
                'Secretario/a' => 'usuarios/administrativos/secretaria/secretaria_home',
                'Digitador/a' => 'usuarios/administrativos/secretaria/secretaria_home',
                'Coordinador(a)' => 'usuarios/administrativos/coordinador/coordinador_home',
                'Coordinador' => 'usuarios/administrativos/coordinador/coordinador_home',
                'Director' => 'usuarios/administrativos/director/director_home',
                'Contable' => 'usuarios/administrativos/contable/contable_home'
            ];

            $vista = $vistas[$funcion] ?? 'usuarios/administrativos/administrador_home';

            $rutaVista = APPPATH . 'Views/' . $vista . '.php';
            if (!file_exists($rutaVista)) {
                log_message('error', 'La vista no existe: ' . $rutaVista . ' para función: ' . $funcion);
                $vista = 'usuarios/administrativos/administrador_home';
                $data['titulo'] = 'Vista Administrativa';
            }

            echo view('header');
            echo view($vista, $data);
            echo view('footer');
        }


        // Si el usuario no pertenece a ninguno de los tipos anteriores
        else {
            return redirect()->to(base_url('login'));
        }
    }

    // Método para debug (temporal - eliminar en producción)
    public function debugUsuario()
    {
        $usuario = session()->get('usuario_id');
        $usuarioData = $this->usuarios->getUsuarioCompleto($usuario);

        echo "<h3>Debug Usuario Completo</h3>";
        echo "<pre>";
        print_r($usuarioData);
        echo "</pre>";

        echo "<h4>Función detectada: " . ($usuarioData['personal_funcion'] ?? 'No encontrada') . "</h4>";
        echo "<h4>Tipo usuario: " . ($usuarioData['tipo_usuario'] ?? 'No encontrado') . "</h4>";
    }
}

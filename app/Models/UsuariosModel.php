<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuariosModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'usuario',
        'clave',
        'id_tipo_usuario',
        'personal_id',
        'estudiantes_id',
        'activo',
        'cambio_clave',
        'id_escuela'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'fecha_alta';
    protected $updatedField = 'fecha_edit';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * 🔍 Verifica las credenciales del usuario
     */
    public function verificarCredenciales($usuario)
    {
        return $this->where('usuario', $usuario)
            ->where('activo', 1) // Solo usuarios activos
            ->first();
    }

    /**
     * 🔥 Obtiene la información del usuario con datos adicionales
     */
    public function getUsuarioCompleto($usuarioId)
    {
        return $this->select('usuarios.*, 
        personal.nombre AS personal_nombre, personal.apellido AS personal_apellido, personal.email AS personal_email, 
        estudiantes.nombre AS estudiante_nombre, estudiantes.apellido AS estudiante_apellido, estudiantes.matricula AS estudiante_matricula, 
        tipo_usuario.nombre AS tipo_usuario')
            ->join('personal', 'personal.id = usuarios.personal_id', 'left')
            ->join('estudiantes', 'estudiantes.id = usuarios.estudiantes_id', 'left')
            ->join('tipo_usuario', 'tipo_usuario.id = usuarios.id_tipo_usuario', 'left')
            ->where('usuarios.id', $usuarioId)
            ->first();
    }

    /**
     * 🔥 Obtener todos los usuarios con su información de personal o estudiante.
     */
    public function getUsuariosConInfo()
    {
        return $this->select('usuarios.*, 
                              personal.nombre AS personal_nombre, personal.apellido AS personal_apellido, personal.email AS personal_email, 
                              estudiantes.nombre AS estudiante_nombre, estudiantes.apellido AS estudiante_apellido, estudiantes.matricula AS estudiante_matricula')
            ->join('personal', 'personal.id = usuarios.personal_id', 'left')
            ->join('estudiantes', 'estudiantes.id = usuarios.estudiantes_id', 'left')
            ->findAll();
    }

    /**
     * 🔥 Verifica si el usuario necesita cambiar la contraseña en el primer login
     */
    public function necesitaCambioClave($usuarioId)
    {
        return $this->where('id', $usuarioId)
            ->where('cambio_clave', 1)
            ->first();
    }

    /**
     * 🔒 Actualiza la contraseña del usuario
     */
    public function actualizarClave($usuarioId, $nuevaClave)
    {
        return $this->update($usuarioId, [
            'clave' => password_hash($nuevaClave, PASSWORD_DEFAULT),
            'cambio_clave' => 0 // Marcar que ya cambió su clave
        ]);
    }

    /**
     * 🔍 Buscar usuario por su nombre de usuario o matrícula
     */
    public function buscarPorUsuario($usuario)
    {
        return $this->where('usuario', $usuario)->first();
    }

    /**
     * 🔄 Restablecer contraseña a la matrícula para estudiantes
     */
    public function resetClaveEstudiante($usuarioId, $matricula)
    {
        return $this->update($usuarioId, [
            'clave' => password_hash($matricula, PASSWORD_DEFAULT),
            'cambio_clave' => 1 // Forzar cambio en el próximo login
        ]);
    }

    /**
     * 🔹 Obtener todos los tipos de usuario disponibles para personal
     */
    public function getTiposUsuariosParaPersonal()
    {
        return $this->db->table('tipo_usuario')
            ->whereNotIn('nombre', ['apoyo', 'estudiante']) // Excluye apoyo y estudiante
            ->get()
            ->getResultArray();
    }

    /**
     * 🔹 Obtener todos los tipos de usuario disponibles para estudiantes
     */
    public function getTiposUsuariosEstudiantes()
    {
        return $this->db->table('tipo_usuario')
            ->whereNotIn('nombre', ['apoyo', 'administrativo', 'docente', 'administrador'])
            ->get()
            ->getResultArray();
    }
}

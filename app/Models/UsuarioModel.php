<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'username',
        'email',
        'password',
        'nombre_completo',
        'rol',
        'estado',
        'ultimo_login',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $hidden = ['password'];

    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[50]|is_unique[usuarios.username]',
        'email'    => 'required|valid_email|is_unique[usuarios.email]',
        'password' => 'required|min_length[6]',
        'rol'      => 'required|in_list[ADMIN,EVALUADOR,CONSULTA]',
    ];

    /**
     * Verifica las credenciales del usuario
     */
    public function verifyCredentials($username, $password)
    {
        $usuario = $this->where('username', $username)
                       ->orWhere('email', $username)
                       ->first();

        if ($usuario && password_verify($password, $usuario['password'])) {
            // Actualizar último login
            $this->update($usuario['id'], ['ultimo_login' => date('Y-m-d H:i:s')]);
            return $usuario;
        }

        return null;
    }

    /**
     * Obtiene usuarios activos
     */
    public function getUsuariosActivos()
    {
        return $this->where('estado', 'ACTIVO')->findAll();
    }

    /**
     * Obtiene usuarios por rol
     */
    public function getUsuariosPorRol($rol)
    {
        return $this->where('rol', $rol)->findAll();
    }

    /**
     * Verifica si el usuario tiene un rol específico
     */
    public function hasRole($userId, $rol)
    {
        $usuario = $this->find($userId);
        return $usuario && $usuario['rol'] === $rol;
    }
}

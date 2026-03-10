<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class AuthController extends BaseController
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        helper(['form', 'session']);
    }

    /**
     * Muestra el formulario de login
     */
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Iniciar Sesión',
        ];

        return view('auth/login', $data);
    }

    /**
     * Procesa el login
     */
    public function attemptLogin()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $usuario = $this->usuarioModel->verifyCredentials($username, $password);

        if ($usuario) {
            // Crear sesión
            session()->set([
                'id'           => $usuario['id'],
                'username'     => $usuario['username'],
                'nombre_completo' => $usuario['nombre_completo'],
                'rol'          => $usuario['rol'],
                'isLoggedIn'   => true,
            ]);

            return redirect()->to('/dashboard')
                ->with('success', 'Bienvenido, ' . $usuario['username']);
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Credenciales incorrectas');
    }

    /**
     * Cierra la sesión
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')
            ->with('success', 'Sesión cerrada exitosamente');
    }

    /**
     * Muestra el formulario de registro
     */
    public function register()
    {
        $data = [
            'title' => 'Registrar Usuario',
        ];

        return view('auth/register', $data);
    }

    /**
     * Procesa el registro
     */
    public function attemptRegister()
    {
        $rules = [
            'username'              => 'required|min_length[3]|max_length[50]|is_unique[usuarios.username]',
            'email'                 => 'required|valid_email|is_unique[usuarios.email]',
            'password'              => 'required|min_length[6]',
            'password_confirmation' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username'       => $this->request->getPost('username'),
            'email'         => $this->request->getPost('email'),
            'password'      => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'nombre_completo' => $this->request->getPost('nombre_completo') ?? '',
            'rol'           => 'CONSULTA',
            'estado'        => 'ACTIVO',
        ];

        $this->usuarioModel->insert($data);

        return redirect()->to('/login')
            ->with('success', 'Usuario registrado exitosamente. Puede iniciar sesión.');
    }
}

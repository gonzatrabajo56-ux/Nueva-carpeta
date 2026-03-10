<?php

namespace App\Controllers;

use App\Models\PersonaModel;
use App\Models\EvaluacionModel;
use App\Models\SeguimientoModel;

class PersonaController extends BaseController
{
    protected $personaModel;
    protected $evaluacionModel;
    protected $seguimientoModel;

    public function __construct()
    {
        $this->personaModel = new PersonaModel();
        $this->evaluacionModel = new EvaluacionModel();
        $this->seguimientoModel = new SeguimientoModel();
    }

    /**
     * Lista todas las personas
     */
    public function index()
    {
        $busqueda = $this->request->getGet('q');
        
        if ($busqueda) {
            $personas = $this->personaModel
                ->groupStart()
                ->like('primer_nombre', $busqueda)
                ->orLike('primer_apellido', $busqueda)
                ->orLike('cedula', $busqueda)
                ->orLike('correo_electronico', $busqueda)
                ->groupEnd()
                ->where('estado_registro', 'ACTIVO')
                ->orderBy('primer_apellido', 'ASC')
                ->paginate(15);
        } else {
            $personas = $this->personaModel
                ->where('estado_registro', 'ACTIVO')
                ->orderBy('primer_apellido', 'ASC')
                ->paginate(15);
        }

        $data = [
            'title'     => 'Lista de Personas',
            'personas'  => $personas,
            'pager'     => $this->personaModel->pager,
            'busqueda'  => $busqueda,
        ];

        return view('personas/index', $data);
    }

    /**
     * Muestra el formulario para crear una nueva persona
     */
    public function create()
    {
        $data = [
            'title'    => 'Nueva Persona',
            'persona' => null,
        ];

        return view('personas/create', $data);
    }

    /**
     * Guarda una nueva persona
     */
    public function store()
    {
        $rules = [
            'cedula'            => 'required|is_unique[personas.cedula]',
            'primer_nombre'     => 'required|min_length[2]|max_length[50]',
            'primer_apellido'   => 'required|min_length[2]|max_length[50]',
            'correo_electronico' => 'valid_email',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();
        
        // Calcular edad si se proporcionó fecha de nacimiento
        if (!empty($data['fecha_nacimiento'])) {
            $data['edad'] = $this->personaModel->calcularEdad($data['fecha_nacimiento']);
        }
        
        // Agregar fecha de registro
        $data['fecha_registro'] = date('Y-m-d H:i:s');
        $data['estado_registro'] = 'ACTIVO';

        $this->personaModel->insert($data);

        return redirect()->to('/personas')
            ->with('success', 'Persona registrada exitosamente');
    }

    /**
     * Muestra los detalles de una persona
     */
    public function show($id = null)
    {
        $persona = $this->personaModel->find($id);

        if (!$persona) {
            return redirect()->to('/personas')
                ->with('error', 'Persona no encontrada');
        }

        // Obtener evaluaciones y seguimientos
        $evaluaciones = $this->evaluacionModel->getEvaluacionesPorPersona($id);
        $seguimientos = $this->seguimientoModel->getSeguimientosPorPersona($id);

        $data = [
            'title'       => 'Ver Persona',
            'persona'    => $persona,
            'evaluaciones'  => $evaluaciones,
            'seguimientos'  => $seguimientos,
        ];

        return view('personas/show', $data);
    }

    /**
     * Muestra el formulario para editar una persona
     */
    public function edit($id = null)
    {
        $persona = $this->personaModel->find($id);

        if (!$persona) {
            return redirect()->to('/personas')
                ->with('error', 'Persona no encontrada');
        }

        $data = [
            'title'    => 'Editar Persona',
            'persona' => $persona,
        ];

        return view('personas/edit', $data);
    }

    /**
     * Actualiza una persona
     */
    public function update($id = null)
    {
        $persona = $this->personaModel->find($id);

        if (!$persona) {
            return redirect()->to('/personas')
                ->with('error', 'Persona no encontrada');
        }

        // Verificar si la cédula cambió
        $cedulaActual = $this->request->getPost('cedula');
        $rules = [
            'primer_nombre'     => 'required|min_length[2]|max_length[50]',
            'primer_apellido'  => 'required|min_length[2]|max_length[50]',
            'correo_electronico' => 'valid_email',
        ];

        if ($cedulaActual !== $persona['cedula']) {
            $rules['cedula'] = 'required|is_unique[personas.cedula]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();

        // Recalcular edad si cambió la fecha de nacimiento
        if (!empty($data['fecha_nacimiento'])) {
            $data['edad'] = $this->personaModel->calcularEdad($data['fecha_nacimiento']);
        }

        $this->personaModel->update($id, $data);

        return redirect()->to('/personas/show/' . $id)
            ->with('success', 'Persona actualizada exitosamente');
    }

    /**
     * Elimina (desactiva) una persona
     */
    public function delete($id = null)
    {
        $persona = $this->personaModel->find($id);

        if (!$persona) {
            return redirect()->to('/personas')
                ->with('error', 'Persona no encontrada');
        }

        // Soft delete - cambiar estado
        $this->personaModel->update($id, ['estado_registro' => 'INACTIVO']);

        return redirect()->to('/personas')
            ->with('success', 'Persona eliminada exitosamente');
    }

    /**
     * Busca personas por cédula (API)
     */
    public function buscar()
    {
        $cedula = $this->request->getGet('cedula');

        if (!$cedula) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cédula no proporcionada'
            ]);
        }

        $persona = $this->personaModel->buscarPorCedula($cedula);

        if ($persona) {
            return $this->response->setJSON([
                'success' => true,
                'data'    => $persona
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'No se encontró ninguna persona con esa cédula'
        ]);
    }

    /**
     * Estadísticas del módulo de personas
     */
    public function estadisticas()
    {
        $data = $this->personaModel->getEstadisticas();
        
        return $this->response->setJSON([
            'success' => true,
            'data'    => $data
        ]);
    }
}

<?php

namespace App\Controllers;

use App\Models\EvaluacionModel;
use App\Models\PersonaModel;

class EvaluacionController extends BaseController
{
    protected $evaluacionModel;
    protected $personaModel;

    public function __construct()
    {
        $this->evaluacionModel = new EvaluacionModel();
        $this->personaModel = new PersonaModel();
    }

    /**
     * Lista todas las evaluaciones
     */
    public function index()
    {
        $personaId = $this->request->getGet('persona_id');
        
        if ($personaId) {
            $evaluaciones = $this->evaluacionModel->getEvaluacionesPorPersona($personaId);
            $persona = $this->personaModel->find($personaId);
            $titulo = 'Evaluaciones de: ' . ($persona ? $persona['primer_nombre'] . ' ' . $persona['primer_apellido'] : 'Desconocido');
        } else {
            $evaluaciones = $this->evaluacionModel->orderBy('fecha_evaluacion', 'DESC')->findAll();
            $titulo = 'Lista de Evaluaciones';
            $persona = null;
        }

        $data = [
            'title'      => $titulo,
            'evaluaciones' => $evaluaciones,
            'persona_id'  => $personaId,
            'persona'     => $persona,
        ];

        return view('evaluaciones/index', $data);
    }

    /**
     * Muestra el formulario para crear una evaluación
     */
    public function create()
    {
        $personaId = $this->request->getGet('persona_id');
        $personas = $this->personaModel->getPersonasActivas();

        $data = [
            'title'     => 'Nueva Evaluación',
            'personas'  => $personas,
            'evaluacion' => null,
            'persona_id' => $personaId,
        ];

        return view('evaluaciones/create', $data);
    }

    /**
     * Guarda una nueva evaluación
     */
    public function store()
    {
        $rules = [
            'persona_id'       => 'required|is_natural_no_zero',
            'tipo_evaluacion' => 'required',
            'fecha_evaluacion' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();
        
        // Agregar evaluador (seudónimo - ajustar según auth)
        $data['evaluador_id'] = 1; 

        $this->evaluacionModel->insert($data);

        $personaId = $data['persona_id'];

        return redirect()->to('/evaluaciones?persona_id=' . $personaId)
            ->with('success', 'Evaluación registrada exitosamente');
    }

    /**
     * Muestra los detalles de una evaluación
     */
    public function show($id = null)
    {
        $evaluacion = $this->evaluacionModel->find($id);

        if (!$evaluacion) {
            return redirect()->to('/evaluaciones')
                ->with('error', 'Evaluación no encontrada');
        }

        $persona = $this->personaModel->find($evaluacion['persona_id']);

        $data = [
            'title'      => 'Ver Evaluación',
            'evaluacion' => $evaluacion,
            'persona'    => $persona,
        ];

        return view('evaluaciones/show', $data);
    }

    /**
     * Muestra el formulario para editar una evaluación
     */
    public function edit($id = null)
    {
        $evaluacion = $this->evaluacionModel->find($id);

        if (!$evaluacion) {
            return redirect()->to('/evaluaciones')
                ->with('error', 'Evaluación no encontrada');
        }

        $personas = $this->personaModel->getPersonasActivas();

        $data = [
            'title'      => 'Editar Evaluación',
            'evaluacion' => $evaluacion,
            'personas'   => $personas,
        ];

        return view('evaluaciones/edit', $data);
    }

    /**
     * Actualiza una evaluación
     */
    public function update($id = null)
    {
        $evaluacion = $this->evaluacionModel->find($id);

        if (!$evaluacion) {
            return redirect()->to('/evaluaciones')
                ->with('error', 'Evaluación no encontrada');
        }

        $rules = [
            'persona_id'       => 'required|is_natural_no_zero',
            'tipo_evaluacion' => 'required',
            'fecha_evaluacion' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();

        $this->evaluacionModel->update($id, $data);

        return redirect()->to('/evaluaciones/show/' . $id)
            ->with('success', 'Evaluación actualizada exitosamente');
    }

    /**
     * Elimina una evaluación
     */
    public function delete($id = null)
    {
        $evaluacion = $this->evaluacionModel->find($id);

        if (!$evaluacion) {
            return redirect()->to('/evaluaciones')
                ->with('error', 'Evaluación no encontrada');
        }

        $personaId = $evaluacion['persona_id'];
        
        $this->evaluacionModel->delete($id);

        return redirect()->to('/evaluaciones?persona_id=' . $personaId)
            ->with('success', 'Evaluación eliminada exitosamente');
    }
}

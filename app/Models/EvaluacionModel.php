<?php

namespace App\Models;

use CodeIgniter\Model;

class EvaluacionModel extends Model
{
    protected $table            = 'evaluaciones';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'persona_id',
        'tipo_evaluacion',
        'titulo',
        'fecha_evaluacion',
        'resultado',
        'observaciones',
        'calificacion',
        'archivo',
        'evaluador_id',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'persona_id'       => 'required|is_natural_no_zero',
        'tipo_evaluacion'  => 'required',
        'fecha_evaluacion' => 'required|valid_date',
    ];

    /**
     * Obtiene las evaluaciones de una persona
     */
    public function getEvaluacionesPorPersona($personaId)
    {
        return $this->where('persona_id', $personaId)
                    ->orderBy('fecha_evaluacion', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene evaluaciones por tipo
     */
    public function getEvaluacionesPorTipo($tipo)
    {
        return $this->where('tipo_evaluacion', $tipo)
                    ->orderBy('fecha_evaluacion', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene la última evaluación de una persona
     */
    public function getUltimaEvaluacion($personaId)
    {
        return $this->where('persona_id', $personaId)
                    ->orderBy('fecha_evaluacion', 'DESC')
                    ->first();
    }

    /**
     * Obtiene evaluaciones por evaluador
     */
    public function getEvaluacionesPorEvaluador($evaluadorId)
    {
        return $this->where('evaluador_id', $evaluadorId)
                    ->orderBy('fecha_evaluacion', 'DESC')
                    ->findAll();
    }

    /**
     * Cuenta evaluaciones por persona
     */
    public function countEvaluacionesPorPersona($personaId)
    {
        return $this->where('persona_id', $personaId)->countAllResults();
    }
}

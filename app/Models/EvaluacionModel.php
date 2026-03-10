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
        'es_empleado_mes',
        'mes_evaluado',
        'puntuacion',
        'asistencia',
        'puntualidad',
        'trabajo_equipo',
        'iniciativa',
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

    /**
     * Obtiene el empleado del mes
     */
    public function getEmpleadoDelMes($mes = null)
    {
        if (!$mes) {
            $mes = date('Y-m');
        }

        return $this->where('mes_evaluado', $mes)
                    ->where('es_empleado_mes', 'S')
                    ->first();
    }

    /**
     * Obtiene los candidatos a empleado del mes (top 5 por puntuación)
     */
    public function getCandidatosEmpleadoDelMes($mes = null, $limite = 5)
    {
        if (!$mes) {
            $mes = date('Y-m');
        }

        return $this->select('evaluaciones.*, personas.primer_nombre, personas.primer_apellido, personas.cedula')
                    ->join('personas', 'personas.id = evaluaciones.persona_id')
                    ->where('evaluaciones.mes_evaluado', $mes)
                    ->where('evaluaciones.tipo_evaluacion', 'MENSUAL')
                    ->orderBy('evaluaciones.puntuacion', 'DESC')
                    ->limit($limite)
                    ->findAll();
    }

    /**
     * Obtiene evaluaciones mensuales por persona
     */
    public function getEvaluacionesMensuales($personaId, $anio = null)
    {
        if (!$anio) {
            $anio = date('Y');
        }

        return $this->where('persona_id', $personaId)
                    ->where('tipo_evaluacion', 'MENSUAL')
                    ->like('fecha_evaluacion', $anio, 'after')
                    ->orderBy('fecha_evaluacion', 'DESC')
                    ->findAll();
    }

    /**
     * Calcula la puntuación total de una evaluación
     */
    public function calcularPuntuacion($data)
    {
        $ponderacion = [
            'asistencia'      => 0.25,
            'puntualidad'     => 0.20,
            'trabajo_equipo'  => 0.25,
            'iniciativa'      => 0.30,
        ];

        $puntuacion = 0;
        foreach ($ponderacion as $campo => $peso) {
            if (isset($data[$campo]) && $data[$campo] > 0) {
                $puntuacion += $data[$campo] * $peso;
            }
        }

        return round($puntuacion, 2);
    }
}

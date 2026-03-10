<?php

namespace App\Controllers;

use App\Models\PersonaModel;
use App\Models\EvaluacionModel;
use App\Models\SeguimientoModel;

class HomeController extends BaseController
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
     * Página principal - redirige a login o dashboard
     */
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        return redirect()->to('/dashboard');
    }

    /**
     * Dashboard principal
     */
    public function dashboard()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Estadísticas
        $statsPersonas = $this->personaModel->getEstadisticas();
        
        $totalEvaluaciones = $this->evaluacionModel->countAll();
        $totalSeguimientos = $this->seguimientoModel->countAll();
        
        $seguimientosPendientes = $this->seguimientoModel->where('estado', 'PENDIENTE')->countAllResults();
        $seguimientosProximos = count($this->seguimientoModel->getSeguimientosProximos());

        $data = [
            'title'        => 'Dashboard',
            'stats'        => [
                'personas_total'      => $statsPersonas['total'],
                'personas_activas'    => $statsPersonas['activos'],
                'evaluaciones'       => $totalEvaluaciones,
                'seguimientos'       => $totalSeguimientos,
                'pendientes'         => $seguimientosPendientes,
                'proximos'           => $seguimientosProximos,
            ],
            'seguimientos_recientes' => $this->seguimientoModel
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->findAll(),
        ];

        return view('home/dashboard', $data);
    }

    /**
     * Página de inicio sin login
     */
    public function welcome()
    {
        $data = [
            'title' => 'Sistema de Caracterización',
        ];

        return view('home/welcome', $data);
    }
}

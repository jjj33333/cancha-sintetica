<?php

namespace App\Http\Controllers\Organizador;

use App\Http\Controllers\Controller;
use App\Models\Torneo;
use App\Models\Equipo;
use App\Models\Partido;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard del organizador con sus torneos asignados.
     */
    public function index()
    {
        $usuarioId = auth()->id();

        // Obtener torneos asignados al organizador
        $torneosAsignados = Torneo::with(['instalacion', 'equipos', 'partidos', 'creadoPorUsuario'])
            ->where('organizador_asignado_id', $usuarioId)
            ->where('esta_activo', true)
            ->whereIn('estado', ['borrador', 'inscripciones_abiertas', 'en_curso', 'finalizado'])
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($torneo) {
                $totalPartidos = $torneo->partidos->count();
                $partidosJugados = $torneo->partidos->where('estado', 'finalizado')->count();
                
                $torneo->equipos_count = $torneo->equipos->where('esta_activo', true)->count();
                $torneo->partidos_jugados = $partidosJugados;
                $torneo->partidos_total = $totalPartidos;
                $torneo->progreso_partidos = $totalPartidos > 0 
                    ? round(($partidosJugados / $totalPartidos) * 100, 1) 
                    : 0;
                
                return $torneo;
            });

        // Estadísticas generales
        $totalTorneos = $torneosAsignados->count();
        $torneosEnCurso = $torneosAsignados->where('estado', 'en_curso')->count();
        $totalEquipos = $torneosAsignados->sum('equipos_count');
        $totalPartidosJugados = $torneosAsignados->sum('partidos_jugados');

        return view('organizador.dashboard', compact(
            'torneosAsignados',
            'totalTorneos',
            'torneosEnCurso',
            'totalEquipos',
            'totalPartidosJugados'
        ));
    }
}
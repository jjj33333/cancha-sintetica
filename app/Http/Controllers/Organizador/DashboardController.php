<?php

namespace App\Http\Controllers\Organizador;

use App\Http\Controllers\Controller;
use App\Models\Torneo;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard del organizador con sus torneos asignados.
     *
     * Los conteos se calculan en la base de datos (withCount) para no cargar
     * en memoria todos los equipos y partidos de cada torneo.
     */
    public function index()
    {
        $usuarioId = auth()->id();

        $torneosAsignados = Torneo::query()
            ->select([
                'id',
                'nombre',
                'categoria',
                'estado',
                'fecha_inicio',
                'fecha_fin',
                'instalacion_id',
                'creado_por',
                'organizador_asignado_id',
            ])
            ->with([
                'instalacion:id,nombre',
                'creadoPorUsuario:id,nombre',
            ])
            ->withCount([
                'equipos as equipos_count' => fn ($query) => $query->where('esta_activo', true),
                'partidos as partidos_total',
                'partidos as partidos_jugados' => fn ($query) => $query->where('estado', 'finalizado'),
            ])
            ->where('organizador_asignado_id', $usuarioId)
            ->where('esta_activo', true)
            ->whereIn('estado', ['borrador', 'inscripciones_abiertas', 'en_curso', 'finalizado'])
            ->orderByDesc('created_at')
            ->get();

        $torneosAsignados->each(function ($torneo) {
            $totalPartidos = (int) $torneo->partidos_total;
            $partidosJugados = (int) $torneo->partidos_jugados;

            $torneo->progreso_partidos = $totalPartidos > 0
                ? round(($partidosJugados / $totalPartidos) * 100, 1)
                : 0;
        });

        // Estadísticas generales
        $totalTorneos = $torneosAsignados->count();
        $torneosEnCurso = $torneosAsignados->where('estado', 'en_curso')->count();
        $totalEquipos = (int) $torneosAsignados->sum('equipos_count');
        $totalPartidosJugados = (int) $torneosAsignados->sum('partidos_jugados');

        return view('organizador.dashboard', compact(
            'torneosAsignados',
            'totalTorneos',
            'torneosEnCurso',
            'totalEquipos',
            'totalPartidosJugados'
        ));
    }
}

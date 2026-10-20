<?php

namespace App\Http\Controllers\Organizador;

use App\Http\Controllers\Controller;
use App\Models\Torneo;
use App\Models\Equipo;
use App\Models\Partido;
use Illuminate\Http\Request;

class MisTorneosController extends Controller
{
    /**
     * Muestra la lista de torneos asignados al organizador.
     */
    public function index(Request $request)
    {
        $usuarioId = auth()->id();

        $query = Torneo::with(['instalacion', 'equipos', 'partidos'])
            ->where('organizador_asignado_id', $usuarioId)
            ->where('esta_activo', true)
            ->orderByDesc('created_at');

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where('nombre', 'like', "%{$buscar}%");
        }

        $torneos = $query->get()->map(function ($torneo) {
            $torneo->equipos_count = $torneo->equipos->where('esta_activo', true)->count();
            $torneo->partidos_total = $torneo->partidos->where('esta_activo', true)->count();
            $torneo->partidos_jugados = $torneo->partidos->where('estado', 'finalizado')->count();
            $torneo->partidos_programados = $torneo->partidos->where('estado', 'programado')->count();
            
            return $torneo;
        });

        return view('organizador.torneos.index', compact('torneos'));
    }

    /**
     * Muestra los detalles de un torneo asignado.
     */
    public function show(Torneo $torneo)
    {
        // Verificar que el torneo esté asignado al organizador actual
        if ($torneo->organizador_asignado_id !== auth()->id()) {
            return redirect()->route('organizador.torneos.index')
                ->with('error', 'No tienes permiso para ver este torneo.');
        }

        $torneo->load(['instalacion', 'equipos', 'partidos', 'partidos.equipoLocal', 'partidos.equipoVisitante']);

        // Estadísticas del torneo
        $equipos = $torneo->equipos->where('esta_activo', true);
        $partidos = $torneo->partidos->where('esta_activo', true);

        $stats = [
            'equipos' => $equipos->count(),
            'max_equipos' => $torneo->max_equipos,
            'partidos_total' => $partidos->count(),
            'partidos_jugados' => $partidos->where('estado', 'finalizado')->count(),
            'partidos_programados' => $partidos->where('estado', 'programado')->count(),
            'goles_total' => $partidos->where('estado', 'finalizado')->sum('marcador_local') + 
                            $partidos->where('estado', 'finalizado')->sum('marcador_visitante'),
            'jugadores_total' => $equipos->sum(function($equipo) {
                return $equipo->jugadores()->where('esta_activo', true)->count();
            }),
        ];

        // Próximos partidos
        $proximosPartidos = $partidos
            ->where('estado', 'programado')
            ->sortBy('fecha_partido')
            ->take(5);

        // Últimos resultados
        $ultimosResultados = $partidos
            ->where('estado', 'finalizado')
            ->sortByDesc('fecha_partido')
            ->take(5);

        // Tabla de posiciones simplificada
        $tablaPosiciones = $equipos
            ->sortByDesc('puntos')
            ->thenByDesc('goles_favor')
            ->values();

        return view('organizador.torneos.show', compact('torneo', 'stats', 'proximosPartidos', 'ultimosResultados', 'tablaPosiciones'));
    }

    /**
     * Cambia el estado de un torneo (solo dentro de los permitidos para el organizador).
     */
    public function cambiarEstado(Request $request, Torneo $torneo)
    {
        // Verificar que el torneo esté asignado al organizador actual
        if ($torneo->organizador_asignado_id !== auth()->id()) {
            return redirect()->route('organizador.torneos.index')
                ->with('error', 'No tienes permiso para modificar este torneo.');
        }

        $request->validate([
            'estado' => ['required', 'in:borrador,inscripciones_abiertas,en_curso,finalizado'],
        ]);

        // El organizador NO puede cancelar un torneo (eso lo hace el admin)
        if ($request->estado === 'cancelado') {
            return redirect()->route('organizador.torneos.show', $torneo)
                ->with('error', 'No puedes cancelar un torneo. Contacta al administrador.');
        }

        $torneo->update([
            'estado' => $request->estado,
            'actualizado_por' => auth()->id(),
        ]);

        return redirect()->route('organizador.torneos.show', $torneo)
            ->with('success', 'Estado del torneo actualizado exitosamente.');
    }
}
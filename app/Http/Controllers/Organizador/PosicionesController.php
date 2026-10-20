<?php

namespace App\Http\Controllers\Organizador;

use App\Http\Controllers\Controller;
use App\Models\Torneo;
use App\Models\Equipo;
use App\Models\Partido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosicionesController extends Controller
{
    /**
     * Muestra la tabla de posiciones de los torneos asignados.
     */
    public function index(Request $request)
    {
        $usuarioId = auth()->id();

        // Obtener torneos asignados al organizador
        $torneosAsignados = Torneo::where('organizador_asignado_id', $usuarioId)
            ->where('esta_activo', true)
            ->orderBy('nombre')
            ->get();

        // Torneo seleccionado (por defecto el primero)
        $torneoSeleccionado = null;
        if ($request->filled('torneo_id')) {
            $torneoSeleccionado = $torneosAsignados->firstWhere('id', $request->torneo_id);
        }
        if (!$torneoSeleccionado && $torneosAsignados->count() > 0) {
            $torneoSeleccionado = $torneosAsignados->first();
        }

        // Tabla de posiciones del torneo seleccionado
        $equipos = collect();
        $partidosFinalizados = 0;
        
        if ($torneoSeleccionado) {
            // Obtener equipos ordenados por puntos, diferencia de goles, goles a favor
            $equipos = Equipo::where('torneo_id', $torneoSeleccionado->id)
                ->where('esta_activo', true)
                ->orderByDesc('puntos')
                ->orderByRaw('(goles_favor - goles_contra) DESC')
                ->orderByDesc('goles_favor')
                ->orderBy('nombre')
                ->get()
                ->map(function ($equipo, $index) {
                    $equipo->posicion = $index + 1;
                    $equipo->diferencia_goles = $equipo->goles_favor - $equipo->goles_contra;
                    return $equipo;
                });

            // Contar partidos finalizados del torneo
            $partidosFinalizados = Partido::where('torneo_id', $torneoSeleccionado->id)
                ->where('estado', 'finalizado')
                ->where('esta_activo', true)
                ->count();
        }

        return view('organizador.posiciones.index', compact(
            'torneosAsignados',
            'torneoSeleccionado',
            'equipos',
            'partidosFinalizados'
        ));
    }

    /**
     * Recalcula las estadísticas de los equipos basándose en los partidos finalizados.
     * Útil para corregir inconsistencias.
     */
    public function actualizar(Request $request)
    {
        $request->validate([
            'torneo_id' => ['required', 'exists:torneos,id'],
        ]);

        $torneoId = $request->torneo_id;
        $usuarioId = auth()->id();

        // Verificar que el torneo esté asignado al organizador
        $torneo = Torneo::where('id', $torneoId)
            ->where('organizador_asignado_id', $usuarioId)
            ->first();

        if (!$torneo) {
            return redirect()->route('organizador.posiciones.index')
                ->with('error', 'No tienes permiso para actualizar este torneo.');
        }

        try {
            DB::beginTransaction();

            // Obtener todos los equipos del torneo
            $equipos = Equipo::where('torneo_id', $torneoId)
                ->where('esta_activo', true)
                ->get();

            // Resetear estadísticas de todos los equipos
            foreach ($equipos as $equipo) {
                $equipo->update([
                    'puntos' => 0,
                    'partidos_jugados' => 0,
                    'partidos_ganados' => 0,
                    'partidos_empatados' => 0,
                    'partidos_perdidos' => 0,
                    'goles_favor' => 0,
                    'goles_contra' => 0,
                ]);
            }

            // Recalcular desde los partidos finalizados
            $partidosFinalizados = Partido::where('torneo_id', $torneoId)
                ->where('estado', 'finalizado')
                ->where('esta_activo', true)
                ->whereNotNull('marcador_local')
                ->whereNotNull('marcador_visitante')
                ->get();

            foreach ($partidosFinalizados as $partido) {
                $equipoLocal = Equipo::find($partido->equipo_local_id);
                $equipoVisitante = Equipo::find($partido->equipo_visitante_id);

                if (!$equipoLocal || !$equipoVisitante) {
                    continue;
                }

                // Incrementar partidos jugados
                $equipoLocal->increment('partidos_jugados');
                $equipoVisitante->increment('partidos_jugados');

                // Actualizar goles
                $equipoLocal->increment('goles_favor', $partido->marcador_local);
                $equipoLocal->increment('goles_contra', $partido->marcador_visitante);
                $equipoVisitante->increment('goles_favor', $partido->marcador_visitante);
                $equipoVisitante->increment('goles_contra', $partido->marcador_local);

                // Determinar ganador y actualizar puntos
                if ($partido->marcador_local > $partido->marcador_visitante) {
                    $equipoLocal->increment('partidos_ganados');
                    $equipoLocal->increment('puntos', 3);
                    $equipoVisitante->increment('partidos_perdidos');
                } elseif ($partido->marcador_visitante > $partido->marcador_local) {
                    $equipoVisitante->increment('partidos_ganados');
                    $equipoVisitante->increment('puntos', 3);
                    $equipoLocal->increment('partidos_perdidos');
                } else {
                    // Empate
                    $equipoLocal->increment('partidos_empatados');
                    $equipoLocal->increment('puntos', 1);
                    $equipoVisitante->increment('partidos_empatados');
                    $equipoVisitante->increment('puntos', 1);
                }
            }

            DB::commit();

            return redirect()->route('organizador.posiciones.index', ['torneo_id' => $torneoId])
                ->with('success', "Tabla de posiciones actualizada exitosamente. Se procesaron {$partidosFinalizados->count()} partidos.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('organizador.posiciones.index', ['torneo_id' => $torneoId])
                ->with('error', 'Error al actualizar la tabla: ' . $e->getMessage());
        }
    }
}
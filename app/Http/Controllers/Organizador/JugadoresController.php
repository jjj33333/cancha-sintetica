<?php

namespace App\Http\Controllers\Organizador;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJugadorOrganizadorRequest;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Torneo;
use Illuminate\Http\Request;

class JugadoresController extends Controller
{
    /**
     * Muestra la lista de jugadores de los torneos asignados.
     */
    public function index(Request $request)
    {
        $usuarioId = auth()->id();

        // Torneos asignados al organizador (orden alfabético)
        $torneos = Torneo::where('organizador_asignado_id', $usuarioId)
            ->where('esta_activo', true)
            ->orderBy('nombre')
            ->get();

        // Torneo seleccionado: por defecto el primero en orden alfabético
        $torneoSeleccionado = $request->filled('torneo_id')
            ? $torneos->firstWhere('id', $request->torneo_id)
            : null;
        $torneoSeleccionado = $torneoSeleccionado ?? $torneos->first();

        // Equipos del torneo seleccionado (para el filtro de equipo)
        $equipos = $torneoSeleccionado
            ? Equipo::where('torneo_id', $torneoSeleccionado->id)
                ->where('esta_activo', true)
                ->orderBy('nombre')
                ->get()
            : collect();

        // Query base: Jugadores de equipos de torneos asignados al organizador
        $query = Jugador::with(['equipo.torneo'])
            ->whereHas('equipo.torneo', function ($q) use ($usuarioId) {
                $q->where('organizador_asignado_id', $usuarioId)
                  ->where('esta_activo', true);
            })
            ->where('esta_activo', true);

        // Filtro por torneo (siempre hay uno seleccionado)
        if ($torneoSeleccionado) {
            $query->whereHas('equipo', function ($q) use ($torneoSeleccionado) {
                $q->where('torneo_id', $torneoSeleccionado->id);
            });
        }

        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre_completo', 'like', "%{$buscar}%")
                  ->orWhere('dni', 'like', "%{$buscar}%");
            });
        }

        // Filtro por equipo (solo equipos del torneo seleccionado)
        if ($request->filled('equipo_id')) {
            $equipoSeleccionado = $equipos->firstWhere('id', $request->equipo_id);
            if ($equipoSeleccionado) {
                $query->where('equipo_id', $equipoSeleccionado->id);
            }
        }

        $jugadores = $query->orderBy('nombre_completo')->get();

        return view('organizador.jugadores.index', compact(
            'jugadores',
            'torneos',
            'equipos',
            'torneoSeleccionado'
        ));
    }

    /**
     * Muestra el formulario para crear un nuevo jugador.
     */
    public function create(Request $request)
    {
        $usuarioId = auth()->id();

        $torneos = Torneo::where('organizador_asignado_id', $usuarioId)
            ->where('esta_activo', true)
            ->whereIn('estado', ['borrador', 'inscripciones_abiertas', 'en_curso'])
            ->orderBy('nombre')
            ->get();

        $equiposQuery = Equipo::with('torneo')
            ->whereIn('torneo_id', $torneos->pluck('id'))
            ->where('esta_activo', true)
            ->whereIn('estado', ['registrado', 'confirmado'])
            ->orderBy('nombre');

        if ($request->filled('torneo_id')) {
            $equiposQuery->where('torneo_id', $request->torneo_id);
        }

        $equipos = $equiposQuery->get();

        return view('organizador.jugadores.create', compact('torneos', 'equipos'));
    }

    /**
     * Almacena un nuevo jugador.
     */
    public function store(StoreJugadorOrganizadorRequest $request)
    {
        $datos = $request->validated();

        // Verificar que el equipo pertenezca a un torneo del organizador
        $equipo = Equipo::with('torneo')->findOrFail($datos['equipo_id']);
        if ($equipo->torneo->organizador_asignado_id !== auth()->id()) {
            return redirect()->route('organizador.jugadores.index')
                ->with('error', 'No tienes permiso para agregar jugadores a este equipo.');
        }

        // Si es capitán, desmarcar a los demás del mismo equipo
        if (!empty($datos['es_capitan'])) {
            Jugador::where('equipo_id', $equipo->id)
                ->where('es_capitan', true)
                ->update(['es_capitan' => false]);
        } else {
            $datos['es_capitan'] = false;
        }

        $datos['esta_activo'] = true;

        Jugador::create($datos);

        return redirect()->route('organizador.jugadores.index')
            ->with('success', 'Jugador registrado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un jugador.
     */
    public function edit(Jugador $jugador)
    {
        // Verificar permisos
        $equipo = Equipo::with('torneo')->findOrFail($jugador->equipo_id);
        if ($equipo->torneo->organizador_asignado_id !== auth()->id()) {
            return redirect()->route('organizador.jugadores.index')
                ->with('error', 'No tienes permiso para editar este jugador.');
        }

        $usuarioId = auth()->id();
        $torneos = Torneo::where('organizador_asignado_id', $usuarioId)
            ->where('esta_activo', true)
            ->orderBy('nombre')
            ->get();

        $equipos = Equipo::where('torneo_id', $equipo->torneo_id)
            ->where('esta_activo', true)
            ->orderBy('nombre')
            ->get();

        return view('organizador.jugadores.edit', compact('jugador', 'torneos', 'equipos', 'equipo'));
    }

    /**
     * Actualiza un jugador.
     */
    public function update(StoreJugadorOrganizadorRequest $request, Jugador $jugador)
    {
        // Verificar permisos
        $equipo = Equipo::with('torneo')->findOrFail($jugador->equipo_id);
        if ($equipo->torneo->organizador_asignado_id !== auth()->id()) {
            return redirect()->route('organizador.jugadores.index')
                ->with('error', 'No tienes permiso para editar este jugador.');
        }

        $datos = $request->validated();

        // Si es capitán, desmarcar a los demás del mismo equipo (excepto él mismo)
        if (!empty($datos['es_capitan'])) {
            Jugador::where('equipo_id', $equipo->id)
                ->where('id', '!=', $jugador->id)
                ->where('es_capitan', true)
                ->update(['es_capitan' => false]);
        } else {
            $datos['es_capitan'] = false;
        }

        $jugador->update($datos);

        return redirect()->route('organizador.jugadores.index')
            ->with('success', 'Jugador actualizado exitosamente.');
    }

    /**
     * Añade o corrige los goles (goleo) de un jugador.
     */
    public function actualizarGoles(Request $request, Jugador $jugador)
    {
        // Verificar permisos
        $equipo = Equipo::with('torneo')->findOrFail($jugador->equipo_id);
        if ($equipo->torneo->organizador_asignado_id !== auth()->id()) {
            return redirect()->route('organizador.jugadores.index')
                ->with('error', 'No tienes permiso para editar el goleo de este jugador.');
        }

        $datos = $request->validate([
            'goles' => ['required', 'integer', 'min:0', 'max:999'],
            'accion' => ['required', 'in:sumar,editar'],
        ]);

        $goles = (int) $datos['goles'];

        if ($datos['accion'] === 'sumar') {
            $jugador->goles = (int) $jugador->goles + $goles;
        } else {
            $jugador->goles = $goles;
        }

        $jugador->actualizado_por = auth()->id();
        $jugador->save();

        $mensaje = $datos['accion'] === 'sumar'
            ? 'Goles añadidos exitosamente. Goleo actual: ' . $jugador->goles . '.'
            : 'Goleo actualizado exitosamente. Total: ' . $jugador->goles . ' goles.';

        return redirect()->route('organizador.jugadores.index', ['torneo_id' => $equipo->torneo_id])
            ->with('success', $mensaje);
    }

    /**
     * Elimina (desactiva) un jugador.
     */
    public function destroy(Jugador $jugador)
    {
        // Verificar permisos
        $equipo = Equipo::with('torneo')->findOrFail($jugador->equipo_id);
        if ($equipo->torneo->organizador_asignado_id !== auth()->id()) {
            return redirect()->route('organizador.jugadores.index')
                ->with('error', 'No tienes permiso para eliminar este jugador.');
        }

        $jugador->update([
            'esta_activo' => false,
            'actualizado_por' => auth()->id(),
        ]);

        return redirect()->route('organizador.jugadores.index')
            ->with('success', 'Jugador eliminado/desactivado exitosamente.');
    }
}
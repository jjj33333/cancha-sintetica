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

        // Query base: Jugadores de equipos de torneos asignados al organizador
        $query = Jugador::with(['equipo.torneo'])
            ->whereHas('equipo.torneo', function ($q) use ($usuarioId) {
                $q->where('organizador_asignado_id', $usuarioId)
                  ->where('esta_activo', true);
            })
            ->where('esta_activo', true);

        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre_completo', 'like', "%{$buscar}%")
                  ->orWhere('dni', 'like', "%{$buscar}%");
            });
        }

        // Filtro por torneo
        if ($request->filled('torneo_id')) {
            $query->whereHas('equipo', function ($q) use ($request) {
                $q->where('torneo_id', $request->torneo_id);
            });
        }

        // Filtro por equipo
        if ($request->filled('equipo_id')) {
            $query->where('equipo_id', $request->equipo_id);
        }

        $jugadores = $query->orderBy('nombre_completo')->get();

        // Obtener torneos y equipos para los filtros
        $torneos = Torneo::where('organizador_asignado_id', $usuarioId)
            ->where('esta_activo', true)
            ->orderBy('nombre')
            ->get();

        $equipos = Equipo::whereIn('torneo_id', $torneos->pluck('id'))
            ->where('esta_activo', true)
            ->orderBy('nombre')
            ->get();

        return view('organizador.jugadores.index', compact('jugadores', 'torneos', 'equipos'));
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
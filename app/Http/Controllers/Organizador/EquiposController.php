<?php

namespace App\Http\Controllers\Organizador;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEquipoOrganizadorRequest;
use App\Models\Equipo;
use App\Models\Torneo;
use App\Models\Jugador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EquiposController extends Controller
{
    /**
     * Muestra la lista de equipos de los torneos asignados al organizador.
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

        // Equipos del torneo seleccionado
        $equipos = collect();
        if ($torneoSeleccionado) {
            $query = Equipo::withCount(['jugadores' => function ($q) {
                $q->where('esta_activo', true);
            }])
            ->where('torneo_id', $torneoSeleccionado->id)
            ->where('esta_activo', true)
            ->orderBy('nombre');

            // Filtro por estado
            if ($request->filled('estado')) {
                $query->where('estado', $request->estado);
            }

            // Filtro por búsqueda
            if ($request->filled('buscar')) {
                $buscar = $request->buscar;
                $query->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'like', "%{$buscar}%")
                      ->orWhere('nombre_capitan', 'like', "%{$buscar}%");
                });
            }

            $equipos = $query->get();
        }

        return view('organizador.equipos.index', compact(
            'torneosAsignados',
            'torneoSeleccionado',
            'equipos'
        ));
    }

    /**
     * Muestra el formulario para crear un nuevo equipo.
     */
    public function create(Request $request)
    {
        $usuarioId = auth()->id();

        $torneosAsignados = Torneo::where('organizador_asignado_id', $usuarioId)
            ->where('esta_activo', true)
            ->whereIn('estado', ['borrador', 'inscripciones_abiertas', 'en_curso'])
            ->orderBy('nombre')
            ->get();

        // Torneo preseleccionado
        $torneoId = $request->get('torneo_id');
        $torneoSeleccionado = $torneoId ? $torneosAsignados->firstWhere('id', $torneoId) : null;

        return view('organizador.equipos.create', compact('torneosAsignados', 'torneoSeleccionado'));
    }

    /**
     * Almacena un nuevo equipo.
     */
    public function store(StoreEquipoOrganizadorRequest $request)
    {
        $datos = $request->validated();

        // Verificar que el torneo esté asignado al organizador
        $torneo = Torneo::where('id', $datos['torneo_id'])
            ->where('organizador_asignado_id', auth()->id())
            ->where('esta_activo', true)
            ->first();

        if (!$torneo) {
            return redirect()->route('organizador.equipos.index')
                ->with('error', 'No tienes permiso para agregar equipos a este torneo.');
        }

        // Manejar subida de logo
        if ($request->hasFile('logo')) {
            $datos['logo'] = $request->file('logo')->store('logos_equipos', 'public');
        }

        $datos['esta_activo'] = true;

        Equipo::create($datos);

        return redirect()->route('organizador.equipos.index', ['torneo_id' => $datos['torneo_id']])
            ->with('success', 'Equipo creado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un equipo.
     */
    public function edit(Equipo $equipo)
    {
        // Verificar que el equipo pertenezca a un torneo del organizador
        $torneo = Torneo::where('id', $equipo->torneo_id)
            ->where('organizador_asignado_id', auth()->id())
            ->first();

        if (!$torneo) {
            return redirect()->route('organizador.equipos.index')
                ->with('error', 'No tienes permiso para editar este equipo.');
        }

        $torneosAsignados = Torneo::where('organizador_asignado_id', auth()->id())
            ->where('esta_activo', true)
            ->orderBy('nombre')
            ->get();

        return view('organizador.equipos.edit', compact('equipo', 'torneosAsignados', 'torneo'));
    }

    /**
     * Actualiza un equipo.
     */
    public function update(StoreEquipoOrganizadorRequest $request, Equipo $equipo)
    {
        // Verificar que el equipo pertenezca a un torneo del organizador
        $torneo = Torneo::where('id', $equipo->torneo_id)
            ->where('organizador_asignado_id', auth()->id())
            ->first();

        if (!$torneo) {
            return redirect()->route('organizador.equipos.index')
                ->with('error', 'No tienes permiso para editar este equipo.');
        }

        $datos = $request->validated();

        // Manejar subida de nuevo logo
        if ($request->hasFile('logo')) {
            if ($equipo->logo) {
                Storage::disk('public')->delete($equipo->logo);
            }
            $datos['logo'] = $request->file('logo')->store('logos_equipos', 'public');
        }

        $datos['actualizado_por'] = auth()->id();

        $equipo->update($datos);

        return redirect()->route('organizador.equipos.index', ['torneo_id' => $equipo->torneo_id])
            ->with('success', 'Equipo actualizado exitosamente.');
    }

    /**
     * Elimina (desactiva) un equipo.
     */
    public function destroy(Equipo $equipo)
    {
        // Verificar que el equipo pertenezca a un torneo del organizador
        $torneo = Torneo::where('id', $equipo->torneo_id)
            ->where('organizador_asignado_id', auth()->id())
            ->first();

        if (!$torneo) {
            return redirect()->route('organizador.equipos.index')
                ->with('error', 'No tienes permiso para eliminar este equipo.');
        }

        // Eliminar logo si existe
        if ($equipo->logo) {
            Storage::disk('public')->delete($equipo->logo);
        }

        $equipo->update([
            'esta_activo' => false,
            'estado' => 'eliminado',
            'actualizado_por' => auth()->id(),
        ]);

        return redirect()->route('organizador.equipos.index', ['torneo_id' => $equipo->torneo_id])
            ->with('success', 'Equipo eliminado/desactivado exitosamente.');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEquipoRequest;
use App\Models\Equipo;
use App\Models\Torneo;
use App\Models\Jugador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EquiposController extends Controller
{
    /**
     * Muestra la lista de equipos.
     */
    public function index(Request $request)
    {
        $query = Equipo::with(['torneo', 'jugadores'])
            ->orderByDesc('created_at');

        // Filtro por torneo
        if ($request->filled('torneo_id')) {
            $query->where('torneo_id', $request->torneo_id);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('nombre_capitan', 'like', "%{$buscar}%");
            });
        }

        $equipos = $query->paginate(10);
        $torneos = Torneo::where('esta_activo', true)->orderBy('nombre')->get();

        return view('admin.equipos.index', compact('equipos', 'torneos'));
    }

    /**
     * Muestra el formulario para crear un nuevo equipo.
     */
    public function create()
    {
        $torneos = Torneo::where('esta_activo', true)
            ->whereIn('estado', ['borrador', 'inscripciones_abiertas', 'en_curso'])
            ->orderBy('nombre')
            ->get();
        
        return view('admin.equipos.create', compact('torneos'));
    }

    /**
     * Almacena un nuevo equipo.
     */
    public function store(StoreEquipoRequest $request)
    {
        $datos = $request->validated();

        // Manejar subida de logo
        if ($request->hasFile('logo')) {
            $datos['logo'] = $request->file('logo')->store('logos_equipos', 'public');
        }

        $datos['esta_activo'] = true;

        Equipo::create($datos);

        return redirect()->route('admin.equipos.index')
            ->with('success', 'Equipo creado exitosamente.');
    }

    /**
     * Muestra los detalles de un equipo con sus jugadores.
     */
    public function show(Equipo $equipo)
    {
        $equipo->load(['torneo', 'jugadores']);
        return view('admin.equipos.show', compact('equipo'));
    }

    /**
     * Muestra el formulario para editar un equipo.
     */
    public function edit(Equipo $equipo)
    {
        $torneos = Torneo::where('esta_activo', true)->orderBy('nombre')->get();
        return view('admin.equipos.edit', compact('equipo', 'torneos'));
    }

    /**
     * Actualiza un equipo.
     */
    public function update(StoreEquipoRequest $request, Equipo $equipo)
    {
        $datos = $request->validated();

        // Manejar subida de nuevo logo
        if ($request->hasFile('logo')) {
            // Eliminar logo anterior si existe
            if ($equipo->logo) {
                Storage::disk('public')->delete($equipo->logo);
            }
            $datos['logo'] = $request->file('logo')->store('logos_equipos', 'public');
        }

        $datos['actualizado_por'] = auth()->id();

        $equipo->update($datos);

        return redirect()->route('admin.equipos.index')
            ->with('success', 'Equipo actualizado exitosamente.');
    }

    /**
     * Elimina (desactiva) un equipo.
     */
    public function destroy(Equipo $equipo)
    {
        // Eliminar logo si existe
        if ($equipo->logo) {
            Storage::disk('public')->delete($equipo->logo);
        }

        $equipo->update([
            'esta_activo' => false,
            'estado' => 'eliminado',
            'actualizado_por' => auth()->id(),
        ]);

        return redirect()->route('admin.equipos.index')
            ->with('success', 'Equipo eliminado/desactivado exitosamente.');
    }
}
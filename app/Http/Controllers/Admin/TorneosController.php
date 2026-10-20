<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTorneoRequest;
use App\Http\Requests\UpdateTorneoRequest;
use App\Models\Torneo;
use App\Models\Instalacion;
use App\Models\Usuario;
use Illuminate\Http\Request;

class TorneosController extends Controller
{
    /**
     * Muestra la lista de torneos.
     */
    public function index(Request $request)
    {
        $query = Torneo::with(['instalacion', 'organizador', 'equipos'])
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

        $torneos = $query->paginate(10);

        return view('admin.torneos.index', compact('torneos'));
    }

    /**
     * Muestra el formulario para crear un nuevo torneo.
     */
    public function create()
    {
        $instalaciones = Instalacion::where('esta_activo', true)->get();
        $organizadores = Usuario::where('rol', 'organizador')->where('esta_activo', true)->get();
        
        return view('admin.torneos.create', compact('instalaciones', 'organizadores'));
    }

    /**
     * Almacena un nuevo torneo.
     */
    public function store(StoreTorneoRequest $request)
    {
        $datos = $request->validated();
        $datos['creado_por'] = auth()->id();
        $datos['esta_activo'] = true;

        Torneo::create($datos);

        return redirect()->route('admin.torneos.index')
            ->with('success', 'Torneo creado exitosamente.');
    }

    /**
     * Muestra los detalles de un torneo.
     */
    public function show(Torneo $torneo)
    {
        $torneo->load(['instalacion', 'organizador', 'equipos', 'partidos']);
        return view('admin.torneos.show', compact('torneo'));
    }

    /**
     * Muestra el formulario para editar un torneo.
     */
    public function edit(Torneo $torneo)
    {
        $instalaciones = Instalacion::where('esta_activo', true)->get();
        $organizadores = Usuario::where('rol', 'organizador')->where('esta_activo', true)->get();
        
        return view('admin.torneos.edit', compact('torneo', 'instalaciones', 'organizadores'));
    }

    /**
     * Actualiza un torneo.
     */
    public function update(UpdateTorneoRequest $request, Torneo $torneo)
    {
        $datos = $request->validated();
        $datos['actualizado_por'] = auth()->id();

        $torneo->update($datos);

        return redirect()->route('admin.torneos.index')
            ->with('success', 'Torneo actualizado exitosamente.');
    }

    /**
     * Elimina (desactiva) un torneo.
     */
    public function destroy(Torneo $torneo)
    {
        // Soft delete: marcamos como inactivo
        $torneo->update([
            'esta_activo' => false,
            'estado' => 'cancelado',
            'actualizado_por' => auth()->id(),
        ]);

        return redirect()->route('admin.torneos.index')
            ->with('success', 'Torneo desactivado/cancelado exitosamente.');
    }
}
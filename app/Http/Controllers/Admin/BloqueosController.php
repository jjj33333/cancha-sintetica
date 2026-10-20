<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBloqueoCanchaRequest;
use App\Models\BloqueoCancha;
use App\Models\Instalacion;
use Illuminate\Http\Request;

class BloqueosController extends Controller
{
    /**
     * Muestra la lista de bloqueos.
     */
    public function index(Request $request)
    {
        $query = BloqueoCancha::with(['instalacion', 'creadoPorUsuario'])
            ->orderByDesc('fecha_bloqueo')
            ->orderByDesc('hora_inicio');

        // Filtro por instalación
        if ($request->filled('instalacion_id')) {
            $query->where('instalacion_id', $request->instalacion_id);
        }

        // Filtro por motivo
        if ($request->filled('motivo')) {
            $query->where('motivo', $request->motivo);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            if ($request->estado === 'programado') {
                $query->where('esta_activo', true)
                      ->where('fecha_bloqueo', '>=', now()->toDateString());
            } elseif ($request->estado === 'completado') {
                $query->where('esta_activo', true)
                      ->where('fecha_bloqueo', '<', now()->toDateString());
            } elseif ($request->estado === 'cancelado') {
                $query->where('esta_activo', false);
            }
        }

        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where('descripcion', 'like', "%{$buscar}%");
        }

        $bloqueos = $query->paginate(15);
        $instalaciones = Instalacion::where('esta_activo', true)->orderBy('nombre')->get();

        return view('admin.bloqueos.index', compact('bloqueos', 'instalaciones'));
    }

    /**
     * Muestra el formulario para crear un nuevo bloqueo.
     */
    public function create()
    {
        $instalaciones = Instalacion::where('esta_activo', true)->orderBy('nombre')->get();
        return view('admin.bloqueos.create', compact('instalaciones'));
    }

    /**
     * Almacena un nuevo bloqueo.
     */
    public function store(StoreBloqueoCanchaRequest $request)
    {
        $datos = $request->validated();

        // Si es día completo, ajustar horas
        if ($request->boolean('dia_completo')) {
            $datos['hora_inicio'] = '00:00';
            $datos['hora_fin'] = '23:59';
        }

        // Manejar campos booleanos
        $datos['esta_activo'] = $request->boolean('esta_activo', true);
        $datos['creado_por'] = auth()->id();

        BloqueoCancha::create($datos);

        return redirect()->route('admin.bloqueos.index')
            ->with('success', 'Bloqueo creado exitosamente.');
    }

    /**
     * Muestra los detalles de un bloqueo.
     */
    public function show(BloqueoCancha $bloqueo)
    {
        $bloqueo->load(['instalacion', 'creadoPorUsuario', 'actualizadoPorUsuario']);
        return view('admin.bloqueos.show', compact('bloqueo'));
    }

    /**
     * Muestra el formulario para editar un bloqueo.
     */
    public function edit(BloqueoCancha $bloqueo)
    {
        $instalaciones = Instalacion::where('esta_activo', true)->orderBy('nombre')->get();
        return view('admin.bloqueos.edit', compact('bloqueo', 'instalaciones'));
    }

    /**
     * Actualiza un bloqueo.
     */
    public function update(StoreBloqueoCanchaRequest $request, BloqueoCancha $bloqueo)
    {
        $datos = $request->validated();

        // Si es día completo, ajustar horas
        if ($request->boolean('dia_completo')) {
            $datos['hora_inicio'] = '00:00';
            $datos['hora_fin'] = '23:59';
        }

        // Manejar campos booleanos
        $datos['esta_activo'] = $request->boolean('esta_activo', true);
        $datos['actualizado_por'] = auth()->id();

        $bloqueo->update($datos);

        return redirect()->route('admin.bloqueos.index')
            ->with('success', 'Bloqueo actualizado exitosamente.');
    }

    /**
     * Elimina (desactiva) un bloqueo.
     */
    public function destroy(BloqueoCancha $bloqueo)
    {
        $bloqueo->update([
            'esta_activo' => false,
            'actualizado_por' => auth()->id(),
        ]);

        return redirect()->route('admin.bloqueos.index')
            ->with('success', 'Bloqueo cancelado/desactivado exitosamente.');
    }
}
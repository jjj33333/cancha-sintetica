<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlanTarifaRequest;
use App\Models\PlanTarifa;
use App\Models\Instalacion;
use Illuminate\Http\Request;

class TarifasController extends Controller
{
    /**
     * Muestra la lista de tarifas.
     */
    public function index(Request $request)
    {
        $query = PlanTarifa::with(['instalacion', 'actualizadoPorUsuario'])
            ->orderBy('instalacion_id')
            ->orderBy('tipo_dia')
            ->orderBy('hora_inicio');

        // Filtro por instalación
        if ($request->filled('instalacion_id')) {
            $query->where('instalacion_id', $request->instalacion_id);
        }

        // Filtro por tipo de día
        if ($request->filled('tipo_dia')) {
            $query->where('tipo_dia', $request->tipo_dia);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('esta_activo', $request->estado === 'activo');
        }

        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where('nombre', 'like', "%{$buscar}%");
        }

        $tarifas = $query->paginate(15);
        $instalaciones = Instalacion::where('esta_activo', true)->orderBy('nombre')->get();

        return view('admin.tarifas.index', compact('tarifas', 'instalaciones'));
    }

    /**
     * Muestra el formulario para crear una nueva tarifa.
     */
    public function create()
    {
        $instalaciones = Instalacion::where('esta_activo', true)->orderBy('nombre')->get();
        return view('admin.tarifas.create', compact('instalaciones'));
    }

    /**
     * Almacena una nueva tarifa.
     */
    public function store(StorePlanTarifaRequest $request)
    {
        $datos = $request->validated();

        // Manejar campos booleanos
        $datos['esta_activo'] = $request->boolean('esta_activo', true);
        $datos['moneda'] = $request->moneda ?? 'GTQ';
        $datos['actualizado_por'] = auth()->id();

        PlanTarifa::create($datos);

        return redirect()->route('admin.tarifas.index')
            ->with('success', 'Tarifa creada exitosamente.');
    }

    /**
     * Muestra los detalles de una tarifa.
     */
    public function show(PlanTarifa $tarifa)
    {
        $tarifa->load(['instalacion', 'actualizadoPorUsuario']);
        return view('admin.tarifas.show', compact('tarifa'));
    }

    /**
     * Muestra el formulario para editar una tarifa.
     */
    public function edit(PlanTarifa $tarifa)
    {
        $instalaciones = Instalacion::where('esta_activo', true)->orderBy('nombre')->get();
        return view('admin.tarifas.edit', compact('tarifa', 'instalaciones'));
    }

    /**
     * Actualiza una tarifa.
     */
    public function update(StorePlanTarifaRequest $request, PlanTarifa $tarifa)
    {
        $datos = $request->validated();

        // Manejar campos booleanos
        $datos['esta_activo'] = $request->boolean('esta_activo', true);
        $datos['moneda'] = $request->moneda ?? 'GTQ';
        $datos['actualizado_por'] = auth()->id();

        $tarifa->update($datos);

        return redirect()->route('admin.tarifas.index')
            ->with('success', 'Tarifa actualizada exitosamente.');
    }

    /**
     * Elimina (desactiva) una tarifa.
     */
    public function destroy(PlanTarifa $tarifa)
    {
        $tarifa->update([
            'esta_activo' => false,
            'actualizado_por' => auth()->id(),
        ]);

        return redirect()->route('admin.tarifas.index')
            ->with('success', 'Tarifa desactivada exitosamente.');
    }
}
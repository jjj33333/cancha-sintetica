<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInstalacionRequest;
use App\Models\Instalacion;
use Illuminate\Http\Request;

class InstalacionesController extends Controller
{
    /**
     * Muestra la lista de instalaciones.
     */
    public function index()
    {
        $instalaciones = Instalacion::with('actualizadoPorUsuario')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.instalaciones.index', compact('instalaciones'));
    }

    /**
     * Muestra el formulario para crear una nueva instalación.
     */
    public function create()
    {
        return view('admin.instalaciones.create');
    }

    /**
     * Almacena una nueva instalación.
     */
    public function store(StoreInstalacionRequest $request)
    {
        $datos = $request->validated();

        // Manejar campos booleanos
        $datos['tiene_iluminacion'] = $request->boolean('tiene_iluminacion', false);
        $datos['tiene_estacionamiento'] = $request->boolean('tiene_estacionamiento', false);
        $datos['tiene_casilleros'] = $request->boolean('tiene_casilleros', false);
        $datos['esta_activo'] = $request->boolean('esta_activo', true);

        $datos['actualizado_por'] = auth()->id();

        Instalacion::create($datos);

        return redirect()->route('admin.instalaciones.index')
            ->with('success', 'Instalación creada exitosamente.');
    }

    /**
     * Muestra los detalles de una instalación.
     */
    public function show(Instalacion $instalacion)
    {
        $instalacion->load('actualizadoPorUsuario');
        return view('admin.instalaciones.show', compact('instalacion'));
    }

    /**
     * Muestra el formulario para editar una instalación.
     */
    public function edit(Instalacion $instalacion)
    {
        return view('admin.instalaciones.edit', compact('instalacion'));
    }

    /**
     * Actualiza una instalación.
     */
    public function update(StoreInstalacionRequest $request, Instalacion $instalacion)
    {
        $datos = $request->validated();

        // Manejar campos booleanos
        $datos['tiene_iluminacion'] = $request->boolean('tiene_iluminacion', false);
        $datos['tiene_estacionamiento'] = $request->boolean('tiene_estacionamiento', false);
        $datos['tiene_casilleros'] = $request->boolean('tiene_casilleros', false);
        $datos['esta_activo'] = $request->boolean('esta_activo', true);

        $datos['actualizado_por'] = auth()->id();

        $instalacion->update($datos);

        return redirect()->route('admin.instalaciones.index')
            ->with('success', 'Instalación actualizada exitosamente.');
    }

    /**
     * Elimina (desactiva) una instalación.
     */
    public function destroy(Instalacion $instalacion)
    {
        $instalacion->update([
            'esta_activo' => false,
            'actualizado_por' => auth()->id(),
        ]);

        return redirect()->route('admin.instalaciones.index')
            ->with('success', 'Instalación desactivada exitosamente.');
    }
}
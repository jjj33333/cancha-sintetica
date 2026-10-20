<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jugador;
use App\Models\Equipo;
use Illuminate\Http\Request;

class JugadoresController extends Controller
{
    /**
     * Almacena un nuevo jugador en un equipo.
     */
    public function store(Request $request, Equipo $equipo)
    {
        $request->validate([
            'nombre_completo' => ['required', 'string', 'max:255'],
            'dni' => ['nullable', 'string', 'max:20'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'posicion' => ['nullable', 'string', 'max:50'],
            'numero_camiseta' => ['nullable', 'integer', 'min:1', 'max:99'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'es_capitan' => ['nullable', 'boolean'],
        ]);

        // Si es capitán, desmarcar los demás jugadores del equipo
        if ($request->boolean('es_capitan')) {
            Jugador::where('equipo_id', $equipo->id)
                ->where('es_capitan', true)
                ->update(['es_capitan' => false]);
        }

        Jugador::create([
            'equipo_id' => $equipo->id,
            'nombre_completo' => $request->nombre_completo,
            'dni' => $request->dni,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'posicion' => $request->posicion,
            'numero_camiseta' => $request->numero_camiseta,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'es_capitan' => $request->boolean('es_capitan', false),
            'esta_activo' => true,
            'actualizado_por' => auth()->id(),
        ]);

        return redirect()->route('admin.equipos.show', $equipo)
            ->with('success', 'Jugador agregado exitosamente.');
    }

    /**
     * Actualiza un jugador.
     */
    public function update(Request $request, Equipo $equipo, Jugador $jugador)
    {
        $request->validate([
            'nombre_completo' => ['required', 'string', 'max:255'],
            'dni' => ['nullable', 'string', 'max:20'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'posicion' => ['nullable', 'string', 'max:50'],
            'numero_camiseta' => ['nullable', 'integer', 'min:1', 'max:99'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'es_capitan' => ['nullable', 'boolean'],
        ]);

        // Si es capitán, desmarcar los demás
        if ($request->boolean('es_capitan')) {
            Jugador::where('equipo_id', $equipo->id)
                ->where('id', '!=', $jugador->id)
                ->where('es_capitan', true)
                ->update(['es_capitan' => false]);
        }

        $jugador->update([
            'nombre_completo' => $request->nombre_completo,
            'dni' => $request->dni,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'posicion' => $request->posicion,
            'numero_camiseta' => $request->numero_camiseta,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'es_capitan' => $request->boolean('es_capitan', false),
            'actualizado_por' => auth()->id(),
        ]);

        return redirect()->route('admin.equipos.show', $equipo)
            ->with('success', 'Jugador actualizado exitosamente.');
    }

    /**
     * Elimina (desactiva) un jugador.
     */
    public function destroy(Equipo $equipo, Jugador $jugador)
    {
        $jugador->update([
            'esta_activo' => false,
            'actualizado_por' => auth()->id(),
        ]);

        return redirect()->route('admin.equipos.show', $equipo)
            ->with('success', 'Jugador eliminado exitosamente.');
    }
}
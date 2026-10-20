<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Instalacion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReservasController extends Controller
{
    /**
     * Muestra la lista de reservas con filtros y búsqueda.
     */
    public function index(Request $request)
    {
        $query = Reserva::with(['instalacion', 'creadoPorUsuario']);

        // Orden predeterminado: hora y fecha más reciente
        $orden = $request->input('orden', 'reciente');
        if ($orden === 'antigua') {
            $query->orderBy('fecha_reserva', 'asc')->orderBy('hora_inicio', 'asc');
        } elseif ($orden === 'creacion') {
            $query->orderByDesc('created_at')->orderByDesc('id');
        } else {
            // Predeterminado: hora y fecha más reciente
            $query->orderByDesc('fecha_reserva')
                  ->orderByDesc('hora_inicio')
                  ->orderByDesc('created_at');
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por fecha
        if ($request->filled('fecha')) {
            $query->where('fecha_reserva', $request->fecha);
        }

        // Filtro por instalación (si se especifica)
        if ($request->filled('instalacion_id')) {
            $query->where('instalacion_id', $request->instalacion_id);
        }

        // Búsqueda por cliente, código o teléfono
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre_cliente', 'like', "%{$buscar}%")
                  ->orWhere('codigo_reserva', 'like', "%{$buscar}%")
                  ->orWhere('telefono_cliente', 'like', "%{$buscar}%");
            });
        }

        // Límite / Ver más: por defecto 10
        $porPaginaRaw = $request->input('por_pagina', 10);
        if ($porPaginaRaw === 'todas' || $porPaginaRaw == -1 || (int)$porPaginaRaw > 500) {
            $porPagina = 9999;
        } else {
            $porPagina = in_array((int)$porPaginaRaw, [10, 25, 50, 100]) ? (int)$porPaginaRaw : 10;
        }

        $reservas = $query->paginate($porPagina)->withQueryString();
        $instalaciones = Instalacion::where('esta_activo', true)->get();

        return view('admin.reservas.index', compact('reservas', 'instalaciones', 'porPagina', 'orden'));
    }

    /**
     * Muestra el formulario para crear una nueva reserva.
     */
    public function create()
    {
        $instalacionPrincipal = Instalacion::where('esta_activo', true)->first();
        return view('admin.reservas.create', compact('instalacionPrincipal'));
    }

    /**
     * Almacena una nueva reserva en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'instalacion_id' => 'nullable|exists:instalaciones,id',
            'nombre_cliente' => 'required|string|max:255',
            'telefono_cliente' => 'nullable|string|max:20',
            'fecha_reserva' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after:hora_inicio',
            'precio_total' => 'required|numeric|min:0',
            'estado' => 'required|in:pendiente,confirmada,cancelada,completada',
            'estado_pago' => 'nullable|in:pendiente,pagado,reembolsado',
            'metodo_pago' => 'nullable|in:efectivo,transferencia,tarjeta,otro',
            'notas' => 'nullable|string',
        ]);

        // Instalación por defecto: Cancha Principal
        $defaultInstalacionId = Instalacion::where('esta_activo', true)->value('id') ?? 1;
        $validated['instalacion_id'] = $validated['instalacion_id'] ?? $defaultInstalacionId;

        // Cliente: DPI y Correo se guardan explícitamente como NULL
        $validated['dni_cliente'] = null;
        $validated['email_cliente'] = null;
        $validated['telefono_cliente'] = !empty($validated['telefono_cliente']) ? trim($validated['telefono_cliente']) : null;

        // Métodos y precios por defecto
        $validated['metodo_pago'] = $validated['metodo_pago'] ?? 'efectivo';
        $validated['estado_pago'] = $validated['estado_pago'] ?? 'pendiente';
        $validated['precio_total'] = isset($validated['precio_total']) ? $validated['precio_total'] : 100.00;

        $inicio = Carbon::parse($validated['hora_inicio']);
        $fin = Carbon::parse($validated['hora_fin']);
        $totalHoras = round($fin->diffInMinutes($inicio) / 60, 2);

        // Generar código único de reserva (ej: RES-20261001-A1B2)
        $codigo = 'RES-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        Reserva::create(array_merge($validated, [
            'codigo_reserva' => $codigo,
            'total_horas' => $totalHoras,
            'tipo_reserva' => 'manual',
            'esta_activo' => true,
            'creado_por' => auth()->id(),
        ]));

        return redirect()->route('admin.reservas.index')->with('success', "Reserva {$codigo} creada exitosamente.");
    }

    /**
     * Muestra una reserva específica.
     */
    public function show(Reserva $reserva)
    {
        $reserva->load(['instalacion', 'creadoPorUsuario', 'torneo', 'partido']);
        return view('admin.reservas.show', compact('reserva'));
    }

    /**
     * Muestra el formulario para editar una reserva.
     */
    public function edit(Reserva $reserva)
    {
        $instalacionPrincipal = $reserva->instalacion ?? Instalacion::where('esta_activo', true)->first();
        return view('admin.reservas.edit', compact('reserva', 'instalacionPrincipal'));
    }

    /**
     * Actualiza una reserva en la base de datos.
     */
    public function update(Request $request, Reserva $reserva)
    {
        $validated = $request->validate([
            'instalacion_id' => 'nullable|exists:instalaciones,id',
            'nombre_cliente' => 'required|string|max:255',
            'telefono_cliente' => 'nullable|string|max:20',
            'fecha_reserva' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after:hora_inicio',
            'precio_total' => 'required|numeric|min:0',
            'estado' => 'required|in:pendiente,confirmada,cancelada,completada',
            'estado_pago' => 'nullable|in:pendiente,pagado,reembolsado',
            'metodo_pago' => 'nullable|in:efectivo,transferencia,tarjeta,otro',
            'notas' => 'nullable|string',
        ]);

        $defaultInstalacionId = $reserva->instalacion_id ?? (Instalacion::where('esta_activo', true)->value('id') ?? 1);
        $validated['instalacion_id'] = $validated['instalacion_id'] ?? $defaultInstalacionId;

        // Cliente: DPI y Correo se guardan explícitamente como NULL
        $validated['dni_cliente'] = null;
        $validated['email_cliente'] = null;
        $validated['telefono_cliente'] = !empty($validated['telefono_cliente']) ? trim($validated['telefono_cliente']) : null;
        $validated['metodo_pago'] = $validated['metodo_pago'] ?? 'efectivo';

        $inicio = Carbon::parse($validated['hora_inicio']);
        $fin = Carbon::parse($validated['hora_fin']);
        $totalHoras = round($fin->diffInMinutes($inicio) / 60, 2);

        $reserva->update(array_merge($validated, [
            'total_horas' => $totalHoras,
            'actualizado_por' => auth()->id(),
        ]));

        return redirect()->route('admin.reservas.index')->with('success', "Reserva {$reserva->codigo_reserva} actualizada exitosamente.");
    }

    /**
     * Elimina una reserva de la base de datos (o la marca como inactiva/cancelada).
     */
    public function destroy(Reserva $reserva)
    {
        $reserva->update(['esta_activo' => false, 'estado' => 'cancelada']);
        return redirect()->route('admin.reservas.index')->with('success', 'Reserva cancelada/eliminada exitosamente.');
    }
}
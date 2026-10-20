<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegistroActividad;
use App\Models\HistorialReserva;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HistorialController extends Controller
{
    /**
     * Muestra el historial de actividad del sistema.
     */
    public function index(Request $request)
    {
        $query = RegistroActividad::with('usuario')
            ->activos()
            ->orderByDesc('created_at');

        // Filtro por búsqueda (acción o detalle)
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('accion', 'like', "%{$buscar}%")
                  ->orWhere('nombre_tabla', 'like', "%{$buscar}%")
                  ->orWhere('registro_id', 'like', "%{$buscar}%");
            });
        }

        // Filtro por usuario
        if ($request->filled('usuario_id')) {
            $query->where('usuario_id', $request->usuario_id);
        }

        // Filtro por tipo de acción
        if ($request->filled('tipo_accion')) {
            $tipo = $request->tipo_accion;
            if ($tipo === 'crear') {
                $query->where('accion', 'like', '%Crear%');
            } elseif ($tipo === 'editar') {
                $query->where('accion', 'like', '%Editar%');
            } elseif ($tipo === 'eliminar') {
                $query->where('accion', 'like', '%Eliminar%');
            } elseif ($tipo === 'programar') {
                $query->where('accion', 'like', '%Programar%');
            }
        }

        // Filtro por tabla
        if ($request->filled('tabla')) {
            $query->where('nombre_tabla', $request->tabla);
        }

        // Filtro por rango de fechas
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->fecha_inicio)->startOfDay(),
                Carbon::parse($request->fecha_fin)->endOfDay(),
            ]);
        }

        $registros = $query->paginate(20);

        // Obtener usuarios para el filtro
        $usuarios = Usuario::where('esta_activo', true)
            ->orderBy('nombre')
            ->get();

        // Tablas disponibles para filtro
        $tablas = [
            'reservas' => 'Reservas',
            'torneos' => 'Torneos',
            'equipos' => 'Equipos',
            'jugadores' => 'Jugadores',
            'partidos' => 'Partidos',
            'anuncios' => 'Anuncios',
            'instalaciones' => 'Instalaciones',
            'planes_tarifas' => 'Tarifas',
            'bloqueos_cancha' => 'Bloqueos',
            'galeria_imagenes' => 'Galería',
            'usuarios' => 'Usuarios',
        ];

        // Estadísticas rápidas
        $totalRegistrosHoy = RegistroActividad::activos()
            ->whereDate('created_at', today())
            ->count();

        $totalRegistrosSemana = RegistroActividad::activos()
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $accionesMasFrecuentes = RegistroActividad::activos()
            ->select('accion')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('accion')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        return view('admin.historial.index', compact(
            'registros',
            'usuarios',
            'tablas',
            'totalRegistrosHoy',
            'totalRegistrosSemana',
            'accionesMasFrecuentes'
        ));
    }

    /**
     * Muestra los detalles de un registro de actividad.
     */
    public function show(RegistroActividad $registro)
    {
        $registro->load('usuario');
        return view('admin.historial.show', compact('registro'));
    }

    /**
     * Muestra el historial de cambios de una reserva específica.
     */
    public function historialReserva($reservaId)
    {
        $historial = HistorialReserva::with(['reserva', 'cambiadoPorUsuario'])
            ->deReserva($reservaId)
            ->activos()
            ->orderByDesc('cambiado_en')
            ->get();

        return view('admin.historial.reserva', compact('historial', 'reservaId'));
    }
}
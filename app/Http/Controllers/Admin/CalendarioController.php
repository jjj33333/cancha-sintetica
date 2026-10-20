<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Partido;
use App\Models\BloqueoCancha;
use App\Models\Instalacion;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CalendarioController extends Controller
{
    /**
     * Muestra el calendario general unificado.
     */
    public function index(Request $request)
    {
        // Determinar la semana a mostrar (por defecto la actual)
        $fechaReferencia = $request->get('fecha', now()->toDateString());
        try {
            $fechaParsed = Carbon::parse($fechaReferencia);
        } catch (\Exception $e) {
            $fechaParsed = Carbon::today();
        }

        $inicioSemana = $fechaParsed->copy()->startOfWeek(Carbon::MONDAY);
        $finSemana = $inicioSemana->copy()->endOfWeek(Carbon::SUNDAY);
        $hoy = Carbon::today();

        // Filtros
        $tipoEvento = $request->get('tipo', 'todos'); // todos, reserva, torneo, bloqueo
        if (!in_array($tipoEvento, ['todos', 'reserva', 'torneo', 'bloqueo'])) {
            $tipoEvento = 'todos';
        }
        $instalacionId = $request->filled('instalacion') ? $request->get('instalacion') : null;

        // Obtener instalaciones activas para el filtro
        $instalaciones = Instalacion::where('esta_activo', true)->get();

        // ============================================
        // 1. OBTENER RESERVAS DE LA SEMANA
        // ============================================
        $reservasQuery = Reserva::activas()
            ->confirmadas()
            ->whereBetween('fecha_reserva', [$inicioSemana->toDateString(), $finSemana->toDateString()])
            ->with(['instalacion', 'creadoPorUsuario']);

        if ($instalacionId) {
            $reservasQuery->where('instalacion_id', $instalacionId);
        }

        $reservas = $reservasQuery->get();

        // ============================================
        // 2. OBTENER PARTIDOS DE LA SEMANA
        // ============================================
        $partidosQuery = Partido::activos()
            ->whereBetween('fecha_partido', [$inicioSemana->toDateString(), $finSemana->toDateString()])
            ->with(['torneo.organizador', 'equipoLocal', 'equipoVisitante', 'instalacion']);

        if ($instalacionId) {
            $partidosQuery->where('instalacion_id', $instalacionId);
        }

        $partidos = $partidosQuery->get();

        // ============================================
        // 3. OBTENER BLOQUEOS DE LA SEMANA
        // ============================================
        $bloqueosQuery = BloqueoCancha::activos()
            ->deRango($inicioSemana->toDateString(), $finSemana->toDateString())
            ->with(['instalacion', 'creadoPorUsuario']);

        if ($instalacionId) {
            $bloqueosQuery->where('instalacion_id', $instalacionId);
        }

        $bloqueos = $bloqueosQuery->get();

        // ============================================
        // 4. UNIFICAR EVENTOS PARA LA TABLA
        // ============================================
        $eventos = new Collection();

        // Agregar reservas
        if ($tipoEvento === 'todos' || $tipoEvento === 'reserva') {
            foreach ($reservas as $reserva) {
                // Determinar origen
                $creador = $reserva->creadoPorUsuario;
                if ($creador) {
                    if (stripos($creador->nombre, 'carlos') !== false) {
                        $origen = 'Carlos Org.';
                    } elseif (stripos($creador->nombre, 'admin') !== false || $creador->rol === 'admin') {
                        $origen = 'Admin';
                    } else {
                        $origen = $creador->nombre;
                    }
                } elseif ($reserva->tipo_reserva === 'manual') {
                    $origen = 'Admin';
                } else {
                    $origen = 'Sistema';
                }

                $detalleCliente = $reserva->nombre_cliente;
                if ($reserva->codigo_reserva) {
                    $detalleCliente .= " ({$reserva->codigo_reserva})";
                }

                $eventos->push([
                    'id' => 'reserva_' . $reserva->id,
                    'fecha' => Carbon::parse($reserva->fecha_reserva),
                    'fecha_str' => Carbon::parse($reserva->fecha_reserva)->toDateString(),
                    'hora_inicio' => Carbon::parse($reserva->hora_inicio)->format('H:i'),
                    'hora_fin' => Carbon::parse($reserva->hora_fin)->format('H:i'),
                    'tipo' => 'reserva',
                    'tipo_texto' => 'Reserva',
                    'detalle' => $detalleCliente,
                    'origen' => $origen,
                    'badge_icon' => '🔷',
                    'badge_color' => 'bg-blue-100 text-blue-800 border border-blue-200',
                    'instalacion' => $reserva->instalacion->nombre ?? 'Cancha no asignada',
                ]);
            }
        }

        // Agregar partidos
        if ($tipoEvento === 'todos' || $tipoEvento === 'torneo') {
            foreach ($partidos as $partido) {
                $equipoLocal = $partido->equipoLocal->nombre ?? 'TBD';
                $equipoVisitante = $partido->equipoVisitante->nombre ?? 'TBD';
                $torneoNombre = $partido->torneo->nombre ?? 'Torneo';

                // Determinar origen
                $organizador = $partido->torneo?->organizador;
                if ($organizador) {
                    if (stripos($organizador->nombre, 'carlos') !== false) {
                        $origen = 'Carlos Org.';
                    } elseif (stripos($organizador->nombre, 'admin') !== false || $organizador->rol === 'admin') {
                        $origen = 'Admin';
                    } else {
                        $origen = $organizador->nombre;
                    }
                } else {
                    $origen = 'Sistema';
                }

                $horaFin = Carbon::parse($partido->hora_inicio)->addHours(2)->format('H:i');

                $eventos->push([
                    'id' => 'partido_' . $partido->id,
                    'fecha' => Carbon::parse($partido->fecha_partido),
                    'fecha_str' => Carbon::parse($partido->fecha_partido)->toDateString(),
                    'hora_inicio' => Carbon::parse($partido->hora_inicio)->format('H:i'),
                    'hora_fin' => $horaFin,
                    'tipo' => 'torneo',
                    'tipo_texto' => 'Torneo',
                    'detalle' => "{$torneoNombre}: {$equipoLocal} vs {$equipoVisitante}",
                    'origen' => $origen,
                    'badge_icon' => '🏆',
                    'badge_color' => 'bg-amber-100 text-amber-800 border border-amber-200',
                    'instalacion' => $partido->instalacion->nombre ?? 'Cancha no asignada',
                ]);
            }
        }

        // Agregar bloqueos
        if ($tipoEvento === 'todos' || $tipoEvento === 'bloqueo') {
            foreach ($bloqueos as $bloqueo) {
                $creador = $bloqueo->creadoPorUsuario;
                if ($creador) {
                    if (stripos($creador->nombre, 'carlos') !== false) {
                        $origen = 'Carlos Org.';
                    } elseif (stripos($creador->nombre, 'admin') !== false || $creador->rol === 'admin') {
                        $origen = 'Admin';
                    } else {
                        $origen = $creador->nombre;
                    }
                } else {
                    $origen = 'Admin';
                }

                $motivoIcono = $bloqueo->motivo_icono ?: '🚫';
                $motivoTexto = $bloqueo->motivo_texto ?: 'Bloqueo';
                $desc = $bloqueo->descripcion ? ": {$bloqueo->descripcion}" : '';

                $eventos->push([
                    'id' => 'bloqueo_' . $bloqueo->id,
                    'fecha' => Carbon::parse($bloqueo->fecha_bloqueo),
                    'fecha_str' => Carbon::parse($bloqueo->fecha_bloqueo)->toDateString(),
                    'hora_inicio' => Carbon::parse($bloqueo->hora_inicio)->format('H:i'),
                    'hora_fin' => Carbon::parse($bloqueo->hora_fin)->format('H:i'),
                    'tipo' => 'bloqueo',
                    'tipo_texto' => 'Bloqueo',
                    'detalle' => "{$motivoIcono} {$motivoTexto}{$desc}",
                    'origen' => $origen,
                    'badge_icon' => '🚫',
                    'badge_color' => 'bg-red-100 text-red-800 border border-red-200',
                    'instalacion' => $bloqueo->instalacion->nombre ?? 'Cancha no asignada',
                ]);
            }
        }

        // Ordenar eventos por fecha y hora
        $eventos = $eventos->sortBy(function ($evento) {
            return $evento['fecha']->format('Y-m-d') . '_' . $evento['hora_inicio'];
        })->values();

        // ============================================
        // 5. CALCULAR ESTADO DE CADA DÍA DE LA SEMANA
        // ============================================
        $diasSemana = [];

        for ($i = 0; $i < 7; $i++) {
            $dia = $inicioSemana->copy()->addDays($i);
            $diaFechaStr = $dia->toDateString();

            // Filtrar eventos por día
            $diaReservas = $reservas->filter(fn($r) => Carbon::parse($r->fecha_reserva)->isSameDay($dia));
            $diaPartidos = $partidos->filter(fn($p) => Carbon::parse($p->fecha_partido)->isSameDay($dia));
            $diaBloqueos = $bloqueos->filter(fn($b) => Carbon::parse($b->fecha_bloqueo)->isSameDay($dia));

            $totalEventos = $diaReservas->count() + $diaPartidos->count() + $diaBloqueos->count();
            $tieneTorneo = $diaPartidos->count() > 0;

            // Determinar estado: Disponible, Parcial, Lleno, Torneo
            if ($tieneTorneo) {
                $estado = 'torneo';
                $estadoTexto = 'Torneo';
                $estadoColor = 'bg-amber-100 text-amber-800 border border-amber-200';
            } elseif ($totalEventos === 0) {
                $estado = 'disponible';
                $estadoTexto = 'Disponible';
                $estadoColor = 'bg-emerald-100 text-emerald-800 border border-emerald-200';
            } elseif ($totalEventos >= 5) {
                $estado = 'lleno';
                $estadoTexto = 'Lleno';
                $estadoColor = 'bg-rose-100 text-rose-800 border border-rose-200';
            } else {
                $estado = 'parcial';
                $estadoTexto = 'Parcial';
                $estadoColor = 'bg-amber-100 text-amber-800 border border-amber-200';
            }

            $diasSemana[] = [
                'fecha' => $dia->copy(),
                'fecha_str' => $diaFechaStr,
                'numero' => $dia->format('d'),
                'nombre' => ucfirst($dia->locale('es')->dayName),
                'total_eventos' => $totalEventos,
                'reservas_count' => $diaReservas->count(),
                'partidos_count' => $diaPartidos->count(),
                'bloqueos_count' => $diaBloqueos->count(),
                'estado' => $estado,
                'estado_color' => $estadoColor,
                'estado_texto' => $estadoTexto,
                'es_hoy' => $dia->isSameDay($hoy),
            ];
        }

        $esSemanaActual = $inicioSemana->isCurrentWeek();

        return view('admin.calendario.index', compact(
            'diasSemana',
            'eventos',
            'instalaciones',
            'inicioSemana',
            'finSemana',
            'tipoEvento',
            'instalacionId',
            'esSemanaActual'
        ));
    }
}
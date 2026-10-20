<?php

namespace App\Http\Controllers\Trabajador;

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
     * Muestra el calendario general (solo lectura para el trabajador).
     */
    public function index(Request $request)
    {
        // Determinar la semana a mostrar (por defecto la actual)
        $fechaReferencia = $request->get('fecha', now()->toDateString());
        $inicioSemana = Carbon::parse($fechaReferencia)->startOfWeek(Carbon::MONDAY);
        $finSemana = $inicioSemana->copy()->endOfWeek(Carbon::SUNDAY);

        // Filtro por instalación (opcional)
        $instalacionId = $request->get('instalacion', null);

        // Obtener instalaciones para el filtro
        $instalaciones = Instalacion::where('esta_activo', true)->orderBy('nombre')->get();

        // ============================================
        // 1. OBTENER RESERVAS DE LA SEMANA
        // ============================================
        $reservasQuery = Reserva::activas()
            ->confirmadas()
            ->whereBetween('fecha_reserva', [$inicioSemana, $finSemana])
            ->with('instalacion');

        if ($instalacionId) {
            $reservasQuery->where('instalacion_id', $instalacionId);
        }

        $reservas = $reservasQuery->get();

        // ============================================
        // 2. OBTENER PARTIDOS DE LA SEMANA
        // ============================================
        $partidosQuery = Partido::activos()
            ->whereBetween('fecha_partido', [$inicioSemana, $finSemana])
            ->with(['torneo', 'equipoLocal', 'equipoVisitante', 'instalacion']);

        if ($instalacionId) {
            $partidosQuery->where('instalacion_id', $instalacionId);
        }

        $partidos = $partidosQuery->get();

        // ============================================
        // 3. OBTENER BLOQUEOS DE LA SEMANA
        // ============================================
        $bloqueosQuery = BloqueoCancha::activos()
            ->deRango($inicioSemana, $finSemana)
            ->with('instalacion');

        if ($instalacionId) {
            $bloqueosQuery->where('instalacion_id', $instalacionId);
        }

        $bloqueos = $bloqueosQuery->get();

        // ============================================
        // 4. UNIFICAR EVENTOS PARA LA TABLA (Solo lectura)
        // ============================================
        $eventos = new Collection();

        // Agregar reservas
        foreach ($reservas as $reserva) {
            $eventos->push([
                'fecha' => Carbon::parse($reserva->fecha_reserva),
                'hora_inicio' => Carbon::parse($reserva->hora_inicio)->format('H:i'),
                'hora_fin' => Carbon::parse($reserva->hora_fin)->format('H:i'),
                'tipo' => 'reserva',
                'detalle' => $reserva->nombre_cliente,
                'telefono' => $reserva->telefono_cliente,
                'badge_color' => 'bg-[#DBEAFE] text-[#1E40AF]',
                'badge_icon' => '',
                'instalacion' => $reserva->instalacion->nombre ?? 'N/A',
            ]);
        }

        // Agregar partidos
        foreach ($partidos as $partido) {
            $equipoLocal = $partido->equipoLocal->nombre ?? 'TBD';
            $equipoVisitante = $partido->equipoVisitante->nombre ?? 'TBD';
            $torneoNombre = $partido->torneo->nombre ?? 'Torneo';
            $categoria = $partido->torneo->categoria ?? '';

            $icono = match ($categoria) {
                'libre' => '🏆',
                'sub20' => '🥇',
                'femenino' => '⭐',
                default => '🏆',
            };

            $eventos->push([
                'fecha' => Carbon::parse($partido->fecha_partido),
                'hora_inicio' => Carbon::parse($partido->hora_inicio)->format('H:i'),
                'hora_fin' => Carbon::parse($partido->hora_inicio)->addHours(2)->format('H:i'),
                'tipo' => 'torneo',
                'detalle' => "{$icono} {$torneoNombre}: {$equipoLocal} vs {$equipoVisitante}",
                'telefono' => null,
                'badge_color' => 'bg-[#FEF3C7] text-[#92400E]',
                'badge_icon' => '🏆',
                'instalacion' => $partido->instalacion->nombre ?? 'N/A',
            ]);
        }

        // Agregar bloqueos
        foreach ($bloqueos as $bloqueo) {
            $eventos->push([
                'fecha' => Carbon::parse($bloqueo->fecha_bloqueo),
                'hora_inicio' => Carbon::parse($bloqueo->hora_inicio)->format('H:i'),
                'hora_fin' => Carbon::parse($bloqueo->hora_fin)->format('H:i'),
                'tipo' => 'bloqueo',
                'detalle' => "{$bloqueo->motivo_icono} {$bloqueo->motivo_texto}: {$bloqueo->descripcion}",
                'telefono' => null,
                'badge_color' => 'bg-[#FEE2E2] text-[#991B1B]',
                'badge_icon' => '🚫',
                'instalacion' => $bloqueo->instalacion->nombre ?? 'N/A',
            ]);
        }

        // Ordenar eventos por fecha y hora
        $eventos = $eventos->sortBy(function ($evento) {
            return $evento['fecha']->format('Y-m-d') . $evento['hora_inicio'];
        })->values();

        // ============================================
        // 5. CALCULAR ESTADO DE CADA DÍA DE LA SEMANA
        // ============================================
        $diasSemana = [];
        $hoy = Carbon::today();

        for ($i = 0; $i < 7; $i++) {
            $dia = $inicioSemana->copy()->addDays($i);
            $diaReservas = $reservas->filter(fn($r) => Carbon::parse($r->fecha_reserva)->isSameDay($dia));
            $diaPartidos = $partidos->filter(fn($p) => Carbon::parse($p->fecha_partido)->isSameDay($dia));
            $diaBloqueos = $bloqueos->filter(fn($b) => Carbon::parse($b->fecha_bloqueo)->isSameDay($dia));

            $totalEventos = $diaReservas->count() + $diaPartidos->count() + $diaBloqueos->count();
            $tieneTorneo = $diaPartidos->count() > 0;

            // Determinar estado
            if ($tieneTorneo) {
                $estado = 'torneo';
                $estadoColor = 'bg-[#DBEAFE] text-[#1E40AF]';
                $estadoTexto = '🏆 Torneo';
            } elseif ($totalEventos === 0) {
                $estado = 'disponible';
                $estadoColor = 'bg-[#D1FAE5] text-[#065F46]';
                $estadoTexto = 'Disponible';
            } elseif ($totalEventos >= 5) {
                $estado = 'lleno';
                $estadoColor = 'bg-[#FEE2E2] text-[#991B1B]';
                $estadoTexto = 'Lleno';
            } else {
                $estado = 'parcial';
                $estadoColor = 'bg-[#FEF3C7] text-[#92400E]';
                $estadoTexto = 'Parcial';
            }

            $diasSemana[] = [
                'fecha' => $dia->copy(),
                'numero' => $dia->format('d'),
                'nombre' => $dia->locale('es')->dayName,
                'total_eventos' => $totalEventos,
                'estado' => $estado,
                'estado_color' => $estadoColor,
                'estado_texto' => $estadoTexto,
                'es_hoy' => $dia->isSameDay($hoy),
            ];
        }

        // Estadísticas rápidas
        $totalReservasSemana = $reservas->count();
        $totalPartidosSemana = $partidos->count();
        $totalBloqueosSemana = $bloqueos->count();

        return view('trabajador.calendario.index', compact(
            'diasSemana',
            'eventos',
            'instalaciones',
            'inicioSemana',
            'finSemana',
            'instalacionId',
            'totalReservasSemana',
            'totalPartidosSemana',
            'totalBloqueosSemana'
        ));
    }
}
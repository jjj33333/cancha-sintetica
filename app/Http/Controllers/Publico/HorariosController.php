<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\HorarioAtencion;
use App\Models\Reserva;
use App\Models\Instalacion;
use App\Models\InformacionContacto;
use App\Models\BloqueoCancha;
use Carbon\Carbon;

class HorariosController extends Controller
{
    public function index()
    {
        $instalacion = Instalacion::activas()->first();
        $contacto = InformacionContacto::activa()->first();

        // Horarios de atención configurados por día de la semana (0=Domingo, 1=Lunes, ..., 6=Sábado)
        $horariosAtencion = HorarioAtencion::where('instalacion_id', $instalacion?->id)
            ->activos()
            ->get()
            ->keyBy('dia_semana');

        // Fecha base: Inicio de la semana actual (Lunes)
        $hoy = Carbon::today();
        $inicioSemanaActual = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $finPeriodo = $inicioSemanaActual->copy()->addWeeks(3)->endOfWeek(Carbon::SUNDAY);

        // Obtener reservas activas y confirmadas/pendientes en el rango de 3 semanas
        $reservas = Reserva::activas()
            ->whereIn('estado', ['confirmada', 'pendiente', 'completada'])
            ->whereBetween('fecha_reserva', [$inicioSemanaActual->toDateString(), $finPeriodo->toDateString()])
            ->get();

        // Obtener bloqueos de cancha activos en el rango de 3 semanas
        $bloqueos = BloqueoCancha::activos()
            ->whereBetween('fecha_bloqueo', [$inicioSemanaActual->toDateString(), $finPeriodo->toDateString()])
            ->get();

        $nombresDias = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            0 => 'Domingo',
        ];

        $nombresMeses = [
            1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr', 5 => 'May', 6 => 'Jun',
            7 => 'Jul', 8 => 'Ago', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic',
        ];

        $nombresMesesCompletos = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
            7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];

        $semanas = [];
        $fechaInicialSeleccionada = null;

        for ($s = 0; $s < 3; $s++) {
            $inicioSemana = $inicioSemanaActual->copy()->addWeeks($s);
            $dias = [];

            for ($d = 0; $d < 7; $d++) {
                $fechaActual = $inicioSemana->copy()->addDays($d);
                $fechaStr = $fechaActual->toDateString();
                $numDiaSemana = $fechaActual->dayOfWeek;
                $horario = $horariosAtencion->get($numDiaSemana);

                $abierto = $horario && $horario->esta_activo;
                $horaAperturaStr = $abierto ? Carbon::parse($horario->hora_apertura)->format('H:i') : null;
                $horaCierreStr = $abierto ? Carbon::parse($horario->hora_cierre)->format('H:i') : null;

                // Generar los bloques de 1 hora
                $slots = [];
                if ($abierto) {
                    $horaInicio = Carbon::parse($horario->hora_apertura);
                    $horaFin = Carbon::parse($horario->hora_cierre);

                    $reservasDia = $reservas->filter(function ($res) use ($fechaStr) {
                        return Carbon::parse($res->fecha_reserva)->toDateString() === $fechaStr;
                    });

                    $bloqueosDia = $bloqueos->filter(function ($bloq) use ($fechaStr) {
                        return Carbon::parse($bloq->fecha_bloqueo)->toDateString() === $fechaStr;
                    });

                    $currentSlot = $horaInicio->copy();
                    while ($currentSlot < $horaFin) {
                        $nextSlot = $currentSlot->copy()->addHour();
                        if ($nextSlot > $horaFin) {
                            break;
                        }

                        $slotInicioStr = $currentSlot->format('H:i');
                        $slotFinStr = $nextSlot->format('H:i');

                        // Comprobar si hay traslape con alguna reserva
                        $ocupadoPorReserva = $reservasDia->contains(function ($res) use ($slotInicioStr, $slotFinStr) {
                            $resIni = Carbon::parse($res->hora_inicio)->format('H:i');
                            $resFin = Carbon::parse($res->hora_fin)->format('H:i');
                            return $resIni < $slotFinStr && $resFin > $slotInicioStr;
                        });

                        // Comprobar si hay traslape con algún bloqueo
                        $ocupadoPorBloqueo = $bloqueosDia->contains(function ($bloq) use ($slotInicioStr, $slotFinStr) {
                            $bloqIni = Carbon::parse($bloq->hora_inicio)->format('H:i');
                            $bloqFin = Carbon::parse($bloq->hora_fin)->format('H:i');
                            return $bloqIni < $slotFinStr && $bloqFin > $slotInicioStr;
                        });

                        $disponible = !$ocupadoPorReserva && !$ocupadoPorBloqueo;

                        $slots[] = [
                            'hora_inicio' => $slotInicioStr,
                            'hora_fin' => $slotFinStr,
                            'texto_horario' => "{$slotInicioStr} - {$slotFinStr}",
                            'disponible' => $disponible,
                            'estado_texto' => $disponible ? 'Disponible' : 'No disponible',
                        ];

                        $currentSlot->addHour();
                    }
                }

                $esHoy = $fechaActual->isSameDay($hoy);
                if ($esHoy && !$fechaInicialSeleccionada) {
                    $fechaInicialSeleccionada = $fechaStr;
                }

                $dias[] = [
                    'fecha' => $fechaStr,
                    'dia_nombre' => $nombresDias[$numDiaSemana] ?? $fechaActual->translatedFormat('l'),
                    'dia_numero' => $fechaActual->format('d'),
                    'mes_nombre' => $nombresMeses[$fechaActual->month] ?? '',
                    'anio' => $fechaActual->format('Y'),
                    'fecha_formato' => $fechaActual->format('d/m/Y'),
                    'fecha_corta' => $fechaActual->format('d') . ' ' . ($nombresMeses[$fechaActual->month] ?? ''),
                    'fecha_completa' => ($nombresDias[$numDiaSemana] ?? '') . ', ' . $fechaActual->format('d') . ' de ' . ($nombresMesesCompletos[$fechaActual->month] ?? '') . ' de ' . $fechaActual->format('Y'),
                    'es_hoy' => $esHoy,
                    'abierto' => $abierto,
                    'hora_apertura' => $horaAperturaStr,
                    'hora_cierre' => $horaCierreStr,
                    'horario_texto' => $abierto ? "{$horaAperturaStr} - {$horaCierreStr}" : 'Cerrado',
                    'slots' => $slots,
                ];
            }

            $finSemana = $inicioSemana->copy()->addDays(6);
            $semanas[] = [
                'numero' => $s,
                'etiqueta' => $s === 0 ? 'Semana Actual' : ($s === 1 ? 'Próxima Semana' : 'En 2 Semanas'),
                'rango' => $inicioSemana->format('d/m') . ' - ' . $finSemana->format('d/m/Y'),
                'dias' => $dias,
            ];
        }

        // Si la fecha de hoy no está en el rango, seleccionar el primer día de la semana 0
        if (!$fechaInicialSeleccionada && !empty($semanas[0]['dias'])) {
            $fechaInicialSeleccionada = $semanas[0]['dias'][0]['fecha'];
        }

        return view('publico.horarios', compact(
            'instalacion',
            'contacto',
            'semanas',
            'fechaInicialSeleccionada'
        ));
    }
}
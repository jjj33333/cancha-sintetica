<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Torneo;
use App\Models\Equipo;
use App\Models\Instalacion;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EstadisticasController extends Controller
{
    /**
     * Muestra el dashboard de estadísticas.
     */
    public function index(Request $request)
    {
        // Determinar el mes/año a mostrar
        $mes = $request->get('mes', now()->month);
        $anio = $request->get('anio', now()->year);

        $fechaInicio = Carbon::create($anio, $mes, 1)->startOfMonth();
        $fechaFin = Carbon::create($anio, $mes, 1)->endOfMonth();

        // ============================================
        // 1. ESTADÍSTICAS GENERALES DEL MES
        // ============================================
        
        // Total de reservas del mes
        $totalReservas = Reserva::activas()
            ->whereBetween('fecha_reserva', [$fechaInicio, $fechaFin])
            ->count();

        // Total de horas reservadas
        $totalHoras = Reserva::activas()
            ->whereBetween('fecha_reserva', [$fechaInicio, $fechaFin])
            ->sum('total_horas');

        // Total de ingresos del mes
        $totalIngresos = Reserva::activas()
            ->whereBetween('fecha_reserva', [$fechaInicio, $fechaFin])
            ->where('estado_pago', 'pagado')
            ->sum('precio_total');

        // Tasa de ocupación (%)
        // Calculamos horas disponibles vs horas reservadas
        $instalacion = Instalacion::activas()->first();
        $horasDisponibles = 0;
        
        // Conteo de cuántas veces ocurre cada día de la semana en el mes (0=Domingo, 1=Lunes, ..., 6=Sábado)
        $diasPorSemana = array_fill_keys([0, 1, 2, 3, 4, 5, 6], 0);
        $diasEnMes = $fechaInicio->daysInMonth;
        for ($i = 0; $i < $diasEnMes; $i++) {
            $diaSemana = $fechaInicio->copy()->addDays($i)->dayOfWeek;
            $diasPorSemana[$diaSemana]++;
        }

        if ($instalacion) {
            foreach ($diasPorSemana as $diaSemana => $cantidad) {
                // Fin de semana: 08:00-22:00 (14 horas), Semana: 06:00-23:00 (17 horas)
                $horasDisponibles += in_array($diaSemana, [0, 6]) ? ($cantidad * 14) : ($cantidad * 17);
            }
        }
        
        $tasaOcupacion = $horasDisponibles > 0 
            ? round(($totalHoras / $horasDisponibles) * 100, 1) 
            : 0;

        // ============================================
        // 2. ESTADÍSTICAS POR DÍA DE LA SEMANA
        // ============================================
        $diasSemana = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            0 => 'Domingo',
        ];

        $estadisticasPorDia = [];
        foreach ($diasSemana as $numero => $nombre) {
            $reservasDia = Reserva::activas()
                ->whereBetween('fecha_reserva', [$fechaInicio, $fechaFin])
                ->whereRaw('DAYOFWEEK(fecha_reserva) - 1 = ?', [$numero])
                ->get();

            $totalReservasDia = $reservasDia->count();
            $totalHorasDia = $reservasDia->sum('total_horas');
            $totalIngresosDia = $reservasDia->where('estado_pago', 'pagado')->sum('precio_total');

            // Horas disponibles por día de la semana en el mes
            $diasDelTipo = $diasPorSemana[$numero] ?? 0;
            $horasDisponiblesDia = $instalacion 
                ? (in_array($numero, [0, 6]) ? $diasDelTipo * 14 : $diasDelTipo * 17) 
                : 0;
            $ocupacionDia = $horasDisponiblesDia > 0 
                ? round(($totalHorasDia / $horasDisponiblesDia) * 100, 1) 
                : 0;

            $estadisticasPorDia[] = [
                'numero' => $numero,
                'nombre' => $nombre,
                'reservas' => $totalReservasDia,
                'horas' => $totalHorasDia,
                'ingresos' => $totalIngresosDia,
                'ocupacion' => $ocupacionDia,
            ];
        }

        // ============================================
        // 3. ESTADÍSTICAS ADICIONALES
        // ============================================
        $torneosActivos = Torneo::activos()
            ->whereIn('estado', ['en_curso', 'inscripciones_abiertas'])
            ->count();

        $equiposRegistrados = Equipo::activos()->count();

        // Top 5 clientes del mes
        $topClientes = Reserva::activas()
            ->whereBetween('fecha_reserva', [$fechaInicio, $fechaFin])
            ->select('nombre_cliente', 'telefono_cliente')
            ->selectRaw('COUNT(*) as total_reservas')
            ->selectRaw('SUM(precio_total) as total_gastado')
            ->groupBy('nombre_cliente', 'telefono_cliente')
            ->orderByDesc('total_reservas')
            ->take(5)
            ->get();

        // Comparación con mes anterior
        $mesAnteriorInicio = $fechaInicio->copy()->subMonth()->startOfMonth();
        $mesAnteriorFin = $fechaInicio->copy()->subMonth()->endOfMonth();

        $reservasMesAnterior = Reserva::activas()
            ->whereBetween('fecha_reserva', [$mesAnteriorInicio, $mesAnteriorFin])
            ->count();

        $variacionReservas = $reservasMesAnterior > 0 
            ? round((($totalReservas - $reservasMesAnterior) / $reservasMesAnterior) * 100, 1)
            : 0;

        $ingresosMesAnterior = Reserva::activas()
            ->whereBetween('fecha_reserva', [$mesAnteriorInicio, $mesAnteriorFin])
            ->where('estado_pago', 'pagado')
            ->sum('precio_total');

        $variacionIngresos = $ingresosMesAnterior > 0 
            ? round((($totalIngresos - $ingresosMesAnterior) / $ingresosMesAnterior) * 100, 1)
            : 0;

        // Nombres de meses en español
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        return view('admin.estadisticas.index', compact(
            'totalReservas',
            'totalHoras',
            'totalIngresos',
            'tasaOcupacion',
            'estadisticasPorDia',
            'torneosActivos',
            'equiposRegistrados',
            'topClientes',
            'variacionReservas',
            'variacionIngresos',
            'mes',
            'anio',
            'meses'
        ));
    }
}
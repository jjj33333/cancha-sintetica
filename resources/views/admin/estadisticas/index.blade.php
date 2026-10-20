@extends('layouts.admin')

@section('header_title', 'Estadísticas y Reportes')
@section('title', 'Estadísticas - Admin')

@section('content')
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#065F46]">📈 Estadísticas y Reportes</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Análisis del rendimiento del negocio por mes.</p>
        </div>
        
        <!-- Selector de Mes/Año -->
        <form method="GET" action="{{ route('admin.estadisticas') }}" class="flex flex-wrap sm:flex-nowrap gap-2 w-full sm:w-auto">
            <select name="mes" onchange="this.form.submit()" class="flex-1 sm:flex-none px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm bg-white">
                @foreach($meses as $num => $nombre)
                    <option value="{{ $num }}" {{ $mes == $num ? 'selected' : '' }}>{{ $nombre }}</option>
                @endforeach
            </select>
            <select name="anio" onchange="this.form.submit()" class="flex-1 sm:flex-none px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm bg-white">
                @for($y = now()->year - 2; $y <= now()->year + 1; $y++)
                    <option value="{{ $y }}" {{ $anio == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </form>
    </div>

    <!-- Tarjetas de Resumen -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <!-- Reservas este mes -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 hover:shadow-md transition-all hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-[#ECFDF5] flex items-center justify-center text-xl sm:text-2xl">
                    📅
                </div>
                @if($variacionReservas != 0)
                    <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $variacionReservas > 0 ? 'bg-[#D1FAE5] text-[#065F46]' : 'bg-[#FEE2E2] text-[#991B1B]' }}">
                        {{ $variacionReservas > 0 ? '↑' : '↓' }} {{ abs($variacionReservas) }}%
                    </span>
                @endif
            </div>
            <p class="text-xs sm:text-sm text-gray-500 mb-1">Reservas este mes</p>
            <p class="text-2xl sm:text-3xl font-bold text-[#065F46]">{{ $totalReservas }}</p>
            <p class="text-xs text-gray-400 mt-2">vs mes anterior</p>
        </div>

        <!-- Horas vendidas -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 hover:shadow-md transition-all hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-[#DBEAFE] flex items-center justify-center text-xl sm:text-2xl">
                    ⏱️
                </div>
            </div>
            <p class="text-xs sm:text-sm text-gray-500 mb-1">Horas vendidas</p>
            <p class="text-2xl sm:text-3xl font-bold text-[#1E40AF]">{{ number_format($totalHoras, 1) }}h</p>
            <p class="text-xs text-gray-400 mt-2">Total acumulado</p>
        </div>

        <!-- Ingresos totales -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 hover:shadow-md transition-all hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-[#D1FAE5] flex items-center justify-center text-xl sm:text-2xl">
                    💰
                </div>
                @if($variacionIngresos != 0)
                    <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $variacionIngresos > 0 ? 'bg-[#D1FAE5] text-[#065F46]' : 'bg-[#FEE2E2] text-[#991B1B]' }}">
                        {{ $variacionIngresos > 0 ? '↑' : '↓' }} {{ abs($variacionIngresos) }}%
                    </span>
                @endif
            </div>
            <p class="text-xs sm:text-sm text-gray-500 mb-1">Ingresos totales</p>
            <p class="text-2xl sm:text-3xl font-bold text-[#065F46]">Q. {{ number_format($totalIngresos, 2) }}</p>
            <p class="text-xs text-gray-400 mt-2">Solo pagos confirmados</p>
        </div>

        <!-- Tasa de ocupación -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 hover:shadow-md transition-all hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-[#FEF3C7] flex items-center justify-center text-xl sm:text-2xl">
                    📊
                </div>
            </div>
            <p class="text-xs sm:text-sm text-gray-500 mb-1">Tasa de ocupación</p>
            <p class="text-2xl sm:text-3xl font-bold {{ $tasaOcupacion >= 75 ? 'text-[#065F46]' : ($tasaOcupacion >= 50 ? 'text-[#92400E]' : 'text-[#991B1B]') }}">
                {{ $tasaOcupacion }}%
            </p>
            <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                <div class="h-2 rounded-full {{ $tasaOcupacion >= 75 ? 'bg-[#10B981]' : ($tasaOcupacion >= 50 ? 'bg-[#F59E0B]' : 'bg-[#EF4444]') }}" 
                     style="width: {{ min($tasaOcupacion, 100) }}%"></div>
            </div>
        </div>
    </div>

    <!-- Grid de 2 columnas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Tabla: Reservas por día de la semana -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-100 bg-[#F9FAFB]">
                <h2 class="text-base sm:text-lg font-bold text-[#065F46]">📅 Reservas por Día de la Semana</h2>
                <p class="text-xs text-gray-500 mt-1">Distribución de reservas según el día</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#F9FAFB] border-b-2 border-gray-200">
                            <th class="px-4 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Día</th>
                            <th class="px-4 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center whitespace-nowrap">Reservas</th>
                            <th class="px-4 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right whitespace-nowrap">Ingresos</th>
                            <th class="px-4 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right whitespace-nowrap">% Ocupación</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-xs sm:text-sm">
                        @foreach($estadisticasPorDia as $dia)
                            <tr class="hover:bg-[#ECFDF5] transition-colors duration-150">
                                <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2 sm:gap-3">
                                        <span class="text-base sm:text-lg">
                                            @if(in_array($dia['numero'], [0, 6])) 🏖️ @else 💼 @endif
                                        </span>
                                        <span class="font-semibold text-gray-800">{{ $dia['nombre'] }}</span>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-3 sm:py-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-[#F3F4F6] text-xs sm:text-sm font-bold text-gray-700">
                                        {{ $dia['reservas'] }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-3 sm:py-4 text-right whitespace-nowrap">
                                    <span class="font-bold text-[#065F46]">
                                        Q. {{ number_format($dia['ingresos'], 2) }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-3 sm:py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <div class="w-16 sm:w-24 bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full {{ $dia['ocupacion'] >= 75 ? 'bg-[#10B981]' : ($dia['ocupacion'] >= 50 ? 'bg-[#F59E0B]' : 'bg-[#EF4444]') }}" 
                                                 style="width: {{ min($dia['ocupacion'], 100) }}%"></div>
                                        </div>
                                        <span class="font-semibold {{ $dia['ocupacion'] >= 75 ? 'text-[#065F46]' : ($dia['ocupacion'] >= 50 ? 'text-[#92400E]' : 'text-[#991B1B]') }}">
                                            {{ $dia['ocupacion'] }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-[#F9FAFB] border-t-2 border-gray-200 font-semibold text-xs sm:text-sm">
                            <td class="px-4 sm:px-6 py-3 sm:py-4 text-gray-700 whitespace-nowrap">TOTAL / PROMEDIO</td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 text-center text-gray-700 whitespace-nowrap">
                                {{ array_sum(array_column($estadisticasPorDia, 'reservas')) }}
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 text-right text-[#065F46] whitespace-nowrap">
                                Q. {{ number_format(array_sum(array_column($estadisticasPorDia, 'ingresos')), 2) }}
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 text-right text-gray-700 whitespace-nowrap">
                                {{ round(array_sum(array_column($estadisticasPorDia, 'ocupacion')) / 7, 1) }}%
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Top Clientes -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-100 bg-[#F9FAFB]">
                <h2 class="text-base sm:text-lg font-bold text-[#065F46]">🏆 Top Clientes del Mes</h2>
                <p class="text-xs text-gray-500 mt-1">Los 5 clientes más frecuentes</p>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($topClientes as $index => $cliente)
                    <div class="p-3 sm:p-4 hover:bg-[#ECFDF5] transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-[#10B981] to-[#065F46] flex items-center justify-center text-white font-bold text-xs sm:text-sm shrink-0">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800 text-xs sm:text-sm truncate">{{ $cliente->nombre_cliente }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $cliente->telefono_cliente }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-xs sm:text-sm font-bold text-[#065F46]">{{ $cliente->total_reservas }} res.</p>
                                <p class="text-[11px] sm:text-xs text-gray-500">Q. {{ number_format($cliente->total_gastado, 2) }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400">
                        <p class="text-4xl mb-2">👥</p>
                        <p class="text-sm">No hay clientes este mes</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Resumen Adicional -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
        <!-- Torneos Activos -->
        <div class="bg-gradient-to-br from-[#065F46] to-[#10B981] rounded-xl shadow-sm p-4 sm:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-white/80 mb-1">Torneos Activos</p>
                    <p class="text-3xl sm:text-4xl font-bold">{{ $torneosActivos }}</p>
                    <p class="text-xs text-white/70 mt-2">En curso o con inscripciones abiertas</p>
                </div>
                <div class="text-5xl sm:text-6xl opacity-20">🏆</div>
            </div>
        </div>

        <!-- Equipos Registrados -->
        <div class="bg-gradient-to-br from-[#1E40AF] to-[#3B82F6] rounded-xl shadow-sm p-4 sm:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-white/80 mb-1">Equipos Registrados</p>
                    <p class="text-3xl sm:text-4xl font-bold">{{ $equiposRegistrados }}</p>
                    <p class="text-xs text-white/70 mt-2">Total en el sistema</p>
                </div>
                <div class="text-5xl sm:text-6xl opacity-20">👥</div>
            </div>
        </div>
    </div>

    <!-- Nota informativa -->
    <div class="mt-6 sm:mt-8 bg-[#ECFDF5] border border-[#10B981] rounded-xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-[#10B981] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div class="text-xs sm:text-sm text-[#065F46]">
            <p class="font-semibold mb-1">ℹ️ Información importante</p>
            <p class="text-gray-700">
                Las estadísticas se calculan en tiempo real a partir de las reservas confirmadas. 
                La tasa de ocupación se basa en horarios de 06:00-23:00 (días de semana) y 08:00-22:00 (fines de semana).
                Los ingresos solo incluyen reservas con estado de pago "pagado".
            </p>
        </div>
    </div>
@endsection
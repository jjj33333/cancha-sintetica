@extends('layouts.trabajador')

@section('header_title', 'Calendario General')
@section('title', 'Calendario - Trabajador')

@section('content')
    <!-- Encabezado -->
    <div class="mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-[#065F46]">🗓️ Calendario General</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">
            ℹ️ Vista completa del calendario con reservas y partidos de torneos.
        </p>
    </div>

    <!-- Tarjetas de Resumen Rápido -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-[#DBEAFE] flex items-center justify-center text-xl shrink-0">📅</div>
                <div>
                    <p class="text-xs text-gray-500">Reservas esta semana</p>
                    <p class="text-xl font-bold text-[#1E40AF]">{{ $totalReservasSemana }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-[#FEF3C7] flex items-center justify-center text-xl shrink-0">🏆</div>
                <div>
                    <p class="text-xs text-gray-500">Partidos programados</p>
                    <p class="text-xl font-bold text-[#92400E]">{{ $totalPartidosSemana }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-[#FEE2E2] flex items-center justify-center text-xl shrink-0">🚫</div>
                <div>
                    <p class="text-xs text-gray-500">Bloqueos</p>
                    <p class="text-xl font-bold text-[#991B1B]">{{ $totalBloqueosSemana }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Navegación de Semanas -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3.5 sm:p-4 mb-6">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">
            <!-- Controles de navegación -->
            <div class="flex items-center justify-between sm:justify-start gap-2 sm:gap-3">
                <a href="{{ route('trabajador.calendario.index', ['fecha' => $inicioSemana->copy()->subWeek()->toDateString(), 'instalacion' => $instalacionId]) }}"
                   class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-white border border-gray-200 rounded-lg text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Anterior
                </a>

                <a href="{{ route('trabajador.calendario.index', ['instalacion' => $instalacionId]) }}"
                   class="inline-flex items-center justify-center px-4 py-2 bg-[#10B981] text-white rounded-lg text-xs sm:text-sm font-medium hover:bg-[#059669] transition-colors shadow-sm">
                    Hoy
                </a>

                <a href="{{ route('trabajador.calendario.index', ['fecha' => $inicioSemana->copy()->addWeek()->toDateString(), 'instalacion' => $instalacionId]) }}"
                   class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-white border border-gray-200 rounded-lg text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Siguiente
                    <svg class="w-4 h-4 ml-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>

            <!-- Rango de fechas -->
            <div class="text-center order-first sm:order-none">
                <p class="text-xs sm:text-sm font-semibold text-gray-700">
                    {{ $inicioSemana->locale('es')->isoFormat('D [de] MMMM') }} - {{ $finSemana->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
                </p>
            </div>

            <!-- Filtro por instalación -->
            <div class="flex items-center">
                <form method="GET" action="{{ route('trabajador.calendario.index') }}" class="w-full sm:w-auto">
                    <input type="hidden" name="fecha" value="{{ $inicioSemana->toDateString() }}">
                    <select name="instalacion" onchange="this.form.submit()" class="w-full sm:w-auto px-3 py-2 border border-gray-300 rounded-lg text-xs sm:text-sm focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
                        <option value="">Todas las canchas</option>
                        @foreach($instalaciones as $inst)
                            <option value="{{ $inst->id }}" {{ $instalacionId == $inst->id ? 'selected' : '' }}>{{ $inst->nombre }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>

    <!-- Vista Semanal (7 días) -->
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-2 sm:gap-3 mb-6 sm:mb-8">
        @foreach($diasSemana as $dia)
            <div class="bg-white rounded-xl border-2 p-3 sm:p-4 text-center transition-all hover:shadow-md
                {{ $dia['es_hoy'] ? 'border-[#10B981] shadow-md bg-emerald-50/20' : 'border-gray-100' }}">

                <h3 class="font-bold text-xs sm:text-sm {{ $dia['es_hoy'] ? 'text-[#065F46]' : 'text-gray-700' }}">
                    {{ ucfirst($dia['nombre']) }}
                    @if($dia['es_hoy'])
                        <span class="block sm:inline sm:ml-1 text-[10px] bg-[#10B981] text-white px-1.5 py-0.5 rounded-full font-semibold">Hoy</span>
                    @endif
                </h3>

                <p class="text-xl sm:text-2xl font-black text-gray-800 my-1 sm:my-2">{{ $dia['numero'] }}</p>

                @if($dia['total_eventos'] > 0)
                    <p class="text-[11px] sm:text-xs text-gray-500 mb-2">{{ $dia['total_eventos'] }} evento{{ $dia['total_eventos'] > 1 ? 's' : '' }}</p>
                @else
                    <p class="text-[11px] sm:text-xs text-gray-400 mb-2">Sin eventos</p>
                @endif

                <span class="inline-block text-[10px] sm:text-xs font-semibold px-2 sm:px-2.5 py-0.5 rounded-full {{ $dia['estado_color'] }}">
                    {{ $dia['estado_texto'] }}
                </span>
            </div>
        @endforeach
    </div>

    <!-- Eventos Programados (Tarjetas en Móvil / Tabla en Desktop) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-3.5 sm:px-6 sm:py-4 border-b border-gray-100 bg-[#F9FAFB] flex flex-col sm:flex-row sm:items-center justify-between gap-1">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-[#065F46]">📋 Eventos Programados</h2>
                <p class="text-xs text-gray-500">Mostrando {{ $eventos->count() }} evento{{ $eventos->count() !== 1 ? 's' : '' }} en esta semana</p>
            </div>
        </div>

        <!-- Vista Móvil: Tarjetas -->
        <div class="block md:hidden divide-y divide-gray-100">
            @forelse($eventos as $evento)
                <div class="p-3.5 space-y-2">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <span class="text-xs font-bold text-gray-800 block">{{ $evento['fecha']->format('d/m/Y') }}</span>
                            <span class="text-xs font-semibold text-emerald-700">⏰ {{ $evento['hora_inicio'] }} - {{ $evento['hora_fin'] }}</span>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold shrink-0 {{ $evento['badge_color'] }}">
                            {{ $evento['badge_icon'] }} {{ ucfirst($evento['tipo']) }}
                        </span>
                    </div>
                    <div class="bg-gray-50 p-2.5 rounded-lg text-xs space-y-1">
                        <p class="text-gray-800 font-medium">{{ $evento['detalle'] }}</p>
                        @if($evento['telefono'])
                            <p class="text-gray-500">📞 {{ $evento['telefono'] }}</p>
                        @endif
                        <p class="text-gray-400 text-[11px]">🏟️ {{ $evento['instalacion'] }}</p>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <p class="text-xs sm:text-sm text-gray-500 font-medium">No hay eventos programados para esta semana</p>
                    <p class="text-[11px] sm:text-xs text-gray-400 mt-1">La cancha está completamente disponible</p>
                </div>
            @endforelse
        </div>

        <!-- Vista Desktop: Tabla -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b-2 border-gray-200">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Hora</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Detalle</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Cancha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($eventos as $evento)
                        <tr class="hover:bg-[#ECFDF5] transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $evento['fecha']->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $evento['hora_inicio'] }} - {{ $evento['hora_fin'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $evento['badge_color'] }}">
                                    {{ $evento['badge_icon'] }} {{ ucfirst($evento['tipo']) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $evento['detalle'] }}
                                @if($evento['telefono'])
                                    <span class="text-xs text-gray-500 ml-2">({{ $evento['telefono'] }})</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $evento['instalacion'] }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-gray-400 mb-3">
                                    <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <p class="text-gray-500 font-medium">No hay eventos programados para esta semana</p>
                                <p class="text-sm text-gray-400 mt-1">La cancha está completamente disponible</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Nota informativa -->
    <div class="mt-6 bg-[#ECFDF5] border border-[#10B981] rounded-xl p-3.5 sm:p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-[#10B981] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div class="text-xs sm:text-sm text-[#065F46]">
            <p class="font-semibold mb-1">ℹ️ Información</p>
            <p class="text-gray-700">
                Este calendario muestra todas las ocupaciones de la cancha: reservas manuales, partidos de torneos y bloqueos programados. 
                Como trabajador, puedes consultar la disponibilidad pero no modificar eventos desde aquí.
            </p>
        </div>
    </div>
@endsection
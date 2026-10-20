@extends('layouts.admin')

@section('header_title', 'Calendario General')
@section('title', 'Calendario - Admin')

@section('content')
<div x-data="{ 
    diaFiltro: '',
    seleccionarDia(fecha) {
        this.diaFiltro = this.diaFiltro === fecha ? '' : fecha;
    },
    limpiarDia() {
        this.diaFiltro = '';
    }
}" class="space-y-6">

    <!-- Encabezado de la página -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#065F46] flex items-center gap-2">
                <span>🗓️</span> Calendario General
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Visualización unificada de reservas, partidos de torneos y bloqueos técnicos de cancha.
            </p>
        </div>

        <!-- Resumen rápido en badges -->
        <div class="flex flex-wrap items-center gap-2 text-xs">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-200 font-medium">
                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                {{ $eventos->where('tipo', 'reserva')->count() }} Reservas
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-50 text-amber-700 border border-amber-200 font-medium">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                {{ $eventos->where('tipo', 'torneo')->count() }} Torneos
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 text-red-700 border border-red-200 font-medium">
                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                {{ $eventos->where('tipo', 'bloqueo')->count() }} Bloqueos
            </span>
        </div>
    </div>

    <!-- Barra de Control: Navegación de Semanas y Filtros -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-4">
            
            <!-- 1. Botones de Navegación Anterior / Hoy / Siguiente -->
            <div class="flex items-center justify-between sm:justify-center gap-2 w-full sm:w-auto">
                <a href="{{ route('admin.calendario', ['fecha' => $inicioSemana->copy()->subWeek()->toDateString(), 'tipo' => $tipoEvento, 'instalacion' => $instalacionId]) }}"
                   class="flex-1 sm:flex-initial inline-flex items-center justify-center px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm"
                   title="Semana anterior">
                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Anterior
                </a>

                <a href="{{ route('admin.calendario', ['tipo' => $tipoEvento, 'instalacion' => $instalacionId]) }}"
                   class="flex-1 sm:flex-initial inline-flex items-center justify-center px-4 py-2 {{ $esSemanaActual ? 'bg-[#065F46] text-white ring-2 ring-[#065F46]/20' : 'bg-[#10B981] hover:bg-[#059669] text-white' }} rounded-lg text-sm font-semibold transition-colors shadow-sm"
                   title="Ir a la semana de hoy">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Hoy
                </a>

                <a href="{{ route('admin.calendario', ['fecha' => $inicioSemana->copy()->addWeek()->toDateString(), 'tipo' => $tipoEvento, 'instalacion' => $instalacionId]) }}"
                   class="flex-1 sm:flex-initial inline-flex items-center justify-center px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm"
                   title="Semana siguiente">
                    Siguiente
                    <svg class="w-4 h-4 ml-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <!-- 2. Rango de Fechas de la Semana -->
            <div class="text-center w-full lg:w-auto">
                <p class="text-sm sm:text-base font-bold text-gray-800 flex items-center justify-center gap-2">
                    <span>📅</span>
                    <span>{{ ucfirst($inicioSemana->locale('es')->isoFormat('D [de] MMMM')) }} - {{ ucfirst($finSemana->locale('es')->isoFormat('D [de] MMMM [de] YYYY')) }}</span>
                </p>
                @if($esSemanaActual)
                    <span class="inline-block mt-0.5 text-xs font-semibold text-[#065F46] bg-[#D1FAE5] px-2.5 py-0.5 rounded-full">
                        Semana actual
                    </span>
                @endif
            </div>

            <!-- 3. Filtros por Tipo de Evento y por Instalación -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 w-full lg:w-auto justify-center lg:justify-end">
                <!-- Filtro por Tipo -->
                <div class="relative w-full sm:w-auto">
                    <select id="filtroTipo" onchange="actualizarFiltros()" 
                            class="w-full sm:w-auto appearance-none bg-white pl-3.5 pr-8 py-2 border border-gray-300 rounded-lg text-base sm:text-sm text-gray-700 font-medium focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent cursor-pointer shadow-sm">
                        <option value="todos" {{ $tipoEvento === 'todos' ? 'selected' : '' }}>Todos los eventos</option>
                        <option value="reserva" {{ $tipoEvento === 'reserva' ? 'selected' : '' }}>🔷 Reservas</option>
                        <option value="torneo" {{ $tipoEvento === 'torneo' ? 'selected' : '' }}>🏆 Torneos</option>
                        <option value="bloqueo" {{ $tipoEvento === 'bloqueo' ? 'selected' : '' }}>🚫 Bloqueos</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <!-- Filtro por Instalación -->
                <div class="relative w-full sm:w-auto">
                    <select id="filtroInstalacion" onchange="actualizarFiltros()" 
                            class="w-full sm:w-auto appearance-none bg-white pl-3.5 pr-8 py-2 border border-gray-300 rounded-lg text-base sm:text-sm text-gray-700 font-medium focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent cursor-pointer shadow-sm">
                        <option value="">Todas las canchas</option>
                        @foreach($instalaciones as $inst)
                            <option value="{{ $inst->id }}" {{ $instalacionId == $inst->id ? 'selected' : '' }}>
                                🏟️ {{ $inst->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <!-- Botón de Limpiar Filtros si hay alguno activo -->
                @if($tipoEvento !== 'todos' || !empty($instalacionId))
                    <a href="{{ route('admin.calendario', ['fecha' => $inicioSemana->toDateString()]) }}" 
                       class="inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition-colors w-full sm:w-auto"
                       title="Restablecer filtros">
                        <svg class="w-3.5 h-3.5 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Limpiar
                    </a>
                @endif
            </div>

        </div>
    </div>

    <!-- 7 Tarjetas de Días de la Semana -->
    <div>
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                Días de la semana
            </h2>
            <span class="text-xs text-gray-400">
                Haz clic en un día para filtrar la lista
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
            @foreach($diasSemana as $dia)
                <button type="button"
                        @click="seleccionarDia('{{ $dia['fecha_str'] }}')"
                        :class="diaFiltro === '{{ $dia['fecha_str'] }}' 
                            ? 'ring-2 ring-[#065F46] border-[#065F46] bg-emerald-50/60 shadow-md' 
                            : '{{ $dia['es_hoy'] ? 'border-2 border-[#10B981] bg-emerald-50/20 shadow-sm' : 'border border-gray-200 bg-white hover:border-gray-300 hover:shadow-sm' }}'"
                        class="rounded-xl p-3.5 text-center transition-all duration-150 relative cursor-pointer flex flex-col justify-between items-center group w-full">
                    
                    <!-- Indicador visual si es Hoy -->
                    @if($dia['es_hoy'])
                        <div class="absolute -top-2.5 right-2">
                            <span class="bg-[#10B981] text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full shadow-sm">
                                HOY
                            </span>
                        </div>
                    @endif

                    <!-- Nombre del día -->
                    <span class="text-xs font-bold uppercase tracking-wider {{ $dia['es_hoy'] ? 'text-[#065F46]' : 'text-gray-600' }}">
                        {{ $dia['nombre'] }}
                    </span>

                    <!-- Número del día -->
                    <span class="text-2xl font-extrabold my-1.5 {{ $dia['es_hoy'] ? 'text-[#065F46]' : 'text-gray-900' }}">
                        {{ $dia['numero'] }}
                    </span>

                    <!-- Cantidad de eventos -->
                    <span class="text-xs mb-2 {{ $dia['total_eventos'] > 0 ? 'font-medium text-gray-600' : 'text-gray-400' }}">
                        @if($dia['total_eventos'] === 0)
                            Sin eventos
                        @elseif($dia['total_eventos'] === 1)
                            1 evento
                        @else
                            {{ $dia['total_eventos'] }} eventos
                        @endif
                    </span>

                    <!-- Badge de Estado (Disponible/Parcial/Lleno/Torneo) -->
                    <span class="inline-block text-[11px] font-bold px-2.5 py-1 rounded-full {{ $dia['estado_color'] }}">
                        @if($dia['estado'] === 'torneo')
                            🏆 Torneo
                        @else
                            {{ $dia['estado_texto'] }}
                        @endif
                    </span>
                </button>
            @endforeach
        </div>
    </div>

    <!-- Barra de Filtro de Día Activo (si Alpine tiene día seleccionado) -->
    <div x-show="diaFiltro" x-cloak class="bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-2.5 flex items-center justify-between transition-all">
        <div class="flex items-center gap-2 text-sm text-emerald-900">
            <span class="text-base">🎯</span>
            <span>Mostrando solo eventos del día:</span>
            <span class="font-bold underline" x-text="diaFiltro"></span>
        </div>
        <button type="button" @click="limpiarDia()" class="text-xs font-bold text-emerald-700 hover:text-emerald-900 hover:underline flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            Ver toda la semana
        </button>
    </div>

    <!-- Tabla de Eventos Programados Unificada -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-[#F9FAFB] flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h2 class="text-lg font-bold text-[#065F46] flex items-center gap-2">
                    <span>📋</span> Eventos Programados de la Semana
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">
                    Mostrando {{ $eventos->count() }} evento{{ $eventos->count() !== 1 ? 's' : '' }} en la semana seleccionada
                </p>
            </div>
            
            <div class="flex items-center gap-3 text-xs text-gray-500">
                <span class="flex items-center gap-1">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500 inline-block"></span> 🔷 Reserva
                </span>
                <span class="flex items-center gap-1">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span> 🏆 Torneo
                </span>
                <span class="flex items-center gap-1">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500 inline-block"></span> 🚫 Bloqueo
                </span>
            </div>
        </div>

        <!-- Vista Móvil: Tarjetas de Eventos -->
        <div class="block md:hidden divide-y divide-gray-100">
            @forelse($eventos as $evento)
                <div x-show="!diaFiltro || diaFiltro === '{{ $evento['fecha_str'] }}'"
                     class="p-4 space-y-3 hover:bg-emerald-50/20 transition-colors">
                    
                    <div class="flex items-center justify-between gap-2">
                        <!-- Fecha y Hora -->
                        <div>
                            <span class="text-sm font-bold text-gray-900">{{ $evento['fecha']->format('d/m/Y') }}</span>
                            <span class="text-xs text-gray-500 ml-1.5 font-medium">🕒 {{ $evento['hora_inicio'] }} - {{ $evento['hora_fin'] }}</span>
                        </div>

                        <!-- Tipo de Evento Badge -->
                        @if($evento['tipo'] === 'reserva')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                🔷 Reserva
                            </span>
                        @elseif($evento['tipo'] === 'torneo')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                🏆 Torneo
                            </span>
                        @elseif($evento['tipo'] === 'bloqueo')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                🚫 Bloqueo
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ ucfirst($evento['tipo']) }}
                            </span>
                        @endif
                    </div>

                    <!-- Detalle -->
                    <p class="text-sm font-semibold text-gray-800">
                        {{ $evento['detalle'] }}
                    </p>

                    <!-- Cancha y Origen -->
                    <div class="flex items-center justify-between text-xs text-gray-600 pt-1 border-t border-gray-50">
                        <span class="flex items-center gap-1 font-medium">
                            <span>🏟️</span> {{ $evento['instalacion'] }}
                        </span>
                        
                        @if($evento['origen'] === 'Admin')
                            <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                Admin
                            </span>
                        @elseif(str_contains($evento['origen'], 'Carlos') || str_contains($evento['origen'], 'Org'))
                            <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-indigo-100 text-indigo-800 border border-indigo-200">
                                {{ $evento['origen'] }}
                            </span>
                        @elseif($evento['origen'] === 'Sistema')
                            <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                Sistema
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-800 border border-slate-200">
                                {{ $evento['origen'] }}
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-4 py-10 text-center">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2 text-xl text-gray-400">
                        📅
                    </div>
                    <p class="text-gray-700 font-semibold text-sm">No hay eventos programados para esta semana</p>
                    <p class="text-xs text-gray-400 mt-1">Las canchas se encuentran libres y disponibles.</p>
                </div>
            @endforelse
        </div>

        <!-- Vista Escritorio: Tabla con Scroll Horizontal -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b border-gray-200">
                        <th class="px-6 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Fecha y Hora</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Detalle del Evento</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Cancha Asignada</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Origen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($eventos as $evento)
                        <tr x-show="!diaFiltro || diaFiltro === '{{ $evento['fecha_str'] }}'"
                            class="hover:bg-emerald-50/30 transition-colors duration-150">
                            
                            <!-- 1. Fecha y hora -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">
                                    {{ $evento['fecha']->format('d/m/Y') }}
                                </div>
                                <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ $evento['hora_inicio'] }} - {{ $evento['hora_fin'] }}</span>
                                </div>
                            </td>

                            <!-- 2. Tipo con badge de color (🔷 Reserva en azul, 🏆 Torneo en ámbar, 🚫 Bloqueo en rojo) -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($evento['tipo'] === 'reserva')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                        <span class="mr-1">🔷</span> Reserva
                                    </span>
                                @elseif($evento['tipo'] === 'torneo')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        <span class="mr-1">🏆</span> Torneo
                                    </span>
                                @elseif($evento['tipo'] === 'bloqueo')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                        <span class="mr-1">🚫</span> Bloqueo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($evento['tipo']) }}
                                    </span>
                                @endif
                            </td>

                            <!-- 3. Detalle del evento -->
                            <td class="px-6 py-4 text-sm text-gray-800">
                                <div class="font-medium">
                                    {{ $evento['detalle'] }}
                                </div>
                            </td>

                            <!-- 4. Cancha asignada -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-gray-400">🏟️</span>
                                    <span class="font-medium">{{ $evento['instalacion'] }}</span>
                                </div>
                            </td>

                            <!-- 5. Origen (Admin/Carlos Org./Sistema) -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($evento['origen'] === 'Admin')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                        Admin
                                    </span>
                                @elseif(str_contains($evento['origen'], 'Carlos') || str_contains($evento['origen'], 'Org'))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800 border border-indigo-200">
                                        {{ $evento['origen'] }}
                                    </span>
                                @elseif($evento['origen'] === 'Sistema')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                        Sistema
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">
                                        {{ $evento['origen'] }}
                                    </span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-14 text-center">
                                <div class="max-w-sm mx-auto">
                                    <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl text-gray-400">
                                        📅
                                    </div>
                                    <p class="text-gray-700 font-semibold text-base">No hay eventos programados para esta semana</p>
                                    <p class="text-sm text-gray-400 mt-1">
                                        Las canchas se encuentran libres y disponibles en este rango de fechas.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Script para actualizar filtros -->
<script>
    function actualizarFiltros() {
        const tipo = document.getElementById('filtroTipo').value;
        const instalacion = document.getElementById('filtroInstalacion').value;
        const fechaActual = '{{ $inicioSemana->toDateString() }}';

        let url = `{{ route('admin.calendario') }}?tipo=${encodeURIComponent(tipo)}&instalacion=${encodeURIComponent(instalacion)}&fecha=${encodeURIComponent(fechaActual)}`;
        window.location.href = url;
    }
</script>
@endsection
@extends('layouts.organizador')

@section('header_title', 'Mis Torneos')
@section('title', 'Mis Torneos - Organizador')

@section('content')
    <!-- Encabezado -->
    <div class="mb-4 sm:mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-[#065F46]">🏆 Mis Torneos</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">Gestiona los torneos que el administrador te ha asignado.</p>
    </div>

    <!-- Mensajes -->
    @if(session('success'))
        <div class="mb-6 bg-[#D1FAE5] border border-[#10B981] text-[#065F46] p-3.5 sm:p-4 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-xs sm:text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-[#FEE2E2] border border-[#EF4444] text-[#991B1B] p-3.5 sm:p-4 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-xs sm:text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3.5 sm:p-4 mb-6">
        <form method="GET" action="{{ route('organizador.torneos.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre del torneo..." 
                       class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none">
            </div>
            <div class="sm:w-48">
                <select name="estado" class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none">
                    <option value="">Todos los estados</option>
                    <option value="borrador" {{ request('estado') === 'borrador' ? 'selected' : '' }}>Borrador</option>
                    <option value="inscripciones_abiertas" {{ request('estado') === 'inscripciones_abiertas' ? 'selected' : '' }}>Inscripciones Abiertas</option>
                    <option value="en_curso" {{ request('estado') === 'en_curso' ? 'selected' : '' }}>En Curso</option>
                    <option value="finalizado" {{ request('estado') === 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 sm:flex-none px-6 py-2 bg-[#065F46] hover:bg-[#047857] text-white text-sm font-semibold rounded-lg transition-colors text-center">
                    Filtrar
                </button>
                @if(request('buscar') || request('estado'))
                    <a href="{{ route('organizador.torneos.index') }}" class="flex-1 sm:flex-none px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors text-center">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Lista de Torneos -->
    @forelse($torneos as $torneo)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6 hover:shadow-md transition-all">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <!-- Información del Torneo -->
                <div class="flex-1">
                    <div class="flex items-start gap-3 sm:gap-4">
                        <div class="text-3xl sm:text-4xl shrink-0">
                            @if($torneo->categoria === 'libre') 🏆
                            @elseif($torneo->categoria === 'sub20') 🥇
                            @elseif($torneo->categoria === 'femenino') ⭐
                            @else 🎯
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-1 break-words">{{ $torneo->nombre }}</h3>
                            <p class="text-xs sm:text-sm text-gray-500 mb-3">
                                {{ $torneo->descripcion ? Str::limit($torneo->descripcion, 100) : 'Sin descripción' }}
                            </p>

                            <!-- Badges de información -->
                            <div class="flex flex-wrap gap-1.5 sm:gap-2 mb-3">
                                @php
                                    $estadoBadge = match($torneo->estado) {
                                        'en_curso' => 'bg-[#D1FAE5] text-[#065F46]',
                                        'inscripciones_abiertas' => 'bg-[#FEF3C7] text-[#92400E]',
                                        'borrador' => 'bg-[#F3F4F6] text-[#374151]',
                                        'finalizado' => 'bg-[#DBEAFE] text-[#1E40AF]',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                    $estadoTexto = match($torneo->estado) {
                                        'en_curso' => 'En curso',
                                        'inscripciones_abiertas' => 'Inscripciones abiertas',
                                        'borrador' => 'Borrador',
                                        'finalizado' => 'Finalizado',
                                        default => ucfirst($torneo->estado),
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $estadoBadge }}">
                                    {{ $estadoTexto }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#F3F4F6] text-[#374151]">
                                    Categoría: {{ ucfirst(str_replace('_', ' ', $torneo->categoria)) }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#DBEAFE] text-[#1E40AF]">
                                    Equipos: {{ $torneo->equipos_count }}/{{ $torneo->max_equipos }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#FEF3C7] text-[#92400E]">
                                    Partidos: {{ $torneo->partidos_jugados }}/{{ $torneo->partidos_total }}
                                </span>
                            </div>

                            <!-- Barra de progreso de partidos -->
                            @if($torneo->partidos_total > 0)
                                <div class="mb-3 max-w-md">
                                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                                        <span>Progreso del torneo</span>
                                        <span class="font-semibold">{{ $torneo->partidos_total > 0 ? round(($torneo->partidos_jugados / $torneo->partidos_total) * 100, 1) : 0 }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                        <div class="bg-[#F59E0B] h-2 rounded-full transition-all duration-500" 
                                             style="width: {{ $torneo->partidos_total > 0 ? ($torneo->partidos_jugados / $torneo->partidos_total) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            @endif

                            <!-- Fechas -->
                            <div class="flex flex-wrap gap-2 sm:gap-4 text-xs text-gray-500">
                                <span>📅 Inicio: {{ $torneo->fecha_inicio->format('d/m/Y') }}</span>
                                <span>🏁 Fin: {{ $torneo->fecha_fin->format('d/m/Y') }}</span>
                                @if($torneo->instalacion)
                                    <span>📍 {{ $torneo->instalacion->nombre }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botón Ver Detalles -->
                <div class="flex-shrink-0 pt-3 lg:pt-0 border-t lg:border-t-0 border-gray-100">
                    <a href="{{ route('organizador.torneos.show', $torneo) }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 bg-[#F59E0B] hover:bg-[#D97706] text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all text-xs sm:text-sm">
                        <svg class="w-4 h-4 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Ver Detalles
                    </a>
                </div>
            </div>
        </div>
    @empty
        <!-- Estado Vacío -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 sm:p-12 text-center">
            <div class="text-gray-400 mb-4">
                <svg class="w-16 h-16 sm:w-20 sm:h-20 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-2">No tienes torneos asignados</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-6 max-w-md mx-auto">
                El administrador aún no te ha asignado ningún torneo. 
                Contacta al administrador para que te asigne torneos a gestionar.
            </p>
            <div class="inline-flex items-center px-4 py-2 bg-[#F3F4F6] text-gray-600 rounded-lg text-xs sm:text-sm">
                <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Esperando asignación del administrador
            </div>
        </div>
    @endforelse

    <!-- Nota informativa -->
    @if($torneos->count() > 0)
        <div class="mt-6 bg-[#FEF3C7] border border-[#F59E0B] rounded-xl p-3.5 sm:p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-[#F59E0B] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-xs sm:text-sm text-[#92400E]">
                <p class="font-semibold mb-1">ℹ️ Información importante</p>
                <p class="text-[#78350F]">
                    Como organizador, puedes gestionar los equipos, jugadores, partidos y posiciones de tus torneos asignados. 
                    No puedes modificar la configuración general del torneo (fechas, costos, reglas). 
                    Si necesitas cambios, contacta al administrador.
                </p>
            </div>
        </div>
    @endif
@endsection
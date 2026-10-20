@extends('layouts.organizador')

@section('header_title', 'Mis Torneos Asignados')
@section('title', 'Dashboard - Organizador')

@section('content')
    <!-- Encabezado -->
    <div class="mb-4 sm:mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-[#065F46]">📊 Mis Torneos Asignados</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">Gestiona los torneos que el administrador te ha asignado.</p>
    </div>

    <!-- Alerta Informativa -->
    <div class="mb-6 bg-[#FEF3C7] border border-[#F59E0B] rounded-xl p-3.5 sm:p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-[#F59E0B] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
        <div class="text-xs sm:text-sm text-[#92400E]">
            <p class="font-semibold mb-1">⚠️ Información importante</p>
            <p class="text-[#78350F]">
                Como organizador, solo puedes gestionar los torneos que el administrador te ha asignado. 
                No puedes crear nuevos torneos ni modificar la configuración general del torneo.
            </p>
        </div>
    </div>

    <!-- Tarjetas de Resumen Rápido -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6 sm:mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-2 sm:mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-[#FEF3C7] flex items-center justify-center text-xl sm:text-2xl shrink-0">🏆</div>
            </div>
            <p class="text-xs sm:text-sm text-gray-500 mb-1">Torneos Asignados</p>
            <p class="text-2xl sm:text-3xl font-bold text-[#065F46]">{{ $totalTorneos }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-2 sm:mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-[#D1FAE5] flex items-center justify-center text-xl sm:text-2xl shrink-0">▶️</div>
            </div>
            <p class="text-xs sm:text-sm text-gray-500 mb-1">En Curso</p>
            <p class="text-2xl sm:text-3xl font-bold text-[#065F46]">{{ $torneosEnCurso }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-2 sm:mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-[#DBEAFE] flex items-center justify-center text-xl sm:text-2xl shrink-0">👥</div>
            </div>
            <p class="text-xs sm:text-sm text-gray-500 mb-1">Equipos Registrados</p>
            <p class="text-2xl sm:text-3xl font-bold text-[#1E40AF]">{{ $totalEquipos }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-2 sm:mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-[#FEE2E2] flex items-center justify-center text-xl sm:text-2xl shrink-0">⚽</div>
            </div>
            <p class="text-xs sm:text-sm text-gray-500 mb-1">Partidos Jugados</p>
            <p class="text-2xl sm:text-3xl font-bold text-[#991B1B]">{{ $totalPartidosJugados }}</p>
        </div>
    </div>

    <!-- Lista de Torneos Asignados -->
    @forelse($torneosAsignados as $torneo)
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
                                Asignado por: <span class="font-medium text-gray-700">{{ $torneo->creadoPorUsuario->nombre ?? 'Administrador' }}</span>
                            </p>

                            <!-- Badges de información -->
                            <div class="flex flex-wrap gap-1.5 sm:gap-2 mb-3">
                                <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#F3F4F6] text-[#374151]">
                                    Categoría: {{ ucfirst(str_replace('_', ' ', $torneo->categoria)) }}
                                </span>
                                <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#DBEAFE] text-[#1E40AF]">
                                    Equipos: {{ $torneo->equipos_count }}
                                </span>
                                <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#FEF3C7] text-[#92400E]">
                                    Partidos: {{ $torneo->partidos_jugados }}/{{ $torneo->partidos_total }}
                                </span>
                                @php
                                    $estadoBadge = match($torneo->estado) {
                                        'en_curso' => 'bg-[#D1FAE5] text-[#065F46]',
                                        'inscripciones_abiertas' => 'bg-[#FEF3C7] text-[#92400E]',
                                        'borrador' => 'bg-[#F3F4F6] text-[#374151]',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                    $estadoTexto = match($torneo->estado) {
                                        'en_curso' => 'En curso',
                                        'inscripciones_abiertas' => 'Inscripciones abiertas',
                                        'borrador' => 'Borrador',
                                        default => ucfirst($torneo->estado),
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $estadoBadge }}">
                                    {{ $estadoTexto }}
                                </span>
                            </div>

                            <!-- Barra de progreso de partidos -->
                            @if($torneo->partidos_total > 0)
                                <div class="mb-3 max-w-md">
                                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                                        <span>Progreso del torneo</span>
                                        <span class="font-semibold">{{ $torneo->progreso_partidos }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                        <div class="bg-[#10B981] h-2 rounded-full transition-all duration-500" 
                                             style="width: {{ $torneo->progreso_partidos }}%"></div>
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

                <!-- Botones de Acción -->
                <div class="flex flex-col sm:flex-row lg:flex-col xl:flex-row items-stretch sm:items-center gap-2 pt-3 lg:pt-0 border-t lg:border-t-0 border-gray-100">
                    <a href="{{ route('organizador.torneos.show', $torneo) }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-[#F59E0B] hover:bg-[#D97706] text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all text-xs sm:text-sm">
                        <svg class="w-4 h-4 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Ver Detalles
                    </a>
                    <a href="{{ route('organizador.equipos.index', ['torneo_id' => $torneo->id]) }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-[#10B981] hover:bg-[#059669] text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all text-xs sm:text-sm">
                        <svg class="w-4 h-4 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Gestionar
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
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Esperando asignación del administrador
            </div>
        </div>
    @endforelse

    <!-- Nota informativa -->
    @if($torneosAsignados->count() > 0)
        <div class="mt-6 bg-[#ECFDF5] border border-[#10B981] rounded-xl p-3.5 sm:p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-[#10B981] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-xs sm:text-sm text-[#065F46]">
                <p class="font-semibold mb-1">ℹ️ ¿Qué puedes hacer aquí?</p>
                <ul class="text-gray-700 space-y-1 list-disc list-inside">
                    <li>Gestionar equipos y jugadores de tus torneos</li>
                    <li>Programar partidos y registrar resultados</li>
                    <li>Consultar la tabla de posiciones</li>
                    <li>Ver el calendario de partidos programados</li>
                </ul>
            </div>
        </div>
    @endif
@endsection
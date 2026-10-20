@extends('layouts.organizador')

@section('header_title', 'Tabla de Posiciones')
@section('title', 'Posiciones - Organizador')

@section('content')
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-5 sm:mb-6 gap-3 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#065F46] flex items-center gap-2">
                <span>🏆</span> Tabla de Posiciones
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Consulta y actualiza las posiciones de los equipos en tus torneos.</p>
        </div>
        @if($torneoSeleccionado)
            <form method="POST" action="{{ route('organizador.posiciones.actualizar') }}" class="w-full sm:w-auto">
                @csrf
                <input type="hidden" name="torneo_id" value="{{ $torneoSeleccionado->id }}">
                <button type="submit" 
                        onclick="return confirm('¿Recalcular las estadísticas de todos los equipos desde los partidos finalizados?');"
                        class="w-full sm:w-auto inline-flex items-center justify-center min-h-[44px] px-4 py-2.5 bg-[#F59E0B] hover:bg-[#D97706] text-white text-xs sm:text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Actualizar Tabla
                </button>
            </form>
        @endif
    </div>

    <!-- Mensajes -->
    @if(session('success'))
        <div class="mb-5 sm:mb-6 bg-[#D1FAE5] border border-[#10B981] text-[#065F46] px-4 py-3 rounded-lg flex items-center gap-3 text-xs sm:text-sm font-medium">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-5 sm:mb-6 bg-[#FEE2E2] border border-[#EF4444] text-[#991B1B] px-4 py-3 rounded-lg flex items-center gap-3 text-xs sm:text-sm font-medium">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($torneosAsignados->count() === 0)
        <!-- Estado Vacío: Sin torneos asignados -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 sm:p-12 text-center">
            <div class="text-gray-400 mb-4">
                <svg class="w-16 h-16 sm:w-20 sm:h-20 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-2">No tienes torneos asignados</h3>
            <p class="text-xs sm:text-sm text-gray-500">Contacta al administrador para que te asigne torneos.</p>
        </div>
    @else
        <!-- Selector de Torneo -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3.5 sm:p-4 mb-5 sm:mb-6">
            <form method="GET" action="{{ route('organizador.posiciones.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Seleccionar Torneo</label>
                    <select name="torneo_id" onchange="this.form.submit()" 
                            class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm">
                        @foreach($torneosAsignados as $torneo)
                            <option value="{{ $torneo->id }}" {{ $torneoSeleccionado && $torneoSeleccionado->id === $torneo->id ? 'selected' : '' }}>
                                {{ $torneo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        @if($torneoSeleccionado)
            <!-- Banner del Torneo -->
            <div class="bg-gradient-to-r from-[#F59E0B] to-[#D97706] rounded-xl shadow-sm p-4 sm:p-6 mb-5 sm:mb-6 text-white">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold flex items-center gap-2 flex-wrap">
                            @if($torneoSeleccionado->categoria === 'libre') 🏆
                            @elseif($torneoSeleccionado->categoria === 'sub20') 🥇
                            @elseif($torneoSeleccionado->categoria === 'femenino') ⭐
                            @else 🎯
                            @endif
                            <span>{{ $torneoSeleccionado->nombre }}</span>
                        </h2>
                        <p class="text-xs sm:text-sm text-white/90 mt-1">
                            {{ $equipos->count() }} equipo{{ $equipos->count() !== 1 ? 's' : '' }} en competencia
                        </p>
                    </div>
                    <div class="flex gap-2 sm:gap-3 text-xs sm:text-sm w-full sm:w-auto">
                        <div class="flex-1 sm:flex-initial bg-white/20 rounded-lg px-3 sm:px-4 py-2 text-center min-w-[90px]">
                            <p class="text-[10px] sm:text-xs text-white/80">Partidos jugados</p>
                            <p class="text-base sm:text-lg font-bold">{{ $partidosFinalizados }}</p>
                        </div>
                        <div class="flex-1 sm:flex-initial bg-white/20 rounded-lg px-3 sm:px-4 py-2 text-center min-w-[90px]">
                            <p class="text-[10px] sm:text-xs text-white/80">Estado</p>
                            <p class="text-base sm:text-lg font-bold capitalize">{{ str_replace('_', ' ', $torneoSeleccionado->estado) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($equipos->count() === 0)
                <!-- Estado Vacío: Sin equipos -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 sm:p-12 text-center">
                    <div class="text-gray-400 mb-4">
                        <svg class="w-16 h-16 sm:w-20 sm:h-20 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-2">No hay equipos registrados</h3>
                    <p class="text-xs sm:text-sm text-gray-500 mb-4">Agrega equipos al torneo para que aparezcan en la tabla de posiciones.</p>
                    <a href="{{ route('organizador.equipos.index', ['torneo_id' => $torneoSeleccionado->id]) }}" 
                       class="inline-flex items-center justify-center min-h-[44px] px-4 py-2 bg-[#F59E0B] hover:bg-[#D97706] text-white text-xs sm:text-sm font-semibold rounded-lg shadow-sm transition-colors">
                        👥 Ir a Equipos
                    </a>
                </div>
            @else
                <!-- Vista Móvil: Tarjetas de Posición (block md:hidden) -->
                <div class="block md:hidden space-y-3">
                    @foreach($equipos as $equipo)
                        @php
                            $dif = $equipo->goles_favor - $equipo->goles_contra;
                        @endphp
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 relative overflow-hidden
                            {{ $equipo->posicion === 1 ? 'border-l-4 border-l-[#F59E0B]' : '' }}
                            {{ $equipo->posicion === 2 ? 'border-l-4 border-l-gray-400' : '' }}
                            {{ $equipo->posicion === 3 ? 'border-l-4 border-l-amber-600' : '' }}">
                            
                            <div class="flex items-center justify-between gap-3 mb-3">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="flex-shrink-0">
                                        @if($equipo->posicion === 1)
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-[#FEF3C7] border border-[#F59E0B] text-sm">🥇</span>
                                        @elseif($equipo->posicion === 2)
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-[#F3F4F6] border border-gray-300 text-sm">🥈</span>
                                        @elseif($equipo->posicion === 3)
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-[#FED7AA] border border-[#F97316] text-sm">🥉</span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gray-100 text-gray-700 text-xs font-bold">{{ $equipo->posicion }}</span>
                                        @endif
                                    </div>
                                    @if($equipo->logo)
                                        <img src="{{ Storage::url($equipo->logo) }}" alt="{{ $equipo->nombre }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-[#FEF3C7] flex items-center justify-center text-[#92400E] font-bold text-xs flex-shrink-0">
                                            {{ substr($equipo->nombre, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-bold text-sm text-gray-900 truncate">{{ $equipo->nombre }}</p>
                                        @if($equipo->nombre_corto)
                                            <p class="text-[11px] text-gray-500 truncate">{{ $equipo->nombre_corto }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-shrink-0 text-center">
                                    <div class="inline-flex flex-col items-center justify-center w-12 h-12 rounded-lg bg-[#F59E0B] text-white">
                                        <span class="text-base font-extrabold leading-none">{{ $equipo->puntos }}</span>
                                        <span class="text-[9px] font-semibold tracking-wider uppercase">PTS</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Estadísticas Rápidas en Cuadrícula -->
                            <div class="grid grid-cols-4 gap-1.5 bg-[#F9FAFB] p-2.5 rounded-lg text-center text-xs">
                                <div>
                                    <p class="text-[10px] text-gray-500 font-medium">PJ</p>
                                    <p class="font-bold text-gray-800">{{ $equipo->partidos_jugados }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-500 font-medium">G / E / P</p>
                                    <p class="font-semibold text-gray-800">
                                        <span class="text-[#065F46] font-bold">{{ $equipo->partidos_ganados }}</span>/<span class="text-[#92400E]">{{ $equipo->partidos_empatados }}</span>/<span class="text-[#991B1B]">{{ $equipo->partidos_perdidos }}</span>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-500 font-medium">GF / GC</p>
                                    <p class="font-semibold text-gray-700">{{ $equipo->goles_favor }}:{{ $equipo->goles_contra }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-500 font-medium">DIF</p>
                                    <p class="font-bold {{ $dif > 0 ? 'text-[#10B981]' : ($dif < 0 ? 'text-[#EF4444]' : 'text-gray-500') }}">
                                        {{ $dif > 0 ? '+' : '' }}{{ $dif }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Vista de Escritorio: Tabla Completa (hidden md:block) -->
                <div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-[#F9FAFB] border-b-2 border-gray-200">
                                    <th class="px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">#</th>
                                    <th class="px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Equipo</th>
                                    <th class="px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">PJ</th>
                                    <th class="px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">PG</th>
                                    <th class="px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">PE</th>
                                    <th class="px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">PP</th>
                                    <th class="px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">GF</th>
                                    <th class="px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">GC</th>
                                    <th class="px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">DIF</th>
                                    <th class="px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">PTS</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($equipos as $equipo)
                                    <tr class="hover:bg-[#FEF3C7]/40 transition-colors duration-150 
                                        {{ $equipo->posicion === 1 ? 'bg-[#FEF3C7]/30' : '' }}
                                        {{ $equipo->posicion === 2 ? 'bg-[#F3F4F6]/40' : '' }}
                                        {{ $equipo->posicion === 3 ? 'bg-[#FED7AA]/20' : '' }}">
                                        
                                        <!-- Posición -->
                                        <td class="px-4 py-4 text-center">
                                            @if($equipo->posicion === 1)
                                                <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#FEF3C7] border-2 border-[#F59E0B] text-[#92400E] font-bold text-sm">
                                                    🥇
                                                </div>
                                            @elseif($equipo->posicion === 2)
                                                <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#F3F4F6] border-2 border-[#9CA3AF] text-[#374151] font-bold text-sm">
                                                    🥈
                                                </div>
                                            @elseif($equipo->posicion === 3)
                                                <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#FED7AA] border-2 border-[#F97316] text-[#9A3412] font-bold text-sm">
                                                    🥉
                                                </div>
                                            @else
                                                <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#F9FAFB] text-gray-600 font-bold text-sm">
                                                    {{ $equipo->posicion }}
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Equipo -->
                                        <td class="px-4 py-4">
                                            <div class="flex items-center gap-3">
                                                @if($equipo->logo)
                                                    <img src="{{ Storage::url($equipo->logo) }}" alt="{{ $equipo->nombre }}" 
                                                         class="w-10 h-10 rounded-full object-cover">
                                                @else
                                                    <div class="w-10 h-10 rounded-full bg-[#FEF3C7] flex items-center justify-center text-[#92400E] font-bold text-sm">
                                                        {{ substr($equipo->nombre, 0, 1) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <p class="font-semibold text-gray-800 flex items-center gap-2">
                                                        {{ $equipo->nombre }}
                                                        @if($equipo->es_capitan)
                                                            <span class="text-xs">⭐</span>
                                                        @endif
                                                    </p>
                                                    @if($equipo->nombre_corto)
                                                        <p class="text-xs text-gray-500">{{ $equipo->nombre_corto }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <!-- PJ -->
                                        <td class="px-4 py-4 text-center">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#F3F4F6] text-sm font-medium text-gray-700">
                                                {{ $equipo->partidos_jugados }}
                                            </span>
                                        </td>

                                        <!-- PG -->
                                        <td class="px-4 py-4 text-center">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#D1FAE5] text-sm font-semibold text-[#065F46]">
                                                {{ $equipo->partidos_ganados }}
                                            </span>
                                        </td>

                                        <!-- PE -->
                                        <td class="px-4 py-4 text-center">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#FEF3C7] text-sm font-semibold text-[#92400E]">
                                                {{ $equipo->partidos_empatados }}
                                            </span>
                                        </td>

                                        <!-- PP -->
                                        <td class="px-4 py-4 text-center">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#FEE2E2] text-sm font-semibold text-[#991B1B]">
                                                {{ $equipo->partidos_perdidos }}
                                            </span>
                                        </td>

                                        <!-- GF -->
                                        <td class="px-4 py-4 text-center text-sm font-medium text-gray-700">
                                            {{ $equipo->goles_favor }}
                                        </td>

                                        <!-- GC -->
                                        <td class="px-4 py-4 text-center text-sm font-medium text-gray-700">
                                            {{ $equipo->goles_contra }}
                                        </td>

                                        <!-- DIF -->
                                        <td class="px-4 py-4 text-center">
                                            @php
                                                $dif = $equipo->goles_favor - $equipo->goles_contra;
                                            @endphp
                                            <span class="text-sm font-bold {{ $dif > 0 ? 'text-[#10B981]' : ($dif < 0 ? 'text-[#EF4444]' : 'text-gray-500') }}">
                                                {{ $dif > 0 ? '+' : '' }}{{ $dif }}
                                            </span>
                                        </td>

                                        <!-- PTS -->
                                        <td class="px-4 py-4 text-center">
                                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-[#F59E0B] text-white font-bold text-lg shadow-sm">
                                                {{ $equipo->puntos }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Leyenda de Criterios de Desempate -->
                <div class="mt-5 sm:mt-6 bg-[#FEF3C7] border border-[#F59E0B] rounded-xl p-3.5 sm:p-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-[#F59E0B] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-xs sm:text-sm text-[#92400E]">
                        <p class="font-semibold mb-1.5 sm:mb-2">📋 Criterios de desempate</p>
                        <ol class="list-decimal list-inside space-y-1 text-[#78350F]">
                            <li><strong>Puntos (PTS):</strong> 3 por victoria, 1 por empate, 0 por derrota</li>
                            <li><strong>Diferencia de goles (DIF):</strong> Goles a favor menos goles en contra</li>
                            <li><strong>Goles a favor (GF):</strong> Total de goles anotados</li>
                        </ol>
                        <p class="mt-2 text-[11px] sm:text-xs text-[#92400E]">
                            💡 Si detectas inconsistencias, usa el botón <strong>"Actualizar Tabla"</strong> para recalcular todas las estadísticas desde los partidos finalizados.
                        </p>
                    </div>
                </div>

                <!-- Resumen de Podio -->
                @if($equipos->count() >= 3)
                    <div class="mt-5 sm:mt-6 grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                        <!-- 2do Lugar -->
                        @php $segundo = $equipos->firstWhere('posicion', 2); @endphp
                        @if($segundo)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 text-center order-2 sm:order-1">
                                <div class="text-3xl sm:text-4xl mb-2">🥈</div>
                                <p class="text-[11px] sm:text-xs text-gray-500 mb-1">2° Lugar</p>
                                <p class="font-bold text-gray-800 text-base sm:text-lg truncate">{{ $segundo->nombre }}</p>
                                <p class="text-xs sm:text-sm text-gray-600 mt-1 font-semibold">{{ $segundo->puntos }} puntos</p>
                            </div>
                        @endif

                        <!-- 1er Lugar -->
                        @php $primero = $equipos->firstWhere('posicion', 1); @endphp
                        @if($primero)
                            <div class="bg-gradient-to-br from-[#FEF3C7] to-[#FDE68A] rounded-xl shadow-md border-2 border-[#F59E0B] p-4 sm:p-6 text-center order-1 sm:order-2 transform sm:-translate-y-2">
                                <div class="text-4xl sm:text-5xl mb-2">🏆</div>
                                <p class="text-[11px] sm:text-xs text-[#92400E] mb-1 font-semibold">1° Lugar - Líder</p>
                                <p class="font-bold text-[#065F46] text-lg sm:text-xl truncate">{{ $primero->nombre }}</p>
                                <p class="text-xs sm:text-sm text-[#92400E] mt-1 font-bold">{{ $primero->puntos }} puntos</p>
                            </div>
                        @endif

                        <!-- 3er Lugar -->
                        @php $tercero = $equipos->firstWhere('posicion', 3); @endphp
                        @if($tercero)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 text-center order-3">
                                <div class="text-3xl sm:text-4xl mb-2">🥉</div>
                                <p class="text-[11px] sm:text-xs text-gray-500 mb-1">3° Lugar</p>
                                <p class="font-bold text-gray-800 text-base sm:text-lg truncate">{{ $tercero->nombre }}</p>
                                <p class="text-xs sm:text-sm text-gray-600 mt-1 font-semibold">{{ $tercero->puntos }} puntos</p>
                            </div>
                        @endif
                    </div>
                @endif
            @endif
        @endif
    @endif
@endsection
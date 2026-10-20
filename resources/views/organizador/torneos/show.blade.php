@extends('layouts.organizador')

@section('header_title', 'Detalles del Torneo')
@section('title', $torneo->nombre . ' - Organizador')

@section('content')
    <!-- Encabezado -->
    <div class="mb-4 sm:mb-6">
        <a href="{{ route('organizador.torneos.index') }}" class="inline-flex items-center text-xs sm:text-sm text-gray-600 hover:text-[#F59E0B] transition-colors py-1">
            <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver a mis torneos
        </a>
        <h1 class="text-xl sm:text-2xl font-bold text-[#065F46] mt-2 flex items-center gap-2 sm:gap-3 flex-wrap">
            <span class="text-2xl sm:text-3xl">
                @if($torneo->categoria === 'libre') 🏆
                @elseif($torneo->categoria === 'sub20') 
                @elseif($torneo->categoria === 'femenino') ⭐
                @else 🎯
                @endif
            </span>
            <span class="break-words">{{ $torneo->nombre }}</span>
        </h1>
    </div>

    <!-- Mensajes -->
    @if(session('success'))
        <div class="mb-4 sm:mb-6 bg-[#D1FAE5] border border-[#10B981] text-[#065F46] px-3.5 sm:px-4 py-3 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-xs sm:text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 sm:mb-6 bg-[#FEE2E2] border border-[#EF4444] text-[#991B1B] px-3.5 sm:px-4 py-3 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-xs sm:text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Badges de Estado -->
    <div class="flex flex-wrap gap-2 mb-4 sm:mb-6">
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
        <span class="inline-flex items-center px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-semibold {{ $estadoBadge }}">
            {{ $estadoTexto }}
        </span>
        <span class="inline-flex items-center px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-semibold bg-[#F3F4F6] text-[#374151]">
            Categoría: {{ ucfirst(str_replace('_', ' ', $torneo->categoria)) }}
        </span>
        <span class="inline-flex items-center px-2.5 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-semibold bg-[#DBEAFE] text-[#1E40AF]">
            Tipo: {{ ucfirst(str_replace('_', ' ', $torneo->tipo_torneo)) }}
        </span>
    </div>

    <!-- Grid de Información -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
        <!-- Columna Principal: Información y Acciones -->
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
            <!-- Información General -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 border-b border-gray-100 pb-2 flex items-center gap-2">
                    <span>📋</span> Información General
                </h2>
                <div class="space-y-2.5 sm:space-y-3">
                    <div class="flex flex-col sm:flex-row sm:justify-between py-1 border-b border-gray-50 sm:border-0">
                        <span class="text-xs sm:text-sm text-gray-500">Instalación:</span>
                        <span class="text-xs sm:text-sm font-medium text-gray-800">{{ $torneo->instalacion->nombre ?? 'N/A' }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between py-1 border-b border-gray-50 sm:border-0">
                        <span class="text-xs sm:text-sm text-gray-500">Fecha de Inicio:</span>
                        <span class="text-xs sm:text-sm font-medium text-gray-800">{{ $torneo->fecha_inicio->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between py-1 border-b border-gray-50 sm:border-0">
                        <span class="text-xs sm:text-sm text-gray-500">Fecha de Fin:</span>
                        <span class="text-xs sm:text-sm font-medium text-gray-800">{{ $torneo->fecha_fin->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between py-1 border-b border-gray-50 sm:border-0">
                        <span class="text-xs sm:text-sm text-gray-500">Inscripciones:</span>
                        <span class="text-xs sm:text-sm font-medium text-gray-800">
                            {{ $torneo->inscripcion_inicio->format('d/m/Y') }} - {{ $torneo->inscripcion_fin->format('d/m/Y') }}
                        </span>
                    </div>
                    @if($torneo->descripcion)
                        <div class="pt-3 border-t border-gray-100">
                            <p class="text-xs text-gray-500 mb-1 font-medium">Descripción:</p>
                            <p class="text-xs sm:text-sm text-gray-700 leading-relaxed">{{ $torneo->descripcion }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Próximos Partidos -->
            @if($proximosPartidos->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 border-b border-gray-100 pb-2 flex items-center gap-2">
                        <span>📅</span> Próximos Partidos
                    </h2>
                    <div class="space-y-2.5 sm:space-y-3">
                        @foreach($proximosPartidos as $partido)
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-[#F9FAFB] rounded-lg hover:bg-[#ECFDF5] transition-colors gap-2">
                                <div class="flex items-start sm:items-center gap-2.5 sm:gap-3">
                                    <span class="text-lg sm:text-xl mt-0.5 sm:mt-0">⚽</span>
                                    <div>
                                        <p class="text-xs sm:text-sm font-semibold text-gray-800">
                                            {{ $partido->equipoLocal->nombre ?? 'TBD' }} vs {{ $partido->equipoVisitante->nombre ?? 'TBD' }}
                                        </p>
                                        <p class="text-[11px] sm:text-xs text-gray-500">
                                            {{ $partido->fecha_partido->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($partido->hora_inicio)->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <span class="self-start sm:self-auto inline-flex items-center px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-full text-[11px] sm:text-xs font-semibold bg-[#DBEAFE] text-[#1E40AF]">
                                    Programado
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Últimos Resultados -->
            @if($ultimosResultados->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 border-b border-gray-100 pb-2 flex items-center gap-2">
                        <span>✅</span> Últimos Resultados
                    </h2>
                    <div class="space-y-2.5 sm:space-y-3">
                        @foreach($ultimosResultados as $partido)
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-[#F9FAFB] rounded-lg gap-2">
                                <div class="flex items-start sm:items-center gap-2.5 sm:gap-3">
                                    <span class="text-lg sm:text-xl mt-0.5 sm:mt-0">⚽</span>
                                    <div>
                                        <p class="text-xs sm:text-sm font-semibold text-gray-800">
                                            {{ $partido->equipoLocal->nombre ?? 'TBD' }} 
                                            <span class="text-[#F59E0B] font-bold">{{ $partido->marcador_local }}</span>
                                            - 
                                            <span class="text-[#F59E0B] font-bold">{{ $partido->marcador_visitante }}</span>
                                            {{ $partido->equipoVisitante->nombre ?? 'TBD' }}
                                        </p>
                                        <p class="text-[11px] sm:text-xs text-gray-500">
                                            {{ $partido->fecha_partido->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </div>
                                <span class="self-start sm:self-auto inline-flex items-center px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-full text-[11px] sm:text-xs font-semibold bg-[#D1FAE5] text-[#065F46]">
                                    Finalizado
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Columna Secundaria: Estadísticas y Acciones -->
        <div class="space-y-4 sm:space-y-6">
            <!-- Estadísticas -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 border-b border-gray-100 pb-2 flex items-center gap-2">
                    <span>📊</span> Estadísticas
                </h2>
                <div class="grid grid-cols-2 sm:grid-cols-1 gap-2.5 sm:gap-3">
                    <div class="p-3 bg-[#ECFDF5] rounded-lg">
                        <p class="text-[11px] sm:text-xs text-gray-500 mb-0.5 sm:mb-1">Equipos</p>
                        <p class="text-xl sm:text-2xl font-bold text-[#065F46]">{{ $stats['equipos'] }}/{{ $stats['max_equipos'] }}</p>
                    </div>
                    <div class="p-3 bg-[#FEF3C7] rounded-lg">
                        <p class="text-[11px] sm:text-xs text-gray-500 mb-0.5 sm:mb-1">Partidos Jugados</p>
                        <p class="text-xl sm:text-2xl font-bold text-[#92400E]">{{ $stats['partidos_jugados'] }}/{{ $stats['partidos_total'] }}</p>
                    </div>
                    <div class="p-3 bg-[#DBEAFE] rounded-lg">
                        <p class="text-[11px] sm:text-xs text-gray-500 mb-0.5 sm:mb-1">Partidos Programados</p>
                        <p class="text-xl sm:text-2xl font-bold text-[#1E40AF]">{{ $stats['partidos_programados'] }}</p>
                    </div>
                    <div class="p-3 bg-[#FEE2E2] rounded-lg">
                        <p class="text-[11px] sm:text-xs text-gray-500 mb-0.5 sm:mb-1">Goles Anotados</p>
                        <p class="text-xl sm:text-2xl font-bold text-[#991B1B]">{{ $stats['goles_total'] }}</p>
                    </div>
                    <div class="p-3 bg-[#F3F4F6] rounded-lg col-span-2 sm:col-span-1">
                        <p class="text-[11px] sm:text-xs text-gray-500 mb-0.5 sm:mb-1">Jugadores Registrados</p>
                        <p class="text-xl sm:text-2xl font-bold text-[#374151]">{{ $stats['jugadores_total'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 border-b border-gray-100 pb-2 flex items-center gap-2">
                    <span>⚡</span> Acciones Rápidas
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-2.5 sm:gap-3">
                    <a href="{{ route('organizador.equipos.index', ['torneo_id' => $torneo->id]) }}" 
                       class="min-h-[44px] flex items-center justify-center px-4 py-2.5 sm:py-3 bg-[#10B981] hover:bg-[#059669] text-white font-medium rounded-lg transition-colors text-center text-xs sm:text-sm">
                        👥 Gestionar Equipos
                    </a>
                    <a href="{{ route('organizador.jugadores.index', ['torneo_id' => $torneo->id]) }}" 
                       class="min-h-[44px] flex items-center justify-center px-4 py-2.5 sm:py-3 bg-[#3B82F6] hover:bg-[#2563EB] text-white font-medium rounded-lg transition-colors text-center text-xs sm:text-sm">
                        🏃 Gestionar Jugadores
                    </a>
                    <a href="{{ route('organizador.partidos.index', ['torneo_id' => $torneo->id]) }}" 
                       class="min-h-[44px] flex items-center justify-center px-4 py-2.5 sm:py-3 bg-[#F59E0B] hover:bg-[#D97706] text-white font-medium rounded-lg transition-colors text-center text-xs sm:text-sm">
                        ⚽ Programar Partidos
                    </a>
                    <a href="{{ route('organizador.posiciones.index', ['torneo_id' => $torneo->id]) }}" 
                       class="min-h-[44px] flex items-center justify-center px-4 py-2.5 sm:py-3 bg-[#8B5CF6] hover:bg-[#7C3AED] text-white font-medium rounded-lg transition-colors text-center text-xs sm:text-sm">
                        📊 Ver Posiciones
                    </a>
                </div>
            </div>

            <!-- Cambiar Estado -->
            @if($torneo->estado !== 'finalizado')
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 border-b border-gray-100 pb-2 flex items-center gap-2">
                        <span>🔄</span> Cambiar Estado
                    </h2>
                    <form method="POST" action="{{ route('organizador.torneos.cambiar-estado', $torneo) }}" class="space-y-3">
                        @csrf
                        @method('PUT')
                        <div>
                            <select name="estado" required
                                    class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm">
                                <option value="borrador" {{ $torneo->estado === 'borrador' ? 'selected' : '' }}>Borrador</option>
                                <option value="inscripciones_abiertas" {{ $torneo->estado === 'inscripciones_abiertas' ? 'selected' : '' }}>Inscripciones Abiertas</option>
                                <option value="en_curso" {{ $torneo->estado === 'en_curso' ? 'selected' : '' }}>En Curso</option>
                                <option value="finalizado" {{ $torneo->estado === 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                            </select>
                        </div>
                        <button type="submit" 
                                class="w-full min-h-[44px] px-4 py-2.5 bg-[#F59E0B] hover:bg-[#D97706] text-white font-medium rounded-lg transition-colors text-xs sm:text-sm"
                                onclick="return confirm('¿Estás seguro de cambiar el estado del torneo?');">
                            Actualizar Estado
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- Tabla de Posiciones Simplificada -->
    @if($tablaPosiciones->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-4 border-b border-gray-100 pb-2">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <span>🏆</span> Tabla de Posiciones
                </h2>
                <a href="{{ route('organizador.posiciones.index', ['torneo_id' => $torneo->id]) }}" 
                   class="text-xs sm:text-sm text-[#F59E0B] hover:text-[#D97706] font-medium inline-flex items-center gap-1 self-start sm:self-auto py-1">
                    <span>Ver completa</span>
                    <span>→</span>
                </a>
            </div>

            <!-- Vista Móvil: Tarjetas compactas -->
            <div class="block sm:hidden space-y-2">
                @foreach($tablaPosiciones->take(5) as $index => $equipo)
                    <div class="p-3 rounded-lg border border-gray-100 flex items-center justify-between {{ $index < 2 ? 'bg-[#FEF3C7]/40 border-amber-200' : 'bg-[#F9FAFB]' }}">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold {{ $index === 0 ? 'bg-amber-400 text-white' : ($index === 1 ? 'bg-gray-300 text-gray-800' : 'bg-gray-100 text-gray-600') }}">
                                {{ $index + 1 }}
                            </span>
                            <div class="truncate">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $equipo->nombre }}</p>
                                <p class="text-[11px] text-gray-500">
                                    PJ: {{ $equipo->partidos_jugados }} | G:{{ $equipo->partidos_ganados }} E:{{ $equipo->partidos_empatados }} P:{{ $equipo->partidos_perdidos }} | DIF: {{ $equipo->diferencia_formateada }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 pl-2">
                            <span class="text-base font-extrabold text-[#F59E0B]">{{ $equipo->puntos }}</span>
                            <span class="text-[10px] text-gray-400 block -mt-1 uppercase">pts</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Vista Desktop / Tablet: Tabla completa con scroll horizontal -->
            <div class="hidden sm:block overflow-x-auto -mx-4 sm:mx-0">
                <table class="w-full text-left border-collapse min-w-[550px]">
                    <thead>
                        <tr class="bg-[#F9FAFB] border-b-2 border-gray-200">
                            <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-xs font-semibold text-gray-500 uppercase">#</th>
                            <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-xs font-semibold text-gray-500 uppercase">Equipo</th>
                            <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-xs font-semibold text-gray-500 uppercase text-center">PJ</th>
                            <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-xs font-semibold text-gray-500 uppercase text-center">PG</th>
                            <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-xs font-semibold text-gray-500 uppercase text-center">PE</th>
                            <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-xs font-semibold text-gray-500 uppercase text-center">PP</th>
                            <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-xs font-semibold text-gray-500 uppercase text-center">GF</th>
                            <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-xs font-semibold text-gray-500 uppercase text-center">GC</th>
                            <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-xs font-semibold text-gray-500 uppercase text-center">DIF</th>
                            <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-xs font-semibold text-gray-500 uppercase text-center">PTS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-xs sm:text-sm">
                        @foreach($tablaPosiciones->take(5) as $index => $equipo)
                            <tr class="hover:bg-[#FEF3C7] transition-colors duration-150 {{ $index < 2 ? 'bg-[#FEF3C7]/30' : '' }}">
                                <td class="px-3 sm:px-4 py-2.5 sm:py-3 font-bold text-gray-700">{{ $index + 1 }}</td>
                                <td class="px-3 sm:px-4 py-2.5 sm:py-3 font-semibold text-gray-800">{{ $equipo->nombre }}</td>
                                <td class="px-3 sm:px-4 py-2.5 sm:py-3 text-center text-gray-600">{{ $equipo->partidos_jugados }}</td>
                                <td class="px-3 sm:px-4 py-2.5 sm:py-3 text-center text-gray-600">{{ $equipo->partidos_ganados }}</td>
                                <td class="px-3 sm:px-4 py-2.5 sm:py-3 text-center text-gray-600">{{ $equipo->partidos_empatados }}</td>
                                <td class="px-3 sm:px-4 py-2.5 sm:py-3 text-center text-gray-600">{{ $equipo->partidos_perdidos }}</td>
                                <td class="px-3 sm:px-4 py-2.5 sm:py-3 text-center text-gray-600">{{ $equipo->goles_favor }}</td>
                                <td class="px-3 sm:px-4 py-2.5 sm:py-3 text-center text-gray-600">{{ $equipo->goles_contra }}</td>
                                <td class="px-3 sm:px-4 py-2.5 sm:py-3 text-center font-medium {{ $equipo->diferencia_goles > 0 ? 'text-[#10B981]' : ($equipo->diferencia_goles < 0 ? 'text-[#EF4444]' : 'text-gray-500') }}">
                                    {{ $equipo->diferencia_formateada }}
                                </td>
                                <td class="px-3 sm:px-4 py-2.5 sm:py-3 text-center font-bold text-[#F59E0B]">{{ $equipo->puntos }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Nota informativa -->
    <div class="mt-4 sm:mt-6 bg-[#FEF3C7] border border-[#F59E0B] rounded-xl p-3.5 sm:p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-[#F59E0B] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div class="text-xs sm:text-sm text-[#92400E]">
            <p class="font-semibold mb-1">ℹ️ Información importante</p>
            <p class="text-[#78350F] leading-relaxed">
                Como organizador, puedes gestionar equipos, jugadores, partidos y posiciones. 
                No puedes modificar la configuración general del torneo (fechas, costos, reglas). 
                Si necesitas cambios, contacta al administrador.
            </p>
        </div>
    </div>
@endsection
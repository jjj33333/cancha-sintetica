@extends('layouts.organizador')

@section('header_title', 'Partidos y Resultados')
@section('title', 'Partidos - Organizador')

@section('content')
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-3 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#065F46] flex items-center gap-2">
                <span>⚽</span> <span>Partidos y Resultados</span>
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5 sm:mt-1">Programa partidos y registra resultados de tus torneos.</p>
        </div>
        @if($torneoSeleccionado)
            @php
                $paramsImpresion = array_filter([
                    'torneo_id' => $torneoSeleccionado->id,
                    'jornada' => $jornadaSeleccionada,
                    'orden' => $orden,
                ], fn ($valor) => $valor !== null && $valor !== '');
            @endphp
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
                <a href="{{ route('organizador.partidos.imprimir', $paramsImpresion) }}" 
                   target="_blank" rel="noopener"
                   class="w-full sm:w-auto inline-flex items-center justify-center min-h-[44px] px-4 py-2.5 bg-white border border-[#065F46] text-[#065F46] hover:bg-[#ECFDF5] text-xs sm:text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"></path>
                    </svg>
                    <span>Imprimir Partidos</span>
                </a>
                <a href="{{ route('organizador.partidos.create', ['torneo_id' => $torneoSeleccionado->id]) }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center min-h-[44px] px-4 py-2.5 bg-[#F59E0B] hover:bg-[#D97706] text-white text-xs sm:text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Programar Partido</span>
                </a>
            </div>
        @endif
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

    @if($torneosAsignados->count() === 0)
        <!-- Estado Vacío: Sin torneos asignados -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-12 text-center">
            <div class="text-gray-400 mb-3 sm:mb-4">
                <svg class="w-16 h-16 sm:w-20 sm:h-20 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-2">No tienes torneos asignados</h3>
            <p class="text-xs sm:text-sm text-gray-500">Contacta al administrador para que te asigne torneos.</p>
        </div>
    @else
        <!-- Selector de Torneo, Orden y Jornada -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3.5 sm:p-4 mb-4 sm:mb-6">
            <form method="GET" action="{{ route('organizador.partidos.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Seleccionar Torneo</label>
                    <select name="torneo_id" onchange="this.form.submit()" 
                            class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm bg-white">
                        @foreach($torneosAsignados as $torneo)
                            <option value="{{ $torneo->id }}" {{ $torneoSeleccionado && $torneoSeleccionado->id === $torneo->id ? 'selected' : '' }}>
                                {{ $torneo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Ordenar por Fecha y Hora</label>
                    <select name="orden" onchange="this.form.submit()" 
                            class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm bg-white">
                        <option value="desc" {{ ($orden ?? 'desc') === 'desc' ? 'selected' : '' }}>
                            ⬇️ Fecha y Hora: Descendente (Más recientes primero)
                        </option>
                        <option value="asc" {{ ($orden ?? 'desc') === 'asc' ? 'selected' : '' }}>
                            ⬆️ Fecha y Hora: Ascendente (Más antiguos primero)
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Filtrar por Jornada</label>
                    <select name="jornada" onchange="this.form.submit()" 
                            class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm bg-white">
                        <option value="">Todas las jornadas</option>
                        @foreach($jornadas as $jornada)
                            <option value="{{ $jornada }}" {{ (string) $jornadaSeleccionada === (string) $jornada ? 'selected' : '' }}>
                                Jornada {{ $jornada }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if(request('orden') === 'asc' || request()->filled('jornada'))
                    <div class="flex items-center">
                        <a href="{{ route('organizador.partidos.index', ['torneo_id' => $torneoSeleccionado?->id]) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-gray-500 hover:text-[#D97706] hover:bg-[#FEF3C7] rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Restablecer filtros
                        </a>
                    </div>
                @endif
            </form>
        </div>

        @if($torneoSeleccionado)
            <!-- Banner del Torneo -->
            <div class="bg-gradient-to-r from-[#F59E0B] to-[#D97706] rounded-xl shadow-sm p-4 sm:p-6 mb-4 sm:mb-6 text-white">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold flex items-center gap-2">
                            <span>
                                @if($torneoSeleccionado->categoria === 'libre') 🏆
                                @elseif($torneoSeleccionado->categoria === 'sub20') 
                                @elseif($torneoSeleccionado->categoria === 'femenino') ⭐
                                @else 🎯
                                @endif
                            </span>
                            <span class="break-words">{{ $torneoSeleccionado->nombre }}</span>
                        </h2>
                        <p class="text-xs sm:text-sm text-white/90 mt-1">
                            {{ $totalPartidos }} partido{{ $totalPartidos !== 1 ? 's' : '' }} programado{{ $totalPartidos !== 1 ? 's' : '' }}
                        </p>
                    </div>
                    <div class="sm:text-right flex items-center sm:block gap-2">
                        <div class="text-2xl sm:text-3xl font-bold">{{ $totalFinalizados }}/{{ $totalPartidos }}</div>
                        <p class="text-[11px] sm:text-xs text-white/80">Partidos finalizados</p>
                    </div>
                </div>
            </div>

            <!-- Alerta Informativa -->
            <div class="mb-4 sm:mb-6 bg-[#DBEAFE] border border-[#3B82F6] rounded-xl p-3.5 sm:p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-[#3B82F6] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-xs sm:text-sm text-[#1E40AF]">
                    <p class="font-semibold mb-0.5 sm:mb-1">ℹ️ Información importante</p>
                    <p class="text-[#1E3A8A] leading-relaxed">
                        ✅ Al programar un partido, la cancha se reserva automáticamente en el calendario general. 
                        No es necesario crear una reserva manual.
                    </p>
                </div>
            </div>

            <!-- Listado de Partidos: Vista Móvil + Vista Desktop -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Vista Móvil (Tarjetas de Partido) -->
                <div class="block md:hidden divide-y divide-gray-100">
                    @forelse($partidos as $partido)
                        <div class="p-4 space-y-3 {{ !$partido->esta_activo ? 'opacity-50 bg-gray-50' : '' }}">
                            <!-- Fila superior: Fecha, hora y estado -->
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span class="flex items-center gap-1 font-medium text-gray-700">
                                    <svg class="w-3.5 h-3.5 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $partido->fecha_partido->format('d/m/Y') }} • {{ \Carbon\Carbon::parse($partido->hora_inicio)->format('g:i A') }}
                                    @if($partido->hora_fin)
                                        - {{ \Carbon\Carbon::parse($partido->hora_fin)->format('g:i A') }}
                                    @else
                                        - {{ \Carbon\Carbon::parse($partido->hora_inicio)->addHours(2)->format('g:i A') }}
                                    @endif
                                </span>
                                @php
                                    $estadoBadge = match($partido->estado) {
                                        'programado' => 'bg-[#DBEAFE] text-[#1E40AF]',
                                        'en_curso' => 'bg-[#FEF3C7] text-[#92400E]',
                                        'finalizado' => 'bg-[#D1FAE5] text-[#065F46]',
                                        'aplazado' => 'bg-[#F3F4F6] text-[#374151]',
                                        'cancelado' => 'bg-[#FEE2E2] text-[#991B1B]',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                    $estadoTexto = match($partido->estado) {
                                        'programado' => 'Programado',
                                        'en_curso' => 'En curso',
                                        'finalizado' => 'Finalizado',
                                        'aplazado' => 'Aplazado',
                                        'cancelado' => 'Cancelado',
                                        default => ucfirst($partido->estado),
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $estadoBadge }}">
                                    {{ $estadoTexto }}
                                </span>
                            </div>

                            <!-- No. y Jornada -->
                            <div class="flex items-center gap-2 text-[10px] text-gray-500">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-100 font-semibold text-gray-600">
                                    No. {{ $partidos->firstItem() + $loop->index }}
                                </span>
                                @if($partido->numero_jornada)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-[#FEF3C7] font-semibold text-[#92400E]">
                                        Jornada {{ $partido->numero_jornada }}
                                    </span>
                                @endif
                            </div>

                            <!-- Marcador / Enfrentamiento -->
                            <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                                <div class="grid grid-cols-12 items-center gap-2">
                                    <!-- Local -->
                                    <div class="col-span-5 text-center flex flex-col items-center">
                                        @if($partido->equipoLocal->logo)
                                            <img src="{{ Storage::url($partido->equipoLocal->logo) }}" alt="{{ $partido->equipoLocal->nombre }}" 
                                                 class="w-10 h-10 rounded-full object-cover mb-1 border border-gray-200">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-[#FEF3C7] flex items-center justify-center text-[#92400E] font-bold text-xs mb-1">
                                                {{ substr($partido->equipoLocal->nombre, 0, 1) }}
                                            </div>
                                        @endif
                                        <p class="font-bold text-xs text-gray-800 line-clamp-2">{{ $partido->equipoLocal->nombre }}</p>
                                        <span class="text-[10px] text-gray-400">Local</span>
                                    </div>

                                    <!-- Resultado / VS -->
                                    <div class="col-span-2 text-center">
                                        @if($partido->estado === 'finalizado' && $partido->marcador_local !== null)
                                            <div class="bg-white py-1 px-1.5 rounded-lg border border-gray-200 shadow-sm">
                                                <span class="text-sm font-extrabold text-[#065F46]">{{ $partido->marcador_local }}</span>
                                                <span class="text-gray-300 text-xs">-</span>
                                                <span class="text-sm font-extrabold text-[#065F46]">{{ $partido->marcador_visitante }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs font-bold text-gray-400 bg-white px-2 py-1 rounded-full border border-gray-200">VS</span>
                                        @endif
                                    </div>

                                    <!-- Visitante -->
                                    <div class="col-span-5 text-center flex flex-col items-center">
                                        @if($partido->equipoVisitante->logo)
                                            <img src="{{ Storage::url($partido->equipoVisitante->logo) }}" alt="{{ $partido->equipoVisitante->nombre }}" 
                                                 class="w-10 h-10 rounded-full object-cover mb-1 border border-gray-200">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-[#FEF3C7] flex items-center justify-center text-[#92400E] font-bold text-xs mb-1">
                                                {{ substr($partido->equipoVisitante->nombre, 0, 1) }}
                                            </div>
                                        @endif
                                        <p class="font-bold text-xs text-gray-800 line-clamp-2">{{ $partido->equipoVisitante->nombre }}</p>
                                        <span class="text-[10px] text-gray-400">Visitante</span>
                                    </div>
                                </div>
                                <div class="text-center mt-2 pt-2 border-t border-gray-200 text-[11px] text-gray-500">
                                    🏟️ {{ $partido->instalacion->nombre ?? 'N/A' }}
                                </div>
                            </div>

                            <!-- Botones de Acción Móviles -->
                            <div class="flex flex-wrap items-center justify-end gap-2 pt-1">
                                <a href="{{ route('organizador.partidos.show', $partido) }}" 
                                   class="inline-flex items-center justify-center min-h-[36px] px-3 py-1.5 bg-blue-50 text-[#3B82F6] hover:bg-blue-100 rounded-lg text-xs font-semibold transition-colors">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Detalles
                                </a>

                                @if($partido->estado !== 'finalizado')
                                    <a href="{{ route('organizador.partidos.edit', $partido) }}" 
                                       class="inline-flex items-center justify-center min-h-[36px] px-3 py-1.5 bg-amber-50 text-[#F59E0B] hover:bg-[#FEF3C7] rounded-lg text-xs font-semibold transition-colors">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Editar
                                    </a>

                                    @if(in_array($partido->estado, ['programado', 'en_curso', 'aplazado']))
                                        <button onclick="abrirModalResultado({{ $partido->id }}, '{{ addslashes($partido->equipoLocal->nombre) }}', '{{ addslashes($partido->equipoVisitante->nombre) }}')" 
                                                class="inline-flex items-center justify-center min-h-[36px] px-3 py-1.5 bg-emerald-50 text-[#10B981] hover:bg-[#D1FAE5] rounded-lg text-xs font-semibold transition-colors">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Resultado
                                        </button>
                                    @endif
                                @endif

                                @if($partido->estado === 'finalizado' && $partido->marcador_local !== null)
                                    <button onclick="abrirModalEditarMarcador({{ $partido->id }}, '{{ addslashes($partido->equipoLocal->nombre) }}', '{{ addslashes($partido->equipoVisitante->nombre) }}', {{ $partido->marcador_local }}, {{ $partido->marcador_visitante }})" 
                                            class="inline-flex items-center justify-center min-h-[36px] px-3 py-1.5 bg-blue-50 text-[#3B82F6] hover:bg-blue-100 rounded-lg text-xs font-semibold transition-colors">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Editar marcador
                                    </button>
                                @endif

                                @if($partido->estado !== 'finalizado' && $partido->estado !== 'cancelado')
                                    <form action="{{ route('organizador.partidos.destroy', $partido) }}" method="POST" 
                                          class="inline" onsubmit="return confirm('¿Estás seguro de cancelar este partido? La reserva asociada también será cancelada.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center justify-center min-h-[36px] px-3 py-1.5 bg-red-50 text-[#EF4444] hover:bg-[#FEE2E2] rounded-lg text-xs font-semibold transition-colors">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Cancelar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <div class="text-gray-400 mb-2">
                                <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-600">No hay partidos programados en este torneo.</p>
                            <p class="text-xs text-gray-400 mt-1">Comienza programando el primer partido.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Vista Desktop (Tabla) -->
                @php
                    $paramsOrden = array_filter([
                        'torneo_id' => $torneoSeleccionado?->id,
                        'jornada' => $jornadaSeleccionada,
                    ], fn ($valor) => $valor !== null && $valor !== '');
                    $paramsOrdenToggle = array_merge($paramsOrden, [
                        'orden' => ($orden ?? 'desc') === 'asc' ? 'desc' : 'asc',
                    ]);
                @endphp
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#F9FAFB] border-b-2 border-gray-200">
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">No.</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <a href="{{ route('organizador.partidos.index', $paramsOrdenToggle) }}" 
                                       class="inline-flex items-center gap-1.5 hover:text-[#F59E0B] transition-colors"
                                       title="Ordenar por fecha y hora: {{ ($orden ?? 'desc') === 'asc' ? 'Ascendente (Clic para Descendente)' : 'Descendente (Clic para Ascendente)' }}">
                                        <span>Fecha y Hora</span>
                                        @if(($orden ?? 'desc') === 'desc')
                                            <svg class="w-3.5 h-3.5 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        @else
                                            <svg class="w-3.5 h-3.5 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                        @endif
                                    </a>
                                </th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Local</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Visitante</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Marcador</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Jornada</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Estado</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($partidos as $partido)
                                @php
                                    $partidoEstadoBadge = match($partido->estado) {
                                        'programado' => 'bg-[#DBEAFE] text-[#1E40AF]',
                                        'en_curso' => 'bg-[#FEF3C7] text-[#92400E]',
                                        'finalizado' => 'bg-[#D1FAE5] text-[#065F46]',
                                        'aplazado' => 'bg-[#F3F4F6] text-[#374151]',
                                        'cancelado' => 'bg-[#FEE2E2] text-[#991B1B]',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                    $partidoEstadoTexto = match($partido->estado) {
                                        'programado' => 'Programado',
                                        'en_curso' => 'En curso',
                                        'finalizado' => 'Finalizado',
                                        'aplazado' => 'Aplazado',
                                        'cancelado' => 'Cancelado',
                                        default => ucfirst($partido->estado),
                                    };
                                @endphp
                                <tr class="hover:bg-[#FEF3C7] transition-colors duration-150 group {{ !$partido->esta_activo ? 'opacity-50' : '' }}">
                                    <!-- No. -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-[#F3F4F6] text-xs font-bold text-gray-600">
                                            {{ $partidos->firstItem() + $loop->index }}
                                        </span>
                                    </td>

                                    <!-- Fecha y Hora (Formato 12 Horas) -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <div class="flex flex-col">
                                                <span class="text-sm font-medium text-gray-900">{{ $partido->fecha_partido->format('d/m/Y') }}</span>
                                                <span class="text-sm font-semibold text-gray-600">
                                                    {{ \Carbon\Carbon::parse($partido->hora_inicio)->format('g:i A') }}
                                                    @if($partido->hora_fin)
                                                        - {{ \Carbon\Carbon::parse($partido->hora_fin)->format('g:i A') }}
                                                    @else
                                                        - {{ \Carbon\Carbon::parse($partido->hora_inicio)->addHours(2)->format('g:i A') }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Local -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            @if($partido->equipoLocal->logo)
                                                <img src="{{ Storage::url($partido->equipoLocal->logo) }}" alt="{{ $partido->equipoLocal->nombre }}" 
                                                     class="w-8 h-8 rounded-full object-cover">
                                            @else
                                                <div class="w-8 h-8 rounded-full bg-[#FEF3C7] flex items-center justify-center text-[#92400E] font-bold text-xs">
                                                    {{ substr($partido->equipoLocal->nombre, 0, 1) }}
                                                </div>
                                            @endif
                                            <span class="text-sm font-semibold text-gray-800">{{ $partido->equipoLocal->nombre }}</span>
                                        </div>
                                    </td>

                                    <!-- Visitante -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            @if($partido->equipoVisitante->logo)
                                                <img src="{{ Storage::url($partido->equipoVisitante->logo) }}" alt="{{ $partido->equipoVisitante->nombre }}" 
                                                     class="w-8 h-8 rounded-full object-cover">
                                            @else
                                                <div class="w-8 h-8 rounded-full bg-[#FEF3C7] flex items-center justify-center text-[#92400E] font-bold text-xs">
                                                    {{ substr($partido->equipoVisitante->nombre, 0, 1) }}
                                                </div>
                                            @endif
                                            <span class="text-sm font-semibold text-gray-800">{{ $partido->equipoVisitante->nombre }}</span>
                                        </div>
                                    </td>

                                    <!-- Marcador -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($partido->estado === 'finalizado' && $partido->marcador_local !== null)
                                            <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#F3F4F6] rounded-lg">
                                                <span class="text-lg font-bold text-[#065F46]">{{ $partido->marcador_local }}</span>
                                                <span class="text-gray-400">-</span>
                                                <span class="text-lg font-bold text-[#065F46]">{{ $partido->marcador_visitante }}</span>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>

                                    <!-- Jornada -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($partido->numero_jornada)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#FEF3C7] text-[#92400E]">
                                                Jornada {{ $partido->numero_jornada }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-400">—</span>
                                        @endif
                                    </td>

                                    <!-- Estado -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $partidoEstadoBadge }}">
                                            {{ $partidoEstadoTexto }}
                                        </span>
                                    </td>

                                    <!-- Acciones -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('organizador.partidos.show', $partido) }}" 
                                               class="p-2 text-gray-500 hover:text-[#3B82F6] hover:bg-[#DBEAFE] rounded-lg transition-colors" 
                                               title="Ver detalles">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>

                                            @if($partido->estado !== 'finalizado')
                                                <a href="{{ route('organizador.partidos.edit', $partido) }}" 
                                                   class="p-2 text-gray-500 hover:text-[#F59E0B] hover:bg-[#FEF3C7] rounded-lg transition-colors" 
                                                   title="Editar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>

                                                @if(in_array($partido->estado, ['programado', 'en_curso', 'aplazado']))
                                                    <button onclick="abrirModalResultado({{ $partido->id }}, '{{ addslashes($partido->equipoLocal->nombre) }}', '{{ addslashes($partido->equipoVisitante->nombre) }}')" 
                                                            class="p-2 text-gray-500 hover:text-[#10B981] hover:bg-[#D1FAE5] rounded-lg transition-colors" 
                                                            title="Registrar resultado">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    </button>
                                                @endif
                                            @endif

                                            @if($partido->estado === 'finalizado' && $partido->marcador_local !== null)
                                                <button onclick="abrirModalEditarMarcador({{ $partido->id }}, '{{ addslashes($partido->equipoLocal->nombre) }}', '{{ addslashes($partido->equipoVisitante->nombre) }}', {{ $partido->marcador_local }}, {{ $partido->marcador_visitante }})" 
                                                        class="p-2 text-gray-500 hover:text-[#3B82F6] hover:bg-[#DBEAFE] rounded-lg transition-colors" 
                                                        title="Editar marcador">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </button>
                                            @endif

                                            @if($partido->estado !== 'finalizado' && $partido->estado !== 'cancelado')
                                                <form action="{{ route('organizador.partidos.destroy', $partido) }}" method="POST" 
                                                      class="inline" onsubmit="return confirm('¿Estás seguro de cancelar este partido? La reserva asociada también será cancelada.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="p-2 text-gray-500 hover:text-[#EF4444] hover:bg-[#FEE2E2] rounded-lg transition-colors" 
                                                            title="Cancelar partido">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="text-gray-400 mb-3">
                                            <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 font-medium">No hay partidos programados en este torneo.</p>
                                        <p class="text-sm text-gray-400 mt-1">Comienza programando el primer partido.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($partidos->hasPages())
                <div class="mt-4 bg-white rounded-xl shadow-sm border border-gray-100 px-4 py-3">
                    {{ $partidos->links() }}
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
                        Al registrar el resultado de un partido, las estadísticas de los equipos (puntos, goles, partidos jugados) 
                        se actualizan automáticamente. Los partidos finalizados no pueden editarse ni eliminarse, pero su marcador 
                        puede corregirse con la acción "Editar marcador".
                    </p>
                </div>
            </div>
        @endif
    @endif

    <!-- Modal para Registrar Resultado -->
    <div id="modalResultado" class="hidden fixed inset-0 z-50 flex items-center justify-center p-3.5 sm:p-4">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="cerrarModalResultado()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden z-10 max-h-[90vh] overflow-y-auto">
            <div class="bg-[#F59E0B] p-4 sm:p-6 text-center relative">
                <h3 class="text-xl sm:text-2xl font-bold text-white">Registrar Resultado</h3>
                <button onclick="cerrarModalResultado()" class="absolute top-3 sm:top-4 right-3 sm:right-4 text-white/70 hover:text-white transition-colors p-1">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form id="formResultado" method="POST" class="p-4 sm:p-6 space-y-4">
                @csrf
                @method('PUT')
                
                <div class="text-center mb-3 sm:mb-4">
                    <p class="text-xs sm:text-sm text-gray-600 mb-1">Partido:</p>
                    <p class="font-bold text-sm sm:text-base text-gray-800 break-words" id="nombrePartido"></p>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 text-center">Marcador Local</label>
                        <input type="number" name="marcador_local" id="marcador_local" min="0" max="99" required
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-center text-xl sm:text-2xl font-bold">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 text-center">Marcador Visitante</label>
                        <input type="number" name="marcador_visitante" id="marcador_visitante" min="0" max="99" required
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-center text-xl sm:text-2xl font-bold">
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-2.5 sm:gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="cerrarModalResultado()" class="w-full sm:w-auto min-h-[44px] px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-xs sm:text-sm">
                        Cancelar
                    </button>
                    <button type="submit" class="w-full sm:w-auto min-h-[44px] px-6 py-2.5 bg-[#F59E0B] hover:bg-[#D97706] text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all text-xs sm:text-sm">
                        ✅ Registrar Resultado
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para Editar Marcador -->
    <div id="modalEditarMarcador" class="hidden fixed inset-0 z-50 flex items-center justify-center p-3.5 sm:p-4">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="cerrarModalEditarMarcador()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden z-10 max-h-[90vh] overflow-y-auto">
            <div class="bg-[#3B82F6] p-4 sm:p-6 text-center relative">
                <h3 class="text-xl sm:text-2xl font-bold text-white">Editar Marcador</h3>
                <button onclick="cerrarModalEditarMarcador()" class="absolute top-3 sm:top-4 right-3 sm:right-4 text-white/70 hover:text-white transition-colors p-1" aria-label="Cerrar modal">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form id="formEditarMarcador" method="POST" class="p-4 sm:p-6 space-y-4">
                @csrf
                @method('PUT')

                <div class="text-center mb-3 sm:mb-4">
                    <p class="text-xs sm:text-sm text-gray-600 mb-1">Partido:</p>
                    <p class="font-bold text-sm sm:text-base text-gray-800 break-words" id="nombrePartidoEditar"></p>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 text-center">Marcador Local</label>
                        <input type="number" name="marcador_local" id="marcador_local_editar" min="0" max="99" required
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent outline-none text-center text-xl sm:text-2xl font-bold">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 text-center">Marcador Visitante</label>
                        <input type="number" name="marcador_visitante" id="marcador_visitante_editar" min="0" max="99" required
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3B82F6] focus:border-transparent outline-none text-center text-xl sm:text-2xl font-bold">
                    </div>
                </div>

                <div class="bg-[#DBEAFE] border border-[#3B82F6]/40 rounded-lg p-3">
                    <p class="text-[11px] sm:text-xs text-[#1E40AF] leading-relaxed">
                        ℹ️ Solo se modificará el marcador. Las estadísticas de los equipos se recalcularán automáticamente.
                    </p>
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-2.5 sm:gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="cerrarModalEditarMarcador()" class="w-full sm:w-auto min-h-[44px] px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-xs sm:text-sm">
                        Cancelar
                    </button>
                    <button type="submit" class="w-full sm:w-auto min-h-[44px] px-6 py-2.5 bg-[#3B82F6] hover:bg-[#2563EB] text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all text-xs sm:text-sm">
                        💾 Guardar Marcador
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModalResultado(partidoId, equipoLocal, equipoVisitante) {
            document.getElementById('modalResultado').classList.remove('hidden');
            document.getElementById('formResultado').action = '/organizador/partidos/' + partidoId + '/registrar-resultado';
            document.getElementById('nombrePartido').textContent = equipoLocal + ' vs ' + equipoVisitante;
            document.getElementById('marcador_local').value = '';
            document.getElementById('marcador_visitante').value = '';
            document.getElementById('marcador_local').focus();
        }

        function cerrarModalResultado() {
            document.getElementById('modalResultado').classList.add('hidden');
        }

        function abrirModalEditarMarcador(partidoId, equipoLocal, equipoVisitante, marcadorLocal, marcadorVisitante) {
            document.getElementById('modalEditarMarcador').classList.remove('hidden');
            document.getElementById('formEditarMarcador').action = '/organizador/partidos/' + partidoId + '/editar-marcador';
            document.getElementById('nombrePartidoEditar').textContent = equipoLocal + ' vs ' + equipoVisitante;
            document.getElementById('marcador_local_editar').value = marcadorLocal;
            document.getElementById('marcador_visitante_editar').value = marcadorVisitante;
            document.getElementById('marcador_local_editar').focus();
        }

        function cerrarModalEditarMarcador() {
            document.getElementById('modalEditarMarcador').classList.add('hidden');
        }

        // Cerrar modales con tecla ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                cerrarModalResultado();
                cerrarModalEditarMarcador();
            }
        });
    </script>
@endsection
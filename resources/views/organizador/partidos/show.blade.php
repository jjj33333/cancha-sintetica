@extends('layouts.organizador')

@section('header_title', 'Detalles del Partido')
@section('title', 'Partido - Organizador')

@section('content')
    <div class="max-w-4xl mx-auto space-y-4 sm:space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
            <div class="w-full sm:w-auto">
                <a href="{{ route('organizador.partidos.index', ['torneo_id' => $partido->torneo_id]) }}" 
                   class="inline-flex items-center text-xs sm:text-sm text-gray-600 hover:text-[#F59E0B] transition-colors py-1">
                    <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a partidos
                </a>
                <h1 class="text-xl sm:text-2xl font-bold text-[#065F46] mt-2 flex items-center gap-2 sm:gap-3 flex-wrap">
                    <span class="text-2xl sm:text-3xl flex-shrink-0">⚽</span>
                    <span>{{ $partido->equipoLocal->nombre }} vs {{ $partido->equipoVisitante->nombre }}</span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Torneo: <span class="font-medium text-gray-700">{{ $torneo->nombre }}</span></p>
            </div>
            @if($partido->estado === 'programado' || $partido->estado === 'en_curso')
                <a href="{{ route('organizador.partidos.edit', $partido) }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center min-h-[44px] px-4 py-2 bg-[#F59E0B] hover:bg-[#D97706] text-white text-xs sm:text-sm font-semibold rounded-lg shadow-sm hover:shadow transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Editar Partido
                </a>
            @endif
        </div>

        <!-- Badges de Estado -->
        <div class="flex flex-wrap gap-2">
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
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-semibold {{ $estadoBadge }}">
                {{ $estadoTexto }}
            </span>
            @if($partido->reservado_automatico)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-semibold bg-[#DBEAFE] text-[#1E40AF]">
                    ✅ Reserva automática creada
                </span>
            @endif
        </div>

        <!-- Información Detallada -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            <!-- Información del Partido -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2 flex items-center gap-2">
                    <span>📋</span> Información del Partido
                </h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-xs sm:text-sm">
                        <span class="text-gray-500">Fecha:</span>
                        <span class="font-medium text-gray-800">{{ $partido->fecha_partido->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs sm:text-sm">
                        <span class="text-gray-500">Hora:</span>
                        <span class="font-medium text-gray-800">
                            {{ \Carbon\Carbon::parse($partido->hora_inicio)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($partido->hora_inicio)->addHours(2)->format('H:i') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-xs sm:text-sm">
                        <span class="text-gray-500">Cancha:</span>
                        <span class="font-medium text-gray-800">{{ $partido->instalacion->nombre ?? 'N/A' }}</span>
                    </div>
                    @if($partido->numero_jornada)
                    <div class="flex justify-between items-center text-xs sm:text-sm">
                        <span class="text-gray-500">Jornada:</span>
                        <span class="font-medium text-gray-800">{{ $partido->numero_jornada }}</span>
                    </div>
                    @endif
                    @if($partido->nombre_grupo)
                    <div class="flex justify-between items-center text-xs sm:text-sm">
                        <span class="text-gray-500">Grupo:</span>
                        <span class="font-medium text-gray-800">{{ $partido->nombre_grupo }}</span>
                    </div>
                    @endif
                    @if($partido->arbitro)
                    <div class="flex justify-between items-center text-xs sm:text-sm">
                        <span class="text-gray-500">Árbitro:</span>
                        <span class="font-medium text-gray-800">{{ $partido->arbitro }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Marcador -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 flex flex-col justify-between">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2 flex items-center gap-2">
                        <span>🎯</span> Marcador
                    </h2>
                    @if($partido->estado === 'finalizado' && $partido->marcador_local !== null)
                        <div class="text-center py-2">
                            <div class="flex items-center justify-center gap-3 sm:gap-6 mb-4">
                                <div class="text-center flex-1 min-w-0">
                                    <p class="text-xs sm:text-sm text-gray-500 mb-1 sm:mb-2 truncate font-medium">{{ $partido->equipoLocal->nombre }}</p>
                                    <p class="text-4xl sm:text-5xl font-bold text-[#065F46]">{{ $partido->marcador_local }}</p>
                                </div>
                                <div class="text-2xl sm:text-3xl text-gray-400 font-bold px-1 sm:px-2">-</div>
                                <div class="text-center flex-1 min-w-0">
                                    <p class="text-xs sm:text-sm text-gray-500 mb-1 sm:mb-2 truncate font-medium">{{ $partido->equipoVisitante->nombre }}</p>
                                    <p class="text-4xl sm:text-5xl font-bold text-[#065F46]">{{ $partido->marcador_visitante }}</p>
                                </div>
                            </div>
                            <div class="p-3 bg-[#D1FAE5] rounded-lg">
                                <p class="text-xs sm:text-sm font-semibold text-[#065F46]">
                                    @if($partido->marcador_local > $partido->marcador_visitante)
                                        🏆 Ganador: {{ $partido->equipoLocal->nombre }}
                                    @elseif($partido->marcador_visitante > $partido->marcador_local)
                                        🏆 Ganador: {{ $partido->equipoVisitante->nombre }}
                                    @else
                                        🤝 Empate
                                    @endif
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-6 sm:py-8">
                            <span class="text-3xl sm:text-4xl mb-2 inline-block">⏳</span>
                            <p class="text-xs sm:text-sm text-gray-500 font-medium">Partido no finalizado</p>
                            @if($partido->estado === 'programado' || $partido->estado === 'en_curso')
                                <button onclick="abrirModalResultado({{ $partido->id }}, '{{ addslashes($partido->equipoLocal->nombre) }}', '{{ addslashes($partido->equipoVisitante->nombre) }}')" 
                                        class="mt-4 w-full sm:w-auto inline-flex items-center justify-center min-h-[44px] px-6 py-2.5 bg-[#F59E0B] hover:bg-[#D97706] text-white font-medium rounded-lg shadow-sm hover:shadow transition-all text-xs sm:text-sm">
                                    ⚽ Registrar Resultado
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Reserva Asociada -->
        @if($reservaAsociada)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2 flex items-center gap-2">
                    <span>📅</span> Reserva Asociada
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                    <div class="p-3 sm:p-4 bg-[#F9FAFB] rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Código de Reserva</p>
                        <p class="text-sm font-mono font-semibold text-gray-800 break-all">{{ $reservaAsociada->codigo_reserva }}</p>
                    </div>
                    <div class="p-3 sm:p-4 bg-[#F9FAFB] rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Estado de Reserva</p>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#D1FAE5] text-[#065F46]">
                            {{ ucfirst($reservaAsociada->estado) }}
                        </span>
                    </div>
                    <div class="p-3 sm:p-4 bg-[#F9FAFB] rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Tipo de Reserva</p>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#DBEAFE] text-[#1E40AF]">
                            🏆 Torneo
                        </span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Notas -->
        @if($partido->notas)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2 flex items-center gap-2">
                    <span>📝</span> Notas
                </h2>
                <p class="text-xs sm:text-sm text-gray-700 leading-relaxed">{{ $partido->notas }}</p>
            </div>
        @endif
    </div>

    <!-- Modal para Registrar Resultado -->
    <div id="modalResultado" class="hidden fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="cerrarModalResultado()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden z-10 my-8">
            <div class="bg-[#F59E0B] p-4 sm:p-6 text-center relative">
                <h3 class="text-lg sm:text-xl font-bold text-white">Registrar Resultado</h3>
                <button onclick="cerrarModalResultado()" class="absolute top-3.5 right-3.5 sm:top-4 sm:right-4 text-white/80 hover:text-white transition-colors p-1" aria-label="Cerrar modal">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form id="formResultado" method="POST" class="p-4 sm:p-6 space-y-4">
                @csrf
                @method('PUT')
                
                <div class="text-center mb-4">
                    <p class="text-xs sm:text-sm text-gray-600 mb-1">Partido:</p>
                    <p class="font-bold text-gray-800 text-sm sm:text-base break-words" id="nombrePartido"></p>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Marcador Local</label>
                        <input type="number" name="marcador_local" id="marcador_local" min="0" max="99" required
                               class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-center text-xl sm:text-2xl font-bold">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Marcador Visitante</label>
                        <input type="number" name="marcador_visitante" id="marcador_visitante" min="0" max="99" required
                               class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-center text-xl sm:text-2xl font-bold">
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="cerrarModalResultado()" class="w-full sm:w-auto inline-flex items-center justify-center min-h-[44px] px-6 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-xs sm:text-sm">
                        Cancelar
                    </button>
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center min-h-[44px] px-6 py-2 bg-[#F59E0B] hover:bg-[#D97706] text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all text-xs sm:text-sm">
                        ✅ Registrar Resultado
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

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                cerrarModalResultado();
            }
        });
    </script>
@endsection
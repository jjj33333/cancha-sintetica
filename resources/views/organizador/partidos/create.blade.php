@extends('layouts.organizador')

@section('header_title', 'Programar Nuevo Partido')
@section('title', 'Crear Partido - Organizador')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-4 sm:mb-6">
            <a href="{{ route('organizador.partidos.index', ['torneo_id' => $torneoSeleccionado?->id]) }}" 
               class="inline-flex items-center text-xs sm:text-sm text-gray-600 hover:text-[#F59E0B] transition-colors py-1">
                <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver a partidos
            </a>
            <h1 class="text-xl sm:text-2xl font-bold text-[#065F46] mt-2">⚽ Programar Nuevo Partido</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Al guardar, la cancha se reservará automáticamente en el calendario general.</p>
        </div>

        <!-- Alerta Informativa -->
        <div class="mb-4 sm:mb-6 bg-[#DBEAFE] border border-[#3B82F6] rounded-xl p-3.5 sm:p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-[#3B82F6] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-xs sm:text-sm text-[#1E40AF]">
                <p class="font-semibold mb-0.5 sm:mb-1">✅ Sincronización automática</p>
                <p class="text-[#1E3A8A] leading-relaxed">
                    Al crear este partido, se generará automáticamente una reserva en el sistema con estado "Confirmada". 
                    No es necesario crear una reserva manual.
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('organizador.partidos.store') }}" 
              class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 space-y-5 sm:space-y-6">
            @csrf

            <!-- Torneo -->
            <div class="border-b border-gray-100 pb-5 sm:pb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                    <span class="text-lg sm:text-xl">🏆</span>
                    Torneo
                </h2>
                <div>
                    <label for="torneo_id" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        Torneo <span class="text-red-500">*</span>
                    </label>
                    <select name="torneo_id" id="torneo_id" required onchange="cargarEquipos(this.value)"
                            class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('torneo_id') border-red-500 @enderror">
                        <option value="">Seleccionar torneo...</option>
                        @foreach($torneosAsignados as $torneo)
                            <option value="{{ $torneo->id }}" {{ old('torneo_id', $torneoSeleccionado?->id) == $torneo->id ? 'selected' : '' }}>
                                {{ $torneo->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('torneo_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Equipos -->
            <div class="border-b border-gray-100 pb-5 sm:pb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                    <span class="text-lg sm:text-xl">🛡️</span>
                    Equipos
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 sm:gap-4">
                    <div>
                        <label for="equipo_local_id" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Equipo Local <span class="text-red-500">*</span>
                        </label>
                        <select name="equipo_local_id" id="equipo_local_id" required
                                class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('equipo_local_id') border-red-500 @enderror">
                            <option value="">Seleccionar equipo local...</option>
                            @foreach($equipos as $equipo)
                                <option value="{{ $equipo->id }}" {{ old('equipo_local_id') == $equipo->id ? 'selected' : '' }}>
                                    {{ $equipo->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('equipo_local_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="equipo_visitante_id" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Equipo Visitante <span class="text-red-500">*</span>
                        </label>
                        <select name="equipo_visitante_id" id="equipo_visitante_id" required
                                class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('equipo_visitante_id') border-red-500 @enderror">
                            <option value="">Seleccionar equipo visitante...</option>
                            @foreach($equipos as $equipo)
                                <option value="{{ $equipo->id }}" {{ old('equipo_visitante_id') == $equipo->id ? 'selected' : '' }}>
                                    {{ $equipo->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('equipo_visitante_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Fecha, Hora y Cancha -->
            <div class="border-b border-gray-100 pb-5 sm:pb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                    <span class="text-lg sm:text-xl">📅</span>
                    Fecha, Hora y Cancha
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3.5 sm:gap-4">
                    <div>
                        <label for="fecha_partido" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Fecha del Partido <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="fecha_partido" id="fecha_partido" value="{{ old('fecha_partido') }}" required min="{{ date('Y-m-d') }}"
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('fecha_partido') border-red-500 @enderror">
                        @error('fecha_partido') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="hora_inicio" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Hora de Inicio <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="hora_inicio" id="hora_inicio" value="{{ old('hora_inicio') }}" required
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('hora_inicio') border-red-500 @enderror">
                        @error('hora_inicio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <p class="text-[11px] sm:text-xs text-gray-500 mt-1">Duración automática: 2 horas</p>
                    </div>

                    <div class="sm:col-span-2 md:col-span-1">
                        <label for="instalacion_id" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Cancha <span class="text-red-500">*</span>
                        </label>
                        <select name="instalacion_id" id="instalacion_id" required
                                class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('instalacion_id') border-red-500 @enderror">
                            <option value="">Seleccionar cancha...</option>
                            @foreach($instalaciones as $inst)
                                <option value="{{ $inst->id }}" {{ old('instalacion_id') == $inst->id ? 'selected' : '' }}>
                                    {{ $inst->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('instalacion_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="border-b border-gray-100 pb-5 sm:pb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                    <span class="text-lg sm:text-xl">📝</span>
                    Información Adicional
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3.5 sm:gap-4">
                    <div>
                        <label for="numero_jornada" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Número de Jornada
                        </label>
                        <input type="number" name="numero_jornada" id="numero_jornada" value="{{ old('numero_jornada') }}" min="1"
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('numero_jornada') border-red-500 @enderror">
                        @error('numero_jornada') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="nombre_grupo" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Grupo (si aplica)
                        </label>
                        <input type="text" name="nombre_grupo" id="nombre_grupo" value="{{ old('nombre_grupo') }}" placeholder="Ej: Grupo A"
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('nombre_grupo') border-red-500 @enderror">
                        @error('nombre_grupo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2 md:col-span-1">
                        <label for="arbitro" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Árbitro
                        </label>
                        <input type="text" name="arbitro" id="arbitro" value="{{ old('arbitro') }}" placeholder="Nombre del árbitro"
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('arbitro') border-red-500 @enderror">
                        @error('arbitro') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-1 sm:col-span-2 md:col-span-3">
                        <label for="notas" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Notas (Opcional)
                        </label>
                        <textarea name="notas" id="notas" rows="2" placeholder="Observaciones adicionales..."
                                  class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('notas') border-red-500 @enderror">{{ old('notas') }}</textarea>
                        @error('notas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Estado -->
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                    <span class="text-lg sm:text-xl">📊</span>
                    Estado del Partido
                </h2>
                <div>
                    <label for="estado" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        Estado <span class="text-red-500">*</span>
                    </label>
                    <select name="estado" id="estado" required
                            class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('estado') border-red-500 @enderror">
                        <option value="programado" {{ old('estado') === 'programado' ? 'selected' : '' }}>📅 Programado</option>
                        <option value="en_curso" {{ old('estado') === 'en_curso' ? 'selected' : '' }}>▶️ En curso</option>
                    </select>
                    @error('estado') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('organizador.partidos.index', ['torneo_id' => $torneoSeleccionado?->id]) }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center min-h-[44px] px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-xs sm:text-sm">
                    Cancelar
                </a>
                <button type="submit" 
                        class="w-full sm:w-auto inline-flex items-center justify-center min-h-[44px] px-6 py-2.5 bg-[#F59E0B] hover:bg-[#D97706] text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all text-xs sm:text-sm">
                    ⚽ Programar Partido
                </button>
            </div>
        </form>
    </div>

    <script>
        function cargarEquipos(torneoId) {
            if (torneoId) {
                // Recargar la página con el torneo seleccionado para cargar los equipos
                window.location.href = '{{ route("organizador.partidos.create") }}?torneo_id=' + torneoId;
            }
        }
    </script>
@endsection
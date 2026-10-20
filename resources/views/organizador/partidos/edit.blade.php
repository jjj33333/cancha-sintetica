@extends('layouts.organizador')

@section('header_title', 'Editar Partido')
@section('title', 'Editar Partido - Organizador')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-4 sm:mb-6">
            <a href="{{ route('organizador.partidos.index', ['torneo_id' => $partido->torneo_id]) }}" 
               class="inline-flex items-center text-xs sm:text-sm text-gray-600 hover:text-[#F59E0B] transition-colors py-1">
                <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver a partidos
            </a>
            <h1 class="text-xl sm:text-2xl font-bold text-[#065F46] mt-2 flex items-center gap-2 flex-wrap">
                <span>✏️</span> <span>Editar Partido</span>
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">{{ $partido->equipoLocal->nombre }} vs {{ $partido->equipoVisitante->nombre }}</p>
        </div>

        <form method="POST" action="{{ route('organizador.partidos.update', $partido) }}" 
              class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 space-y-5 sm:space-y-6">
            @csrf
            @method('PUT')

            <!-- Torneo (solo lectura) -->
            <div class="border-b border-gray-100 pb-5 sm:pb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                    <span class="text-lg sm:text-xl">🏆</span>
                    Torneo
                </h2>
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Torneo</label>
                    <input type="hidden" name="torneo_id" value="{{ $partido->torneo_id }}">
                    <input type="text" value="{{ $torneo->nombre }}" disabled
                           class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed text-base sm:text-sm">
                    <p class="text-[11px] sm:text-xs text-gray-500 mt-1">El torneo no se puede cambiar.</p>
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
                                <option value="{{ $equipo->id }}" {{ old('equipo_local_id', $partido->equipo_local_id) == $equipo->id ? 'selected' : '' }}>
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
                                <option value="{{ $equipo->id }}" {{ old('equipo_visitante_id', $partido->equipo_visitante_id) == $equipo->id ? 'selected' : '' }}>
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
                        <input type="date" name="fecha_partido" id="fecha_partido" value="{{ old('fecha_partido', $partido->fecha_partido->format('Y-m-d')) }}" required
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('fecha_partido') border-red-500 @enderror">
                        @error('fecha_partido') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="hora_inicio" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Hora de Inicio <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="hora_inicio" id="hora_inicio" value="{{ old('hora_inicio', \Carbon\Carbon::parse($partido->hora_inicio)->format('H:i')) }}" required
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('hora_inicio') border-red-500 @enderror">
                        @error('hora_inicio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2 md:col-span-1">
                        <label for="instalacion_id" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Cancha <span class="text-red-500">*</span>
                        </label>
                        <select name="instalacion_id" id="instalacion_id" required
                                class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('instalacion_id') border-red-500 @enderror">
                            <option value="">Seleccionar cancha...</option>
                            @foreach($instalaciones as $inst)
                                <option value="{{ $inst->id }}" {{ old('instalacion_id', $partido->instalacion_id) == $inst->id ? 'selected' : '' }}>
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
                        <input type="number" name="numero_jornada" id="numero_jornada" value="{{ old('numero_jornada', $partido->numero_jornada) }}" min="1"
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('numero_jornada') border-red-500 @enderror">
                        @error('numero_jornada') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="nombre_grupo" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Grupo (si aplica)
                        </label>
                        <input type="text" name="nombre_grupo" id="nombre_grupo" value="{{ old('nombre_grupo', $partido->nombre_grupo) }}" placeholder="Ej: Grupo A"
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('nombre_grupo') border-red-500 @enderror">
                        @error('nombre_grupo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2 md:col-span-1">
                        <label for="arbitro" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Árbitro
                        </label>
                        <input type="text" name="arbitro" id="arbitro" value="{{ old('arbitro', $partido->arbitro) }}" placeholder="Nombre del árbitro"
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('arbitro') border-red-500 @enderror">
                        @error('arbitro') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="col-span-1 sm:col-span-2 md:col-span-3">
                        <label for="notas" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Notas (Opcional)
                        </label>
                        <textarea name="notas" id="notas" rows="2"
                                  class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('notas') border-red-500 @enderror">{{ old('notas', $partido->notas) }}</textarea>
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
                        <option value="programado" {{ old('estado', $partido->estado) === 'programado' ? 'selected' : '' }}>📅 Programado</option>
                        <option value="en_curso" {{ old('estado', $partido->estado) === 'en_curso' ? 'selected' : '' }}>▶️ En curso</option>
                        <option value="aplazado" {{ old('estado', $partido->estado) === 'aplazado' ? 'selected' : '' }}>⏸️ Aplazado</option>
                        <option value="cancelado" {{ old('estado', $partido->estado) === 'cancelado' ? 'selected' : '' }}>❌ Cancelado</option>
                    </select>
                    @error('estado') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('organizador.partidos.index', ['torneo_id' => $partido->torneo_id]) }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center min-h-[44px] px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-xs sm:text-sm">
                    Cancelar
                </a>
                <button type="submit" 
                        class="w-full sm:w-auto inline-flex items-center justify-center min-h-[44px] px-6 py-2.5 bg-[#F59E0B] hover:bg-[#D97706] text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all text-xs sm:text-sm">
                    💾 Actualizar Partido
                </button>
            </div>
        </form>
    </div>
@endsection
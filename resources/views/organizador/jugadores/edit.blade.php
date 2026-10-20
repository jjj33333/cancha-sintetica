@extends('layouts.organizador')

@section('header_title', 'Editar Jugador')
@section('title', 'Editar Jugador - Organizador')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-4 sm:mb-6">
            <a href="{{ route('organizador.jugadores.index') }}" 
               class="inline-flex items-center text-xs sm:text-sm text-gray-600 hover:text-[#F59E0B] transition-colors py-1">
                <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a jugadores
            </a>
            <h1 class="text-xl sm:text-2xl font-bold text-[#065F46] mt-2 flex items-center gap-2 flex-wrap">
                <span>✏️</span> <span>Editar Jugador:</span> <span class="break-words">{{ $jugador->nombre_completo }}</span>
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Equipo: {{ $equipo->nombre }} | Torneo: {{ $equipo->torneo->nombre }}</p>
        </div>

        <form method="POST" action="{{ route('organizador.jugadores.update', $jugador) }}" 
              class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 space-y-5 sm:space-y-6">
            @csrf
            @method('PUT')

            <!-- Asignación (Solo lectura) -->
            <div class="border-b border-gray-100 pb-5 sm:pb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                    <span class="text-lg sm:text-xl">🏆</span>
                    Asignación
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 sm:gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Equipo</label>
                        <input type="hidden" name="equipo_id" value="{{ $jugador->equipo_id }}">
                        <input type="text" value="{{ $equipo->nombre }} ({{ $equipo->torneo->nombre }})" disabled
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed text-base sm:text-sm">
                        <p class="text-[11px] sm:text-xs text-gray-500 mt-1">El equipo no se puede cambiar. Si necesitas mover al jugador, elimínalo y créalo en el otro equipo.</p>
                    </div>
                </div>
            </div>

            <!-- Información Personal -->
            <div class="border-b border-gray-100 pb-5 sm:pb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                    <span class="text-lg sm:text-xl">👤</span>
                    Información Personal
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 sm:gap-4">
                    <div class="md:col-span-2">
                        <label for="nombre_completo" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Nombre Completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nombre_completo" id="nombre_completo" value="{{ old('nombre_completo', $jugador->nombre_completo) }}" required
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('nombre_completo') border-red-500 @enderror">
                        @error('nombre_completo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="dni" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            DNI / Documento
                        </label>
                        <input type="text" name="dni" id="dni" value="{{ old('dni', $jugador->dni) }}"
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('dni') border-red-500 @enderror">
                        @error('dni') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="fecha_nacimiento" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Fecha de Nacimiento
                        </label>
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="{{ old('fecha_nacimiento', $jugador->fecha_nacimiento?->format('Y-m-d')) }}"
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('fecha_nacimiento') border-red-500 @enderror">
                        @error('fecha_nacimiento') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="telefono" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Teléfono
                        </label>
                        <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $jugador->telefono) }}"
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('telefono') border-red-500 @enderror">
                        @error('telefono') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Email (Opcional)
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', $jugador->email) }}"
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Información Deportiva -->
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                    <span class="text-lg sm:text-xl">⚽</span>
                    Información Deportiva
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5 sm:gap-4">
                    <div>
                        <label for="posicion" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Posición
                        </label>
                        <select name="posicion" id="posicion"
                                class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('posicion') border-red-500 @enderror">
                            <option value="">Seleccionar...</option>
                            <option value="Portero" {{ old('posicion', $jugador->posicion) === 'Portero' ? 'selected' : '' }}>Portero</option>
                            <option value="Defensa" {{ old('posicion', $jugador->posicion) === 'Defensa' ? 'selected' : '' }}>Defensa</option>
                            <option value="Mediocampista" {{ old('posicion', $jugador->posicion) === 'Mediocampista' ? 'selected' : '' }}>Mediocampista</option>
                            <option value="Delantero" {{ old('posicion', $jugador->posicion) === 'Delantero' ? 'selected' : '' }}>Delantero</option>
                        </select>
                        @error('posicion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="numero_camiseta" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Número de Camiseta
                        </label>
                        <input type="number" name="numero_camiseta" id="numero_camiseta" value="{{ old('numero_camiseta', $jugador->numero_camiseta) }}" min="1" max="99"
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('numero_camiseta') border-red-500 @enderror">
                        @error('numero_camiseta') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-end">
                        <label class="flex items-center cursor-pointer w-full min-h-[44px] p-3 bg-[#F9FAFB] rounded-lg hover:bg-[#FEF3C7] transition-colors border border-gray-200">
                            <input type="checkbox" name="es_capitan" value="1" {{ old('es_capitan', $jugador->es_capitan) ? 'checked' : '' }} 
                                   class="w-4 h-4 text-[#F59E0B] border-gray-300 rounded focus:ring-[#F59E0B]">
                            <span class="ml-2 text-xs sm:text-sm font-medium text-gray-700">Es el capitán del equipo</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('organizador.jugadores.index') }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center min-h-[44px] px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-xs sm:text-sm">
                    Cancelar
                </a>
                <button type="submit" 
                        class="w-full sm:w-auto inline-flex items-center justify-center min-h-[44px] px-6 py-2.5 bg-[#F59E0B] hover:bg-[#D97706] text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all text-xs sm:text-sm">
                    💾 Actualizar Jugador
                </button>
            </div>
        </form>
    </div>
@endsection
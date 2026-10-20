@extends('layouts.organizador')

@section('header_title', 'Agregar Nuevo Jugador')
@section('title', 'Crear Jugador - Organizador')

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
            <h1 class="text-xl sm:text-2xl font-bold text-[#065F46] mt-2">⚽ Agregar Nuevo Jugador</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Registra un nuevo jugador y asígnalo a un equipo.</p>
        </div>

        <form method="POST" action="{{ route('organizador.jugadores.store') }}" 
              class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 space-y-5 sm:space-y-6">
            @csrf

            <!-- Asignación -->
            <div class="border-b border-gray-100 pb-5 sm:pb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                    <span class="text-lg sm:text-xl">🏆</span>
                    Asignación
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 sm:gap-4">
                    <div class="md:col-span-2">
                        <label for="equipo_id" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Equipo <span class="text-red-500">*</span>
                        </label>
                        <select name="equipo_id" id="equipo_id" required
                                class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('equipo_id') border-red-500 @enderror">
                            <option value="">Seleccionar equipo...</option>
                            @foreach($equipos as $equipo)
                                <option value="{{ $equipo->id }}" {{ old('equipo_id') == $equipo->id ? 'selected' : '' }}>
                                    {{ $equipo->nombre }} ({{ $equipo->torneo->nombre }})
                                </option>
                            @endforeach
                        </select>
                        @error('equipo_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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
                        <input type="text" name="nombre_completo" id="nombre_completo" value="{{ old('nombre_completo') }}" required
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('nombre_completo') border-red-500 @enderror">
                        @error('nombre_completo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="dni" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            DNI / Documento
                        </label>
                        <input type="text" name="dni" id="dni" value="{{ old('dni') }}" placeholder="12345678"
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('dni') border-red-500 @enderror">
                        @error('dni') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="fecha_nacimiento" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Fecha de Nacimiento
                        </label>
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('fecha_nacimiento') border-red-500 @enderror">
                        @error('fecha_nacimiento') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="telefono" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Teléfono
                        </label>
                        <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}" placeholder="999-999-999"
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('telefono') border-red-500 @enderror">
                        @error('telefono') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Email (Opcional)
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="jugador@email.com"
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
                            <option value="Portero" {{ old('posicion') === 'Portero' ? 'selected' : '' }}>Portero</option>
                            <option value="Defensa" {{ old('posicion') === 'Defensa' ? 'selected' : '' }}>Defensa</option>
                            <option value="Mediocampista" {{ old('posicion') === 'Mediocampista' ? 'selected' : '' }}>Mediocampista</option>
                            <option value="Delantero" {{ old('posicion') === 'Delantero' ? 'selected' : '' }}>Delantero</option>
                        </select>
                        @error('posicion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="numero_camiseta" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Número de Camiseta
                        </label>
                        <input type="number" name="numero_camiseta" id="numero_camiseta" value="{{ old('numero_camiseta') }}" min="1" max="99"
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('numero_camiseta') border-red-500 @enderror">
                        @error('numero_camiseta') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-end">
                        <label class="flex items-center cursor-pointer w-full min-h-[44px] p-3 bg-[#F9FAFB] rounded-lg hover:bg-[#FEF3C7] transition-colors border border-gray-200">
                            <input type="checkbox" name="es_capitan" value="1" {{ old('es_capitan') ? 'checked' : '' }} 
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
                    💾 Registrar Jugador
                </button>
            </div>
        </form>
    </div>

    <script>
        // Script simple para cargar equipos según el torneo seleccionado (si se implementa en el futuro)
        // Por ahora, el select de equipos ya viene filtrado o vacío según la lógica del controlador.
    </script>
@endsection
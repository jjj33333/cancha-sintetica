@extends('layouts.organizador')

@section('header_title', 'Editar Equipo')
@section('title', 'Editar Equipo - Organizador')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-4 sm:mb-6">
            <a href="{{ route('organizador.equipos.index', ['torneo_id' => $equipo->torneo_id]) }}" 
               class="inline-flex items-center text-xs sm:text-sm text-gray-600 hover:text-[#F59E0B] transition-colors py-1">
                <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a equipos
            </a>
            <h1 class="text-xl sm:text-2xl font-bold text-[#065F46] mt-2 flex items-center gap-2 flex-wrap">
                <span>✏️</span> <span>Editar Equipo:</span> <span class="break-words">{{ $equipo->nombre }}</span>
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Torneo: {{ $torneo->nombre }}</p>
        </div>

        <form method="POST" action="{{ route('organizador.equipos.update', $equipo) }}" enctype="multipart/form-data" 
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
                    <input type="hidden" name="torneo_id" value="{{ $equipo->torneo_id }}">
                    <input type="text" value="{{ $torneo->nombre }}" disabled
                           class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed text-base sm:text-sm">
                    <p class="text-[11px] sm:text-xs text-gray-500 mt-1">El torneo no se puede cambiar.</p>
                </div>
            </div>

            <!-- Información del Equipo -->
            <div class="border-b border-gray-100 pb-5 sm:pb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                    <span class="text-lg sm:text-xl">🛡️</span>
                    Información del Equipo
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 sm:gap-4">
                    <div class="md:col-span-2">
                        <label for="nombre" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Nombre del Equipo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $equipo->nombre) }}" required
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('nombre') border-red-500 @enderror">
                        @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="nombre_corto" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Nombre Corto (Abreviatura)
                        </label>
                        <input type="text" name="nombre_corto" id="nombre_corto" value="{{ old('nombre_corto', $equipo->nombre_corto) }}" maxlength="50"
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('nombre_corto') border-red-500 @enderror">
                        @error('nombre_corto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="color_uniforme" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Color del Uniforme
                        </label>
                        <input type="text" name="color_uniforme" id="color_uniforme" value="{{ old('color_uniforme', $equipo->color_uniforme) }}" 
                               placeholder="Ej: Rojo, Azul y Blanco"
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('color_uniforme') border-red-500 @enderror">
                        @error('color_uniforme') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="estado" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Estado <span class="text-red-500">*</span>
                        </label>
                        <select name="estado" id="estado" required
                                class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('estado') border-red-500 @enderror">
                            <option value="registrado" {{ old('estado', $equipo->estado) === 'registrado' ? 'selected' : '' }}>Registrado</option>
                            <option value="confirmado" {{ old('estado', $equipo->estado) === 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                            <option value="campeon" {{ old('estado', $equipo->estado) === 'campeon' ? 'selected' : '' }}>🏆 Campeón</option>
                            <option value="subcampeon" {{ old('estado', $equipo->estado) === 'subcampeon' ? 'selected' : '' }}>🥈 Subcampeón</option>
                            <option value="eliminado" {{ old('estado', $equipo->estado) === 'eliminado' ? 'selected' : '' }}>Eliminado</option>
                        </select>
                        @error('estado') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="logo" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Logo del Equipo
                        </label>
                        @if($equipo->logo)
                            <div class="mb-2">
                                <img src="{{ Storage::url($equipo->logo) }}" alt="Logo actual" 
                                     class="w-14 h-14 sm:w-16 sm:h-16 rounded-full object-cover border border-gray-200">
                                <p class="text-[11px] sm:text-xs text-gray-500 mt-1">Logo actual. Sube uno nuevo para reemplazarlo.</p>
                            </div>
                        @endif
                        <input type="file" name="logo" id="logo" accept="image/*"
                               class="w-full px-3.5 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-xs sm:text-sm file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 @error('logo') border-red-500 @enderror">
                        @error('logo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Información del Capitán -->
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                    <span class="text-lg sm:text-xl">👤</span>
                    Información del Capitán
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 sm:gap-4">
                    <div>
                        <label for="nombre_capitan" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Nombre Completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nombre_capitan" id="nombre_capitan" value="{{ old('nombre_capitan', $equipo->nombre_capitan) }}" required
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('nombre_capitan') border-red-500 @enderror">
                        @error('nombre_capitan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="telefono_capitan" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Teléfono <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="telefono_capitan" id="telefono_capitan" value="{{ old('telefono_capitan', $equipo->telefono_capitan) }}" required
                               placeholder="999-999-999"
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('telefono_capitan') border-red-500 @enderror">
                        @error('telefono_capitan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="email_capitan" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                            Email (Opcional)
                        </label>
                        <input type="email" name="email_capitan" id="email_capitan" value="{{ old('email_capitan', $equipo->email_capitan) }}"
                               placeholder="capitan@equipo.com"
                               class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm @error('email_capitan') border-red-500 @enderror">
                        @error('email_capitan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('organizador.equipos.index', ['torneo_id' => $equipo->torneo_id]) }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center min-h-[44px] px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-xs sm:text-sm">
                    Cancelar
                </a>
                <button type="submit" 
                        class="w-full sm:w-auto inline-flex items-center justify-center min-h-[44px] px-6 py-2.5 bg-[#F59E0B] hover:bg-[#D97706] text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all text-xs sm:text-sm">
                    💾 Actualizar Equipo
                </button>
            </div>
        </form>
    </div>
@endsection
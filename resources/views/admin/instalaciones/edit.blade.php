@extends('layouts.admin')

@section('header_title', 'Editar Instalación')
@section('title', 'Editar Instalación - Admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.instalaciones.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-[#10B981] transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver a la lista
            </a>
            <h1 class="text-xl sm:text-2xl font-bold text-[#065F46] mt-2">✏️ Editar Instalación: {{ $instalacion->nombre }}</h1>
        </div>

        <form method="POST" action="{{ route('admin.instalaciones.update', $instalacion) }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Información General -->
            <div class="border-b border-gray-100 pb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Información General</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="nombre" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Nombre de la Instalación <span class="text-red-500">*</span></label>
                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $instalacion->nombre) }}" required
                               class="w-full px-3.5 py-2.5 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('nombre') border-red-500 @enderror">
                        @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tipo" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Tipo de Instalación <span class="text-red-500">*</span></label>
                        <select name="tipo" id="tipo" required
                                class="w-full px-3.5 py-2.5 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white @error('tipo') border-red-500 @enderror">
                            <option value="">Seleccionar...</option>
                            <option value="sintetica" {{ old('tipo', $instalacion->tipo) === 'sintetica' ? 'selected' : '' }}>Sintética</option>
                            <option value="natural" {{ old('tipo', $instalacion->tipo) === 'natural' ? 'selected' : '' }}>Natural</option>
                            <option value="mixta" {{ old('tipo', $instalacion->tipo) === 'mixta' ? 'selected' : '' }}>Mixta</option>
                            <option value="indoor" {{ old('tipo', $instalacion->tipo) === 'indoor' ? 'selected' : '' }}>Indoor (Cubierta)</option>
                            <option value="outdoor" {{ old('tipo', $instalacion->tipo) === 'outdoor' ? 'selected' : '' }}>Outdoor (Aire libre)</option>
                        </select>
                        @error('tipo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="capacidad" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Capacidad (jugadores) <span class="text-red-500">*</span></label>
                        <input type="number" name="capacidad" id="capacidad" value="{{ old('capacidad', $instalacion->capacidad) }}" min="1" max="100" required
                               class="w-full px-3.5 py-2.5 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('capacidad') border-red-500 @enderror">
                        @error('capacidad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="dimensiones" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Dimensiones</label>
                        <input type="text" name="dimensiones" id="dimensiones" value="{{ old('dimensiones', $instalacion->dimensiones) }}" placeholder="Ej: 40m x 20m"
                               class="w-full px-3.5 py-2.5 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('dimensiones') border-red-500 @enderror">
                        @error('dimensiones') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tipo_superficie" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Tipo de Superficie</label>
                        <input type="text" name="tipo_superficie" id="tipo_superficie" value="{{ old('tipo_superficie', $instalacion->tipo_superficie) }}" placeholder="Ej: FIFA Quality Pro"
                               class="w-full px-3.5 py-2.5 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('tipo_superficie') border-red-500 @enderror">
                        @error('tipo_superficie') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="descripcion" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea name="descripcion" id="descripcion" rows="3"
                                  class="w-full px-3.5 py-2.5 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('descripcion') border-red-500 @enderror">{{ old('descripcion', $instalacion->descripcion) }}</textarea>
                        @error('descripcion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Características -->
            <div class="border-b border-gray-100 pb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Características</h2>
                <div class="space-y-4">
                    <!-- Toggle: Iluminación -->
                    <div class="flex items-center justify-between p-3 sm:p-4 bg-[#F9FAFB] rounded-lg gap-3">
                        <div>
                            <label class="text-xs sm:text-sm font-medium text-gray-700">💡 Tiene iluminación</label>
                            <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5">La instalación cuenta con sistema de iluminación para juegos nocturnos.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="checkbox" name="tiene_iluminacion" value="1" {{ old('tiene_iluminacion', $instalacion->tiene_iluminacion) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#10B981]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#10B981]"></div>
                        </label>
                    </div>

                    <!-- Toggle: Estacionamiento -->
                    <div class="flex items-center justify-between p-3 sm:p-4 bg-[#F9FAFB] rounded-lg gap-3">
                        <div>
                            <label class="text-xs sm:text-sm font-medium text-gray-700">🚗 Tiene estacionamiento</label>
                            <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5">La instalación cuenta con área de estacionamiento para los clientes.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="checkbox" name="tiene_estacionamiento" value="1" {{ old('tiene_estacionamiento', $instalacion->tiene_estacionamiento) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#10B981]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#10B981]"></div>
                        </label>
                    </div>

                    <!-- Toggle: Casilleros -->
                    <div class="flex items-center justify-between p-3 sm:p-4 bg-[#F9FAFB] rounded-lg gap-3">
                        <div>
                            <label class="text-xs sm:text-sm font-medium text-gray-700">🚿 Tiene casilleros</label>
                            <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5">La instalación cuenta con casilleros y duchas disponibles.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="checkbox" name="tiene_casilleros" value="1" {{ old('tiene_casilleros', $instalacion->tiene_casilleros) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#10B981]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#10B981]"></div>
                        </label>
                    </div>

                    <!-- Toggle: Está Activo -->
                    <div class="flex items-center justify-between p-3 sm:p-4 bg-[#F9FAFB] rounded-lg gap-3">
                        <div>
                            <label class="text-xs sm:text-sm font-medium text-gray-700">✅ Instalación activa</label>
                            <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5">Si está desactivado, la instalación no estará disponible para reservas.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="checkbox" name="esta_activo" value="1" {{ old('esta_activo', $instalacion->esta_activo) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#10B981]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#10B981]"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.instalaciones.index') }}" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-center text-sm">
                    Cancelar
                </a>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-[#10B981] hover:bg-[#059669] text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all text-center text-sm">
                    Actualizar Instalación
                </button>
            </div>
        </form>
    </div>
@endsection
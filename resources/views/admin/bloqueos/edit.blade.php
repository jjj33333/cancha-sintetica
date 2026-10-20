@extends('layouts.admin')

@section('header_title', 'Editar Bloqueo')
@section('title', 'Editar Bloqueo - Admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.bloqueos.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-[#10B981] transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver a la lista
            </a>
            <h1 class="text-2xl font-bold text-[#065F46] mt-2">✏️ Editar Bloqueo</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $bloqueo->fecha_bloqueo->format('d/m/Y') }} - {{ $bloqueo->motivo_texto }}</p>
        </div>

        <form method="POST" action="{{ route('admin.bloqueos.update', $bloqueo) }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Información General -->
            <div class="border-b border-gray-100 pb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Información General</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="instalacion_id" class="block text-sm font-medium text-gray-700 mb-1">Instalación <span class="text-red-500">*</span></label>
                        <select name="instalacion_id" id="instalacion_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('instalacion_id') border-red-500 @enderror">
                            <option value="">Seleccionar instalación...</option>
                            @foreach($instalaciones as $inst)
                                <option value="{{ $inst->id }}" {{ old('instalacion_id', $bloqueo->instalacion_id) == $inst->id ? 'selected' : '' }}>{{ $inst->nombre }}</option>
                            @endforeach
                        </select>
                        @error('instalacion_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="motivo" class="block text-sm font-medium text-gray-700 mb-1">Motivo del Bloqueo <span class="text-red-500">*</span></label>
                        <select name="motivo" id="motivo" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('motivo') border-red-500 @enderror">
                            <option value="">Seleccionar motivo...</option>
                            <option value="mantenimiento" {{ old('motivo', $bloqueo->motivo) === 'mantenimiento' ? 'selected' : '' }}>🔧 Mantenimiento</option>
                            <option value="evento" {{ old('motivo', $bloqueo->motivo) === 'evento' ? 'selected' : '' }}>🎉 Evento</option>
                            <option value="feriado" {{ old('motivo', $bloqueo->motivo) === 'feriado' ? 'selected' : '' }}>🏖️ Feriado</option>
                            <option value="otro" {{ old('motivo', $bloqueo->motivo) === 'otro' ? 'selected' : '' }}>📋 Otro</option>
                        </select>
                        @error('motivo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="fecha_bloqueo" class="block text-sm font-medium text-gray-700 mb-1">Fecha del Bloqueo <span class="text-red-500">*</span></label>
                        <input type="date" name="fecha_bloqueo" id="fecha_bloqueo" value="{{ old('fecha_bloqueo', $bloqueo->fecha_bloqueo->format('Y-m-d')) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('fecha_bloqueo') border-red-500 @enderror">
                        @error('fecha_bloqueo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-2 md:pt-6">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="dia_completo" id="dia_completo" value="1" {{ $bloqueo->es_dia_completo ? 'checked' : '' }} 
                                   class="w-4 h-4 text-[#10B981] border-gray-300 rounded focus:ring-[#10B981]"
                                   onchange="toggleHoras()">
                            <span class="ml-2 text-sm font-medium text-gray-700">Bloquear todo el día</span>
                        </label>
                    </div>

                    <div>
                        <label for="hora_inicio" class="block text-sm font-medium text-gray-700 mb-1">Hora de Inicio <span class="text-red-500">*</span></label>
                        <input type="time" name="hora_inicio" id="hora_inicio" value="{{ old('hora_inicio', \Carbon\Carbon::parse($bloqueo->hora_inicio)->format('H:i')) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('hora_inicio') border-red-500 @enderror">
                        @error('hora_inicio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="hora_fin" class="block text-sm font-medium text-gray-700 mb-1">Hora de Fin <span class="text-red-500">*</span></label>
                        <input type="time" name="hora_fin" id="hora_fin" value="{{ old('hora_fin', \Carbon\Carbon::parse($bloqueo->hora_fin)->format('H:i')) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('hora_fin') border-red-500 @enderror">
                        @error('hora_fin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción (Opcional)</label>
                        <textarea name="descripcion" id="descripcion" rows="3" placeholder="Describe el motivo del bloqueo..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('descripcion') border-red-500 @enderror">{{ old('descripcion', $bloqueo->descripcion) }}</textarea>
                        @error('descripcion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Configuración -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Configuración</h2>
                <div class="flex items-center justify-between p-4 bg-[#F9FAFB] rounded-lg">
                    <div>
                        <label class="text-sm font-medium text-gray-700">✅ Bloqueo activo</label>
                        <p class="text-xs text-gray-500 mt-1">Si está desactivado, el bloqueo no se aplicará.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="esta_activo" value="1" {{ old('esta_activo', $bloqueo->esta_activo) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#10B981]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#10B981]"></div>
                    </label>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.bloqueos.index') }}" class="w-full sm:w-auto text-center px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-[#10B981] hover:bg-[#059669] text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                    Actualizar Bloqueo
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleHoras() {
            const checkbox = document.getElementById('dia_completo');
            const horaInicio = document.getElementById('hora_inicio');
            const horaFin = document.getElementById('hora_fin');
            
            if (checkbox.checked) {
                horaInicio.value = '00:00';
                horaFin.value = '23:59';
                horaInicio.disabled = true;
                horaFin.disabled = true;
                horaInicio.classList.add('bg-gray-100', 'cursor-not-allowed');
                horaFin.classList.add('bg-gray-100', 'cursor-not-allowed');
            } else {
                horaInicio.value = '';
                horaFin.value = '';
                horaInicio.disabled = false;
                horaFin.disabled = false;
                horaInicio.classList.remove('bg-gray-100', 'cursor-not-allowed');
                horaFin.classList.remove('bg-gray-100', 'cursor-not-allowed');
            }
        }

        // Ejecutar al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            toggleHoras();
        });
    </script>
@endsection
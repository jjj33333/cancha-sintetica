@extends('layouts.admin')

@section('header_title', 'Crear Nuevo Torneo')
@section('title', 'Crear Torneo - Admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-4 sm:mb-6">
            <a href="{{ route('admin.torneos.index') }}" class="inline-flex items-center text-xs sm:text-sm text-gray-600 hover:text-[#10B981] transition-colors">
                <svg class="w-4 h-4 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver a la lista
            </a>
            <h1 class="text-xl sm:text-2xl font-bold text-[#065F46] mt-2">🏆 Crear Nuevo Torneo</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Completa la información para crear un nuevo torneo.</p>
        </div>

        <form method="POST" action="{{ route('admin.torneos.store') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 space-y-6">
            @csrf

            <!-- Información General -->
            <div class="border-b border-gray-100 pb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Información General</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="nombre" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Nombre del Torneo <span class="text-red-500">*</span></label>
                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required
                               class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('nombre') border-red-500 @enderror">
                        @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="descripcion" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea name="descripcion" id="descripcion" rows="3"
                                  class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('descripcion') border-red-500 @enderror">{{ old('descripcion') }}</textarea>
                        @error('descripcion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="instalacion_id" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Instalación <span class="text-red-500">*</span></label>
                        <select name="instalacion_id" id="instalacion_id" required
                                class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('instalacion_id') border-red-500 @enderror">
                            <option value="">Seleccionar...</option>
                            @foreach($instalaciones as $inst)
                                <option value="{{ $inst->id }}" {{ old('instalacion_id') == $inst->id ? 'selected' : '' }}>{{ $inst->nombre }}</option>
                            @endforeach
                        </select>
                        @error('instalacion_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tipo_torneo" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Tipo de Torneo <span class="text-red-500">*</span></label>
                        <select name="tipo_torneo" id="tipo_torneo" required
                                class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('tipo_torneo') border-red-500 @enderror">
                            <option value="liga" {{ old('tipo_torneo') === 'liga' ? 'selected' : '' }}>Liga</option>
                            <option value="eliminacion_directa" {{ old('tipo_torneo') === 'eliminacion_directa' ? 'selected' : '' }}>Eliminación Directa</option>
                            <option value="fase_grupos" {{ old('tipo_torneo') === 'fase_grupos' ? 'selected' : '' }}>Fase de Grupos</option>
                        </select>
                        @error('tipo_torneo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="categoria" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Categoría <span class="text-red-500">*</span></label>
                        <select name="categoria" id="categoria" required
                                class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('categoria') border-red-500 @enderror">
                            <option value="libre" {{ old('categoria') === 'libre' ? 'selected' : '' }}>Libre</option>
                            <option value="sub20" {{ old('categoria') === 'sub20' ? 'selected' : '' }}>Sub-20</option>
                            <option value="sub18" {{ old('categoria') === 'sub18' ? 'selected' : '' }}>Sub-18</option>
                            <option value="femenino" {{ old('categoria') === 'femenino' ? 'selected' : '' }}>Femenino</option>
                            <option value="mixto" {{ old('categoria') === 'mixto' ? 'selected' : '' }}>Mixto</option>
                        </select>
                        @error('categoria') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="estado" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Estado Inicial <span class="text-red-500">*</span></label>
                        <select name="estado" id="estado" required
                                class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('estado') border-red-500 @enderror">
                            <option value="borrador" {{ old('estado') === 'borrador' ? 'selected' : '' }}>Borrador</option>
                            <option value="inscripciones_abiertas" {{ old('estado') === 'inscripciones_abiertas' ? 'selected' : '' }}>Inscripciones Abiertas</option>
                            <option value="en_curso" {{ old('estado') === 'en_curso' ? 'selected' : '' }}>En Curso</option>
                        </select>
                        @error('estado') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Fechas -->
            <div class="border-b border-gray-100 pb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Fechas</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="fecha_inicio" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Fecha de Inicio <span class="text-red-500">*</span></label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ old('fecha_inicio') }}" required
                               class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('fecha_inicio') border-red-500 @enderror">
                        @error('fecha_inicio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="fecha_fin" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Fecha de Fin <span class="text-red-500">*</span></label>
                        <input type="date" name="fecha_fin" id="fecha_fin" value="{{ old('fecha_fin') }}" required
                               class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('fecha_fin') border-red-500 @enderror">
                        @error('fecha_fin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="inscripcion_inicio" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Inicio de Inscripciones <span class="text-red-500">*</span></label>
                        <input type="date" name="inscripcion_inicio" id="inscripcion_inicio" value="{{ old('inscripcion_inicio') }}" required
                               class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('inscripcion_inicio') border-red-500 @enderror">
                        @error('inscripcion_inicio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="inscripcion_fin" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Fin de Inscripciones <span class="text-red-500">*</span></label>
                        <input type="date" name="inscripcion_fin" id="inscripcion_fin" value="{{ old('inscripcion_fin') }}" required
                               class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('inscripcion_fin') border-red-500 @enderror">
                        @error('inscripcion_fin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Configuración de Equipos -->
            <div class="border-b border-gray-100 pb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Configuración de Equipos</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="min_equipos" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Mínimo Equipos <span class="text-red-500">*</span></label>
                        <input type="number" name="min_equipos" id="min_equipos" value="{{ old('min_equipos', 4) }}" min="2" required
                               class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('min_equipos') border-red-500 @enderror">
                        @error('min_equipos') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="max_equipos" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Máximo Equipos <span class="text-red-500">*</span></label>
                        <input type="number" name="max_equipos" id="max_equipos" value="{{ old('max_equipos', 8) }}" min="2" required
                               class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('max_equipos') border-red-500 @enderror">
                        @error('max_equipos') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="jugadores_por_equipo" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Jugadores por Equipo <span class="text-red-500">*</span></label>
                        <input type="number" name="jugadores_por_equipo" id="jugadores_por_equipo" value="{{ old('jugadores_por_equipo', 10) }}" min="5" required
                               class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('jugadores_por_equipo') border-red-500 @enderror">
                        @error('jugadores_por_equipo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Costos y Premios -->
            <div class="border-b border-gray-100 pb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Costos y Premios</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="costo_inscripcion" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Costo de Inscripción (Q.)</label>
                        <input type="number" name="costo_inscripcion" id="costo_inscripcion" value="{{ old('costo_inscripcion') }}" step="0.01" min="0"
                               class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('costo_inscripcion') border-red-500 @enderror">
                        @error('costo_inscripcion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="descripcion_premios" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Descripción de Premios</label>
                        <textarea name="descripcion_premios" id="descripcion_premios" rows="2"
                                  class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('descripcion_premios') border-red-500 @enderror">{{ old('descripcion_premios') }}</textarea>
                        @error('descripcion_premios') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Asignación y Reglas -->
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Asignación y Reglas</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="organizador_asignado_id" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Asignar Organizador</label>
                        <select name="organizador_asignado_id" id="organizador_asignado_id"
                                class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('organizador_asignado_id') border-red-500 @enderror">
                            <option value="">Sin asignar</option>
                            @foreach($organizadores as $org)
                                <option value="{{ $org->id }}" {{ old('organizador_asignado_id') == $org->id ? 'selected' : '' }}>{{ $org->nombre }}</option>
                            @endforeach
                        </select>
                        @error('organizador_asignado_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="reglas" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Reglas del Torneo</label>
                        <textarea name="reglas" id="reglas" rows="4"
                                  class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('reglas') border-red-500 @enderror">{{ old('reglas') }}</textarea>
                        @error('reglas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.torneos.index') }}" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-center text-sm">
                    Cancelar
                </a>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-[#10B981] hover:bg-[#059669] text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all text-center text-sm">
                    Crear Torneo
                </button>
            </div>
        </form>
    </div>
@endsection
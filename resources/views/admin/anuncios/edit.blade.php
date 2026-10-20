@extends('layouts.admin')

@section('header_title', 'Editar Anuncio')
@section('title', 'Editar Anuncio - Admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.anuncios.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-[#10B981] transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver a la lista
            </a>
            <h1 class="text-2xl font-bold text-[#065F46] mt-2 truncate">Editar Anuncio: {{ $anuncio->titulo }}</h1>
        </div>

        <form method="POST" action="{{ route('admin.anuncios.update', $anuncio) }}" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 md:p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Información General -->
            <div class="border-b border-gray-100 pb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Información General</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título <span class="text-red-500">*</span></label>
                        <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $anuncio->titulo) }}" required
                               class="w-full px-4 py-2.5 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('titulo') border-red-500 @enderror">
                        @error('titulo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="contenido" class="block text-sm font-medium text-gray-700 mb-1">Contenido <span class="text-red-500">*</span></label>
                        <textarea name="contenido" id="contenido" rows="5" required
                                  class="w-full px-4 py-2.5 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('contenido') border-red-500 @enderror">{{ old('contenido', $anuncio->contenido) }}</textarea>
                        @error('contenido') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">Tipo <span class="text-red-500">*</span></label>
                        <select name="tipo" id="tipo" required
                                class="w-full px-4 py-2.5 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white @error('tipo') border-red-500 @enderror">
                            <option value="noticia" {{ old('tipo', $anuncio->tipo) === 'noticia' ? 'selected' : '' }}>📰 Noticia</option>
                            <option value="promocion" {{ old('tipo', $anuncio->tipo) === 'promocion' ? 'selected' : '' }}>🏷️ Promoción</option>
                            <option value="alerta" {{ old('tipo', $anuncio->tipo) === 'alerta' ? 'selected' : '' }}>⚠️ Alerta</option>
                            <option value="evento" {{ old('tipo', $anuncio->tipo) === 'evento' ? 'selected' : '' }}>🎉 Evento</option>
                            <option value="general" {{ old('tipo', $anuncio->tipo) === 'general' ? 'selected' : '' }}>📢 General</option>
                        </select>
                        @error('tipo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="prioridad" class="block text-sm font-medium text-gray-700 mb-1">Prioridad <span class="text-red-500">*</span></label>
                        <select name="prioridad" id="prioridad" required
                                class="w-full px-4 py-2.5 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white @error('prioridad') border-red-500 @enderror">
                            <option value="baja" {{ old('prioridad', $anuncio->prioridad) === 'baja' ? 'selected' : '' }}>Baja</option>
                            <option value="media" {{ old('prioridad', $anuncio->prioridad) === 'media' ? 'selected' : '' }}>Media</option>
                            <option value="alta" {{ old('prioridad', $anuncio->prioridad) === 'alta' ? 'selected' : '' }}>Alta</option>
                            <option value="urgente" {{ old('prioridad', $anuncio->prioridad) === 'urgente' ? 'selected' : '' }}>Urgente</option>
                        </select>
                        @error('prioridad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="imagen" class="block text-sm font-medium text-gray-700 mb-1">Imagen (Opcional)</label>
                        @if($anuncio->imagen)
                            <div class="mb-2 flex items-center gap-3">
                                <img src="{{ Storage::url($anuncio->imagen) }}" alt="{{ $anuncio->titulo }}" class="w-16 h-16 rounded-lg object-cover">
                                <span class="text-xs text-gray-500">Imagen actual</span>
                            </div>
                        @endif
                        <input type="file" name="imagen" id="imagen" accept="image/*"
                               class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('imagen') border-red-500 @enderror">
                        @error('imagen') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <p class="text-xs text-gray-500 mt-1">Formatos: JPG, PNG, GIF, WEBP. Máximo 2MB.</p>
                    </div>
                </div>
            </div>

            <!-- Fechas -->
            <div class="border-b border-gray-100 pb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Fechas</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="publicado_en" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Publicación</label>
                        <input type="date" name="publicado_en" id="publicado_en" value="{{ old('publicado_en', $anuncio->publicado_en?->format('Y-m-d')) }}"
                               class="w-full px-4 py-2.5 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('publicado_en') border-red-500 @enderror">
                        @error('publicado_en') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="expira_en" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Expiración</label>
                        <input type="date" name="expira_en" id="expira_en" value="{{ old('expira_en', $anuncio->expira_en?->format('Y-m-d')) }}"
                               class="w-full px-4 py-2.5 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('expira_en') border-red-500 @enderror">
                        @error('expira_en') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Configuración de Visibilidad -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Configuración de Visibilidad</h2>
                <div class="space-y-4">
                    <!-- Toggle: Mostrar en Público -->
                    <div class="flex items-center justify-between p-4 bg-[#F9FAFB] rounded-xl gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700 block">Mostrar en página pública</label>
                            <p class="text-xs text-gray-500 mt-0.5">Si está activado, será visible en la sección pública.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                            <input type="checkbox" name="mostrar_en_publico" value="1" {{ old('mostrar_en_publico', $anuncio->mostrar_en_publico) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#10B981]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#10B981]"></div>
                        </label>
                    </div>

                    <!-- Toggle: Está Publicado -->
                    <div class="flex items-center justify-between p-4 bg-[#F9FAFB] rounded-xl gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700 block">Publicar anuncio</label>
                            <p class="text-xs text-gray-500 mt-0.5">Si está desactivado, se guardará como borrador.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                            <input type="checkbox" name="esta_publicado" value="1" {{ old('esta_publicado', $anuncio->esta_publicado) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#10B981]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#10B981]"></div>
                        </label>
                    </div>

                    <!-- Toggle: Está Activo -->
                    <div class="flex items-center justify-between p-4 bg-[#F9FAFB] rounded-xl gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700 block">Anuncio activo</label>
                            <p class="text-xs text-gray-500 mt-0.5">Si está desactivado, no se mostrará en el sistema.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                            <input type="checkbox" name="esta_activo" value="1" {{ old('esta_activo', $anuncio->esta_activo) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#10B981]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#10B981]"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción Responsive -->
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.anuncios.index') }}" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-center text-sm">
                    Cancelar
                </a>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-[#10B981] hover:bg-[#059669] text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all transform hover:-translate-y-0.5 text-center text-sm">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
@endsection
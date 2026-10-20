@extends('layouts.admin')

@section('header_title', 'Editar Archivo de Galería')
@section('title', 'Editar Galería - Admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.galeria.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-[#10B981] transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver a la galería
            </a>
            <h1 class="text-2xl font-bold text-[#065F46] mt-2">✏️ Editar Archivo</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Izquierda: Vista Previa Actual -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sticky top-24">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Archivo Actual</h3>
                    <div class="aspect-video bg-gray-100 rounded-lg overflow-hidden mb-3">
                        @if($galeria->tipo_archivo === 'video')
                            <video src="{{ $galeria->url_archivo }}" controls class="w-full h-full object-cover"></video>
                        @else
                            <img src="{{ $galeria->url_archivo }}" alt="{{ $galeria->titulo }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="space-y-2 text-xs text-gray-500">
                        <div class="flex justify-between">
                            <span>Tipo:</span>
                            <span class="font-medium text-gray-700 uppercase">{{ $galeria->tipo_archivo }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tamaño:</span>
                            <span class="font-medium text-gray-700">{{ $galeria->tamano_formateado }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Subido el:</span>
                            <span class="font-medium text-gray-700">{{ $galeria->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Formulario -->
            <div class="lg:col-span-2">
                <form method="POST" action="{{ route('admin.galeria.update', $galeria) }}" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Cambio de Archivo (Opcional) -->
                    <div class="border-b border-gray-100 pb-6">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Reemplazar Archivo (Opcional)</h2>
                        <div class="flex items-center justify-center w-full">
                            <label for="archivo" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors p-4 text-center">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    <p class="text-xs sm:text-sm text-gray-500"><span class="font-semibold">Haz clic para reemplazar</span></p>
                                    <p class="text-[11px] sm:text-xs text-gray-500">Se eliminará el archivo actual</p>
                                </div>
                                <input id="archivo" name="archivo" type="file" class="hidden" accept="image/*,video/*" onchange="previewFile()" />
                            </label>
                        </div>
                        @error('archivo') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                        
                        <div id="preview-container" class="hidden mt-4">
                            <p class="text-xs sm:text-sm font-medium text-gray-700 mb-2">Nuevo archivo:</p>
                            <div id="preview-content" class="rounded-lg overflow-hidden border border-gray-200 max-h-48"></div>
                        </div>
                    </div>

                    <!-- Información -->
                    <div class="border-b border-gray-100 pb-6">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Información</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label for="titulo" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Título</label>
                                <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $galeria->titulo) }}"
                                       class="w-full px-3.5 py-2.5 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('titulo') border-red-500 @enderror">
                                @error('titulo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="descripcion" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Descripción</label>
                                <textarea name="descripcion" id="descripcion" rows="3"
                                          class="w-full px-3.5 py-2.5 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('descripcion') border-red-500 @enderror">{{ old('descripcion', $galeria->descripcion) }}</textarea>
                                @error('descripcion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="categoria" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Categoría <span class="text-red-500">*</span></label>
                                <select name="categoria" id="categoria" required
                                        class="w-full px-3.5 py-2.5 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white @error('categoria') border-red-500 @enderror">
                                    <option value="instalacion" {{ old('categoria', $galeria->categoria) === 'instalacion' ? 'selected' : '' }}>Instalación</option>
                                    <option value="torneo" {{ old('categoria', $galeria->categoria) === 'torneo' ? 'selected' : '' }}>Torneo</option>
                                    <option value="evento" {{ old('categoria', $galeria->categoria) === 'evento' ? 'selected' : '' }}>Evento</option>
                                    <option value="general" {{ old('categoria', $galeria->categoria) === 'general' ? 'selected' : '' }}>General</option>
                                </select>
                                @error('categoria') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="orden_visualizacion" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Orden de visualización</label>
                                <input type="number" name="orden_visualizacion" id="orden_visualizacion" value="{{ old('orden_visualizacion', $galeria->orden_visualizacion) }}" min="0"
                                       class="w-full px-3.5 py-2.5 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('orden_visualizacion') border-red-500 @enderror">
                                @error('orden_visualizacion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Configuración -->
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Configuración</h2>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 sm:p-4 bg-[#F9FAFB] rounded-lg gap-3">
                                <div>
                                    <label class="text-xs sm:text-sm font-medium text-gray-700">⭐ Marcar como destacado</label>
                                    <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5">Los archivos destacados aparecerán primero.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                    <input type="checkbox" name="es_destacado" value="1" {{ old('es_destacado', $galeria->es_destacado) ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#10B981]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#10B981]"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-3 sm:p-4 bg-[#F9FAFB] rounded-lg gap-3">
                                <div>
                                    <label class="text-xs sm:text-sm font-medium text-gray-700">✅ Archivo activo</label>
                                    <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5">Si está desactivado, no se mostrará públicamente.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                    <input type="checkbox" name="esta_activo" value="1" {{ old('esta_activo', $galeria->esta_activo) ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#10B981]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#10B981]"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.galeria.index') }}" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-center text-sm">
                            Cancelar
                        </a>
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-[#10B981] hover:bg-[#059669] text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all text-center text-sm">
                            Actualizar Archivo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewFile() {
            const fileInput = document.getElementById('archivo');
            const previewContainer = document.getElementById('preview-container');
            const previewContent = document.getElementById('preview-content');
            
            if (fileInput.files && fileInput.files[0]) {
                const file = fileInput.files[0];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewContainer.classList.remove('hidden');
                    if (file.type.startsWith('image/')) {
                        previewContent.innerHTML = `<img src="${e.target.result}" class="w-full h-auto max-h-48 object-contain bg-gray-100">`;
                    } else if (file.type.startsWith('video/')) {
                        previewContent.innerHTML = `<video src="${e.target.result}" controls class="w-full h-auto max-h-48 bg-gray-100"></video>`;
                    }
                }
                
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
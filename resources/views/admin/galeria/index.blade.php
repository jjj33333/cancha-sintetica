@extends('layouts.admin')

@section('header_title', 'Galería de Imágenes')
@section('title', 'Galería - Admin')

@section('content')
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#065F46]">🖼️ Galería de Imágenes</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Gestiona las imágenes y videos del sitio web.</p>
        </div>
        <a href="{{ route('admin.galeria.create') }}" class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-[#10B981] hover:bg-[#059669] text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Subir Archivo
        </a>
    </div>

    <!-- Mensaje de éxito -->
    @if(session('success'))
        <div class="mb-6 bg-[#D1FAE5] border border-[#10B981] text-[#065F46] px-4 py-3 rounded-lg flex items-center gap-3 text-sm">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" action="{{ route('admin.galeria.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por título..." 
                       class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
            </div>
            <div class="w-full sm:w-44">
                <select name="categoria" class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white">
                    <option value="">Todas las categorías</option>
                    <option value="instalacion" {{ request('categoria') === 'instalacion' ? 'selected' : '' }}>Instalación</option>
                    <option value="torneo" {{ request('categoria') === 'torneo' ? 'selected' : '' }}>Torneo</option>
                    <option value="evento" {{ request('categoria') === 'evento' ? 'selected' : '' }}>Evento</option>
                    <option value="general" {{ request('categoria') === 'general' ? 'selected' : '' }}>General</option>
                </select>
            </div>
            <div class="w-full sm:w-36">
                <select name="destacados" class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white">
                    <option value="">Todos</option>
                    <option value="1" {{ request('destacados') === '1' ? 'selected' : '' }}>Destacados</option>
                </select>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none px-6 py-2 bg-[#065F46] hover:bg-[#047857] text-white font-medium rounded-lg transition-colors text-center text-sm">
                    Filtrar
                </button>
                @if(request('buscar') || request('categoria') || request('destacados'))
                    <a href="{{ route('admin.galeria.index') }}" class="flex-1 sm:flex-none px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors text-center text-sm">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Grid de Galería -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
        @forelse($imagenes as $item)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all hover:-translate-y-1 group">
                <!-- Contenedor de Imagen/Video -->
                <div class="relative aspect-video bg-gray-100 overflow-hidden">
                    @if($item->tipo_archivo === 'video')
                        <video src="{{ $item->url_archivo }}" class="w-full h-full object-cover" muted></video>
                        <div class="absolute inset-0 flex items-center justify-center bg-black/20">
                            <div class="w-12 h-12 bg-white/90 rounded-full flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-[#065F46] ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>
                    @else
                        <img src="{{ $item->url_archivo }}" alt="{{ $item->titulo }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    @endif

                    <!-- Badges superpuestos -->
                    <div class="absolute top-2 left-2 flex gap-2">
                        @if($item->es_destacado)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-[#FEF3C7] text-[#92400E] shadow-sm">
                                ⭐ Destacado
                            </span>
                        @endif
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $item->categoria_badge }} shadow-sm">
                            {{ $item->categoria_texto }}
                        </span>
                    </div>

                    <!-- Botón Eliminar (visible en móvil, hover en desktop) -->
                    <div class="absolute top-2 right-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                        <form action="{{ route('admin.galeria.destroy', $item) }}" method="POST" onsubmit="return confirm('¿Eliminar este archivo?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 bg-white/90 hover:bg-[#FEE2E2] text-gray-600 hover:text-[#EF4444] rounded-lg shadow-sm transition-colors" title="Eliminar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Contenido de la Card -->
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 truncate mb-1">{{ $item->titulo ?? 'Sin título' }}</h3>
                    @if($item->descripcion)
                        <p class="text-xs text-gray-500 line-clamp-2 mb-3">{{ $item->descripcion }}</p>
                    @else
                        <div class="h-4 mb-3"></div>
                    @endif
                    
                    <div class="flex items-center justify-between text-xs text-gray-400 pt-3 border-t border-gray-100">
                        <span>{{ $item->tamano_formateado }}</span>
                        <span>{{ $item->created_at->format('d/m/Y') }}</span>
                    </div>
                    
                    <!-- Botón Editar -->
                    <a href="{{ route('admin.galeria.edit', $item) }}" class="mt-3 w-full inline-flex items-center justify-center px-3 py-2 bg-gray-50 hover:bg-[#ECFDF5] text-gray-700 hover:text-[#065F46] text-xs font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Editar detalles
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <div class="text-gray-400 mb-3">
                        <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-gray-500 font-medium">No hay archivos en la galería aún.</p>
                    <p class="text-sm text-gray-400 mt-1">Comienza subiendo tu primera imagen o video.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($imagenes->hasPages())
        <div class="mt-8">
            {{ $imagenes->links() }}
        </div>
    @endif
@endsection
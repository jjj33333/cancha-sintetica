@extends('layouts.admin')

@section('header_title', 'Gestión de Anuncios')
@section('title', 'Anuncios - Admin')

@section('content')
    <!-- Encabezado Responsive -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#065F46]">📢 Anuncios</h1>
            <p class="text-sm text-gray-500 mt-1">
                ℹ️ Usa el interruptor para mostrar u ocultar anuncios en la página pública. Si todos están desactivados, la sección "Anuncios" no ocupará espacio.
            </p>
        </div>
        <a href="{{ route('admin.anuncios.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-[#10B981] hover:bg-[#059669] text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all transform hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nuevo Anuncio
        </a>
    </div>

    <!-- Mensaje de éxito -->
    @if(session('success'))
        <div class="mb-6 bg-[#D1FAE5] border border-[#10B981] text-[#065F46] px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Filtros Responsive -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" action="{{ route('admin.anuncios.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div class="sm:col-span-2 lg:col-span-1">
                <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar título o contenido..." 
                       class="w-full px-4 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
            </div>
            <div>
                <select name="tipo" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white">
                    <option value="">Todos los tipos</option>
                    <option value="noticia" {{ request('tipo') === 'noticia' ? 'selected' : '' }}>📰 Noticia</option>
                    <option value="promocion" {{ request('tipo') === 'promocion' ? 'selected' : '' }}>🏷️ Promoción</option>
                    <option value="alerta" {{ request('tipo') === 'alerta' ? 'selected' : '' }}>⚠️ Alerta</option>
                    <option value="evento" {{ request('tipo') === 'evento' ? 'selected' : '' }}>🎉 Evento</option>
                    <option value="general" {{ request('tipo') === 'general' ? 'selected' : '' }}>📢 General</option>
                </select>
            </div>
            <div>
                <select name="prioridad" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white">
                    <option value="">Todas las prioridades</option>
                    <option value="baja" {{ request('prioridad') === 'baja' ? 'selected' : '' }}>Baja</option>
                    <option value="media" {{ request('prioridad') === 'media' ? 'selected' : '' }}>Media</option>
                    <option value="alta" {{ request('prioridad') === 'alta' ? 'selected' : '' }}>Alta</option>
                    <option value="urgente" {{ request('prioridad') === 'urgente' ? 'selected' : '' }}>Urgente</option>
                </select>
            </div>
            <div>
                <select name="visibilidad" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white">
                    <option value="">Toda visibilidad</option>
                    <option value="visible" {{ request('visibilidad') === 'visible' ? 'selected' : '' }}>Visible</option>
                    <option value="oculto" {{ request('visibilidad') === 'oculto' ? 'selected' : '' }}>Oculto</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-[#065F46] hover:bg-[#047857] text-white text-sm font-semibold rounded-lg transition-colors text-center">
                    Filtrar
                </button>
                @if(request('buscar') || request('tipo') || request('prioridad') || request('visibilidad'))
                    <a href="{{ route('admin.anuncios.index') }}" class="p-2 text-gray-500 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Limpiar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Contenedor Principal de Anuncios -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        
        <!-- VISTA DE TARJETAS EN MÓVIL (block md:hidden) -->
        <div class="block md:hidden divide-y divide-gray-100">
            @forelse($anuncios as $anuncio)
                <div class="p-4 space-y-3 {{ !$anuncio->esta_activo ? 'opacity-60 bg-gray-50/50' : '' }}">
                    <div class="flex items-start gap-3">
                        @if($anuncio->imagen)
                            <img src="{{ Storage::url($anuncio->imagen) }}" alt="{{ $anuncio->titulo }}" class="w-14 h-14 rounded-lg object-cover flex-shrink-0">
                        @else
                            <div class="w-14 h-14 rounded-lg bg-gray-100 flex items-center justify-center text-2xl flex-shrink-0">
                                {{ $anuncio->tipo_icono }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-900 text-sm truncate">{{ $anuncio->titulo }}</h3>
                            <div class="flex flex-wrap gap-1.5 mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $anuncio->tipo_badge }}">
                                    {{ ucfirst($anuncio->tipo) }}
                                </span>
                                @if($anuncio->mostrar_en_publico)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                                        Público
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        Oculto
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs text-gray-500 pt-1">
                        <div>
                            <span class="block text-gray-400">Publicado</span>
                            <span class="font-medium text-gray-700">{{ $anuncio->publicado_en?->format('d/m/Y') ?? 'Borrador' }}</span>
                        </div>
                        <div>
                            <span class="block text-gray-400">Expira</span>
                            <span class="font-medium text-gray-700">{{ $anuncio->expira_en?->format('d/m/Y') ?? 'Sin fecha' }}</span>
                        </div>
                    </div>

                    <!-- Toggle y Acciones Móviles -->
                    <div class="flex items-center justify-between pt-2 border-t border-gray-50">
                        <!-- Toggle Visibilidad -->
                        <form action="{{ route('admin.anuncios.toggle-visibilidad', $anuncio) }}" method="POST" class="inline-flex items-center gap-1.5">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-xs px-2.5 py-1 rounded-full font-semibold border transition-all {{ $anuncio->mostrar_en_publico ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                {{ $anuncio->mostrar_en_publico ? '👁️ Visible' : '🙈 Oculto' }}
                            </button>
                        </form>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.anuncios.show', $anuncio) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg" title="Ver">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            <a href="{{ route('admin.anuncios.edit', $anuncio) }}" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg" title="Editar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('admin.anuncios.destroy', $anuncio) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este anuncio?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg" title="Eliminar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500 font-medium">
                    No hay anuncios registrados con los filtros aplicados.
                </div>
            @endforelse
        </div>

        <!-- VISTA DE TABLA EN ESCRITORIO/TABLET (hidden md:block) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b-2 border-gray-200">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Título</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Publicado</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Expira</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Visible</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($anuncios as $anuncio)
                        <tr class="hover:bg-[#ECFDF5] transition-colors duration-150 group {{ !$anuncio->esta_activo ? 'opacity-50' : '' }}">
                            <!-- Título -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($anuncio->imagen)
                                        <img src="{{ Storage::url($anuncio->imagen) }}" alt="{{ $anuncio->titulo }}" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-[#F3F4F6] flex items-center justify-center text-xl flex-shrink-0">
                                            {{ $anuncio->tipo_icono }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <div class="font-medium text-gray-900 truncate max-w-xs">{{ $anuncio->titulo }}</div>
                                        <div class="text-xs text-gray-500 truncate max-w-xs">{{ Str::limit($anuncio->contenido, 60) }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Tipo -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $anuncio->tipo_badge }}">
                                    {{ $anuncio->tipo_icono }} {{ ucfirst($anuncio->tipo) }}
                                </span>
                            </td>

                            <!-- Publicado -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $anuncio->publicado_en?->format('d/m/Y') ?? 'Borrador' }}
                            </td>

                            <!-- Expira -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                @if($anuncio->expira_en)
                                    <span class="{{ $anuncio->expira_en->isPast() ? 'text-red-500 font-semibold' : '' }}">
                                        {{ $anuncio->expira_en->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="text-gray-400">Sin límite</span>
                                @endif
                            </td>

                            <!-- Visible Switch -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <form action="{{ route('admin.anuncios.toggle-visibilidad', $anuncio) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-3 py-1 rounded-full text-xs font-semibold border transition-all {{ $anuncio->mostrar_en_publico ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                                        {{ $anuncio->mostrar_en_publico ? '👁️ Visible' : '🙈 Oculto' }}
                                    </button>
                                </form>
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.anuncios.show', $anuncio) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Ver">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.anuncios.edit', $anuncio) }}" class="p-2 text-gray-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.anuncios.destroy', $anuncio) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este anuncio?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 font-medium">
                                No hay anuncios registrados aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($anuncios->hasPages())
            <div class="px-4 sm:px-6 py-4 border-t border-gray-100 bg-[#F9FAFB]">
                {{ $anuncios->links() }}
            </div>
        @endif
    </div>
@endsection
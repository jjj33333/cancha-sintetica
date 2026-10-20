@extends('layouts.admin')

@section('header_title', 'Gestión de Torneos')
@section('title', 'Torneos - Admin')

@section('content')
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#065F46]">🏆 Torneos</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Administra todos los torneos de la cancha.</p>
        </div>
        <a href="{{ route('admin.torneos.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-[#10B981] hover:bg-[#059669] text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Crear Torneo
        </a>
    </div>

    <!-- Mensaje de éxito -->
    @if(session('success'))
        <div class="mb-6 bg-[#D1FAE5] border border-[#10B981] text-[#065F46] p-3 sm:p-4 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-xs sm:text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 mb-6">
        <form method="GET" action="{{ route('admin.torneos.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre..." 
                       class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
            </div>
            <div class="sm:w-48">
                <select name="estado" class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
                    <option value="">Todos los estados</option>
                    <option value="borrador" {{ request('estado') === 'borrador' ? 'selected' : '' }}>Borrador</option>
                    <option value="inscripciones_abiertas" {{ request('estado') === 'inscripciones_abiertas' ? 'selected' : '' }}>Inscripciones Abiertas</option>
                    <option value="en_curso" {{ request('estado') === 'en_curso' ? 'selected' : '' }}>En Curso</option>
                    <option value="finalizado" {{ request('estado') === 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                    <option value="cancelado" {{ request('estado') === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 sm:flex-none px-6 py-2 bg-[#065F46] hover:bg-[#047857] text-white text-sm font-medium rounded-lg transition-colors text-center">
                    Filtrar
                </button>
                @if(request('buscar') || request('estado'))
                    <a href="{{ route('admin.torneos.index') }}" class="flex-1 sm:flex-none px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors text-center">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Vista de Torneos (Tarjetas en Móvil / Tabla en Desktop) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Vista Móvil (Tarjetas) -->
        <div class="block md:hidden divide-y divide-gray-100">
            @forelse($torneos as $torneo)
                @php
                    $badgeClass = match($torneo->estado) {
                        'en_curso' => 'bg-[#D1FAE5] text-[#065F46]',
                        'inscripciones_abiertas' => 'bg-[#FEF3C7] text-[#92400E]',
                        'borrador' => 'bg-[#F3F4F6] text-[#374151]',
                        'finalizado' => 'bg-[#DBEAFE] text-[#1E40AF]',
                        'cancelado' => 'bg-[#FEE2E2] text-[#991B1B]',
                        default => 'bg-gray-100 text-gray-600',
                    };
                    $estadoTexto = match($torneo->estado) {
                        'en_curso' => 'En curso',
                        'inscripciones_abiertas' => 'Inscripciones',
                        'borrador' => 'Borrador',
                        'finalizado' => 'Finalizado',
                        'cancelado' => 'Cancelado',
                        default => ucfirst($torneo->estado),
                    };
                @endphp
                <div class="p-4 space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-2.5">
                            <span class="text-2xl shrink-0">
                                @if($torneo->categoria === 'libre') 🏆
                                @elseif($torneo->categoria === 'sub20') 🥇
                                @elseif($torneo->categoria === 'femenino') ⭐
                                @else 🎯
                                @endif
                            </span>
                            <div>
                                <p class="font-bold text-gray-800 text-sm">{{ $torneo->nombre }}</p>
                                <p class="text-xs text-gray-500">{{ $torneo->instalacion->nombre ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold shrink-0 {{ $badgeClass }}">
                            {{ $estadoTexto }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs text-gray-600 bg-gray-50 p-2.5 rounded-lg">
                        <div>
                            <span class="text-gray-400 block text-[10px] uppercase font-semibold">Categoría</span>
                            <span class="capitalize font-medium text-gray-700">{{ str_replace('_', ' ', $torneo->categoria) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10px] uppercase font-semibold">Equipos</span>
                            <span class="font-medium text-gray-700">{{ $torneo->equipos_count ?? $torneo->equipos->count() }} inscritos</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10px] uppercase font-semibold">Inicio</span>
                            <span class="font-medium text-gray-700">{{ $torneo->fecha_inicio->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10px] uppercase font-semibold">Fin</span>
                            <span class="font-medium text-gray-700">{{ $torneo->fecha_fin->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-gray-100 text-xs">
                        <span class="text-gray-500 truncate max-w-[150px]">
                            👤 {{ $torneo->organizador->nombre ?? 'Sin asignar' }}
                        </span>
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.torneos.show', $torneo) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg" title="Ver detalles">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            <a href="{{ route('admin.torneos.edit', $torneo) }}" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg" title="Editar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('admin.torneos.destroy', $torneo) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de cancelar/desactivar este torneo?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg" title="Cancelar/Desactivar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <p class="text-sm text-gray-500 font-medium">No hay torneos registrados aún.</p>
                </div>
            @endforelse
        </div>

        <!-- Vista Desktop (Tabla) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b-2 border-gray-200">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Categoría</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Inicio</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fin</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Equipos</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Organizador</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($torneos as $torneo)
                        <tr class="hover:bg-[#ECFDF5] transition-colors duration-150 group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">
                                        @if($torneo->categoria === 'libre') 🏆
                                        @elseif($torneo->categoria === 'sub20') 🥇
                                        @elseif($torneo->categoria === 'femenino') ⭐
                                        @else 🎯
                                        @endif
                                    </span>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $torneo->nombre }}</p>
                                        <p class="text-xs text-gray-500">{{ $torneo->instalacion->nombre ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-700 capitalize">{{ str_replace('_', ' ', $torneo->categoria) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $torneo->fecha_inicio->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $torneo->fecha_fin->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#F3F4F6] text-sm font-medium text-gray-700">
                                    {{ $torneo->equipos_count ?? $torneo->equipos->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $torneo->organizador->nombre ?? 'Sin asignar' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $badgeClass = match($torneo->estado) {
                                        'en_curso' => 'bg-[#D1FAE5] text-[#065F46]',
                                        'inscripciones_abiertas' => 'bg-[#FEF3C7] text-[#92400E]',
                                        'borrador' => 'bg-[#F3F4F6] text-[#374151]',
                                        'finalizado' => 'bg-[#DBEAFE] text-[#1E40AF]',
                                        'cancelado' => 'bg-[#FEE2E2] text-[#991B1B]',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                    $estadoTexto = match($torneo->estado) {
                                        'en_curso' => 'En curso',
                                        'inscripciones_abiertas' => 'Inscripciones',
                                        'borrador' => 'Borrador',
                                        'finalizado' => 'Finalizado',
                                        'cancelado' => 'Cancelado',
                                        default => ucfirst($torneo->estado),
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                    {{ $estadoTexto }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.torneos.show', $torneo) }}" class="p-2 text-gray-500 hover:text-[#3B82F6] hover:bg-[#DBEAFE] rounded-lg transition-colors" title="Ver detalles">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.torneos.edit', $torneo) }}" class="p-2 text-gray-500 hover:text-[#F59E0B] hover:bg-[#FEF3C7] rounded-lg transition-colors" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.torneos.destroy', $torneo) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de cancelar/desactivar este torneo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-500 hover:text-[#EF4444] hover:bg-[#FEE2E2] rounded-lg transition-colors" title="Cancelar/Desactivar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="text-gray-400 mb-3">
                                    <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </div>
                                <p class="text-gray-500 font-medium">No hay torneos registrados aún.</p>
                                <p class="text-sm text-gray-400 mt-1">Comienza creando un nuevo torneo.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($torneos->hasPages())
            <div class="px-4 sm:px-6 py-4 border-t border-gray-100 bg-[#F9FAFB]">
                {{ $torneos->links() }}
            </div>
        @endif
    </div>
@endsection
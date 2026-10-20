@extends('layouts.admin')

@section('header_title', 'Gestión de Reservas')
@section('title', 'Reservas - Admin')

@section('content')
    <!-- Encabezado de la sección Responsive -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#065F46]">Reservas</h1>
            <p class="text-sm text-gray-500 mt-1">Administra y consulta todas las reservas de la cancha deportiva.</p>
        </div>
        <a href="{{ route('admin.reservas.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-[#10B981] hover:bg-[#059669] text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all transform hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nueva Reserva
        </a>
    </div>

    <!-- Mensaje de éxito (Flash message) -->
    @if(session('success'))
        <div class="mb-6 bg-[#D1FAE5] border border-[#10B981] text-[#065F46] px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Filtros y Búsqueda Responsive -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
        <form method="GET" action="{{ route('admin.reservas.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Buscar -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Buscar</label>
                <div class="relative">
                    <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Cliente, código o tel..." class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <!-- Fecha -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Fecha de Reserva</label>
                <input type="date" name="fecha" value="{{ request('fecha') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white">
            </div>

            <!-- Estado -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Estado</label>
                <select name="estado" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white">
                    <option value="">Todos los estados</option>
                    <option value="confirmada" {{ request('estado') === 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                    <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="completada" {{ request('estado') === 'completada' ? 'selected' : '' }}>Completada</option>
                    <option value="cancelada" {{ request('estado') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>

            <!-- Orden Predeterminado / Personalizado -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Ordenar por</label>
                <select name="orden" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white">
                    <option value="reciente" {{ request('orden', 'reciente') === 'reciente' ? 'selected' : '' }}>Hora y Fecha más reciente</option>
                    <option value="antigua" {{ request('orden') === 'antigua' ? 'selected' : '' }}>Hora y Fecha más antigua</option>
                    <option value="creacion" {{ request('orden') === 'creacion' ? 'selected' : '' }}>Últimas registradas (Creación)</option>
                </select>
            </div>

            <!-- Botones de Acción de Filtro -->
            <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-1">
                <button type="submit" class="flex-1 px-4 py-2 bg-[#065F46] hover:bg-[#047857] text-white text-sm font-semibold rounded-lg transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filtrar
                </button>
                @if(request()->hasAny(['buscar', 'fecha', 'estado', 'orden', 'por_pagina']))
                    <a href="{{ route('admin.reservas.index') }}" class="p-2 text-gray-500 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Limpiar filtros">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>

            <!-- Mantener cantidad por página activa en el formulario -->
            @if(request()->filled('por_pagina'))
                <input type="hidden" name="por_pagina" value="{{ request('por_pagina') }}">
            @endif
        </form>
    </div>

    <!-- Contenedor Principal de la Tabla de Reservas -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        
        <!-- Barra de Control Superior: Conteo y Selector "Ver más" -->
        <div class="bg-gray-50/90 px-4 sm:px-6 py-3 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div class="text-xs sm:text-sm text-gray-600 font-medium flex items-center gap-2">
                <span>📋</span>
                <span>
                    Mostrando <strong class="text-gray-900 font-semibold">{{ $reservas->firstItem() ?? 0 }}-{{ $reservas->lastItem() ?? 0 }}</strong> de <strong class="text-[#065F46] font-bold">{{ $reservas->total() }}</strong> reservas
                </span>
                <span class="hidden md:inline-block text-gray-300">|</span>
                <span class="text-xs text-gray-500 hidden md:inline-block">
                    Orden: <span class="font-medium text-emerald-800">{{ request('orden') === 'antigua' ? 'Fecha más antigua' : (request('orden') === 'creacion' ? 'Últimas registradas' : 'Hora y fecha más reciente') }}</span>
                </span>
            </div>

            <!-- Opciones rápidas de "Ver más" / Filas por página -->
            <div class="flex items-center gap-1.5 self-end sm:self-auto">
                <span class="text-xs text-gray-500 font-semibold mr-1">Ver:</span>
                @foreach([10, 25, 50, 100] as $cantidad)
                    <a href="{{ request()->fullUrlWithQuery(['por_pagina' => $cantidad, 'page' => 1]) }}"
                       class="px-2.5 py-1 rounded-md text-xs font-semibold transition-all {{ ($porPagina == $cantidad) ? 'bg-[#065F46] text-white shadow-xs' : 'bg-white text-gray-600 hover:bg-emerald-50 hover:text-[#065F46] border border-gray-200' }}">
                        {{ $cantidad }}
                    </a>
                @endforeach
                <a href="{{ request()->fullUrlWithQuery(['por_pagina' => 'todas', 'page' => 1]) }}"
                   class="px-2.5 py-1 rounded-md text-xs font-semibold transition-all {{ ($porPagina == 9999 || request('por_pagina') === 'todas') ? 'bg-[#065F46] text-white shadow-xs' : 'bg-white text-gray-600 hover:bg-emerald-50 hover:text-[#065F46] border border-gray-200' }}">
                    Todas
                </a>
            </div>
        </div>

        <!-- VISTA DE TARJETAS EN MÓVIL (block md:hidden) -->
        <div class="block md:hidden divide-y divide-gray-100">
            @forelse($reservas as $reserva)
                @php
                    $badgeClass = match($reserva->estado) {
                        'confirmada' => 'bg-[#D1FAE5] text-[#065F46]',
                        'pendiente' => 'bg-[#FEF3C7] text-[#92400E]',
                        'cancelada' => 'bg-[#FEE2E2] text-[#991B1B]',
                        'completada' => 'bg-[#DBEAFE] text-[#1E40AF]',
                        default => 'bg-gray-100 text-gray-600',
                    };
                @endphp
                <div class="p-4 space-y-3">
                    <!-- Cabecera de la tarjeta: Código y Estado -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-sm font-bold text-gray-800">#{{ $reserva->codigo_reserva }}</span>
                            @if($reserva->tipo_reserva === 'torneo')
                                <span class="text-xs text-[#F59E0B] font-semibold">🏆 Torneo</span>
                            @endif
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $badgeClass }}">
                            {{ $reserva->estado }}
                        </span>
                    </div>

                    <!-- Datos Principales -->
                    <div class="grid grid-cols-2 gap-2 text-sm pt-1">
                        <div>
                            <span class="text-xs text-gray-400 block font-medium">Cliente</span>
                            <span class="font-semibold text-gray-900 block truncate">{{ $reserva->nombre_cliente }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 block font-medium">Total</span>
                            <span class="font-bold text-[#065F46] block">Q. {{ number_format($reserva->precio_total, 2) }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 block font-medium">Fecha</span>
                            <span class="text-gray-700 block font-medium">{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 block font-medium">Horario</span>
                            <span class="text-gray-700 block font-medium">
                                {{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('H:i') }}
                            </span>
                        </div>
                    </div>

                    @if(!empty($reserva->telefono_cliente))
                        <div class="text-xs text-gray-500 flex items-center gap-1 pt-1">
                            <span>📞</span>
                            <a href="tel:{{ $reserva->telefono_cliente }}" class="text-[#065F46] font-medium hover:underline">
                                {{ $reserva->telefono_cliente }}
                            </a>
                        </div>
                    @endif

                    <!-- Botones de Acción Móvil -->
                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-50">
                        <a href="{{ route('admin.reservas.show', $reserva) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Ver
                        </a>
                        <a href="{{ route('admin.reservas.edit', $reserva) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Editar
                        </a>
                        <form action="{{ route('admin.reservas.destroy', $reserva) }}" method="POST" class="inline" onsubmit="return confirm('¿Deseas cancelar/eliminar esta reserva?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Cancelar
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <p class="text-gray-500 font-medium">No se encontraron reservas con los filtros aplicados.</p>
                </div>
            @endforelse
        </div>

        <!-- VISTA DE TABLA EN ESCRITORIO/TABLET (hidden md:block) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b-2 border-gray-200">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Código</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha y Hora</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Teléfono</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reservas as $reserva)
                        <tr class="hover:bg-[#ECFDF5] transition-colors duration-150 group">
                            <!-- Código -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-mono font-medium text-gray-700">#{{ $reserva->codigo_reserva }}</span>
                                @if($reserva->tipo_reserva === 'torneo')
                                    <span class="ml-2 text-xs text-[#F59E0B] font-semibold">🏆 Torneo</span>
                                @endif
                            </td>
                            
                            <!-- Fecha y Hora -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-semibold">{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('H:i') }}</div>
                            </td>
                            
                            <!-- Cliente -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $reserva->nombre_cliente }}</div>
                                <div class="text-xs text-emerald-700 font-medium">{{ $reserva->instalacion->nombre ?? 'Cancha Principal' }}</div>
                            </td>
                            
                            <!-- Teléfono -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $reserva->telefono_cliente ?: 'No registrado' }}
                            </td>
                            
                            <!-- Total -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                Q. {{ number_format($reserva->precio_total, 2) }}
                            </td>
                            
                            <!-- Estado (Badge) -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $badgeClass = match($reserva->estado) {
                                        'confirmada' => 'bg-[#D1FAE5] text-[#065F46]',
                                        'pendiente' => 'bg-[#FEF3C7] text-[#92400E]',
                                        'cancelada' => 'bg-[#FEE2E2] text-[#991B1B]',
                                        'completada' => 'bg-[#DBEAFE] text-[#1E40AF]',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                    $estadoTexto = ucfirst($reserva->estado);
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                    {{ $estadoTexto }}
                                </span>
                            </td>
                            
                            <!-- Acciones -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.reservas.show', $reserva) }}" class="p-2 text-gray-500 hover:text-[#3B82F6] hover:bg-[#DBEAFE] rounded-lg transition-colors" title="Ver detalles">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.reservas.edit', $reserva) }}" class="p-2 text-gray-500 hover:text-[#F59E0B] hover:bg-[#FEF3C7] rounded-lg transition-colors" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.reservas.destroy', $reserva) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas cancelar/eliminar esta reserva?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-500 hover:text-[#EF4444] hover:bg-[#FEE2E2] rounded-lg transition-colors" title="Eliminar/Cancelar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-400 mb-3">
                                    <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <p class="text-gray-500 font-medium">No se encontraron reservas con los filtros aplicados.</p>
                                <p class="text-sm text-gray-400 mt-1">Intenta con otros términos o limpia los filtros.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación y Botón "Ver Más" Inferior -->
        <div class="px-4 sm:px-6 py-4 border-t border-gray-100 bg-[#F9FAFB] flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-xs text-gray-500 text-center sm:text-left">
                Mostrando página <strong class="font-semibold text-gray-700">{{ $reservas->currentPage() }}</strong> de <strong class="font-semibold text-gray-700">{{ $reservas->lastPage() }}</strong> 
                @if($porPagina == 10 && $reservas->total() > 10)
                    · <a href="{{ request()->fullUrlWithQuery(['por_pagina' => 25, 'page' => 1]) }}" class="text-[#065F46] font-semibold hover:underline inline-flex items-center gap-1">
                        Ver más reservas (mostrar 25) ↗
                    </a>
                @endif
            </div>

            <!-- Paginación estándar -->
            @if($reservas->hasPages())
                <div class="w-full sm:w-auto flex justify-center sm:justify-end">
                    {{ $reservas->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
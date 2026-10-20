@extends('layouts.admin')

@section('header_title', 'Bloqueos de Cancha')
@section('title', 'Bloqueos - Admin')

@section('content')
    <!-- Encabezado Responsive -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#065F46]">🚫 Bloqueos de Cancha</h1>
            <p class="text-sm text-gray-500 mt-1">Gestiona los bloqueos por mantenimiento, eventos o feriados.</p>
        </div>
        <a href="{{ route('admin.bloqueos.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-[#10B981] hover:bg-[#059669] text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all transform hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nuevo Bloqueo
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
        <form method="GET" action="{{ route('admin.bloqueos.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div class="sm:col-span-2 lg:col-span-1">
                <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por descripción..." 
                       class="w-full px-4 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
            </div>
            <div>
                <select name="instalacion_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white">
                    <option value="">Todas las instalaciones</option>
                    @foreach($instalaciones as $inst)
                        <option value="{{ $inst->id }}" {{ request('instalacion_id') == $inst->id ? 'selected' : '' }}>{{ $inst->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="motivo" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white">
                    <option value="">Todos los motivos</option>
                    <option value="mantenimiento" {{ request('motivo') === 'mantenimiento' ? 'selected' : '' }}>🔧 Mantenimiento</option>
                    <option value="evento" {{ request('motivo') === 'evento' ? 'selected' : '' }}>🎉 Evento</option>
                    <option value="feriado" {{ request('motivo') === 'feriado' ? 'selected' : '' }}>🏖️ Feriado</option>
                    <option value="otro" {{ request('motivo') === 'otro' ? 'selected' : '' }}>📋 Otro</option>
                </select>
            </div>
            <div>
                <select name="estado" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white">
                    <option value="">Todos los estados</option>
                    <option value="programado" {{ request('estado') === 'programado' ? 'selected' : '' }}>Programados</option>
                    <option value="completado" {{ request('estado') === 'completado' ? 'selected' : '' }}>Completados</option>
                    <option value="cancelado" {{ request('estado') === 'cancelado' ? 'selected' : '' }}>Cancelados</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-[#065F46] hover:bg-[#047857] text-white text-sm font-semibold rounded-lg transition-colors text-center">
                    Filtrar
                </button>
                @if(request('buscar') || request('instalacion_id') || request('motivo') || request('estado'))
                    <a href="{{ route('admin.bloqueos.index') }}" class="p-2 text-gray-500 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Limpiar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Contenedor Principal de Bloqueos -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        
        <!-- VISTA DE TARJETAS EN MÓVIL (block md:hidden) -->
        <div class="block md:hidden divide-y divide-gray-100">
            @forelse($bloqueos as $bloqueo)
                @php
                    $badgeClass = match($bloqueo->estado) {
                        'programado' => 'bg-[#FEF3C7] text-[#92400E]',
                        'completado' => 'bg-[#D1FAE5] text-[#065F46]',
                        'cancelado' => 'bg-[#FEE2E2] text-[#991B1B]',
                        default => 'bg-gray-100 text-gray-600',
                    };
                @endphp
                <div class="p-4 space-y-3 {{ !$bloqueo->esta_activo ? 'opacity-50 bg-gray-50' : '' }}">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $bloqueo->motivo_badge }}">
                            {{ $bloqueo->motivo_icono }} {{ ucfirst($bloqueo->motivo) }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $badgeClass }}">
                            {{ ucfirst($bloqueo->estado) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs pt-1">
                        <div>
                            <span class="text-gray-400 block font-medium">Fecha</span>
                            <span class="font-semibold text-gray-900 block">{{ $bloqueo->fecha_bloqueo->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block font-medium">Horario</span>
                            <span class="font-semibold text-gray-900 block">
                                @if($bloqueo->dia_completo)
                                    <span class="text-[#065F46] font-bold">Día completo</span>
                                @else
                                    {{ \Carbon\Carbon::parse($bloqueo->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($bloqueo->hora_fin)->format('H:i') }}
                                @endif
                            </span>
                        </div>
                        <div class="col-span-2">
                            <span class="text-gray-400 block font-medium">Instalación</span>
                            <span class="font-medium text-emerald-800 block">{{ $bloqueo->instalacion->nombre }}</span>
                        </div>
                    </div>

                    @if($bloqueo->descripcion)
                        <p class="text-xs text-gray-600 bg-gray-50 p-2 rounded-lg border border-gray-100">
                            {{ Str::limit($bloqueo->descripcion, 90) }}
                        </p>
                    @endif

                    <!-- Acciones Móviles -->
                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-50">
                        <a href="{{ route('admin.bloqueos.show', $bloqueo) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg" title="Ver">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </a>
                        <a href="{{ route('admin.bloqueos.edit', $bloqueo) }}" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg" title="Editar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <form action="{{ route('admin.bloqueos.destroy', $bloqueo) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este bloqueo?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg" title="Eliminar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500 font-medium">
                    No hay bloqueos registrados aún.
                </div>
            @endforelse
        </div>

        <!-- VISTA DE TABLA EN ESCRITORIO/TABLET (hidden md:block) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b-2 border-gray-200">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Hora</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Motivo</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Descripción</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Instalación</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Estado</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bloqueos as $bloqueo)
                        <tr class="hover:bg-[#ECFDF5] transition-colors duration-150 group {{ !$bloqueo->esta_activo ? 'opacity-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">{{ $bloqueo->fecha_bloqueo->format('d/m/Y') }}</span>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-700">
                                    @if($bloqueo->dia_completo)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-[#D1FAE5] text-[#065F46]">
                                            Día completo
                                        </span>
                                    @else
                                        {{ \Carbon\Carbon::parse($bloqueo->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($bloqueo->hora_fin)->format('H:i') }}
                                    @endif
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $bloqueo->motivo_badge }}">
                                    {{ $bloqueo->motivo_icono }} {{ ucfirst($bloqueo->motivo) }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $bloqueo->descripcion }}">
                                    {{ $bloqueo->descripcion ?: 'Sin descripción' }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900 font-medium">{{ $bloqueo->instalacion->nombre }}</span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $badgeClass = match($bloqueo->estado) {
                                        'programado' => 'bg-[#FEF3C7] text-[#92400E]',
                                        'completado' => 'bg-[#D1FAE5] text-[#065F46]',
                                        'cancelado' => 'bg-[#FEE2E2] text-[#991B1B]',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                    {{ ucfirst($bloqueo->estado) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.bloqueos.show', $bloqueo) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Ver">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.bloqueos.edit', $bloqueo) }}" class="p-2 text-gray-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.bloqueos.destroy', $bloqueo) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este bloqueo?');">
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
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 font-medium">
                                No hay bloqueos registrados aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bloqueos->hasPages())
            <div class="px-4 sm:px-6 py-4 border-t border-gray-100 bg-[#F9FAFB]">
                {{ $bloqueos->links() }}
            </div>
        @endif
    </div>
@endsection
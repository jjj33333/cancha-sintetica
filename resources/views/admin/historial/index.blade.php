@extends('layouts.admin')

@section('header_title', 'Historial y Auditoría')
@section('title', 'Historial - Admin')

@section('content')
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#065F46]">📋 Historial y Auditoría</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Registro completo de todas las acciones realizadas en el sistema.</p>
        </div>
    </div>

    <!-- Tarjetas de Resumen Rápido -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 hover:shadow-md transition-all hover:-translate-y-1">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-[#ECFDF5] flex items-center justify-center text-xl sm:text-2xl">📋</div>
                <div>
                    <p class="text-xs sm:text-sm text-gray-500">Registros Hoy</p>
                    <p class="text-xl sm:text-2xl font-bold text-[#065F46]">{{ $totalRegistrosHoy }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 hover:shadow-md transition-all hover:-translate-y-1">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-[#DBEAFE] flex items-center justify-center text-xl sm:text-2xl">📊</div>
                <div>
                    <p class="text-xs sm:text-sm text-gray-500">Esta Semana</p>
                    <p class="text-xl sm:text-2xl font-bold text-[#1E40AF]">{{ $totalRegistrosSemana }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 hover:shadow-md transition-all hover:-translate-y-1">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-[#FEF3C7] flex items-center justify-center text-xl sm:text-2xl">🔥</div>
                <div class="min-w-0">
                    <p class="text-xs sm:text-sm text-gray-500">Acción más frecuente</p>
                    <p class="text-base sm:text-lg font-bold text-[#92400E] truncate">
                        {{ $accionesMasFrecuentes->first()->accion ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" action="{{ route('admin.historial.index') }}" class="flex flex-col lg:flex-row flex-wrap gap-2.5">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por acción, tabla o ID..." 
                       class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
            </div>
            <div class="w-full sm:w-auto flex-1 min-w-[140px]">
                <select name="usuario_id" class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white">
                    <option value="">Todos los usuarios</option>
                    @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ request('usuario_id') == $usuario->id ? 'selected' : '' }}>
                            {{ $usuario->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-auto flex-1 min-w-[140px]">
                <select name="tipo_accion" class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white">
                    <option value="">Todas las acciones</option>
                    <option value="crear" {{ request('tipo_accion') === 'crear' ? 'selected' : '' }}>➕ Crear</option>
                    <option value="editar" {{ request('tipo_accion') === 'editar' ? 'selected' : '' }}>✏️ Editar</option>
                    <option value="eliminar" {{ request('tipo_accion') === 'eliminar' ? 'selected' : '' }}>🗑️ Eliminar</option>
                    <option value="programar" {{ request('tipo_accion') === 'programar' ? 'selected' : '' }}>📅 Programar</option>
                </select>
            </div>
            <div class="w-full sm:w-auto flex-1 min-w-[140px]">
                <select name="tabla" class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white">
                    <option value="">Todas las tablas</option>
                    @foreach($tablas as $clave => $nombre)
                        <option value="{{ $clave }}" {{ request('tabla') === $clave ? 'selected' : '' }}>{{ $nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-auto flex-1 min-w-[130px]">
                <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" 
                       class="w-full px-3 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white"
                       title="Fecha inicio">
            </div>
            <div class="w-full sm:w-auto flex-1 min-w-[130px]">
                <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" 
                       class="w-full px-3 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white"
                       title="Fecha fin">
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none px-6 py-2 bg-[#065F46] hover:bg-[#047857] text-white font-medium rounded-lg transition-colors text-center text-sm">
                    Filtrar
                </button>
                @if(request('buscar') || request('usuario_id') || request('tipo_accion') || request('tabla') || request('fecha_inicio'))
                    <a href="{{ route('admin.historial.index') }}" class="flex-1 sm:flex-none px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors text-center text-sm">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabla de Historial (Mobile Cards + Desktop Table) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Mobile View (block md:hidden) -->
        <div class="block md:hidden divide-y divide-gray-100">
            @forelse($registros as $registro)
                <div class="p-4 hover:bg-gray-50/50 transition-colors">
                    <div class="flex justify-between items-start gap-2 mb-2">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#10B981] to-[#065F46] flex items-center justify-center text-white font-bold text-xs shrink-0">
                                {{ $registro->usuario ? substr($registro->usuario->nombre, 0, 1) : '?' }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 leading-tight">{{ $registro->usuario->nombre ?? 'Sistema' }}</p>
                                @if($registro->usuario)
                                    <span class="text-[10px] text-gray-500">{{ ucfirst($registro->usuario->rol) }}</span>
                                @endif
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $registro->accion_badge }}">
                            {{ $registro->accion_icono }} {{ $registro->accion }}
                        </span>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-2.5 my-2 text-xs text-gray-700">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-gray-400">Tabla / Elemento:</span>
                            <span class="font-medium text-gray-800">
                                @if($registro->nombre_tabla)
                                    {{ ucfirst(str_replace('_', ' ', $registro->nombre_tabla)) }}
                                    @if($registro->registro_id) #{{ $registro->registro_id }} @endif
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-[11px] text-gray-400">
                            <span>IP: <span class="font-mono">{{ $registro->direccion_ip ?? '-' }}</span></span>
                            <span>{{ $registro->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-400">
                    <p class="text-sm">No hay registros de actividad.</p>
                </div>
            @endforelse
        </div>

        <!-- Desktop View (hidden md:block) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b-2 border-gray-200">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha/Hora</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Usuario</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Rol</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Acción</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Detalle</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($registros as $registro)
                        <tr class="hover:bg-[#ECFDF5] transition-colors duration-150 group">
                            <!-- Fecha/Hora -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $registro->created_at->format('d/m/Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $registro->created_at->format('H:i') }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Usuario -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#10B981] to-[#065F46] flex items-center justify-center text-white font-bold text-xs">
                                        {{ $registro->usuario ? substr($registro->usuario->nombre, 0, 1) : '?' }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $registro->usuario->nombre ?? 'Sistema' }}</p>
                                        @if($registro->usuario)
                                            <p class="text-xs text-gray-500">{{ $registro->usuario->email }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Rol -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($registro->usuario)
                                    @php
                                        $rolBadge = match($registro->usuario->rol) {
                                            'admin' => 'bg-[#DBEAFE] text-[#1E40AF]',
                                            'organizador' => 'bg-[#FEF3C7] text-[#92400E]',
                                            'trabajador' => 'bg-[#D1FAE5] text-[#065F46]',
                                            default => 'bg-gray-100 text-gray-600',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $rolBadge }}">
                                        {{ ucfirst($registro->usuario->rol) }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>

                            <!-- Acción -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $registro->accion_badge }}">
                                    {{ $registro->accion_icono }} {{ $registro->accion }}
                                </span>
                            </td>

                            <!-- Detalle -->
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700">
                                    @if($registro->nombre_tabla)
                                        <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $registro->nombre_tabla)) }}</span>
                                        @if($registro->registro_id)
                                            <span class="text-gray-500">#{{ $registro->registro_id }}</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </p>
                            </td>

                            <!-- IP -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-xs text-gray-500 font-mono">{{ $registro->direccion_ip ?? '-' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-400 mb-3">
                                    <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-medium">No hay registros de actividad.</p>
                                <p class="text-sm text-gray-400 mt-1">Las acciones realizadas en el sistema aparecerán aquí.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($registros->hasPages())
            <div class="px-4 sm:px-6 py-4 border-t border-gray-100 bg-[#F9FAFB]">
                {{ $registros->links() }}
            </div>
        @endif
    </div>

    <!-- Nota informativa -->
    <div class="mt-6 bg-[#ECFDF5] border border-[#10B981] rounded-xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-[#10B981] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div class="text-sm text-[#065F46]">
            <p class="font-semibold mb-1">️ Información sobre el historial</p>
            <p class="text-gray-700">
                Este registro muestra todas las acciones realizadas por los usuarios del sistema. 
                Los registros se mantienen por auditoría y seguridad. No es posible eliminar registros del historial.
            </p>
        </div>
    </div>
@endsection
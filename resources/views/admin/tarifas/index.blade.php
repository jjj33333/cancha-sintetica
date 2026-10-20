@extends('layouts.admin')

@section('header_title', 'Tarifas y Precios')
@section('title', 'Tarifas - Admin')

@section('content')
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#065F46]">💰 Tarifas y Precios</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Define los precios por hora según el tipo de día y horario.</p>
        </div>
        <a href="{{ route('admin.tarifas.create') }}" class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-[#10B981] hover:bg-[#059669] text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nueva Tarifa
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
        <form method="GET" action="{{ route('admin.tarifas.index') }}" class="flex flex-col sm:flex-row flex-wrap gap-2.5">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre de tarifa..." 
                       class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
            </div>
            <div class="w-full sm:w-auto flex-1 min-w-[160px]">
                <select name="instalacion_id" class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white">
                    <option value="">Todas las instalaciones</option>
                    @foreach($instalaciones as $inst)
                        <option value="{{ $inst->id }}" {{ request('instalacion_id') == $inst->id ? 'selected' : '' }}>{{ $inst->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-auto flex-1 min-w-[150px]">
                <select name="tipo_dia" class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white">
                    <option value="">Todos los tipos</option>
                    <option value="semana" {{ request('tipo_dia') === 'semana' ? 'selected' : '' }}>Semana (Lun-Vie)</option>
                    <option value="fin_semana" {{ request('tipo_dia') === 'fin_semana' ? 'selected' : '' }}>Fin de Semana</option>
                    <option value="feriado" {{ request('tipo_dia') === 'feriado' ? 'selected' : '' }}>Feriado</option>
                </select>
            </div>
            <div class="w-full sm:w-auto flex-1 min-w-[130px]">
                <select name="estado" class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white">
                    <option value="">Todos</option>
                    <option value="activo" {{ request('estado') === 'activo' ? 'selected' : '' }}>Activas</option>
                    <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>Inactivas</option>
                </select>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none px-6 py-2 bg-[#065F46] hover:bg-[#047857] text-white font-medium rounded-lg transition-colors text-center text-sm">
                    Filtrar
                </button>
                @if(request('buscar') || request('instalacion_id') || request('tipo_dia') || request('estado'))
                    <a href="{{ route('admin.tarifas.index') }}" class="flex-1 sm:flex-none px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors text-center text-sm">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabla de Tarifas (Mobile Cards + Desktop Table) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Mobile View (block md:hidden) -->
        <div class="block md:hidden divide-y divide-gray-100">
            @forelse($tarifas as $tarifa)
                <div class="p-4 hover:bg-gray-50/50 transition-colors {{ !$tarifa->esta_activo ? 'opacity-60' : '' }}">
                    <div class="flex justify-between items-start gap-2 mb-2">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-lg bg-[#ECFDF5] flex items-center justify-center text-lg shrink-0">
                                💰
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm leading-tight">{{ $tarifa->nombre }}</h4>
                                <p class="text-xs text-gray-500">{{ $tarifa->instalacion->nombre ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-base font-bold text-[#065F46] block">{{ $tarifa->precio_formateado }}</span>
                            <span class="text-[10px] text-gray-400">por hora</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 text-xs py-2 my-2 border-y border-gray-100">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $tarifa->tipo_dia_badge }}">
                            {{ $tarifa->tipo_dia_texto }}
                        </span>
                        <div class="flex items-center gap-1 text-gray-600">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>{{ $tarifa->horario_formateado }}</span>
                        </div>
                        <span class="text-gray-400">({{ $tarifa->duracion_horas }}h)</span>
                        <span class="ml-auto inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $tarifa->esta_activo ? 'bg-[#D1FAE5] text-[#065F46]' : 'bg-[#FEE2E2] text-[#991B1B]' }}">
                            {{ $tarifa->esta_activo ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-1">
                        <a href="{{ route('admin.tarifas.show', $tarifa) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Ver
                        </a>
                        <a href="{{ route('admin.tarifas.edit', $tarifa) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-amber-50 hover:text-amber-600 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Editar
                        </a>
                        @if($tarifa->esta_activo)
                            <form action="{{ route('admin.tarifas.destroy', $tarifa) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de desactivar esta tarifa?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 bg-white border border-gray-200 rounded-lg hover:bg-red-50 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Desactivar
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-400">
                    <p class="text-sm">No hay tarifas registradas aún.</p>
                </div>
            @endforelse
        </div>

        <!-- Desktop View (hidden md:block) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b-2 border-gray-200">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Instalación</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo de Día</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Horario</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Precio</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Estado</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tarifas as $tarifa)
                        <tr class="hover:bg-[#ECFDF5] transition-colors duration-150 group {{ !$tarifa->esta_activo ? 'opacity-50' : '' }}">
                            <!-- Nombre -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-[#ECFDF5] flex items-center justify-center text-xl">
                                        💰
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $tarifa->nombre }}</p>
                                        <p class="text-xs text-gray-500">{{ $tarifa->duracion_horas }} hora{{ $tarifa->duracion_horas != 1 ? 's' : '' }}</p>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Instalación -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-700">{{ $tarifa->instalacion->nombre ?? 'N/A' }}</span>
                            </td>
                            
                            <!-- Tipo de Día -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $tarifa->tipo_dia_badge }}">
                                    {{ $tarifa->tipo_dia_texto }}
                                </span>
                            </td>
                            
                            <!-- Horario -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="text-sm font-medium text-gray-700">{{ $tarifa->horario_formateado }}</span>
                                </div>
                            </td>
                            
                            <!-- Precio -->
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="text-lg font-bold text-[#065F46]">{{ $tarifa->precio_formateado }}</span>
                                <p class="text-xs text-gray-500">por hora</p>
                            </td>
                            
                            <!-- Estado -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($tarifa->esta_activo)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#D1FAE5] text-[#065F46]">
                                        ✅ Activa
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#FEE2E2] text-[#991B1B]">
                                        ❌ Inactiva
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Acciones -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.tarifas.show', $tarifa) }}" class="p-2 text-gray-500 hover:text-[#3B82F6] hover:bg-[#DBEAFE] rounded-lg transition-colors" title="Ver detalles">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.tarifas.edit', $tarifa) }}" class="p-2 text-gray-500 hover:text-[#F59E0B] hover:bg-[#FEF3C7] rounded-lg transition-colors" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.tarifas.destroy', $tarifa) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de desactivar esta tarifa?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-500 hover:text-[#EF4444] hover:bg-[#FEE2E2] rounded-lg transition-colors" title="Desactivar">
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
                                    <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <p class="text-gray-500 font-medium">No hay tarifas registradas aún.</p>
                                <p class="text-sm text-gray-400 mt-1">Comienza creando una nueva tarifa.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($tarifas->hasPages())
            <div class="px-4 sm:px-6 py-4 border-t border-gray-100 bg-[#F9FAFB]">
                {{ $tarifas->links() }}
            </div>
        @endif
    </div>
@endsection
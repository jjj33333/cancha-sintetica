@extends('layouts.organizador')

@section('header_title', 'Jugadores Registrados')
@section('title', 'Jugadores - Organizador')

@section('content')
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-3 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#065F46] flex items-center gap-2">
                <span>⚽</span> <span>Jugadores Registrados</span>
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5 sm:mt-1">Gestiona los jugadores de los equipos en tus torneos asignados.</p>
        </div>
        <a href="{{ route('organizador.jugadores.create') }}" 
           class="w-full sm:w-auto inline-flex items-center justify-center min-h-[44px] px-4 py-2.5 bg-[#F59E0B] hover:bg-[#D97706] text-white text-xs sm:text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Agregar Jugador</span>
        </a>
    </div>

    <!-- Mensajes -->
    @if(session('success'))
        <div class="mb-4 sm:mb-6 bg-[#D1FAE5] border border-[#10B981] text-[#065F46] px-3.5 sm:px-4 py-3 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-xs sm:text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 sm:mb-6 bg-[#FEE2E2] border border-[#EF4444] text-[#991B1B] px-3.5 sm:px-4 py-3 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-xs sm:text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3.5 sm:p-4 mb-4 sm:mb-6">
        <form method="GET" action="{{ route('organizador.jugadores.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
            <div class="lg:col-span-4">
                <label class="block text-xs font-medium text-gray-500 mb-1">Buscar</label>
                <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Nombre o DPI..." 
                       class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm">
            </div>
            <div class="lg:col-span-3">
                <label class="block text-xs font-medium text-gray-500 mb-1">Torneo</label>
                <select name="torneo_id" onchange="this.form.submit()" class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm">
                    <option value="">Todos los torneos</option>
                    @foreach($torneos as $torneo)
                        <option value="{{ $torneo->id }}" {{ request('torneo_id') == $torneo->id ? 'selected' : '' }}>{{ $torneo->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-3">
                <label class="block text-xs font-medium text-gray-500 mb-1">Equipo</label>
                <select name="equipo_id" class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm">
                    <option value="">Todos los equipos</option>
                    @foreach($equipos as $equipo)
                        <option value="{{ $equipo->id }}" {{ request('equipo_id') == $equipo->id ? 'selected' : '' }}>{{ $equipo->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 min-h-[42px] px-4 py-2.5 bg-[#065F46] hover:bg-[#047857] text-white font-medium rounded-lg transition-colors text-xs sm:text-sm flex items-center justify-center">
                    Filtrar
                </button>
                @if(request()->hasAny(['buscar', 'torneo_id', 'equipo_id']))
                    <a href="{{ route('organizador.jugadores.index') }}" class="min-h-[42px] px-3.5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors text-xs sm:text-sm flex items-center justify-center" title="Limpiar filtros">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Listado de Jugadores: Vista Móvil + Vista Desktop -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Vista Móvil (Tarjetas) -->
        <div class="block md:hidden divide-y divide-gray-100">
            @forelse($jugadores as $jugador)
                <div class="p-4 space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-[#FEF3C7] flex items-center justify-center text-[#92400E] font-bold text-sm flex-shrink-0">
                                {{ substr($jugador->nombre_completo, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-gray-800 text-sm truncate flex items-center gap-1.5 flex-wrap">
                                    <span>{{ $jugador->nombre_completo }}</span>
                                    @if($jugador->es_capitan)
                                        <span class="text-[10px] bg-[#FEF3C7] text-[#92400E] px-1.5 py-0.5 rounded-full font-semibold">Capitán</span>
                                    @endif
                                </p>
                                @if($jugador->email)
                                    <p class="text-xs text-gray-500 truncate">{{ $jugador->email }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex-shrink-0 flex items-center gap-1.5">
                            @if($jugador->numero_camiseta)
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-[#FEF3C7] text-xs font-bold text-[#92400E]" title="Número de camiseta">
                                    #{{ $jugador->numero_camiseta }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Datos complementarios -->
                    <div class="bg-gray-50 rounded-lg p-2.5 text-xs text-gray-600 space-y-1">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Equipo:</span>
                            <span class="font-medium text-gray-800 truncate ml-2">{{ $jugador->equipo->nombre ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Torneo:</span>
                            <span class="text-gray-700 truncate ml-2">{{ $jugador->equipo->torneo->nombre ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-1 border-t border-gray-200">
                            <span class="text-gray-400">DPI / Posición:</span>
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-gray-700">{{ $jugador->dni ?: '-' }}</span>
                                @if($jugador->posicion)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-200 text-gray-700">
                                        {{ $jugador->posicion }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Acciones Móviles -->
                    <div class="flex items-center justify-between pt-1">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-[#D1FAE5] text-[#065F46]">
                            Activo
                        </span>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('organizador.jugadores.edit', $jugador) }}" 
                               class="inline-flex items-center justify-center min-h-[36px] px-3 py-1.5 bg-amber-50 text-[#F59E0B] hover:bg-[#FEF3C7] rounded-lg text-xs font-semibold transition-colors">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Editar
                            </a>
                            <form action="{{ route('organizador.jugadores.destroy', $jugador) }}" method="POST" 
                                  class="inline" onsubmit="return confirm('¿Estás seguro de eliminar/desactivar este jugador?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center justify-center min-h-[36px] px-3 py-1.5 bg-red-50 text-[#EF4444] hover:bg-[#FEE2E2] rounded-lg text-xs font-semibold transition-colors">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <div class="text-gray-400 mb-2">
                        <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-600">No hay jugadores registrados.</p>
                    <p class="text-xs text-gray-400 mt-1">Comienza agregando el primer jugador a un equipo.</p>
                </div>
            @endforelse
        </div>

        <!-- Vista Desktop (Tabla) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b-2 border-gray-200">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">DPI</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Posición</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center"># Camiseta</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Equipo</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Torneo</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Estado</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($jugadores as $jugador)
                        <tr class="hover:bg-[#FEF3C7] transition-colors duration-150 group">
                            <!-- Nombre -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-[#FEF3C7] flex items-center justify-center text-[#92400E] font-bold text-xs">
                                        {{ substr($jugador->nombre_completo, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800 flex items-center gap-2">
                                            {{ $jugador->nombre_completo }}
                                            @if($jugador->es_capitan)
                                                <span class="text-xs bg-[#FEF3C7] text-[#92400E] px-2 py-0.5 rounded-full">Capitán</span>
                                            @endif
                                        </p>
                                        @if($jugador->email)
                                            <p class="text-xs text-gray-500">{{ $jugador->email }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- DNI -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $jugador->dni ?: '-' }}
                            </td>

                            <!-- Posición -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($jugador->posicion)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#F3F4F6] text-[#374151]">
                                        {{ $jugador->posicion }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>

                            <!-- # Camiseta -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($jugador->numero_camiseta)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#FEF3C7] text-sm font-bold text-[#92400E]">
                                        {{ $jugador->numero_camiseta }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>

                            <!-- Equipo -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $jugador->equipo->nombre ?? 'N/A' }}
                            </td>

                            <!-- Torneo -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $jugador->equipo->torneo->nombre ?? 'N/A' }}
                            </td>

                            <!-- Estado -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#D1FAE5] text-[#065F46]">
                                    Activo
                                </span>
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('organizador.jugadores.edit', $jugador) }}" 
                                       class="p-2 text-gray-500 hover:text-[#F59E0B] hover:bg-[#FEF3C7] rounded-lg transition-colors" 
                                       title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('organizador.jugadores.destroy', $jugador) }}" method="POST" 
                                          class="inline" onsubmit="return confirm('¿Estás seguro de eliminar/desactivar este jugador?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 text-gray-500 hover:text-[#EF4444] hover:bg-[#FEE2E2] rounded-lg transition-colors" 
                                                title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="text-gray-400 mb-3">
                                    <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-medium">No hay jugadores registrados.</p>
                                <p class="text-sm text-gray-400 mt-1">Comienza agregando el primer jugador a un equipo.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Nota informativa -->
    <div class="mt-4 sm:mt-6 bg-[#FEF3C7] border border-[#F59E0B] rounded-xl p-3.5 sm:p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-[#F59E0B] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div class="text-xs sm:text-sm text-[#92400E]">
            <p class="font-semibold mb-1">ℹ️ Información importante</p>
            <p class="text-[#78350F] leading-relaxed">
                Solo puedes gestionar jugadores de los equipos pertenecientes a los torneos que te fueron asignados. 
                Cada jugador debe estar vinculado a un equipo específico.
            </p>
        </div>
    </div>
@endsection
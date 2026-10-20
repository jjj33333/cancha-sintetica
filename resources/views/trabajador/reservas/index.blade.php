@extends('layouts.trabajador')

@section('header_title', 'Gestión de Reservas')
@section('title', 'Reservas - Trabajador')

@section('content')
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#065F46]">📅 Gestión de Reservas</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Administra las reservas manuales de los clientes.</p>
        </div>
        <a href="{{ route('trabajador.reservas.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-[#10B981] hover:bg-[#059669] text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nueva Reserva
        </a>
    </div>

    <!-- Mensajes -->
    @if(session('success'))
        <div class="mb-6 bg-[#D1FAE5] border border-[#10B981] text-[#065F46] p-3.5 sm:p-4 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-xs sm:text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-[#FEE2E2] border border-[#EF4444] text-[#991B1B] p-3.5 sm:p-4 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-xs sm:text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Tarjetas de Resumen Rápido -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-[#ECFDF5] flex items-center justify-center text-xl shrink-0">📅</div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Reservas Hoy</p>
                    <p class="text-xl font-bold text-[#065F46]">{{ $reservasHoy }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-[#FEF3C7] flex items-center justify-center text-xl shrink-0">⏳</div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Pendientes</p>
                    <p class="text-xl font-bold text-[#92400E]">{{ $reservasPendientes }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-[#DBEAFE] flex items-center justify-center text-xl shrink-0">⏰</div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Próxima Reserva</p>
                    <p class="text-sm font-bold text-[#1E40AF]">
                        {{ $proximaReserva ? \Carbon\Carbon::parse($proximaReserva->hora_inicio)->format('H:i') : 'Ninguna hoy' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y Opciones de visualización -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3.5 sm:p-4 mb-6">
        <form method="GET" action="{{ route('trabajador.reservas.index') }}" class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <!-- Búsqueda -->
                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Buscar</label>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Nombre cliente, teléfono o código..." 
                           class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
                </div>

                <!-- Estado -->
                <div>
                    <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Estado</label>
                    <select name="estado" class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
                        <option value="">Todos los estados</option>
                        <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="confirmada" {{ request('estado') === 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                        <option value="completada" {{ request('estado') === 'completada' ? 'selected' : '' }}>Completada</option>
                        <option value="cancelada" {{ request('estado') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>

                <!-- Fecha específica -->
                <div>
                    <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Fecha</label>
                    <input type="date" name="fecha" value="{{ request('fecha') }}" 
                           class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
                </div>
            </div>

            <!-- Segunda fila: Cancha, Tipo, Límite por página y Orden -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-1 border-t border-gray-100">
                <!-- Cancha -->
                <div>
                    <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Cancha</label>
                    <select name="instalacion_id" class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
                        <option value="">Todas las canchas</option>
                        @foreach($instalaciones as $inst)
                            <option value="{{ $inst->id }}" {{ request('instalacion_id') == $inst->id ? 'selected' : '' }}>{{ $inst->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipo -->
                <div>
                    <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Tipo</label>
                    <select name="tipo_reserva" class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
                        <option value="">Todos los tipos</option>
                        <option value="manual" {{ request('tipo_reserva') === 'manual' ? 'selected' : '' }}>Manual</option>
                        <option value="torneo" {{ request('tipo_reserva') === 'torneo' ? 'selected' : '' }}>Torneo</option>
                    </select>
                </div>

                <!-- Límite / Ver más -->
                <div>
                    <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Mostrar</label>
                    <select name="por_pagina" class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
                        <option value="10" {{ ($porPagina ?? 10) == 10 ? 'selected' : '' }}>10 por página</option>
                        <option value="25" {{ ($porPagina ?? 10) == 25 ? 'selected' : '' }}>25 por página</option>
                        <option value="50" {{ ($porPagina ?? 10) == 50 ? 'selected' : '' }}>50 por página</option>
                        <option value="100" {{ ($porPagina ?? 10) == 100 ? 'selected' : '' }}>100 por página</option>
                        <option value="todas" {{ ($porPagina ?? 10) > 500 ? 'selected' : '' }}>Ver todas</option>
                    </select>
                </div>

                <!-- Orden -->
                <div>
                    <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">Ordenar por</label>
                    <select name="orden" class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
                        <option value="reciente" {{ ($orden ?? 'reciente') === 'reciente' ? 'selected' : '' }}>Más reciente primero</option>
                        <option value="antigua" {{ ($orden ?? 'reciente') === 'antigua' ? 'selected' : '' }}>Más antigua primero</option>
                        <option value="creacion" {{ ($orden ?? 'reciente') === 'creacion' ? 'selected' : '' }}>Fecha de registro</option>
                    </select>
                </div>
            </div>

            <!-- Botones de Filtrado -->
            <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                <button type="submit" class="flex-1 sm:flex-none px-6 py-2 bg-[#065F46] hover:bg-[#047857] text-white text-sm font-semibold rounded-lg transition-colors text-center">
                    Filtrar
                </button>
                @if(request()->hasAny(['buscar', 'instalacion_id', 'estado', 'tipo_reserva', 'fecha', 'por_pagina', 'orden']))
                    <a href="{{ route('trabajador.reservas.index') }}" class="flex-1 sm:flex-none px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors text-center">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Vista de Reservas (Tarjetas en Móvil / Tabla en Desktop) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Vista Móvil (Tarjetas) -->
        <div class="block md:hidden divide-y divide-gray-100">
            @forelse($reservas as $reserva)
                @php
                    $estadoBadge = match($reserva->estado) {
                        'pendiente' => 'bg-[#FEF3C7] text-[#92400E]',
                        'confirmada' => 'bg-[#D1FAE5] text-[#065F46]',
                        'cancelada' => 'bg-[#FEE2E2] text-[#991B1B]',
                        'completada' => 'bg-[#DBEAFE] text-[#1E40AF]',
                        default => 'bg-gray-100 text-gray-600',
                    };
                    $pagoBadge = match($reserva->estado_pago) {
                        'pendiente' => 'bg-[#FEF3C7] text-[#92400E]',
                        'pagado' => 'bg-[#D1FAE5] text-[#065F46]',
                        'reembolsado' => 'bg-[#F3F4F6] text-[#374151]',
                        default => 'bg-gray-100 text-gray-600',
                    };
                @endphp
                <div class="p-4 space-y-3 {{ !$reserva->esta_activo ? 'opacity-50 bg-gray-50/50' : '' }}">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <span class="font-mono text-xs font-bold text-[#065F46] bg-emerald-50 px-2 py-0.5 rounded">
                                #{{ $reserva->codigo_reserva }}
                            </span>
                            <p class="font-bold text-gray-800 text-sm mt-1">{{ $reserva->nombre_cliente }}</p>
                            @if(!empty($reserva->telefono_cliente))
                                <p class="text-xs text-gray-500">📞 {{ $reserva->telefono_cliente }}</p>
                            @endif
                        </div>
                        <div class="flex flex-col items-end gap-1 shrink-0">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $estadoBadge }}">
                                {{ ucfirst($reserva->estado) }}
                            </span>
                            @if($reserva->tipo_reserva === 'torneo')
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-[#DBEAFE] text-[#1E40AF]">
                                    🏆 Torneo
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs text-gray-600 bg-gray-50 p-2.5 rounded-lg">
                        <div>
                            <span class="text-gray-400 block text-[10px] uppercase font-semibold">Fecha y Hora</span>
                            <span class="font-medium text-gray-700 block">{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y') }}</span>
                            <span class="text-gray-500 text-[11px]">{{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10px] uppercase font-semibold">Cancha / Total</span>
                            <span class="font-medium text-gray-700 block truncate">{{ $reserva->instalacion->nombre ?? 'Cancha Principal' }}</span>
                            <span class="font-bold text-[#065F46] text-xs">Q. {{ number_format($reserva->precio_total, 2) }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-gray-100 text-xs">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $pagoBadge }}">
                            Pago: {{ ucfirst($reserva->estado_pago ?? 'Pendiente') }}
                        </span>
                        <div class="flex items-center gap-1">
                            <a href="{{ route('trabajador.reservas.show', $reserva) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg" title="Ver detalles">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>

                            @if($reserva->tipo_reserva !== 'torneo')
                                <a href="{{ route('trabajador.reservas.edit', $reserva) }}" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg" title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>

                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="p-1.5 text-gray-500 hover:bg-gray-100 rounded-lg" title="Cambiar estado">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-44 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-20 text-xs">
                                        <form method="POST" action="{{ route('trabajador.reservas.cambiar-estado', $reserva) }}">
                                            @csrf
                                            <input type="hidden" name="estado" value="confirmada">
                                            <button type="submit" class="w-full text-left px-3 py-1.5 text-gray-700 hover:bg-[#D1FAE5] hover:text-[#065F46]">
                                                ✅ Confirmar
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('trabajador.reservas.cambiar-estado', $reserva) }}">
                                            @csrf
                                            <input type="hidden" name="estado" value="completada">
                                            <button type="submit" class="w-full text-left px-3 py-1.5 text-gray-700 hover:bg-[#DBEAFE] hover:text-[#1E40AF]">
                                                ✔️ Completar
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('trabajador.reservas.cambiar-estado', $reserva) }}" onsubmit="return confirm('¿Cancelar esta reserva?');">
                                            @csrf
                                            <input type="hidden" name="estado" value="cancelada">
                                            <button type="submit" class="w-full text-left px-3 py-1.5 text-gray-700 hover:bg-[#FEE2E2] hover:text-[#991B1B]">
                                                ❌ Cancelar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <p class="text-sm text-gray-500 font-medium">No hay reservas registradas.</p>
                </div>
            @endforelse
        </div>

        <!-- Vista Desktop (Tabla) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b-2 border-gray-200">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Código</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha/Hora</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Cancha</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Total</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Tipo</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Estado</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Pago</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reservas as $reserva)
                        <tr class="hover:bg-[#ECFDF5] transition-colors duration-150 group {{ !$reserva->esta_activo ? 'opacity-50' : '' }}">
                            <!-- Código -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-sm font-semibold text-[#065F46]">{{ $reserva->codigo_reserva }}</span>
                            </td>

                            <!-- Fecha/Hora -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('H:i') }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Cliente -->
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $reserva->nombre_cliente }}</p>
                                @if(!empty($reserva->telefono_cliente))
                                    <p class="text-xs text-gray-500">{{ $reserva->telefono_cliente }}</p>
                                @endif
                            </td>

                            <!-- Cancha -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-700">{{ $reserva->instalacion->nombre ?? 'Cancha Principal' }}</span>
                            </td>

                            <!-- Total -->
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="text-sm font-bold text-[#065F46]">Q. {{ number_format($reserva->precio_total, 2) }}</span>
                                <p class="text-xs text-gray-500">{{ $reserva->total_horas }}h</p>
                            </td>

                            <!-- Tipo -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($reserva->tipo_reserva === 'torneo')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-[#DBEAFE] text-[#1E40AF]">
                                        🏆 Torneo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-[#F3F4F6] text-[#374151]">
                                        👤 Manual
                                    </span>
                                @endif
                            </td>

                            <!-- Estado -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $estadoBadge = match($reserva->estado) {
                                        'pendiente' => 'bg-[#FEF3C7] text-[#92400E]',
                                        'confirmada' => 'bg-[#D1FAE5] text-[#065F46]',
                                        'cancelada' => 'bg-[#FEE2E2] text-[#991B1B]',
                                        'completada' => 'bg-[#DBEAFE] text-[#1E40AF]',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $estadoBadge }}">
                                    {{ ucfirst($reserva->estado) }}
                                </span>
                            </td>

                            <!-- Pago -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $pagoBadge = match($reserva->estado_pago) {
                                        'pendiente' => 'bg-[#FEF3C7] text-[#92400E]',
                                        'pagado' => 'bg-[#D1FAE5] text-[#065F46]',
                                        'reembolsado' => 'bg-[#F3F4F6] text-[#374151]',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $pagoBadge }}">
                                    {{ ucfirst($reserva->estado_pago) }}
                                </span>
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-1 opacity-80 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('trabajador.reservas.show', $reserva) }}" class="p-2 text-gray-500 hover:text-[#3B82F6] hover:bg-[#DBEAFE] rounded-lg transition-colors" title="Ver detalles">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>

                                    @if($reserva->tipo_reserva !== 'torneo')
                                        <a href="{{ route('trabajador.reservas.edit', $reserva) }}" class="p-2 text-gray-500 hover:text-[#F59E0B] hover:bg-[#FEF3C7] rounded-lg transition-colors" title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>

                                        <!-- Dropdown de acciones rápidas -->
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open" class="p-2 text-gray-500 hover:text-[#065F46] hover:bg-[#ECFDF5] rounded-lg transition-colors" title="Cambiar estado">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                            </button>
                                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-10 text-left">
                                                <form method="POST" action="{{ route('trabajador.reservas.cambiar-estado', $reserva) }}">
                                                    @csrf
                                                    <input type="hidden" name="estado" value="confirmada">
                                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-[#D1FAE5] hover:text-[#065F46]">
                                                        ✅ Confirmar
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('trabajador.reservas.cambiar-estado', $reserva) }}">
                                                    @csrf
                                                    <input type="hidden" name="estado" value="completada">
                                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-[#DBEAFE] hover:text-[#1E40AF]">
                                                        ✔️ Completar
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('trabajador.reservas.cambiar-estado', $reserva) }}" onsubmit="return confirm('¿Cancelar esta reserva?');">
                                                    @csrf
                                                    <input type="hidden" name="estado" value="cancelada">
                                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-[#FEE2E2] hover:text-[#991B1B]">
                                                        ❌ Cancelar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <span class="p-2 text-gray-300" title="Reserva de torneo - Solo lectura">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="text-gray-400 mb-3">
                                    <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <p class="text-gray-500 font-medium">No hay reservas registradas.</p>
                                <p class="text-sm text-gray-400 mt-1">Comienza creando una nueva reserva.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reservas->hasPages())
            <div class="px-4 sm:px-6 py-4 border-t border-gray-100 bg-[#F9FAFB]">
                {{ $reservas->links() }}
            </div>
        @endif
    </div>

    <!-- Nota informativa -->
    <div class="mt-6 bg-[#ECFDF5] border border-[#10B981] rounded-xl p-3.5 sm:p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-[#10B981] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div class="text-xs sm:text-sm text-[#065F46]">
            <p class="font-semibold mb-1">ℹ️ Información importante</p>
            <p class="text-gray-700">
                Las reservas marcadas con <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[11px] font-semibold bg-[#DBEAFE] text-[#1E40AF]">🏆 Torneo</span> 
                son generadas automáticamente por los organizadores al programar partidos. No puedes editarlas ni eliminarlas. 
                Si necesitas modificarlas, contacta al organizador del torneo.
            </p>
        </div>
    </div>
@endsection
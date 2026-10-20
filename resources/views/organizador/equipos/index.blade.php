@extends('layouts.organizador')

@section('header_title', 'Equipos de Mis Torneos')
@section('title', 'Equipos - Organizador')

@section('content')
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-3 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#065F46] flex items-center gap-2">
                <span>👥</span> <span>Equipos de Mis Torneos</span>
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5 sm:mt-1">Gestiona los equipos de los torneos que te fueron asignados.</p>
        </div>
        @if($torneoSeleccionado)
            <a href="{{ route('organizador.equipos.create', ['torneo_id' => $torneoSeleccionado->id]) }}" 
               class="w-full sm:w-auto inline-flex items-center justify-center min-h-[44px] px-4 py-2.5 bg-[#F59E0B] hover:bg-[#D97706] text-white text-xs sm:text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Agregar Equipo</span>
            </a>
        @endif
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

    @if($torneosAsignados->count() === 0)
        <!-- Estado Vacío: Sin torneos asignados -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-12 text-center">
            <div class="text-gray-400 mb-3 sm:mb-4">
                <svg class="w-16 h-16 sm:w-20 sm:h-20 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-2">No tienes torneos asignados</h3>
            <p class="text-xs sm:text-sm text-gray-500 max-w-md mx-auto">
                El administrador aún no te ha asignado ningún torneo. 
                Contacta al administrador para que te asigne torneos a gestionar.
            </p>
        </div>
    @else
        <!-- Selector y Filtros de Torneo -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3.5 sm:p-4 mb-4 sm:mb-6">
            <form method="GET" action="{{ route('organizador.equipos.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
                <div class="lg:col-span-5">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Seleccionar Torneo</label>
                    <select name="torneo_id" onchange="this.form.submit()" 
                            class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm">
                        @foreach($torneosAsignados as $torneo)
                            <option value="{{ $torneo->id }}" {{ $torneoSeleccionado && $torneoSeleccionado->id === $torneo->id ? 'selected' : '' }}>
                                {{ $torneo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="lg:col-span-3">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Buscar</label>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Equipo o capitán..." 
                           class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm">
                </div>
                <div class="lg:col-span-2">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Estado</label>
                    <select name="estado" onchange="this.form.submit()" 
                            class="w-full px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent outline-none text-base sm:text-sm">
                        <option value="">Todos</option>
                        <option value="registrado" {{ request('estado') === 'registrado' ? 'selected' : '' }}>Registrado</option>
                        <option value="confirmado" {{ request('estado') === 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                        <option value="campeon" {{ request('estado') === 'campeon' ? 'selected' : '' }}>Campeón</option>
                        <option value="subcampeon" {{ request('estado') === 'subcampeon' ? 'selected' : '' }}>Subcampeón</option>
                    </select>
                </div>
                <div class="lg:col-span-2">
                    <button type="submit" class="w-full min-h-[42px] px-4 py-2.5 bg-[#065F46] hover:bg-[#047857] text-white font-medium rounded-lg transition-colors text-xs sm:text-sm flex items-center justify-center">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        @if($torneoSeleccionado)
            <!-- Información del Torneo -->
            <div class="bg-gradient-to-r from-[#F59E0B] to-[#D97706] rounded-xl shadow-sm p-4 sm:p-6 mb-4 sm:mb-6 text-white">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg sm:text-xl font-bold flex items-center gap-2">
                            <span>
                                @if($torneoSeleccionado->categoria === 'libre') 🏆
                                @elseif($torneoSeleccionado->categoria === 'sub20') 
                                @elseif($torneoSeleccionado->categoria === 'femenino') ⭐
                                @else 🎯
                                @endif
                            </span>
                            <span class="break-words">{{ $torneoSeleccionado->nombre }}</span>
                        </h2>
                        <p class="text-xs sm:text-sm text-white/90 mt-1">
                            {{ $equipos->count() }} equipo{{ $equipos->count() !== 1 ? 's' : '' }} registrado{{ $equipos->count() !== 1 ? 's' : '' }}
                            @if($torneoSeleccionado->max_equipos)
                                de {{ $torneoSeleccionado->max_equipos }} permitidos
                            @endif
                        </p>
                    </div>
                    <div class="self-start sm:self-auto">
                        @php
                            $estadoBadge = match($torneoSeleccionado->estado) {
                                'en_curso' => 'bg-white/20 text-white',
                                'inscripciones_abiertas' => 'bg-white/20 text-white',
                                'borrador' => 'bg-white/10 text-white/80',
                                default => 'bg-white/10 text-white/80',
                            };
                            $estadoTexto = match($torneoSeleccionado->estado) {
                                'en_curso' => 'En curso',
                                'inscripciones_abiertas' => 'Inscripciones abiertas',
                                'borrador' => 'Borrador',
                                'finalizado' => 'Finalizado',
                                default => ucfirst($torneoSeleccionado->estado),
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 sm:px-3 py-1 rounded-full text-xs font-semibold {{ $estadoBadge }}">
                            {{ $estadoTexto }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Listado de Equipos: Vista Móvil + Vista Desktop -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Vista Móvil (Tarjetas) -->
                <div class="block md:hidden divide-y divide-gray-100">
                    @forelse($equipos as $equipo)
                        <div class="p-4 space-y-3 {{ !$equipo->esta_activo ? 'opacity-60 bg-gray-50' : '' }}">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    @if($equipo->logo)
                                        <img src="{{ Storage::url($equipo->logo) }}" alt="{{ $equipo->nombre }}" 
                                             class="w-11 h-11 rounded-full object-cover flex-shrink-0 border border-gray-200">
                                    @else
                                        <div class="w-11 h-11 rounded-full bg-[#FEF3C7] flex items-center justify-center text-[#92400E] font-bold text-base flex-shrink-0">
                                            {{ substr($equipo->nombre, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-800 text-sm truncate">{{ $equipo->nombre }}</p>
                                        <div class="flex flex-wrap items-center gap-2 mt-0.5">
                                            @if($equipo->nombre_corto)
                                                <span class="text-xs text-gray-500 font-mono bg-gray-100 px-1.5 py-0.5 rounded">{{ $equipo->nombre_corto }}</span>
                                            @endif
                                            @if($equipo->color_uniforme)
                                                <span class="text-[11px] text-gray-500">🎨 {{ $equipo->color_uniforme }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    @php
                                        $estadoBadge = match($equipo->estado) {
                                            'confirmado' => 'bg-[#D1FAE5] text-[#065F46]',
                                            'registrado' => 'bg-[#FEF3C7] text-[#92400E]',
                                            'campeon' => 'bg-[#FEF3C7] text-[#92400E]',
                                            'subcampeon' => 'bg-[#DBEAFE] text-[#1E40AF]',
                                            'eliminado' => 'bg-[#FEE2E2] text-[#991B1B]',
                                            default => 'bg-gray-100 text-gray-600',
                                        };
                                        $estadoTexto = match($equipo->estado) {
                                            'confirmado' => 'Confirmado',
                                            'registrado' => 'Registrado',
                                            'campeon' => '🏆 Campeón',
                                            'subcampeon' => '🥈 Subcampeón',
                                            'eliminado' => 'Eliminado',
                                            default => ucfirst($equipo->estado),
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $estadoBadge }}">
                                        {{ $estadoTexto }}
                                    </span>
                                </div>
                            </div>

                            <!-- Detalles de Capitán y Jugadores -->
                            <div class="bg-gray-50 rounded-lg p-2.5 text-xs text-gray-600 space-y-1">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400">Capitán:</span>
                                    <span class="font-medium text-gray-800">{{ $equipo->nombre_capitan }}</span>
                                </div>
                                @if($equipo->telefono_capitan)
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-400">Teléfono:</span>
                                        <a href="tel:{{ $equipo->telefono_capitan }}" class="text-[#065F46] font-medium hover:underline">{{ $equipo->telefono_capitan }}</a>
                                    </div>
                                @endif
                                <div class="flex justify-between items-center pt-1 border-t border-gray-200">
                                    <span class="text-gray-400">Jugadores registrados:</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-white font-bold text-gray-800 border border-gray-200">
                                        {{ $equipo->jugadores_count }}
                                    </span>
                                </div>
                            </div>

                            <!-- Botones de Acción Móvil -->
                            <div class="flex items-center justify-end gap-2 pt-1">
                                <a href="{{ route('organizador.equipos.edit', $equipo) }}" 
                                   class="inline-flex items-center justify-center min-h-[36px] px-3 py-1.5 bg-amber-50 text-[#F59E0B] hover:bg-[#FEF3C7] rounded-lg text-xs font-semibold transition-colors">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Editar
                                </a>
                                <form action="{{ route('organizador.equipos.destroy', $equipo) }}" method="POST" 
                                      class="inline" onsubmit="return confirm('¿Estás seguro de eliminar/desactivar este equipo?');">
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
                    @empty
                        <div class="p-8 text-center">
                            <div class="text-gray-400 mb-2">
                                <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-600">No hay equipos registrados en este torneo.</p>
                            <p class="text-xs text-gray-400 mt-1">Comienza agregando el primer equipo.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Vista Desktop (Tabla) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#F9FAFB] border-b-2 border-gray-200">
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Equipo</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Capitán</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Teléfono</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Jugadores</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($equipos as $equipo)
                                <tr class="hover:bg-[#FEF3C7] transition-colors duration-150 group {{ !$equipo->esta_activo ? 'opacity-50' : '' }}">
                                    <!-- Equipo -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($equipo->logo)
                                                <img src="{{ Storage::url($equipo->logo) }}" alt="{{ $equipo->nombre }}" 
                                                     class="w-10 h-10 rounded-full object-cover">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-[#FEF3C7] flex items-center justify-center text-[#92400E] font-bold text-sm">
                                                    {{ substr($equipo->nombre, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-semibold text-gray-800">{{ $equipo->nombre }}</p>
                                                @if($equipo->nombre_corto)
                                                    <p class="text-xs text-gray-500">{{ $equipo->nombre_corto }}</p>
                                                @endif
                                                @if($equipo->color_uniforme)
                                                    <p class="text-xs text-gray-400">🎨 {{ $equipo->color_uniforme }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Capitán -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $equipo->nombre_capitan }}</div>
                                        @if($equipo->email_capitan)
                                            <div class="text-xs text-gray-500">{{ $equipo->email_capitan }}</div>
                                        @endif
                                    </td>

                                    <!-- Teléfono -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $equipo->telefono_capitan }}
                                    </td>

                                    <!-- Jugadores -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#F3F4F6] text-sm font-medium text-gray-700">
                                            {{ $equipo->jugadores_count }}
                                        </span>
                                    </td>

                                    <!-- Estado -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $estadoBadge = match($equipo->estado) {
                                                'confirmado' => 'bg-[#D1FAE5] text-[#065F46]',
                                                'registrado' => 'bg-[#FEF3C7] text-[#92400E]',
                                                'campeon' => 'bg-[#FEF3C7] text-[#92400E]',
                                                'subcampeon' => 'bg-[#DBEAFE] text-[#1E40AF]',
                                                'eliminado' => 'bg-[#FEE2E2] text-[#991B1B]',
                                                default => 'bg-gray-100 text-gray-600',
                                            };
                                            $estadoTexto = match($equipo->estado) {
                                                'confirmado' => 'Confirmado',
                                                'registrado' => 'Registrado',
                                                'campeon' => '🏆 Campeón',
                                                'subcampeon' => '🥈 Subcampeón',
                                                'eliminado' => 'Eliminado',
                                                default => ucfirst($equipo->estado),
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $estadoBadge }}">
                                            {{ $estadoTexto }}
                                        </span>
                                    </td>

                                    <!-- Acciones -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('organizador.equipos.edit', $equipo) }}" 
                                               class="p-2 text-gray-500 hover:text-[#F59E0B] hover:bg-[#FEF3C7] rounded-lg transition-colors" 
                                               title="Editar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('organizador.equipos.destroy', $equipo) }}" method="POST" 
                                                  class="inline" onsubmit="return confirm('¿Estás seguro de eliminar/desactivar este equipo?');">
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
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="text-gray-400 mb-3">
                                            <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 font-medium">No hay equipos registrados en este torneo.</p>
                                        <p class="text-sm text-gray-400 mt-1">Comienza agregando el primer equipo.</p>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-xs sm:text-sm text-[#92400E]">
                    <p class="font-semibold mb-1">ℹ️ Información importante</p>
                    <p class="text-[#78350F] leading-relaxed">
                        Puedes agregar, editar y eliminar equipos de tus torneos asignados. 
                        Los jugadores de cada equipo se gestionan desde el módulo "Jugadores".
                    </p>
                </div>
            </div>
        @endif
    @endif
@endsection
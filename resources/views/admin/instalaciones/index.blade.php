@extends('layouts.admin')

@section('header_title', 'Gestión de Instalaciones')
@section('title', 'Instalaciones - Admin')

@section('content')
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#065F46]">🏟️ Instalaciones</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Administra las canchas e instalaciones del complejo deportivo.</p>
        </div>
        <a href="{{ route('admin.instalaciones.create') }}" class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-[#10B981] hover:bg-[#059669] text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nueva Instalación
        </a>
    </div>

    <!-- Mensaje de éxito -->
    @if(session('success'))
        <div class="mb-6 bg-[#D1FAE5] border border-[#10B981] text-[#065F46] px-4 py-3 rounded-lg flex items-center gap-3 text-sm">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Grid de Cards de Instalaciones -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @forelse($instalaciones as $instalacion)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all hover:-translate-y-1 {{ !$instalacion->esta_activo ? 'opacity-60' : '' }}">
                <!-- Header de la Card -->
                <div class="bg-gradient-to-r from-[#065F46] to-[#10B981] p-4 text-white">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold">{{ $instalacion->nombre }}</h3>
                        @if($instalacion->esta_activo)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-white/20 text-white">
                                Activa
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#FEE2E2] text-[#991B1B]">
                                Inactiva
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-white/80 mt-1 capitalize">{{ str_replace('_', ' ', $instalacion->tipo) }}</p>
                </div>

                <!-- Contenido de la Card -->
                <div class="p-6 space-y-4">
                    <!-- Dimensiones y Capacidad -->
                    <div class="flex items-center gap-4">
                        @if($instalacion->dimensiones)
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                                <span class="text-sm font-medium text-gray-700">{{ $instalacion->dimensiones }}</span>
                            </div>
                        @endif
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span class="text-sm font-medium text-gray-700">{{ $instalacion->capacidad }} jugadores</span>
                        </div>
                    </div>

                    <!-- Tipo de Superficie -->
                    @if($instalacion->tipo_superficie)
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            <span class="text-sm text-gray-600">{{ $instalacion->tipo_superficie }}</span>
                        </div>
                    @endif

                    <!-- Características -->
                    <div class="space-y-2 pt-3 border-t border-gray-100">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">{{ $instalacion->tiene_iluminacion ? '' : '⚫' }}</span>
                            <span class="text-sm text-gray-600">Iluminación {{ $instalacion->tiene_iluminacion ? 'disponible' : 'no disponible' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-lg">{{ $instalacion->tiene_estacionamiento ? '🚗' : '⚫' }}</span>
                            <span class="text-sm text-gray-600">Estacionamiento {{ $instalacion->tiene_estacionamiento ? 'disponible' : 'no disponible' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-lg">{{ $instalacion->tiene_casilleros ? '🚿' : '⚫' }}</span>
                            <span class="text-sm text-gray-600">Casilleros {{ $instalacion->tiene_casilleros ? 'disponibles' : 'no disponibles' }}</span>
                        </div>
                    </div>

                    <!-- Descripción -->
                    @if($instalacion->descripcion)
                        <div class="pt-3 border-t border-gray-100">
                            <p class="text-sm text-gray-600 line-clamp-3">{{ Str::limit($instalacion->descripcion, 100) }}</p>
                        </div>
                    @endif
                </div>

                <!-- Footer de la Card (Acciones) -->
                <div class="px-6 py-4 bg-[#F9FAFB] border-t border-gray-100 flex justify-between items-center">
                    <a href="{{ route('admin.instalaciones.show', $instalacion) }}" class="inline-flex items-center text-sm text-[#10B981] hover:text-[#059669] font-medium transition-colors">
                        Ver detalles
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.instalaciones.edit', $instalacion) }}" class="p-2 text-gray-500 hover:text-[#F59E0B] hover:bg-[#FEF3C7] rounded-lg transition-colors" title="Editar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        @if($instalacion->esta_activo)
                            <form action="{{ route('admin.instalaciones.destroy', $instalacion) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de desactivar esta instalación?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-500 hover:text-[#EF4444] hover:bg-[#FEE2E2] rounded-lg transition-colors" title="Desactivar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <div class="text-gray-400 mb-3">
                        <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <p class="text-gray-500 font-medium">No hay instalaciones registradas aún.</p>
                    <p class="text-sm text-gray-400 mt-1">Comienza creando una nueva instalación.</p>
                </div>
            </div>
        @endforelse
    </div>
@endsection
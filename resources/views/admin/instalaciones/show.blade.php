@extends('layouts.admin')

@section('header_title', 'Detalles de la Instalación')
@section('title', $instalacion->nombre . ' - Admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start gap-4">
            <div>
                <a href="{{ route('admin.instalaciones.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-[#10B981] transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a la lista
                </a>
                <h1 class="text-xl sm:text-2xl font-bold text-[#065F46] mt-2">🏟️ {{ $instalacion->nombre }}</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1 capitalize">{{ str_replace('_', ' ', $instalacion->tipo) }}</p>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <a href="{{ route('admin.instalaciones.edit', $instalacion) }}" class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-[#F59E0B] hover:bg-[#D97706] text-white text-sm font-semibold rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Editar
                </a>
            </div>
        </div>

        <!-- Estado -->
        <div class="mb-6">
            @if($instalacion->esta_activo)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-[#D1FAE5] text-[#065F46]">
                    ✅ Activa
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-[#FEE2E2] text-[#991B1B]">
                    ❌ Inactiva
                </span>
            @endif
        </div>

        <!-- Información Detallada -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Información General -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Información General</h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Nombre:</span>
                        <span class="font-medium text-gray-800">{{ $instalacion->nombre }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Tipo:</span>
                        <span class="font-medium text-gray-800 capitalize">{{ str_replace('_', ' ', $instalacion->tipo) }}</span>
                    </div>
                    @if($instalacion->dimensiones)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Dimensiones:</span>
                        <span class="font-medium text-gray-800">{{ $instalacion->dimensiones }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Capacidad:</span>
                        <span class="font-medium text-gray-800">{{ $instalacion->capacidad }} jugadores</span>
                    </div>
                    @if($instalacion->tipo_superficie)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Tipo de Superficie:</span>
                        <span class="font-medium text-gray-800">{{ $instalacion->tipo_superficie }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Características -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Características</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-[#F9FAFB] rounded-lg">
                        <span class="text-xs sm:text-sm text-gray-700">💡 Iluminación</span>
                        <span class="text-xs sm:text-sm font-medium {{ $instalacion->tiene_iluminacion ? 'text-[#10B981]' : 'text-gray-400' }}">
                            {{ $instalacion->tiene_iluminacion ? 'Disponible' : 'No disponible' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-[#F9FAFB] rounded-lg">
                        <span class="text-xs sm:text-sm text-gray-700">🚗 Estacionamiento</span>
                        <span class="text-xs sm:text-sm font-medium {{ $instalacion->tiene_estacionamiento ? 'text-[#10B981]' : 'text-gray-400' }}">
                            {{ $instalacion->tiene_estacionamiento ? 'Disponible' : 'No disponible' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-[#F9FAFB] rounded-lg">
                        <span class="text-xs sm:text-sm text-gray-700">🚿 Casilleros</span>
                        <span class="text-xs sm:text-sm font-medium {{ $instalacion->tiene_casilleros ? 'text-[#10B981]' : 'text-gray-400' }}">
                            {{ $instalacion->tiene_casilleros ? 'Disponibles' : 'No disponibles' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Descripción -->
        @if($instalacion->descripcion)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Descripción</h2>
                <p class="text-gray-700 leading-relaxed text-sm">{{ $instalacion->descripcion }}</p>
            </div>
        @endif

        <!-- Información de Auditoría -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Información del Sistema</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500">Creado el</p>
                    <p class="text-sm font-medium text-gray-800">{{ $instalacion->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Actualizado el</p>
                    <p class="text-sm font-medium text-gray-800">{{ $instalacion->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                @if($instalacion->actualizadoPorUsuario)
                <div class="md:col-span-2">
                    <p class="text-xs text-gray-500">Última actualización por</p>
                    <p class="text-sm font-medium text-gray-800">{{ $instalacion->actualizadoPorUsuario->nombre }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
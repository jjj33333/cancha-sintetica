@extends('layouts.admin')

@section('header_title', 'Detalles de la Tarifa')
@section('title', $tarifa->nombre . ' - Admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('admin.tarifas.index') }}" class="inline-flex items-center text-xs sm:text-sm text-gray-600 hover:text-[#10B981] transition-colors">
                    <svg class="w-4 h-4 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a la lista
                </a>
                <h1 class="text-xl sm:text-2xl font-bold text-[#065F46] mt-2 flex items-center gap-2 sm:gap-3">
                    <span class="text-2xl sm:text-3xl">💰</span>
                    <span>{{ $tarifa->nombre }}</span>
                </h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.tarifas.edit', $tarifa) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-[#F59E0B] hover:bg-[#D97706] text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Editar
                </a>
            </div>
        </div>

        <!-- Estado -->
        <div class="mb-6">
            @if($tarifa->esta_activo)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-semibold bg-[#D1FAE5] text-[#065F46]">
                    ✅ Activa
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-semibold bg-[#FEE2E2] text-[#991B1B]">
                    ❌ Inactiva
                </span>
            @endif
        </div>

        <!-- Información Detallada -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-6">
            <!-- Información General -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Información General</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs sm:text-sm text-gray-500">Nombre:</span>
                        <span class="text-xs sm:text-sm font-medium text-gray-800 text-right">{{ $tarifa->nombre }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs sm:text-sm text-gray-500">Instalación:</span>
                        <span class="text-xs sm:text-sm font-medium text-gray-800 text-right">{{ $tarifa->instalacion->nombre ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs sm:text-sm text-gray-500">Tipo de Día:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $tarifa->tipo_dia_badge }}">
                            {{ $tarifa->tipo_dia_texto }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs sm:text-sm text-gray-500">Moneda:</span>
                        <span class="text-xs sm:text-sm font-medium text-gray-800 text-right">{{ $tarifa->moneda ?? 'GTQ' }}</span>
                    </div>
                </div>
            </div>

            <!-- Horario y Precio -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Horario y Precio</h2>
                <div class="space-y-4">
                    <div class="p-3 sm:p-4 bg-[#F9FAFB] rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Horario</p>
                        <p class="text-base sm:text-lg font-bold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#10B981] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>{{ $tarifa->horario_formateado }}</span>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Duración: {{ $tarifa->duracion_horas }} hora{{ $tarifa->duracion_horas != 1 ? 's' : '' }}</p>
                    </div>
                    <div class="p-3 sm:p-4 bg-[#ECFDF5] rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Precio por Hora</p>
                        <p class="text-2xl sm:text-3xl font-bold text-[#065F46]">{{ $tarifa->precio_formateado }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de Auditoría -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Información del Sistema</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500">Creado el</p>
                    <p class="text-xs sm:text-sm font-medium text-gray-800">{{ $tarifa->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Actualizado el</p>
                    <p class="text-xs sm:text-sm font-medium text-gray-800">{{ $tarifa->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                @if($tarifa->actualizadoPorUsuario)
                <div class="sm:col-span-2">
                    <p class="text-xs text-gray-500">Última actualización por</p>
                    <p class="text-xs sm:text-sm font-medium text-gray-800">{{ $tarifa->actualizadoPorUsuario->nombre }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
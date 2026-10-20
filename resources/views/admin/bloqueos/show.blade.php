@extends('layouts.admin')

@section('header_title', 'Detalles del Bloqueo')
@section('title', 'Bloqueo - Admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <a href="{{ route('admin.bloqueos.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-[#10B981] transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a la lista
                </a>
                <h1 class="text-2xl font-bold text-[#065F46] mt-2 flex items-center gap-3">
                    <span class="text-3xl">{{ $bloqueo->motivo_icono }}</span>
                    {{ $bloqueo->motivo_texto }}
                </h1>
                <p class="text-sm text-gray-500 mt-1">{{ $bloqueo->fecha_bloqueo->format('d/m/Y') }}</p>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <a href="{{ route('admin.bloqueos.edit', $bloqueo) }}" class="w-full sm:w-auto justify-center inline-flex items-center px-4 py-2 bg-[#F59E0B] hover:bg-[#D97706] text-white text-sm font-semibold rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Editar
                </a>
            </div>
        </div>

        <!-- Estado -->
        <div class="mb-6">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $bloqueo->estado_badge }}">
                {{ $bloqueo->estado_texto }}
            </span>
        </div>

        <!-- Información Detallada -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Información General -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Información General</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Motivo:</span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $bloqueo->motivo_badge }}">
                            {{ $bloqueo->motivo_icono }} {{ $bloqueo->motivo_texto }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Fecha:</span>
                        <span class="text-sm font-medium text-gray-800">{{ $bloqueo->fecha_bloqueo->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Horario:</span>
                        <span class="text-sm font-medium text-gray-800">
                            @if($bloqueo->es_dia_completo)
                                Todo el día
                            @else
                                {{ $bloqueo->horario_formateado }}
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Instalación:</span>
                        <span class="text-sm font-medium text-gray-800">{{ $bloqueo->instalacion->nombre ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Descripción -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Descripción</h2>
                @if($bloqueo->descripcion)
                    <p class="text-gray-700 leading-relaxed">{{ $bloqueo->descripcion }}</p>
                @else
                    <p class="text-gray-400 italic">Sin descripción</p>
                @endif
            </div>
        </div>

        <!-- Información de Auditoría -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Información del Sistema</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500">Creado el</p>
                    <p class="text-sm font-medium text-gray-800">{{ $bloqueo->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Actualizado el</p>
                    <p class="text-sm font-medium text-gray-800">{{ $bloqueo->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                @if($bloqueo->creadoPorUsuario)
                <div>
                    <p class="text-xs text-gray-500">Creado por</p>
                    <p class="text-sm font-medium text-gray-800">{{ $bloqueo->creadoPorUsuario->nombre }}</p>
                </div>
                @endif
                @if($bloqueo->actualizadoPorUsuario)
                <div>
                    <p class="text-xs text-gray-500">Última actualización por</p>
                    <p class="text-sm font-medium text-gray-800">{{ $bloqueo->actualizadoPorUsuario->nombre }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
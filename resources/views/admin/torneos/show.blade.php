@extends('layouts.admin')

@section('header_title', 'Detalles del Torneo')
@section('title', 'Detalles - ' . $torneo->nombre)

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('admin.torneos.index') }}" class="inline-flex items-center text-xs sm:text-sm text-gray-600 hover:text-[#10B981] transition-colors">
                    <svg class="w-4 h-4 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a la lista
                </a>
                <h1 class="text-xl sm:text-2xl font-bold text-[#065F46] mt-2 flex items-center gap-2">
                    <span>🏆</span>
                    <span>{{ $torneo->nombre }}</span>
                </h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.torneos.edit', $torneo) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-[#F59E0B] hover:bg-[#D97706] text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Editar
                </a>
            </div>
        </div>

        @php
            $badgeClass = match($torneo->estado) {
                'en_curso' => 'bg-[#D1FAE5] text-[#065F46]',
                'inscripciones_abiertas' => 'bg-[#FEF3C7] text-[#92400E]',
                'borrador' => 'bg-[#F3F4F6] text-[#374151]',
                'finalizado' => 'bg-[#DBEAFE] text-[#1E40AF]',
                'cancelado' => 'bg-[#FEE2E2] text-[#991B1B]',
                default => 'bg-gray-100 text-gray-600',
            };
            $estadoTexto = match($torneo->estado) {
                'en_curso' => 'En curso',
                'inscripciones_abiertas' => 'Inscripciones Abiertas',
                'borrador' => 'Borrador',
                'finalizado' => 'Finalizado',
                'cancelado' => 'Cancelado',
                default => ucfirst($torneo->estado),
            };
        @endphp

        <!-- Grid de Información -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-6">
            <!-- Información General -->
            <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Información General</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs sm:text-sm text-gray-500">Categoría:</span>
                        <span class="text-xs sm:text-sm font-medium text-gray-800 capitalize text-right">{{ str_replace('_', ' ', $torneo->categoria) }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs sm:text-sm text-gray-500">Tipo:</span>
                        <span class="text-xs sm:text-sm font-medium text-gray-800 capitalize text-right">{{ str_replace('_', ' ', $torneo->tipo_torneo) }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs sm:text-sm text-gray-500">Instalación:</span>
                        <span class="text-xs sm:text-sm font-medium text-gray-800 text-right">{{ $torneo->instalacion->nombre ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs sm:text-sm text-gray-500">Organizador:</span>
                        <span class="text-xs sm:text-sm font-medium text-gray-800 text-right">{{ $torneo->organizador->nombre ?? 'Sin asignar' }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs sm:text-sm text-gray-500">Estado:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badgeClass }}">{{ $estadoTexto }}</span>
                    </div>
                </div>

                @if($torneo->descripcion)
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <h3 class="text-xs sm:text-sm font-semibold text-gray-700 mb-2">Descripción</h3>
                        <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">{{ $torneo->descripcion }}</p>
                    </div>
                @endif
            </div>

            <!-- Resumen Rápido -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Resumen</h2>
                <div class="grid grid-cols-3 md:grid-cols-1 gap-2 sm:gap-4">
                    <div class="text-center p-2.5 sm:p-3 bg-[#ECFDF5] rounded-lg">
                        <p class="text-xl sm:text-2xl font-bold text-[#065F46]">{{ $torneo->equipos->count() }}</p>
                        <p class="text-[11px] sm:text-xs text-gray-600 mt-0.5">Equipos</p>
                    </div>
                    <div class="text-center p-2.5 sm:p-3 bg-[#FEF3C7] rounded-lg">
                        <p class="text-xl sm:text-2xl font-bold text-[#92400E]">{{ $torneo->max_equipos }}</p>
                        <p class="text-[11px] sm:text-xs text-gray-600 mt-0.5">Máx. Equipos</p>
                    </div>
                    <div class="text-center p-2.5 sm:p-3 bg-[#DBEAFE] rounded-lg">
                        <p class="text-xl sm:text-2xl font-bold text-[#1E40AF]">{{ $torneo->jugadores_por_equipo }}</p>
                        <p class="text-[11px] sm:text-xs text-gray-600 mt-0.5">Jug./Equipo</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fechas -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-6">
            <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Fechas Importantes</h2>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <div class="p-3 sm:p-4 bg-gray-50 rounded-lg">
                    <p class="text-[11px] sm:text-xs text-gray-500 mb-1">Inicio de Inscripciones</p>
                    <p class="text-xs sm:text-sm font-semibold text-gray-800">{{ $torneo->inscripcion_inicio->format('d/m/Y') }}</p>
                </div>
                <div class="p-3 sm:p-4 bg-gray-50 rounded-lg">
                    <p class="text-[11px] sm:text-xs text-gray-500 mb-1">Fin de Inscripciones</p>
                    <p class="text-xs sm:text-sm font-semibold text-gray-800">{{ $torneo->inscripcion_fin->format('d/m/Y') }}</p>
                </div>
                <div class="p-3 sm:p-4 bg-gray-50 rounded-lg">
                    <p class="text-[11px] sm:text-xs text-gray-500 mb-1">Inicio del Torneo</p>
                    <p class="text-xs sm:text-sm font-semibold text-gray-800">{{ $torneo->fecha_inicio->format('d/m/Y') }}</p>
                </div>
                <div class="p-3 sm:p-4 bg-gray-50 rounded-lg">
                    <p class="text-[11px] sm:text-xs text-gray-500 mb-1">Fin del Torneo</p>
                    <p class="text-xs sm:text-sm font-semibold text-gray-800">{{ $torneo->fecha_fin->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Costos y Premios -->
        @if($torneo->costo_inscripcion || $torneo->descripcion_premios)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Costos y Premios</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($torneo->costo_inscripcion)
                        <div>
                            <p class="text-xs sm:text-sm text-gray-500 mb-1">Costo de Inscripción</p>
                            <p class="text-lg sm:text-xl font-bold text-[#065F46]">Q. {{ number_format($torneo->costo_inscripcion, 2) }}</p>
                        </div>
                    @endif
                    @if($torneo->descripcion_premios)
                        <div class="md:col-span-2">
                            <p class="text-xs sm:text-sm text-gray-500 mb-1">Premios</p>
                            <p class="text-xs sm:text-sm text-gray-700 leading-relaxed">{{ $torneo->descripcion_premios }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Reglas -->
        @if($torneo->reglas)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Reglas del Torneo</h2>
                <div class="prose prose-sm text-gray-600 whitespace-pre-line text-xs sm:text-sm">
                    {{ $torneo->reglas }}
                </div>
            </div>
        @endif
    </div>
@endsection
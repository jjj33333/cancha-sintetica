@extends('layouts.admin')

@section('header_title', 'Historial de Reserva')
@section('title', 'Historial Reserva - Admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.reservas.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-[#10B981] transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a reservas
            </a>
            <h1 class="text-xl sm:text-2xl font-bold text-[#065F46] mt-2">📋 Historial de Cambios - Reserva #{{ $reservaId }}</h1>
        </div>

        <!-- Timeline de Cambios -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
            @forelse($historial as $cambio)
                <div class="relative pl-8 pb-6 border-l-2 border-gray-200 last:border-0 last:pb-0">
                    <!-- Punto del timeline -->
                    <div class="absolute left-0 top-0 w-4 h-4 rounded-full bg-[#10B981] border-4 border-white shadow-sm -translate-x-1/2"></div>
                    
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                        <div class="flex items-center gap-3">
                            @php
                                $estadoBadgeAnterior = $cambio->estado_anterior ? match($cambio->estado_anterior) {
                                    'pendiente' => 'bg-[#FEF3C7] text-[#92400E]',
                                    'confirmada' => 'bg-[#D1FAE5] text-[#065F46]',
                                    'cancelada' => 'bg-[#FEE2E2] text-[#991B1B]',
                                    'completada' => 'bg-[#DBEAFE] text-[#1E40AF]',
                                    default => 'bg-gray-100 text-gray-600',
                                } : null;
                                
                                $estadoBadgeNuevo = match($cambio->estado_nuevo) {
                                    'pendiente' => 'bg-[#FEF3C7] text-[#92400E]',
                                    'confirmada' => 'bg-[#D1FAE5] text-[#065F46]',
                                    'cancelada' => 'bg-[#FEE2E2] text-[#991B1B]',
                                    'completada' => 'bg-[#DBEAFE] text-[#1E40AF]',
                                    default => 'bg-gray-100 text-gray-600',
                                };
                            @endphp

                            @if($cambio->estado_anterior)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $estadoBadgeAnterior }}">
                                    {{ ucfirst($cambio->estado_anterior) }}
                                </span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            @endif
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $estadoBadgeNuevo }}">
                                {{ ucfirst($cambio->estado_nuevo) }}
                            </span>
                        </div>
                        <span class="text-xs text-gray-500">{{ $cambio->fecha_formateada }}</span>
                    </div>

                    @if($cambio->motivo_cambio)
                        <p class="text-sm text-gray-600 mt-2 bg-[#F9FAFB] p-3 rounded-lg">
                            <span class="font-semibold">Motivo:</span> {{ $cambio->motivo_cambio }}
                        </p>
                    @endif

                    @if($cambio->cambiadoPorUsuario)
                        <p class="text-xs text-gray-500 mt-2">
                            Cambiado por: <span class="font-medium text-gray-700">{{ $cambio->cambiadoPorUsuario->nombre }}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[#F3F4F6] text-gray-600 ml-2">
                                {{ ucfirst($cambio->cambiadoPorUsuario->rol) }}
                            </span>
                        </p>
                    @endif
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="text-gray-400 mb-3">
                        <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium">No hay historial de cambios para esta reserva.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
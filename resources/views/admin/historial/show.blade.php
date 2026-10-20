@extends('layouts.admin')

@section('header_title', 'Detalle de Actividad')
@section('title', 'Detalle - Admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.historial.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-[#10B981] transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver al historial
            </a>
            <h1 class="text-xl sm:text-2xl font-bold text-[#065F46] mt-2 flex items-center gap-3">
                <span class="text-2xl sm:text-3xl">{{ $registro->accion_icono }}</span>
                <span class="break-words">{{ $registro->accion }}</span>
            </h1>
        </div>

        <!-- Información Principal -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Información General</h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Acción:</span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $registro->accion_badge }}">
                            {{ $registro->accion_icono }} {{ $registro->accion }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Fecha/Hora:</span>
                        <span class="font-medium text-gray-800">{{ $registro->created_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Tabla:</span>
                        <span class="font-medium text-gray-800">{{ ucfirst(str_replace('_', ' ', $registro->nombre_tabla ?? 'N/A')) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Registro ID:</span>
                        <span class="font-mono font-medium text-gray-800">{{ $registro->registro_id ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Usuario</h2>
                @if($registro->usuario)
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-[#10B981] to-[#065F46] flex items-center justify-center text-white font-bold text-base sm:text-lg shrink-0">
                                {{ substr($registro->usuario->nombre, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">{{ $registro->usuario->nombre }}</p>
                                <p class="text-xs sm:text-sm text-gray-500 truncate">{{ $registro->usuario->email }}</p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-gray-100 text-sm">
                            <span class="text-gray-500">Rol:</span>
                            @php
                                $rolBadge = match($registro->usuario->rol) {
                                    'admin' => 'bg-[#DBEAFE] text-[#1E40AF]',
                                    'organizador' => 'bg-[#FEF3C7] text-[#92400E]',
                                    'trabajador' => 'bg-[#D1FAE5] text-[#065F46]',
                                    default => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $rolBadge }}">
                                {{ ucfirst($registro->usuario->rol) }}
                            </span>
                        </div>
                    </div>
                @else
                    <p class="text-gray-400 italic text-sm">Acción realizada por el sistema</p>
                @endif
            </div>
        </div>

        <!-- Valores Anteriores y Nuevos -->
        @if($registro->valores_anteriores || $registro->valores_nuevos)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                @if($registro->valores_anteriores)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2 flex items-center gap-2">
                            <span class="text-red-500">⬅️</span> Valores Anteriores
                        </h2>
                        <div class="bg-[#FEE2E2] rounded-lg p-3 sm:p-4 font-mono text-[11px] sm:text-xs text-gray-700 overflow-x-auto">
                            <pre class="whitespace-pre-wrap break-all">{{ json_encode($registro->valores_anteriores, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                @endif

                @if($registro->valores_nuevos)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2 flex items-center gap-2">
                            <span class="text-green-500">➡️</span> Valores Nuevos
                        </h2>
                        <div class="bg-[#D1FAE5] rounded-lg p-3 sm:p-4 font-mono text-[11px] sm:text-xs text-gray-700 overflow-x-auto">
                            <pre class="whitespace-pre-wrap break-all">{{ json_encode($registro->valores_nuevos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Información Técnica -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Información Técnica</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500">Dirección IP</p>
                    <p class="text-sm font-mono font-medium text-gray-800">{{ $registro->direccion_ip ?? 'No registrada' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Agente de Usuario</p>
                    <p class="text-sm font-medium text-gray-800 break-all">{{ $registro->agente_usuario ?? 'No registrado' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">ID del Registro</p>
                    <p class="text-sm font-mono font-medium text-gray-800">#{{ $registro->id }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Estado</p>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#D1FAE5] text-[#065F46]">
                        ✅ Activo
                    </span>
                </div>
            </div>
        </div>
    </div>
@endsection
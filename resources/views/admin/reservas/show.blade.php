@extends('layouts.admin')

@section('header_title', 'Detalles de la Reserva')
@section('title', 'Reserva #' . $reserva->codigo_reserva . ' - Admin')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Retorno y Botones Principales Responsive -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <a href="{{ route('admin.reservas.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-[#065F46] transition-colors mb-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a Reservas
                </a>
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl font-bold text-[#065F46]">
                        Reserva <span class="font-mono text-gray-800">#{{ $reserva->codigo_reserva }}</span>
                    </h1>
                    @php
                        $badgeClass = match($reserva->estado) {
                            'confirmada' => 'bg-[#D1FAE5] text-[#065F46] border border-emerald-200',
                            'pendiente' => 'bg-[#FEF3C7] text-[#92400E] border border-amber-200',
                            'cancelada' => 'bg-[#FEE2E2] text-[#991B1B] border border-red-200',
                            'completada' => 'bg-[#DBEAFE] text-[#1E40AF] border border-blue-200',
                            default => 'bg-gray-100 text-gray-600 border border-gray-200',
                        };
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $badgeClass }}">
                        {{ $reserva->estado }}
                    </span>
                </div>
            </div>

            <!-- Botones de Acción Responsive -->
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <a href="{{ route('admin.reservas.edit', $reserva) }}" class="flex-1 sm:flex-initial inline-flex items-center justify-center px-4 py-2 bg-[#F59E0B] hover:bg-[#D97706] text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Editar
                </a>
                <form action="{{ route('admin.reservas.destroy', $reserva) }}" method="POST" class="flex-1 sm:flex-initial" onsubmit="return confirm('¿Confirmas que deseas cancelar/eliminar esta reserva?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-50 text-red-600 hover:bg-red-100 text-sm font-semibold rounded-lg transition-colors border border-red-200">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Cancelar
                    </button>
                </form>
            </div>
        </div>

        <!-- Tarjetas de Información en Cuadrícula Responsive -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            <!-- 1. Datos del Cliente -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 sm:p-6">
                <h3 class="text-base font-bold text-[#065F46] mb-4 flex items-center gap-2">
                    <span>👤</span> Información del Cliente
                </h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between items-center pb-2.5 border-b border-gray-50">
                        <dt class="text-gray-500 font-medium">Nombre Completo:</dt>
                        <dd class="text-gray-900 font-semibold">{{ $reserva->nombre_cliente }}</dd>
                    </div>
                    <div class="flex justify-between items-center pb-2.5 border-b border-gray-50">
                        <dt class="text-gray-500 font-medium">Teléfono / WhatsApp:</dt>
                        <dd class="text-gray-900 font-semibold">
                            @if(!empty($reserva->telefono_cliente))
                                <a href="tel:{{ $reserva->telefono_cliente }}" class="text-[#065F46] hover:underline flex items-center gap-1">
                                    <span>📞</span> {{ $reserva->telefono_cliente }}
                                </a>
                            @else
                                <span class="text-gray-400 font-normal italic">No registrado</span>
                            @endif
                        </dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-gray-500 font-medium">Tipo de Registro:</dt>
                        <dd class="capitalize font-medium text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full text-xs">
                            {{ $reserva->tipo_reserva }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- 2. Información de la Cancha -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 sm:p-6">
                <h3 class="text-base font-bold text-[#065F46] mb-4 flex items-center gap-2">
                    <span>🏟️</span> Espacio / Instalación
                </h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between items-center pb-2.5 border-b border-gray-50">
                        <dt class="text-gray-500 font-medium">Espacio:</dt>
                        <dd class="text-gray-900 font-bold">{{ $reserva->instalacion->nombre ?? 'Cancha Principal' }}</dd>
                    </div>
                    <div class="flex justify-between items-center pb-2.5 border-b border-gray-50">
                        <dt class="text-gray-500 font-medium">Superficie:</dt>
                        <dd class="text-gray-900">{{ $reserva->instalacion->tipo_superficie ?? 'Césped sintético' }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-gray-500 font-medium">Dimensiones:</dt>
                        <dd class="text-gray-900 font-medium">{{ $reserva->instalacion->dimensiones ?? '40m x 20m' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- 3. Fecha y Horario -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 sm:p-6">
                <h3 class="text-base font-bold text-[#065F46] mb-4 flex items-center gap-2">
                    <span>📅</span> Fecha y Horario
                </h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between items-center pb-2.5 border-b border-gray-50">
                        <dt class="text-gray-500 font-medium">Fecha:</dt>
                        <dd class="text-gray-900 font-semibold">{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y') }}</dd>
                    </div>
                    <div class="flex justify-between items-center pb-2.5 border-b border-gray-50">
                        <dt class="text-gray-500 font-medium">Horario:</dt>
                        <dd class="text-gray-900 font-bold text-[#065F46]">
                            {{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('H:i') }}
                        </dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-gray-500 font-medium">Duración:</dt>
                        <dd class="text-gray-900 font-semibold">{{ $reserva->total_horas }} hrs</dd>
                    </div>
                </dl>
            </div>

            <!-- 4. Pago y Finanzas -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 sm:p-6">
                <h3 class="text-base font-bold text-[#065F46] mb-4 flex items-center gap-2">
                    <span>💳</span> Pago y Finanzas
                </h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between items-center pb-2.5 border-b border-gray-50">
                        <dt class="text-gray-500 font-medium">Precio Total:</dt>
                        <dd class="text-xl font-black text-[#065F46]">Q. {{ number_format($reserva->precio_total, 2) }}</dd>
                    </div>
                    <div class="flex justify-between items-center pb-2.5 border-b border-gray-50">
                        <dt class="text-gray-500 font-medium">Estado del Pago:</dt>
                        <dd class="capitalize font-semibold {{ $reserva->estado_pago === 'pagado' ? 'text-green-600' : 'text-amber-600' }}">
                            {{ $reserva->estado_pago ?? 'Pendiente' }}
                        </dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-gray-500 font-medium">Método de Pago:</dt>
                        <dd class="capitalize text-gray-900 font-semibold">{{ $reserva->metodo_pago ?? 'Efectivo' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Observaciones / Notas -->
        @if($reserva->notas)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 sm:p-6">
                <h3 class="text-base font-bold text-[#065F46] mb-2 flex items-center gap-2">
                    <span>📝</span> Observaciones
                </h3>
                <p class="text-sm text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-lg border border-gray-100">
                    {{ $reserva->notas }}
                </p>
            </div>
        @endif

        <!-- Auditoría -->
        <div class="text-xs text-gray-400 text-right px-2">
            Registrado por: <span class="font-medium text-gray-600">{{ $reserva->creadoPorUsuario->nombre ?? 'Administrador' }}</span> 
            el {{ $reserva->created_at ? $reserva->created_at->format('d/m/Y H:i') : 'N/A' }}
        </div>
    </div>
@endsection

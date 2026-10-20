@extends('layouts.trabajador')

@section('header_title', 'Detalles de la Reserva')
@section('title', 'Reserva #' . $reserva->codigo_reserva . ' - Trabajador')

@section('content')
    <div class="max-w-4xl mx-auto space-y-4 sm:space-y-6">
        <!-- Retorno y Botones Principales Responsive -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <a href="{{ route('trabajador.reservas.index') }}" class="inline-flex items-center text-xs sm:text-sm font-medium text-gray-500 hover:text-[#065F46] transition-colors mb-2">
                    <svg class="w-4 h-4 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a Reservas
                </a>
                <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                    <h1 class="text-xl sm:text-2xl font-bold text-[#065F46]">
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
                    <span class="inline-flex items-center px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $badgeClass }}">
                        {{ $reserva->estado }}
                    </span>
                    @if($reserva->tipo_reserva === 'torneo')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#DBEAFE] text-[#1E40AF] border border-blue-200">
                            🏆 Torneo
                        </span>
                    @endif
                </div>
            </div>

            <!-- Botones de Acción Responsive -->
            @if($reserva->tipo_reserva !== 'torneo')
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <a href="{{ route('trabajador.reservas.edit', $reserva) }}" class="flex-1 sm:flex-initial inline-flex items-center justify-center px-4 py-2 bg-[#F59E0B] hover:bg-[#D97706] text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Editar
                    </a>
                </div>
            @endif
        </div>

        <!-- Tarjetas de Información en Cuadrícula Responsive -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            <!-- 1. Datos del Cliente -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h3 class="text-sm sm:text-base font-bold text-[#065F46] mb-4 flex items-center gap-2">
                    <span>👤</span> Información del Cliente
                </h3>
                <dl class="space-y-3 text-xs sm:text-sm">
                    <div class="flex justify-between items-center pb-2.5 border-b border-gray-50">
                        <dt class="text-gray-500 font-medium">Nombre Completo:</dt>
                        <dd class="text-gray-900 font-semibold text-right">{{ $reserva->nombre_cliente }}</dd>
                    </div>
                    <div class="flex justify-between items-center pb-2.5 border-b border-gray-50">
                        <dt class="text-gray-500 font-medium">Teléfono / WhatsApp:</dt>
                        <dd class="text-gray-900 font-semibold text-right">
                            @if(!empty($reserva->telefono_cliente))
                                <a href="tel:{{ $reserva->telefono_cliente }}" class="text-[#065F46] hover:underline flex items-center gap-1 justify-end">
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
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h3 class="text-sm sm:text-base font-bold text-[#065F46] mb-4 flex items-center gap-2">
                    <span>🏟️</span> Espacio / Instalación
                </h3>
                <dl class="space-y-3 text-xs sm:text-sm">
                    <div class="flex justify-between items-center pb-2.5 border-b border-gray-50">
                        <dt class="text-gray-500 font-medium">Cancha:</dt>
                        <dd class="text-gray-900 font-semibold text-right">{{ $reserva->instalacion->nombre ?? 'Cancha Principal' }}</dd>
                    </div>
                    <div class="flex justify-between items-center pb-2.5 border-b border-gray-50">
                        <dt class="text-gray-500 font-medium">Tipo de Superficie:</dt>
                        <dd class="text-gray-900 font-medium text-right">{{ $reserva->instalacion->tipo ?? 'Grama Sintética' }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-gray-500 font-medium">Capacidad:</dt>
                        <dd class="text-gray-900 font-medium text-right">{{ $reserva->instalacion->capacidad ?? '10-14 jugadores' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- 3. Fecha y Horario -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h3 class="text-sm sm:text-base font-bold text-[#065F46] mb-4 flex items-center gap-2">
                    <span>📅</span> Fecha y Horario
                </h3>
                <dl class="space-y-3 text-xs sm:text-sm">
                    <div class="flex justify-between items-center pb-2.5 border-b border-gray-50">
                        <dt class="text-gray-500 font-medium">Fecha programada:</dt>
                        <dd class="text-gray-900 font-semibold text-right">{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</dd>
                    </div>
                    <div class="flex justify-between items-center pb-2.5 border-b border-gray-50">
                        <dt class="text-gray-500 font-medium">Horario reservado:</dt>
                        <dd class="text-emerald-700 font-bold text-right">
                            {{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('H:i') }}
                        </dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-gray-500 font-medium">Total de Tiempo:</dt>
                        <dd class="text-gray-900 font-medium text-right">{{ $reserva->total_horas }} hora(s)</dd>
                    </div>
                </dl>
            </div>

            <!-- 4. Pago y Estados -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h3 class="text-sm sm:text-base font-bold text-[#065F46] mb-4 flex items-center gap-2">
                    <span>💳</span> Pago y Liquidación
                </h3>
                <dl class="space-y-3 text-xs sm:text-sm">
                    <div class="flex justify-between items-center pb-2.5 border-b border-gray-50">
                        <dt class="text-gray-500 font-medium">Importe Total:</dt>
                        <dd class="text-base sm:text-lg font-black text-[#065F46]">Q. {{ number_format($reserva->precio_total, 2) }}</dd>
                    </div>
                    <div class="flex justify-between items-center pb-2.5 border-b border-gray-50">
                        <dt class="text-gray-500 font-medium">Estado del Pago:</dt>
                        <dd>
                            @php
                                $pagoClass = match($reserva->estado_pago) {
                                    'pagado' => 'bg-emerald-100 text-emerald-800',
                                    'pendiente' => 'bg-amber-100 text-amber-800',
                                    'reembolsado' => 'bg-purple-100 text-purple-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold capitalize {{ $pagoClass }}">
                                {{ $reserva->estado_pago ?? 'Pendiente' }}
                            </span>
                        </dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-gray-500 font-medium">Método Utilizado:</dt>
                        <dd class="text-gray-900 font-medium capitalize text-right">{{ $reserva->metodo_pago ?? 'Efectivo' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- 5. Observaciones y Notas -->
        @if(!empty($reserva->notas))
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h3 class="text-sm sm:text-base font-bold text-[#065F46] mb-2 flex items-center gap-2">
                    <span>📝</span> Notas y Observaciones
                </h3>
                <p class="text-xs sm:text-sm text-gray-700 bg-gray-50 p-3 sm:p-4 rounded-lg whitespace-pre-line leading-relaxed">
                    {{ $reserva->notas }}
                </p>
            </div>
        @endif

        <!-- 6. Acciones Rápidas de Cambio de Estado (solo para reservas no completadas/canceladas y manuales) -->
        @if($reserva->tipo_reserva !== 'torneo' && $reserva->estado !== 'completada' && $reserva->estado !== 'cancelada')
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h3 class="text-sm sm:text-base font-bold text-[#065F46] mb-3">Acciones Rápidas</h3>
                <div class="flex flex-wrap items-center gap-3">
                    @if($reserva->estado === 'pendiente')
                        <form method="POST" action="{{ route('trabajador.reservas.cambiar-estado', $reserva) }}">
                            @csrf
                            <input type="hidden" name="estado" value="confirmada">
                            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-[#10B981] hover:bg-[#059669] text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                                ✅ Confirmar Reserva
                            </button>
                        </form>
                    @endif
                    @if($reserva->estado === 'confirmada')
                        <form method="POST" action="{{ route('trabajador.reservas.cambiar-estado', $reserva) }}">
                            @csrf
                            <input type="hidden" name="estado" value="completada">
                            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-[#3B82F6] hover:bg-[#2563EB] text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                                ✔️ Marcar como Completada
                            </button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('trabajador.reservas.cambiar-estado', $reserva) }}" onsubmit="return confirm('¿Estás seguro de cancelar esta reserva?');">
                        @csrf
                        <input type="hidden" name="estado" value="cancelada">
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-white border border-[#EF4444] text-[#EF4444] hover:bg-[#FEE2E2] text-sm font-semibold rounded-lg transition-colors">
                            ❌ Cancelar Reserva
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <!-- 7. Historial y Auditoría -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 text-xs text-gray-500">
            <h3 class="font-semibold text-gray-700 text-xs sm:text-sm mb-2">Auditoría del Registro</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                <div>Registrado el: <span class="font-medium text-gray-700">{{ $reserva->created_at?->format('d/m/Y H:i') ?? '-' }}</span></div>
                <div>Última actualización: <span class="font-medium text-gray-700">{{ $reserva->updated_at?->format('d/m/Y H:i') ?? '-' }}</span></div>
                <div>Registrado por: <span class="font-medium text-gray-700">{{ $reserva->creadoPorUsuario->nombre ?? 'Sistema' }}</span></div>
            </div>
        </div>
    </div>
@endsection
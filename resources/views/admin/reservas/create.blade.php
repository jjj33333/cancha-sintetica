@extends('layouts.admin')

@section('header_title', 'Crear Nueva Reserva')
@section('title', 'Nueva Reserva - Admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Encabezado con navegación de retorno -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="{{ route('admin.reservas.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-[#065F46] transition-colors mb-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a la lista de reservas
                </a>
                <h1 class="text-2xl font-bold text-[#065F46]">Nueva Reserva</h1>
                <p class="text-sm text-gray-500">Registra manualmente una reserva para los clientes.</p>
            </div>
        </div>

        <!-- Errores de validación -->
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700">
                <div class="flex items-center gap-2 mb-2 font-semibold">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Por favor revisa los errores en el formulario:
                </div>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario -->
        <form action="{{ route('admin.reservas.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 md:p-8 space-y-6 sm:space-y-8">
            @csrf

            <!-- 1. Información de la Instalación (Fijada a Cancha Principal) -->
            <input type="hidden" name="instalacion_id" value="{{ $instalacionPrincipal->id ?? 1 }}">
            <div class="bg-emerald-50/70 border border-emerald-200/80 rounded-xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-[#065F46] flex items-center justify-center text-xl flex-shrink-0">
                        🏟️
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-emerald-800 uppercase tracking-wider block">Espacio asignado</span>
                        <span class="text-base font-bold text-[#065F46]">{{ $instalacionPrincipal->nombre ?? 'Cancha Principal' }}</span>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                    Cancha por defecto
                </span>
            </div>

            <!-- 2. Datos del Cliente -->
            <div>
                <h2 class="text-lg font-bold text-[#065F46] mb-4 flex items-center gap-2">
                    <span>👤</span> Datos del Cliente
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nombre Completo <span class="text-red-500">*</span></label>
                        <input type="text" name="nombre_cliente" value="{{ old('nombre_cliente') }}" required placeholder="Ej: Juan Pérez" class="w-full px-4 py-2.5 text-base sm:text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Teléfono / WhatsApp <span class="text-gray-400 font-normal">(Opcional)</span></label>
                        <input type="text" name="telefono_cliente" value="{{ old('telefono_cliente') }}" placeholder="Ej: 5555 1234 (Opcional)" class="w-full px-4 py-2.5 text-base sm:text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none transition-all">
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            <!-- 3. Fecha y Horario -->
            <div>
                <h2 class="text-lg font-bold text-[#065F46] mb-4 flex items-center gap-2">
                    <span>📅</span> Fecha y Horario
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Fecha de la Reserva <span class="text-red-500">*</span></label>
                        <input type="date" name="fecha_reserva" value="{{ old('fecha_reserva', date('Y-m-d')) }}" required class="w-full px-4 py-2.5 text-base sm:text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Hora Inicio <span class="text-red-500">*</span></label>
                        <input type="time" name="hora_inicio" value="{{ old('hora_inicio', '18:00') }}" required class="w-full px-4 py-2.5 text-base sm:text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Hora Fin <span class="text-red-500">*</span></label>
                        <input type="time" name="hora_fin" value="{{ old('hora_fin', '19:00') }}" required class="w-full px-4 py-2.5 text-base sm:text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none transition-all">
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            <!-- 4. Pago y Estados -->
            <div>
                <h2 class="text-lg font-bold text-[#065F46] mb-4 flex items-center gap-2">
                    <span>💳</span> Pago y Estado
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Precio Total (Q.) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.50" min="0" name="precio_total" value="{{ old('precio_total', '100.00') }}" required class="w-full px-4 py-2.5 text-base sm:text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none font-bold text-gray-800 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Estado de Reserva <span class="text-red-500">*</span></label>
                        <select name="estado" required class="w-full px-3 py-2.5 text-base sm:text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white transition-all">
                            <option value="confirmada" {{ old('estado', 'confirmada') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                            <option value="pendiente" {{ old('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="completada" {{ old('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
                            <option value="cancelada" {{ old('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Estado del Pago</label>
                        <select name="estado_pago" class="w-full px-3 py-2.5 text-base sm:text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white transition-all">
                            <option value="pendiente" {{ old('estado_pago', 'pendiente') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="pagado" {{ old('estado_pago') == 'pagado' ? 'selected' : '' }}>Pagado</option>
                            <option value="reembolsado" {{ old('estado_pago') == 'reembolsado' ? 'selected' : '' }}>Reembolsado</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Método de Pago</label>
                        <select name="metodo_pago" class="w-full px-3 py-2.5 text-base sm:text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white transition-all">
                            <option value="efectivo" {{ old('metodo_pago', 'efectivo') == 'efectivo' ? 'selected' : '' }}>Efectivo (Por defecto)</option>
                            <option value="transferencia" {{ old('metodo_pago') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                            <option value="tarjeta" {{ old('metodo_pago') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                            <option value="otro" {{ old('metodo_pago') == 'otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Notas -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Notas u Observaciones Adicionales</label>
                <textarea name="notas" rows="3" placeholder="Detalles específicos, adelantos entregados, etc..." class="w-full px-4 py-2.5 text-base sm:text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none transition-all">{{ old('notas') }}</textarea>
            </div>

            <!-- Botones de Acción Responsive -->
            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.reservas.index') }}" class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors text-center">
                    Cancelar
                </a>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-[#10B981] hover:bg-[#059669] text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Guardar Reserva
                </button>
            </div>
        </form>
    </div>
@endsection

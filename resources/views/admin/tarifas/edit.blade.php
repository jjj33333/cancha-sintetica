@extends('layouts.admin')

@section('header_title', 'Editar Tarifa')
@section('title', 'Editar Tarifa - Admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-4 sm:mb-6">
            <a href="{{ route('admin.tarifas.index') }}" class="inline-flex items-center text-xs sm:text-sm text-gray-600 hover:text-[#10B981] transition-colors">
                <svg class="w-4 h-4 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver a la lista
            </a>
            <h1 class="text-xl sm:text-2xl font-bold text-[#065F46] mt-2">✏️ Editar Tarifa: {{ $tarifa->nombre }}</h1>
        </div>

        <form method="POST" action="{{ route('admin.tarifas.update', $tarifa) }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Información General -->
            <div class="border-b border-gray-100 pb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Información General</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="nombre" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Nombre de la Tarifa <span class="text-red-500">*</span></label>
                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $tarifa->nombre) }}" required placeholder="Ej: Hora Normal, Hora Prime, Fin de Semana"
                               class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('nombre') border-red-500 @enderror">
                        @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="instalacion_id" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Instalación <span class="text-red-500">*</span></label>
                        <select name="instalacion_id" id="instalacion_id" required
                                class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('instalacion_id') border-red-500 @enderror">
                            <option value="">Seleccionar instalación...</option>
                            @foreach($instalaciones as $inst)
                                <option value="{{ $inst->id }}" {{ old('instalacion_id', $tarifa->instalacion_id) == $inst->id ? 'selected' : '' }}>{{ $inst->nombre }}</option>
                            @endforeach
                        </select>
                        @error('instalacion_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tipo_dia" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Tipo de Día <span class="text-red-500">*</span></label>
                        <select name="tipo_dia" id="tipo_dia" required
                                class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('tipo_dia') border-red-500 @enderror">
                            <option value="">Seleccionar tipo...</option>
                            <option value="semana" {{ old('tipo_dia', $tarifa->tipo_dia) === 'semana' ? 'selected' : '' }}>Semana (Lunes a Viernes)</option>
                            <option value="fin_semana" {{ old('tipo_dia', $tarifa->tipo_dia) === 'fin_semana' ? 'selected' : '' }}>Fin de Semana (Sábado y Domingo)</option>
                            <option value="feriado" {{ old('tipo_dia', $tarifa->tipo_dia) === 'feriado' ? 'selected' : '' }}>Feriado</option>
                        </select>
                        @error('tipo_dia') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Horario y Precio -->
            <div class="border-b border-gray-100 pb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Horario y Precio</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <div>
                        <label for="hora_inicio" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Hora de Inicio <span class="text-red-500">*</span></label>
                        <input type="time" name="hora_inicio" id="hora_inicio" value="{{ old('hora_inicio', \Carbon\Carbon::parse($tarifa->hora_inicio)->format('H:i')) }}" required
                               class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('hora_inicio') border-red-500 @enderror">
                        @error('hora_inicio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="hora_fin" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Hora de Fin <span class="text-red-500">*</span></label>
                        <input type="time" name="hora_fin" id="hora_fin" value="{{ old('hora_fin', \Carbon\Carbon::parse($tarifa->hora_fin)->format('H:i')) }}" required
                               class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('hora_fin') border-red-500 @enderror">
                        @error('hora_fin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="precio" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Precio por Hora <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium text-sm">Q.</span>
                            <input type="number" name="precio" id="precio" value="{{ old('precio', $tarifa->precio) }}" step="0.01" min="0" required
                                   class="w-full pl-9 pr-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('precio') border-red-500 @enderror">
                        </div>
                        @error('precio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2 md:col-span-1">
                        <label for="moneda" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Moneda</label>
                        <select name="moneda" id="moneda"
                                class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('moneda') border-red-500 @enderror">
                            <option value="GTQ" {{ old('moneda', $tarifa->moneda ?? 'GTQ') === 'GTQ' ? 'selected' : '' }}>GTQ (Quetzales)</option>
                            <option value="USD" {{ old('moneda', $tarifa->moneda) === 'USD' ? 'selected' : '' }}>USD (Dólares)</option>
                            <option value="EUR" {{ old('moneda', $tarifa->moneda) === 'EUR' ? 'selected' : '' }}>EUR (Euros)</option>
                        </select>
                        @error('moneda') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Configuración -->
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Configuración</h2>
                <div class="flex items-center justify-between p-3 sm:p-4 bg-[#F9FAFB] rounded-lg gap-3">
                    <div>
                        <label class="text-xs sm:text-sm font-medium text-gray-700">✅ Tarifa activa</label>
                        <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5">Si está desactivada, no se aplicará en las reservas.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="checkbox" name="esta_activo" value="1" {{ old('esta_activo', $tarifa->esta_activo) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#10B981]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#10B981]"></div>
                    </label>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.tarifas.index') }}" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-center text-sm">
                    Cancelar
                </a>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-[#10B981] hover:bg-[#059669] text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all text-center text-sm">
                    Actualizar Tarifa
                </button>
            </div>
        </form>
    </div>
@endsection
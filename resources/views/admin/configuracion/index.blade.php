@extends('layouts.admin')

@section('header_title', 'Configuración del Sistema')
@section('title', 'Configuración - Admin')

@section('content')
    <!-- Encabezado -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#065F46]">️ Configuración del Sistema</h1>
        <p class="text-sm text-gray-500 mt-1">Gestiona los parámetros generales del sistema y realiza respaldos de la base de datos.</p>
    </div>

    <!-- Mensajes -->
    @if(session('success'))
        <div class="mb-6 bg-[#D1FAE5] border border-[#10B981] text-[#065F46] px-4 py-3 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-[#FEE2E2] border border-[#EF4444] text-[#991B1B] px-4 py-3 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna Principal: Formulario de Configuración -->
        <div class="lg:col-span-2 space-y-6">
            <form method="POST" action="{{ route('admin.configuracion.update') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                @csrf
                @method('PUT')

                <!-- Configuración General -->
                <div class="border-b border-gray-100 pb-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="text-2xl">🌐</span>
                        Configuración General
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="nombre_sitio" class="block text-sm font-medium text-gray-700 mb-1">
                                Nombre del Sitio <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nombre_sitio" id="nombre_sitio" 
                                   value="{{ old('nombre_sitio', \App\Models\Configuracion::obtener('nombre_sitio', 'Cancha Sintética Deportiva')) }}" 
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('nombre_sitio') border-red-500 @enderror">
                            @error('nombre_sitio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="zona_horaria" class="block text-sm font-medium text-gray-700 mb-1">
                                Zona Horaria <span class="text-red-500">*</span>
                            </label>
                            <select name="zona_horaria" id="zona_horaria" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('zona_horaria') border-red-500 @enderror">
                                <option value="America/Guatemala" {{ old('zona_horaria', \App\Models\Configuracion::obtener('zona_horaria', 'America/Guatemala')) === 'America/Guatemala' ? 'selected' : '' }}>America/Guatemala (Guatemala)</option>
                                <option value="America/Bogota" {{ old('zona_horaria') === 'America/Bogota' ? 'selected' : '' }}>America/Bogota (Colombia)</option>
                                <option value="America/Mexico_City" {{ old('zona_horaria') === 'America/Mexico_City' ? 'selected' : '' }}>America/Mexico_City (México)</option>
                                <option value="America/Argentina/Buenos_Aires" {{ old('zona_horaria') === 'America/Argentina/Buenos_Aires' ? 'selected' : '' }}>America/Argentina/Buenos_Aires (Argentina)</option>
                                <option value="America/Santiago" {{ old('zona_horaria') === 'America/Santiago' ? 'selected' : '' }}>America/Santiago (Chile)</option>
                            </select>
                            @error('zona_horaria') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="moneda" class="block text-sm font-medium text-gray-700 mb-1">
                                Moneda <span class="text-red-500">*</span>
                            </label>
                            <select name="moneda" id="moneda" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('moneda') border-red-500 @enderror">
                                <option value="GTQ" {{ old('moneda', \App\Models\Configuracion::obtener('moneda', 'GTQ')) === 'GTQ' ? 'selected' : '' }}>GTQ (Quetzales Guatemaltecos)</option>
                                <option value="USD" {{ old('moneda') === 'USD' ? 'selected' : '' }}>USD (Dólares Americanos)</option>
                                <option value="EUR" {{ old('moneda') === 'EUR' ? 'selected' : '' }}>EUR (Euros)</option>
                                <option value="COP" {{ old('moneda') === 'COP' ? 'selected' : '' }}>COP (Pesos Colombianos)</option>
                                <option value="MXN" {{ old('moneda') === 'MXN' ? 'selected' : '' }}>MXN (Pesos Mexicanos)</option>
                            </select>
                            @error('moneda') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Configuración de Reservas -->
                <div class="border-b border-gray-100 pb-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="text-2xl">📅</span>
                        Configuración de Reservas
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="dias_maximos_anticipacion" class="block text-sm font-medium text-gray-700 mb-1">
                                Días Máximos de Anticipación <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="dias_maximos_anticipacion" id="dias_maximos_anticipacion" 
                                   value="{{ old('dias_maximos_anticipacion', \App\Models\Configuracion::obtener('dias_maximos_anticipacion', 30)) }}" 
                                   min="1" max="365" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('dias_maximos_anticipacion') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">Máximo de días que un cliente puede reservar con anticipación.</p>
                            @error('dias_maximos_anticipacion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="horas_minimas_cancelar" class="block text-sm font-medium text-gray-700 mb-1">
                                Horas Mínimas para Cancelar <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="horas_minimas_cancelar" id="horas_minimas_cancelar" 
                                   value="{{ old('horas_minimas_cancelar', \App\Models\Configuracion::obtener('horas_minimas_cancelar', 24)) }}" 
                                   min="1" max="168" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('horas_minimas_cancelar') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">Horas mínimas de anticipación para cancelar una reserva.</p>
                            @error('horas_minimas_cancelar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="border-b border-gray-100 pb-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="text-2xl">📞</span>
                        Información de Contacto
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="email_contacto" class="block text-sm font-medium text-gray-700 mb-1">
                                Email de Contacto
                            </label>
                            <input type="email" name="email_contacto" id="email_contacto" 
                                   value="{{ old('email_contacto', \App\Models\Configuracion::obtener('email_contacto', 'contacto@cancha.com')) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('email_contacto') border-red-500 @enderror">
                            @error('email_contacto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="telefono_contacto" class="block text-sm font-medium text-gray-700 mb-1">
                                Teléfono de Contacto
                            </label>
                            <input type="text" name="telefono_contacto" id="telefono_contacto" 
                                   value="{{ old('telefono_contacto', \App\Models\Configuracion::obtener('telefono_contacto', '+502 5555 1234')) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('telefono_contacto') border-red-500 @enderror">
                            @error('telefono_contacto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Modo Mantenimiento -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="text-2xl">🛠️</span>
                        Modo Mantenimiento
                    </h2>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-[#F9FAFB] rounded-lg gap-3">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Activar modo mantenimiento</label>
                            <p class="text-xs text-gray-500 mt-1">Si está activado, los usuarios públicos verán un mensaje de mantenimiento.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                            <input type="checkbox" name="mantenimiento_activo" value="1" 
                                   {{ old('mantenimiento_activo', \App\Models\Configuracion::obtener('mantenimiento_activo', false)) ? 'checked' : '' }} 
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#10B981]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#10B981]"></div>
                        </label>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex justify-end pt-2">
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-[#10B981] hover:bg-[#059669] text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                        💾 Guardar Configuración
                    </button>
                </div>
            </form>
        </div>

        <!-- Columna Secundaria: Respaldos -->
        <div class="space-y-6">
            <!-- Card de Respaldos -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="text-2xl">💾</span>
                    Respaldos
                </h2>

                <!-- Último Respaldo -->
                <div class="bg-[#F9FAFB] rounded-lg p-4 mb-4">
                    <p class="text-xs text-gray-500 mb-1">Último respaldo:</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $ultimoRespaldo }}</p>
                </div>

                <!-- Botones de Acción -->
                <div class="space-y-3">
                    <form method="POST" action="{{ route('admin.configuracion.generar-respaldo') }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-[#10B981] hover:bg-[#059669] text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Generar Respaldo Ahora
                        </button>
                    </form>

                    <a href="#" class="w-full px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors flex items-center justify-center gap-2 text-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Restaurar (Próximamente)
                    </a>
                </div>
            </div>

            <!-- Lista de Respaldos -->
            @if($respaldos->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Respaldos Disponibles</h3>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @foreach($respaldos->take(5) as $respaldo)
                            <div class="flex items-center justify-between p-3 bg-[#F9FAFB] rounded-lg hover:bg-[#ECFDF5] transition-colors">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-800 truncate">{{ $respaldo['nombre'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $respaldo['tamano'] }} • {{ $respaldo['fecha'] }}</p>
                                </div>
                                <div class="flex gap-1 ml-2">
                                    <a href="{{ route('admin.configuracion.descargar-respaldo', $respaldo['nombre']) }}" 
                                       class="p-2 text-gray-500 hover:text-[#3B82F6] hover:bg-[#DBEAFE] rounded-lg transition-colors" 
                                       title="Descargar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.configuracion.eliminar-respaldo', $respaldo['nombre']) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este respaldo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-500 hover:text-[#EF4444] hover:bg-[#FEE2E2] rounded-lg transition-colors" title="Eliminar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Información del Sistema -->
            <div class="bg-gradient-to-br from-[#065F46] to-[#10B981] rounded-xl shadow-sm p-4 sm:p-6 text-white">
                <h3 class="text-sm font-semibold mb-3 opacity-90">Información del Sistema</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="opacity-80">Laravel:</span>
                        <span class="font-medium">{{ app()->version() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="opacity-80">PHP:</span>
                        <span class="font-medium">{{ phpversion() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="opacity-80">Base de datos:</span>
                        <span class="font-medium">{{ config('database.connections.mysql.database') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="opacity-80">Entorno:</span>
                        <span class="font-medium capitalize">{{ app()->environment() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nota informativa -->
    <div class="mt-6 bg-[#ECFDF5] border border-[#10B981] rounded-xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-[#10B981] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div class="text-sm text-[#065F46]">
            <p class="font-semibold mb-1">ℹ️ Información importante</p>
            <p class="text-gray-700">
                Los cambios en la configuración se aplican inmediatamente en todo el sistema. 
                Los respaldos se almacenan en <code class="bg-white/50 px-1 rounded">storage/app/respaldos/</code>. 
                Se recomienda generar respaldos periódicamente antes de realizar cambios importantes.
            </p>
        </div>
    </div>
@endsection
@extends('layouts.admin')

@section('header_title', 'Información de Contacto')
@section('title', 'Contacto - Admin')

@section('content')
    <!-- Encabezado -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#065F46]">📞 Información de Contacto</h1>
        <p class="text-sm text-gray-500 mt-1">
            Esta información se muestra en la página pública de contacto y en el footer del sitio.
        </p>
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

    <form method="POST" action="{{ route('admin.contacto.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna Principal: Formulario -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Información del Negocio -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="text-2xl">🏢</span>
                        Información del Negocio
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="nombre_negocio" class="block text-sm font-medium text-gray-700 mb-1">
                                Nombre del Negocio <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nombre_negocio" id="nombre_negocio" 
                                   value="{{ old('nombre_negocio', $contacto->nombre_negocio) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('nombre_negocio') border-red-500 @enderror">
                            @error('nombre_negocio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="ruc_nit" class="block text-sm font-medium text-gray-700 mb-1">
                                RUC / NIT
                            </label>
                            <input type="text" name="ruc_nit" id="ruc_nit" 
                                   value="{{ old('ruc_nit', $contacto->ruc_nit) }}" placeholder="Ej: 20123456789"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('ruc_nit') border-red-500 @enderror">
                            @error('ruc_nit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="sitio_web" class="block text-sm font-medium text-gray-700 mb-1">
                                Sitio Web
                            </label>
                            <input type="url" name="sitio_web" id="sitio_web" 
                                   value="{{ old('sitio_web', $contacto->sitio_web) }}" placeholder="https://www.ejemplo.com"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('sitio_web') border-red-500 @enderror">
                            @error('sitio_web') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="text-2xl">📱</span>
                        Información de Contacto
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="telefono_principal" class="block text-sm font-medium text-gray-700 mb-1">
                                Teléfono Principal <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="telefono_principal" id="telefono_principal" 
                                   value="{{ old('telefono_principal', $contacto->telefono_principal) }}" required
                                   placeholder="+502 5555 1234"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('telefono_principal') border-red-500 @enderror">
                            @error('telefono_principal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="telefono_secundario" class="block text-sm font-medium text-gray-700 mb-1">
                                Teléfono Secundario
                            </label>
                            <input type="text" name="telefono_secundario" id="telefono_secundario" 
                                   value="{{ old('telefono_secundario', $contacto->telefono_secundario) }}"
                                   placeholder="+502 5555 5678"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('telefono_secundario') border-red-500 @enderror">
                            @error('telefono_secundario') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="numero_whatsapp" class="block text-sm font-medium text-gray-700 mb-1">
                                Número de WhatsApp
                            </label>
                            <input type="text" name="numero_whatsapp" id="numero_whatsapp" 
                                   value="{{ old('numero_whatsapp', $contacto->numero_whatsapp) }}"
                                   placeholder="+502 5555 1234"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('numero_whatsapp') border-red-500 @enderror">
                            @error('numero_whatsapp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email_principal" class="block text-sm font-medium text-gray-700 mb-1">
                                Email Principal <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email_principal" id="email_principal" 
                                   value="{{ old('email_principal', $contacto->email_principal) }}" required
                                   placeholder="contacto@cancha.com"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('email_principal') border-red-500 @enderror">
                            @error('email_principal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="email_secundario" class="block text-sm font-medium text-gray-700 mb-1">
                                Email Secundario
                            </label>
                            <input type="email" name="email_secundario" id="email_secundario" 
                                   value="{{ old('email_secundario', $contacto->email_secundario) }}"
                                   placeholder="reservas@cancha.com"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('email_secundario') border-red-500 @enderror">
                            @error('email_secundario') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="horario_atencion" class="block text-sm font-medium text-gray-700 mb-1">
                                Horario de Atención
                            </label>
                            <textarea name="horario_atencion" id="horario_atencion" rows="2"
                                      placeholder="Lunes a Viernes: 06:00 - 23:00 | Sábados y Domingos: 08:00 - 22:00"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('horario_atencion') border-red-500 @enderror">{{ old('horario_atencion', $contacto->horario_atencion) }}</textarea>
                            @error('horario_atencion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Redes Sociales -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="text-2xl">🌐</span>
                        Redes Sociales
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="url_facebook" class="block text-sm font-medium text-gray-700 mb-1">
                                📘 Facebook
                            </label>
                            <input type="url" name="url_facebook" id="url_facebook" 
                                   value="{{ old('url_facebook', $contacto->url_facebook) }}"
                                   placeholder="https://facebook.com/CanchaSintetica"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('url_facebook') border-red-500 @enderror">
                            @error('url_facebook') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="url_instagram" class="block text-sm font-medium text-gray-700 mb-1">
                                📷 Instagram
                            </label>
                            <input type="url" name="url_instagram" id="url_instagram" 
                                   value="{{ old('url_instagram', $contacto->url_instagram) }}"
                                   placeholder="https://instagram.com/cancha_sintetica"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('url_instagram') border-red-500 @enderror">
                            @error('url_instagram') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="url_twitter" class="block text-sm font-medium text-gray-700 mb-1">
                                🐦 Twitter / X
                            </label>
                            <input type="url" name="url_twitter" id="url_twitter" 
                                   value="{{ old('url_twitter', $contacto->url_twitter) }}"
                                   placeholder="https://twitter.com/cancha_sintetica"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('url_twitter') border-red-500 @enderror">
                            @error('url_twitter') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Ubicación -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="text-2xl">📍</span>
                        Ubicación
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="direccion" class="block text-sm font-medium text-gray-700 mb-1">
                                Dirección Completa
                            </label>
                            <input type="text" name="direccion" id="direccion" 
                                   value="{{ old('direccion', $contacto->direccion) }}"
                                   placeholder="Av. Los Deportistas 123, Chinique"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('direccion') border-red-500 @enderror">
                            @error('direccion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="ciudad" class="block text-sm font-medium text-gray-700 mb-1">
                                Ciudad
                            </label>
                            <input type="text" name="ciudad" id="ciudad" 
                                   value="{{ old('ciudad', $contacto->ciudad) }}"
                                   placeholder="Chinique"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('ciudad') border-red-500 @enderror">
                            @error('ciudad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="pais" class="block text-sm font-medium text-gray-700 mb-1">
                                País
                            </label>
                            <input type="text" name="pais" id="pais" 
                                   value="{{ old('pais', $contacto->pais ?? 'Guatemala') }}"
                                   placeholder="Guatemala"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('pais') border-red-500 @enderror">
                            @error('pais') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="enlace_google_maps" class="block text-sm font-medium text-gray-700 mb-1">
                                🔗 Enlace de Google Maps
                            </label>
                            <input type="url" name="enlace_google_maps" id="enlace_google_maps" 
                                   value="{{ old('enlace_google_maps', $contacto->enlace_google_maps) }}"
                                   placeholder="https://maps.google.com/?q=Av.+Los+Deportistas+123+Guatemala"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm @error('enlace_google_maps') border-red-500 @enderror">
                            @error('enlace_google_maps') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            <p class="text-xs text-gray-500 mt-1">Pega aquí el enlace de "Compartir" de Google Maps.</p>
                        </div>
                    </div>
                </div>

                <!-- Archivos (Logo y Favicon) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="text-2xl">🖼️</span>
                        Archivos de Identidad
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Logo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Logo del Negocio</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                                @if($contacto->logo)
                                    <img src="{{ Storage::url($contacto->logo) }}" alt="Logo actual" 
                                         class="max-h-32 mx-auto mb-3 rounded-lg">
                                    <div class="flex justify-center gap-2">
                                        <label class="cursor-pointer px-3 py-1 bg-[#10B981] text-white text-xs font-medium rounded-lg hover:bg-[#059669] transition-colors">
                                            Cambiar
                                            <input type="file" name="logo" accept="image/*" class="hidden" onchange="previewImage(this, 'preview-logo')">
                                        </label>
                                        <a href="{{ route('admin.contacto.eliminar-logo') }}" 
                                           class="px-3 py-1 bg-[#EF4444] text-white text-xs font-medium rounded-lg hover:bg-[#DC2626] transition-colors"
                                           onclick="return confirm('¿Eliminar el logo actual?');">
                                            Eliminar
                                        </a>
                                    </div>
                                @else
                                    <div id="preview-logo" class="mb-3">
                                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <label class="cursor-pointer inline-block px-4 py-2 bg-[#10B981] text-white text-sm font-medium rounded-lg hover:bg-[#059669] transition-colors">
                                        Subir Logo
                                        <input type="file" name="logo" accept="image/*" class="hidden" onchange="previewImage(this, 'preview-logo')">
                                    </label>
                                @endif
                                <p class="text-xs text-gray-500 mt-2">JPG, PNG, SVG, WEBP. Máx. 2MB.</p>
                            </div>
                            @error('logo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Favicon -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                                @if($contacto->favicon)
                                    <img src="{{ Storage::url($contacto->favicon) }}" alt="Favicon actual" 
                                         class="w-16 h-16 mx-auto mb-3 rounded-lg object-contain bg-gray-100 p-2">
                                    <div class="flex justify-center gap-2">
                                        <label class="cursor-pointer px-3 py-1 bg-[#10B981] text-white text-xs font-medium rounded-lg hover:bg-[#059669] transition-colors">
                                            Cambiar
                                            <input type="file" name="favicon" accept="image/*" class="hidden" onchange="previewImage(this, 'preview-favicon')">
                                        </label>
                                        <a href="{{ route('admin.contacto.eliminar-favicon') }}" 
                                           class="px-3 py-1 bg-[#EF4444] text-white text-xs font-medium rounded-lg hover:bg-[#DC2626] transition-colors"
                                           onclick="return confirm('¿Eliminar el favicon actual?');">
                                            Eliminar
                                        </a>
                                    </div>
                                @else
                                    <div id="preview-favicon" class="mb-3">
                                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <label class="cursor-pointer inline-block px-4 py-2 bg-[#10B981] text-white text-sm font-medium rounded-lg hover:bg-[#059669] transition-colors">
                                        Subir Favicon
                                        <input type="file" name="favicon" accept="image/*" class="hidden" onchange="previewImage(this, 'preview-favicon')">
                                    </label>
                                @endif
                                <p class="text-xs text-gray-500 mt-2">PNG, ICO, WEBP. Máx. 512KB. Se recomienda 32x32px o 64x64px.</p>
                            </div>
                            @error('favicon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Configuración -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="text-2xl">⚙️</span>
                        Configuración
                    </h2>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-[#F9FAFB] rounded-lg gap-3">
                        <div>
                            <label class="text-sm font-medium text-gray-700">✅ Información activa</label>
                            <p class="text-xs text-gray-500 mt-1">Si está desactivada, la información no se mostrará en la página pública.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                            <input type="checkbox" name="esta_activo" value="1" 
                                   {{ old('esta_activo', $contacto->esta_activo ?? true) ? 'checked' : '' }} 
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#10B981]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#10B981]"></div>
                        </label>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-2">
                    <a href="{{ route('admin.dashboard') }}" class="w-full sm:w-auto text-center px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-[#10B981] hover:bg-[#059669] text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
                        💾 Guardar Información
                    </button>
                </div>
            </div>

            <!-- Columna Secundaria: Vista Previa -->
            <div class="space-y-6">
                <!-- Preview de cómo se verá en la web pública -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 lg:sticky lg:top-24">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <span class="text-lg">👁️</span>
                        Vista Previa (Web Pública)
                    </h3>

                    <div class="space-y-3 text-sm">
                        <!-- Teléfono -->
                        <div class="flex items-start gap-3 p-3 bg-[#F9FAFB] rounded-lg">
                            <span class="text-xl">📱</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500">Teléfono</p>
                                <p class="font-semibold text-gray-800 truncate" id="preview-telefono">
                                    {{ $contacto->telefono_principal ?? '+502 5555 1234' }}
                                </p>
                            </div>
                        </div>

                        <!-- WhatsApp -->
                        <div class="flex items-start gap-3 p-3 bg-[#F9FAFB] rounded-lg">
                            <span class="text-xl"></span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500">WhatsApp</p>
                                <p class="font-semibold text-gray-800 truncate" id="preview-whatsapp">
                                    {{ $contacto->numero_whatsapp ?? '+502 5555 1234' }}
                                </p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-start gap-3 p-3 bg-[#F9FAFB] rounded-lg">
                            <span class="text-xl">✉️</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500">Email</p>
                                <p class="font-semibold text-gray-800 truncate" id="preview-email">
                                    {{ $contacto->email_principal ?? 'contacto@cancha.com' }}
                                </p>
                            </div>
                        </div>

                        <!-- Facebook -->
                        @if($contacto->url_facebook)
                        <div class="flex items-start gap-3 p-3 bg-[#F9FAFB] rounded-lg">
                            <span class="text-xl">📘</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500">Facebook</p>
                                <p class="font-semibold text-[#10B981] truncate">{{ parse_url($contacto->url_facebook, PHP_URL_PATH) ?: '/CanchaSintetica' }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Instagram -->
                        @if($contacto->url_instagram)
                        <div class="flex items-start gap-3 p-3 bg-[#F9FAFB] rounded-lg">
                            <span class="text-xl">📷</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500">Instagram</p>
                                <p class="font-semibold text-[#10B981] truncate">{{ parse_url($contacto->url_instagram, PHP_URL_PATH) ?: '@cancha_sintetica' }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Dirección -->
                        <div class="flex items-start gap-3 p-3 bg-[#F9FAFB] rounded-lg">
                            <span class="text-xl">📍</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500">Dirección</p>
                                <p class="font-semibold text-gray-800" id="preview-direccion">
                                    {{ $contacto->direccion ?? 'Av. Los Deportistas 123, Chinique' }}
                                </p>
                            </div>
                        </div>

                        <!-- Horario -->
                        @if($contacto->horario_atencion)
                        <div class="flex items-start gap-3 p-3 bg-[#ECFDF5] rounded-lg border border-[#10B981]/20">
                            <span class="text-xl">🕐</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500">Horario</p>
                                <p class="font-semibold text-[#065F46] text-xs">{{ $contacto->horario_atencion }}</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Logo Preview -->
                    @if($contacto->logo)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500 mb-2">Logo actual:</p>
                        <img src="{{ Storage::url($contacto->logo) }}" alt="Logo" class="max-h-20 mx-auto rounded-lg">
                    </div>
                    @endif
                </div>

                <!-- Información del Sistema -->
                <div class="bg-gradient-to-br from-[#065F46] to-[#10B981] rounded-xl shadow-sm p-6 text-white">
                    <h3 class="text-sm font-semibold mb-3 opacity-90">ℹ️ Información</h3>
                    <div class="space-y-2 text-sm">
                        <p class="opacity-90">
                            Los cambios se reflejarán inmediatamente en:
                        </p>
                        <ul class="list-disc list-inside text-xs opacity-80 space-y-1">
                            <li>Página de Contacto (/contacto)</li>
                            <li>Footer del sitio web</li>
                            <li>Navbar (nombre del negocio)</li>
                        </ul>
                        @if($contacto->updated_at)
                        <div class="pt-3 mt-3 border-t border-white/20">
                            <p class="text-xs opacity-80">Última actualización:</p>
                            <p class="text-sm font-medium">{{ $contacto->updated_at->format('d/m/Y H:i') }}</p>
                            @if($contacto->actualizadoPorUsuario)
                            <p class="text-xs opacity-80">Por: {{ $contacto->actualizadoPorUsuario->nombre }}</p>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Script para preview en tiempo real -->
    <script>
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(previewId);
                    preview.innerHTML = `<img src="${e.target.result}" class="max-h-32 mx-auto rounded-lg">`;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Actualizar vista previa en tiempo real
        document.getElementById('telefono_principal')?.addEventListener('input', function() {
            document.getElementById('preview-telefono').textContent = this.value || '+502 5555 1234';
        });

        document.getElementById('numero_whatsapp')?.addEventListener('input', function() {
            document.getElementById('preview-whatsapp').textContent = this.value || '+502 5555 1234';
        });

        document.getElementById('email_principal')?.addEventListener('input', function() {
            document.getElementById('preview-email').textContent = this.value || 'contacto@cancha.com';
        });

        document.getElementById('direccion')?.addEventListener('input', function() {
            document.getElementById('preview-direccion').textContent = this.value || 'Av. Los Deportistas 123, Chinique';
        });
    </script>
@endsection
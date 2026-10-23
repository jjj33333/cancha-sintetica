@extends('layouts.guest')

@section('title', 'Inicio - ' . ($contacto->nombre_negocio ?? 'Cancha Sintética Deportiva'))

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-b from-[#ECFDF5] via-[#D1FAE5]/60 to-white pt-10 pb-14 sm:pt-16 sm:pb-20 lg:pt-20 lg:pb-24 overflow-hidden">
        <!-- Elementos decorativos de fondo -->
        <div class="pointer-events-none absolute -top-24 -right-24 w-72 sm:w-96 h-72 sm:h-96 bg-emerald-200/40 rounded-full blur-3xl"></div>
        <div class="pointer-events-none absolute top-1/2 -left-24 w-60 sm:w-80 h-60 sm:h-80 bg-teal-200/30 rounded-full blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Badge / Píldora de bienvenida -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs sm:text-sm font-semibold bg-emerald-100/90 text-[#065F46] border border-emerald-200/80 shadow-sm mb-4 sm:mb-6">
                <span class="w-2 h-2 rounded-full bg-[#10B981] animate-pulse"></span>
                <span>Instalaciones de Primer Nivel</span>
            </div>

            <!-- Título Principal Responsive -->
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-[#065F46] tracking-tight mb-4 sm:mb-6 leading-tight max-w-4xl mx-auto break-words">
                Bienvenido a <span class="text-[#047857]">{{ $contacto->nombre_negocio ?? 'Cancha Sintética Deportiva' }}</span>
            </h1>

            <!-- Subtítulo -->
            <p class="text-base sm:text-lg md:text-xl text-gray-600 max-w-2xl mx-auto mb-8 sm:mb-10 px-2 leading-relaxed">
                El mejor lugar para disfrutar del fútbol.
            </p>

            <!-- Botones de Acción Responsive -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 max-w-md sm:max-w-none mx-auto mb-10 sm:mb-12 px-2 sm:px-0 w-full">
                <a href="{{ route('horarios') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#10B981] hover:bg-[#059669] text-white font-semibold py-3.5 px-7 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 active:translate-y-0 text-center text-sm sm:text-base">
                    <span>📅</span> Ver Horarios y Disponibilidad
                </a>

                @if(!empty($contacto?->numero_whatsapp ?? $contacto?->telefono_principal))
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contacto?->numero_whatsapp ?? $contacto?->telefono_principal) }}?text=Hola,%20quisiera%20consultar%20disponibilidad%20de%20la%20cancha" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white hover:bg-emerald-50 text-[#065F46] border border-emerald-300 font-semibold py-3.5 px-6 rounded-xl shadow-sm hover:shadow transition-all text-center text-sm sm:text-base">
                        <span>💬</span> Contactar por WhatsApp
                    </a>
                @else
                    <a href="{{ route('torneos') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white hover:bg-emerald-50 text-[#065F46] border border-emerald-300 font-semibold py-3.5 px-6 rounded-xl shadow-sm hover:shadow transition-all text-center text-sm sm:text-base">
                        <span>🏆</span> Ver Torneos
                    </a>
                @endif
            </div>

            <!-- Tira de Destacados Rápidos (Responsive Pills) -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-4 max-w-4xl mx-auto pt-6 border-t border-emerald-200/60">
                <div class="bg-white/80 backdrop-blur-sm rounded-xl p-3 border border-emerald-100 flex items-center justify-center gap-2 text-xs sm:text-sm font-medium text-emerald-900 shadow-sm">
                    <span class="text-base sm:text-lg">🌱</span>
                    <span class="truncate">Césped Sintético Pro</span>
                </div>
                <div class="bg-white/80 backdrop-blur-sm rounded-xl p-3 border border-emerald-100 flex items-center justify-center gap-2 text-xs sm:text-sm font-medium text-emerald-900 shadow-sm">
                    <span class="text-base sm:text-lg">💡</span>
                    <span class="truncate">Iluminación LED</span>
                </div>
                <div class="bg-white/80 backdrop-blur-sm rounded-xl p-3 border border-emerald-100 flex items-center justify-center gap-2 text-xs sm:text-sm font-medium text-emerald-900 shadow-sm">
                    <span class="text-base sm:text-lg">🚗</span>
                    <span class="truncate">Estacionamiento</span>
                </div>
                <div class="bg-white/80 backdrop-blur-sm rounded-xl p-3 border border-emerald-100 flex items-center justify-center gap-2 text-xs sm:text-sm font-medium text-emerald-900 shadow-sm">
                    <span class="text-base sm:text-lg">⏱️</span>
                    <span class="truncate">Alquiler por Hora</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Información de la Cancha -->
    <div class="py-12 sm:py-16 md:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-10 sm:mb-14">
                <span class="text-emerald-700 text-xs sm:text-sm font-bold tracking-wider uppercase">Equipamiento y Servicios</span>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#065F46] mt-1.5 mb-3">🏟️ Sobre Nuestra Cancha</h2>
                <p class="text-sm sm:text-base text-gray-600 px-2">
                    Nuestras instalaciones están acondicionadas para ofrecerte la máxima seguridad, tracción y confort deportivo.
                </p>
            </div>
            
            <!-- Grid de Tarjetas (1 col en móvil, 2 col en tablet, 3 col en desktop) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-10 sm:mb-12">
                <!-- Tarjeta 1: Ubicación -->
                <div class="group bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all transform hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                        📍
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2">Ubicación</h3>
                    <p class="text-sm sm:text-base text-gray-600 leading-relaxed mb-3">
                        {{ $contacto?->direccion ?? 'Av. Los Deportistas 123, Chinique' }}
                        @if(!empty($contacto?->ciudad))
                            <span class="block text-xs text-gray-500 mt-1">{{ $contacto?->ciudad }}{{ !empty($contacto?->pais) ? ', ' . $contacto?->pais : '' }}</span>
                        @endif
                    </p>
                    @if(!empty($contacto?->enlace_google_maps))
                        <a href="{{ $contacto?->enlace_google_maps }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-xs font-semibold text-[#10B981] hover:underline">
                            Ver en Google Maps ↗
                        </a>
                    @endif
                </div>

                <!-- Tarjeta 2: Dimensiones -->
                <div class="group bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all transform hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                        📏
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2">Dimensiones</h3>
                    <p class="text-sm sm:text-base text-gray-600 leading-relaxed mb-1">
                        {{ $instalacion?->dimensiones ?? '40m x 20m' }}
                    </p>
                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100 mt-1">
                        Capacidad: {{ $instalacion?->capacidad ?? 10 }} jugadores
                    </span>
                </div>

                <!-- Tarjeta 3: Iluminación -->
                <!-- <div class="group bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all transform hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                        💡
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2">Iluminación</h3>
                    <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                        @if(!empty($instalacion->tiene_iluminacion))
                            Reflectores LED profesionales de alta cobertura para encuentros nocturnos con excelente visibilidad.
                        @else
                            Partidos en horario diurno con óptima iluminación natural.
                        @endif
                    </p>
                </div> -->

                <!-- Tarjeta 4: Estacionamiento -->
                <div class="group bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all transform hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                        🚗
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2">Estacionamiento</h3>
                    <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                        @if(!empty($instalacion?->tiene_estacionamiento))
                            Estacionamiento privado para vehículos y motocicletas, garantizando tu tranquilidad durante el juego.
                        @else
                            Espacio accesible en los alrededores de las instalaciones.
                        @endif
                    </p>
                </div>

                <!-- Tarjeta 5: Vestuarios y Comodidades -->
                <!-- <div class="group bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all transform hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                        🚿
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2">Vestuarios</h3>
                    <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                        @if(!empty($instalacion?->tiene_casilleros))
                            Casilleros individuales, duchas y área de vestuario totalmente higienizadas.
                        @else
                            Áreas de hidratación y sanitarios disponibles en el recinto deportivo.
                        @endif
                    </p>
                </div> -->

                <!-- Tarjeta 6: Superficie -->
                <div class="group bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all transform hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                        ⚽
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2">Superficie</h3>
                    <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                        {{ $instalacion?->tipo_superficie ?? 'Césped sintético' }}.
                        Fibras sintéticas diseñadas para reducir el desgaste articular y asegurar un bote regular del balón.
                    </p>
                </div>
            </div>

            <!-- Descripción adicional en banner responsive -->
            @if(!empty($instalacion?->descripcion))
                <div class="bg-gradient-to-r from-emerald-50/90 via-[#ECFDF5] to-teal-50/90 rounded-2xl p-6 sm:p-8 border border-emerald-100/90 shadow-sm max-w-4xl mx-auto">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 text-center sm:text-left">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-[#065F46] flex items-center justify-center text-2xl flex-shrink-0">
                            ℹ️
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-[#065F46] mb-1">
                                {{ $instalacion?->nombre ?? 'Instalación Principal' }}
                            </h3>
                            <p class="text-gray-700 text-sm sm:text-base leading-relaxed">
                                {{ $instalacion?->descripcion }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Sección de Horarios y Reservas Rápidas -->
    <div class="py-12 sm:py-16 md:py-20 bg-gradient-to-b from-[#F9FAFB] to-[#ECFDF5]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="bg-white rounded-3xl p-6 sm:p-10 md:p-12 shadow-sm border border-emerald-100">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-emerald-100 text-[#065F46] text-2xl mb-4 sm:mb-6 shadow-inner">
                    🕐
                </div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#065F46] mb-3">
                    Horarios de Atención y Reservas
                </h2>

                @if(!empty($contacto?->horario_atencion))
                    <div class="inline-block bg-emerald-50 border border-emerald-200/80 text-emerald-900 rounded-xl px-4 py-2.5 text-xs sm:text-sm md:text-base font-medium mb-6 max-w-2xl">
                        ⏰ {{ $contacto?->horario_atencion }}
                    </div>
                @endif

                <p class="text-gray-600 text-sm sm:text-base md:text-lg max-w-2xl mx-auto mb-8 leading-relaxed">
                    Consulta el calendario de disponibilidad en tiempo real o contáctanos por teléfono o WhatsApp para apartar tu horario.
                </p>

                <!-- Botones responsive en columna para móviles, fila para tablets/escritorio -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-3 sm:gap-4 max-w-md sm:max-w-none mx-auto">
                    <a href="{{ route('horarios') }}" class="inline-flex items-center justify-center gap-2 bg-[#065F46] hover:bg-[#047857] text-white font-semibold py-3.5 px-8 rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 text-center text-sm sm:text-base">
                        <span>📅</span> Ver Calendario Completo
                    </a>

                    @if(!empty($contacto?->telefono_principal))
                        <a href="tel:{{ $contacto?->telefono_principal }}" class="inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-[#065F46] border border-emerald-300 font-semibold py-3.5 px-6 rounded-xl shadow-sm transition-all text-center text-sm sm:text-base">
                            <span>📞</span> Llamar {{ $contacto?->telefono_principal }}
                        </a>
                    @endif

                    @if(!empty($contacto?->numero_whatsapp))
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contacto?->numero_whatsapp) }}?text=Hola,%20quisiera%20reservar%20la%20cancha" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 bg-[#10B981] hover:bg-[#059669] text-white font-semibold py-3.5 px-6 rounded-xl shadow-md transition-all transform hover:-translate-y-0.5 text-center text-sm sm:text-base">
                            <span>💬</span> WhatsApp
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
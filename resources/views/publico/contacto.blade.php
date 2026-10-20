@extends('layouts.guest')

@section('title', 'Contacto')

@section('content')
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#ECFDF5] to-[#D1FAE5] py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-[#065F46] mb-3">📞 Contacto</h1>
            <p class="text-gray-600">¿Tienes preguntas? Contáctanos por cualquiera de estos medios.</p>
        </div>
    </div>

    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Teléfono -->
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all hover:-translate-y-1 p-6 text-center">
                    <div class="text-4xl mb-4">📱</div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Teléfono</h3>
                    <a href="tel:{{ $contacto->telefono_principal }}" class="text-[#10B981] font-semibold hover:underline text-lg">
                        {{ $contacto->telefono_principal }}
                    </a>
                    @if($contacto->telefono_secundario)
                        <p class="text-sm text-gray-500 mt-1">{{ $contacto->telefono_secundario }}</p>
                    @endif
                </div>

                <!-- WhatsApp -->
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all hover:-translate-y-1 p-6 text-center">
                    <div class="text-4xl mb-4">💬</div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">WhatsApp</h3>
                    <a href="https://wa.me/{{ str_replace(['+', ' '], '', $contacto->numero_whatsapp) }}" target="_blank"
                       class="text-[#10B981] font-semibold hover:underline text-lg">
                        {{ $contacto->numero_whatsapp }}
                    </a>
                </div>

                <!-- Email -->
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all hover:-translate-y-1 p-6 text-center">
                    <div class="text-4xl mb-4">✉️</div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Email</h3>
                    <a href="mailto:{{ $contacto->email_principal }}" class="text-[#10B981] font-semibold hover:underline">
                        {{ $contacto->email_principal }}
                    </a>
                </div>

                <!-- Facebook -->
                @if($contacto->url_facebook)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all hover:-translate-y-1 p-6 text-center">
                        <div class="text-4xl mb-4">📘</div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Facebook</h3>
                        <a href="{{ $contacto->url_facebook }}" target="_blank" class="text-[#10B981] font-semibold hover:underline">
                            /CanchaSintetica
                        </a>
                    </div>
                @endif

                <!-- Instagram -->
                @if($contacto->url_instagram)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all hover:-translate-y-1 p-6 text-center">
                        <div class="text-4xl mb-4">📷</div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Instagram</h3>
                        <a href="{{ $contacto->url_instagram }}" target="_blank" class="text-[#10B981] font-semibold hover:underline">
                            @cancha_sintetica
                        </a>
                    </div>
                @endif

                <!-- Dirección -->
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all hover:-translate-y-1 p-6 text-center">
                    <div class="text-4xl mb-4">📍</div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Dirección</h3>
                    <p class="text-gray-600">{{ $contacto->direccion }}</p>
                    @if($contacto->enlace_google_maps)
                        <a href="{{ $contacto->enlace_google_maps }}" target="_blank"
                           class="inline-block mt-3 text-sm bg-[#ECFDF5] hover:bg-[#D1FAE5] text-[#065F46] font-semibold px-4 py-2 rounded-lg transition-colors">
                            Ver en Google Maps
                        </a>
                    @endif
                </div>
            </div>

            <!-- Horario de Atención -->
            @if($contacto->horario_atencion)
                <div class="mt-12 bg-[#ECFDF5] rounded-xl p-8 text-center">
                    <h3 class="text-xl font-bold text-[#065F46] mb-3">🕐 Horario de Atención</h3>
                    <p class="text-gray-700 text-lg">{{ $contacto->horario_atencion }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection
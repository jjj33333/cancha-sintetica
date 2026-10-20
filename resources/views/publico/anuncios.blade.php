@extends('layouts.guest')

@section('title', 'Anuncios y Noticias - ' . ($contacto->nombre_negocio ?? 'Cancha Sintética'))

@section('content')
    <!-- Header Hero Responsive -->
    <div class="relative bg-gradient-to-b from-[#ECFDF5] via-[#D1FAE5]/50 to-white py-10 sm:py-14 lg:py-16 overflow-hidden">
        <div class="pointer-events-none absolute -top-20 -right-20 w-64 sm:w-80 h-64 sm:h-80 bg-emerald-200/30 rounded-full blur-3xl"></div>
        <div class="pointer-events-none absolute top-1/2 -left-20 w-52 sm:w-72 h-52 sm:h-72 bg-teal-200/30 rounded-full blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs sm:text-sm font-semibold bg-emerald-100/90 text-[#065F46] border border-emerald-200 shadow-sm mb-3 sm:mb-4">
                <span>📢</span>
                <span>Comunicados y Novedades</span>
            </div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-[#065F46] tracking-tight mb-3">
                Anuncios y Noticias
            </h1>
            <p class="text-sm sm:text-base md:text-lg text-gray-600 max-w-2xl mx-auto px-2 leading-relaxed">
                Mantente informado sobre próximos torneos, promociones, mantenimientos y avisos importantes de nuestras instalaciones.
            </p>
        </div>
    </div>

    <!-- Listado de Anuncios -->
    <div class="py-8 sm:py-12 bg-white min-h-[50vh]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @forelse($anuncios as $anuncio)
                <article class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 overflow-hidden mb-6 sm:mb-8">
                    <!-- Barra de color según prioridad -->
                    <div class="h-2 {{ $anuncio->prioridad === 'urgente' ? 'bg-[#EF4444]' : ($anuncio->prioridad === 'alta' ? 'bg-[#F59E0B]' : ($anuncio->prioridad === 'media' ? 'bg-[#3B82F6]' : 'bg-[#10B981]')) }}"></div>
                    
                    <div class="p-5 sm:p-7 md:p-8">
                        <!-- Metadatos superiores: tipo, prioridad, fecha -->
                        <div class="flex flex-wrap items-center justify-between gap-2.5 mb-4">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $anuncio->tipo_badge }}">
                                    <span>{{ $anuncio->tipo_icono }}</span>
                                    <span>{{ ucfirst($anuncio->tipo) }}</span>
                                </span>

                                @if($anuncio->prioridad === 'urgente')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                        🚨 Urgente
                                    </span>
                                @elseif($anuncio->prioridad === 'alta')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                        ⚡ Importante
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                @if($anuncio->publicado_en)
                                    <span class="inline-flex items-center gap-1 font-medium bg-gray-50 px-2.5 py-1 rounded-md border border-gray-100">
                                        <span>📅</span> {{ $anuncio->publicado_en->format('d/m/Y') }}
                                    </span>
                                @elseif($anuncio->created_at)
                                    <span class="inline-flex items-center gap-1 font-medium bg-gray-50 px-2.5 py-1 rounded-md border border-gray-100">
                                        <span>📅</span> {{ $anuncio->created_at->format('d/m/Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Imagen del anuncio si existe -->
                        @if($anuncio->imagen)
                            <div class="mb-5 rounded-xl overflow-hidden border border-gray-100 shadow-sm bg-gray-50">
                                <img src="{{ Storage::url($anuncio->imagen) }}" 
                                     alt="{{ $anuncio->titulo }}" 
                                     class="w-full max-h-72 sm:max-h-96 object-cover hover:scale-[1.01] transition-transform duration-300">
                            </div>
                        @endif

                        <!-- Título -->
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 leading-snug break-words">
                            {{ $anuncio->titulo }}
                        </h2>

                        <!-- Contenido con saltos de línea respetados -->
                        <div class="text-sm sm:text-base text-gray-700 leading-relaxed space-y-2 break-words">
                            {!! nl2br(e($anuncio->contenido)) !!}
                        </div>

                        <!-- Vigencia / Expiración si aplica -->
                        @if($anuncio->expira_en)
                            <div class="mt-5 pt-4 border-t border-gray-100 flex items-center gap-2 text-xs text-amber-800 bg-amber-50/70 px-3.5 py-2 rounded-lg">
                                <span>⏳</span>
                                <span class="font-medium">Vigencia hasta: <strong>{{ $anuncio->expira_en->format('d/m/Y') }}</strong></span>
                            </div>
                        @endif
                    </div>
                </article>
            @empty
                <!-- Estado vacío responsive y amigable -->
                <div class="text-center py-16 sm:py-20 bg-gray-50/70 rounded-3xl border border-dashed border-gray-200 p-6 sm:p-12">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-3xl sm:text-4xl mb-4 shadow-sm">
                        📢
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2">No hay anuncios disponibles en este momento</h3>
                    <p class="text-xs sm:text-sm text-gray-500 max-w-md mx-auto mb-6">
                        No hay comunicados activos actualmente. Puedes consultar la disponibilidad de la cancha o nuestros torneos.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        <a href="{{ route('horarios') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#10B981] hover:bg-[#059669] text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm transition-all">
                            <span>📅</span> Ver Horarios
                        </a>
                        <a href="{{ route('torneos') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs sm:text-sm font-semibold rounded-xl transition-all">
                            <span>🏆</span> Ver Torneos
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
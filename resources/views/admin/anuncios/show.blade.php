@extends('layouts.admin')

@section('header_title', 'Detalles del Anuncio')
@section('title', $anuncio->titulo . ' - Admin')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <a href="{{ route('admin.anuncios.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-[#10B981] transition-colors mb-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a la lista
                </a>
                <h1 class="text-xl sm:text-2xl font-bold text-[#065F46] flex items-center gap-2">
                    <span>{{ $anuncio->tipo_icono }}</span>
                    <span>{{ $anuncio->titulo }}</span>
                </h1>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <a href="{{ route('admin.anuncios.edit', $anuncio) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-[#F59E0B] hover:bg-[#D97706] text-white text-sm font-semibold rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Editar
                </a>
            </div>
        </div>

        <!-- Imagen -->
        @if($anuncio->imagen)
            <div class="rounded-xl overflow-hidden shadow-sm">
                <img src="{{ Storage::url($anuncio->imagen) }}" alt="{{ $anuncio->titulo }}" class="w-full h-48 sm:h-64 object-cover">
            </div>
        @endif

        <!-- Badges de Estado -->
        <div class="flex flex-wrap gap-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-semibold {{ $anuncio->tipo_badge }}">
                {{ $anuncio->tipo_icono }} {{ ucfirst($anuncio->tipo) }}
            </span>
            
            @php
                $prioridadColor = match($anuncio->prioridad) {
                    'baja' => 'bg-[#F3F4F6] text-[#374151]',
                    'media' => 'bg-[#DBEAFE] text-[#1E40AF]',
                    'alta' => 'bg-[#FEF3C7] text-[#92400E]',
                    'urgente' => 'bg-[#FEE2E2] text-[#991B1B]',
                    default => 'bg-gray-100 text-gray-600',
                };
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-semibold {{ $prioridadColor }}">
                Prioridad: {{ ucfirst($anuncio->prioridad) }}
            </span>

            @if($anuncio->esta_publicado)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-semibold bg-[#D1FAE5] text-[#065F46]">
                    ✓ Publicado
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-semibold bg-[#F3F4F6] text-[#374151]">
                    Borrador
                </span>
            @endif

            @if($anuncio->mostrar_en_publico)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-semibold bg-[#DBEAFE] text-[#1E40AF]">
                    👁️ Visible en público
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-semibold bg-[#FEE2E2] text-[#991B1B]">
                    🙈 Oculto en público
                </span>
            @endif
        </div>

        <!-- Información Detallada -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            <!-- Fechas -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 sm:p-6">
                <h2 class="text-base font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Fechas</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500 font-medium">Publicado el:</span>
                        <span class="font-medium text-gray-800">
                            {{ $anuncio->publicado_en?->format('d/m/Y H:i') ?? 'No publicado' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 font-medium">Expira el:</span>
                        <span class="font-medium text-gray-800">
                            @if($anuncio->expira_en)
                                {{ $anuncio->expira_en->format('d/m/Y H:i') }}
                                @if($anuncio->expira_en->isPast())
                                    <span class="ml-2 text-xs bg-[#FEE2E2] text-[#991B1B] px-2 py-0.5 rounded-full font-bold">Expirado</span>
                                @endif
                            @else
                                No expira
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 font-medium">Creado el:</span>
                        <span class="font-medium text-gray-800">{{ $anuncio->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 sm:p-6">
                <h2 class="text-base font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Estadísticas</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500 font-medium">Visualizaciones:</span>
                        <span class="font-bold text-[#065F46]">{{ number_format($anuncio->vistas) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 font-medium">Publicado por:</span>
                        <span class="font-medium text-gray-800">{{ $anuncio->publicadoPorUsuario?->nombre ?? 'Sistema' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 sm:p-6">
            <h2 class="text-base font-bold text-gray-800 mb-3 border-b border-gray-100 pb-2">Contenido</h2>
            <div class="prose max-w-none text-gray-700 leading-relaxed text-sm sm:text-base whitespace-pre-line">
                {{ $anuncio->contenido }}
            </div>
        </div>
    </div>
@endsection
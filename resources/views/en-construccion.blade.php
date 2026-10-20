@php
    // Detecta automáticamente qué layout usar según el rol del usuario logueado
    $layout = 'layouts.guest';
    if (Auth::check()) {
        $layout = 'layouts.' . Auth::user()->rol; // Ej: 'layouts.admin', 'layouts.trabajador'
    }
@endphp

@extends($layout)

@section('header_title', $titulo ?? 'Módulo')
@section('title', $titulo ?? 'En Construcción')

@section('content')
    <div class="flex flex-col items-center justify-center py-20 text-center animate-fade-in">
        <!-- Icono animado o estático -->
        <div class="text-7xl mb-6">🚧</div>
        
        <h2 class="text-3xl font-bold text-[#065F46] mb-3">
            {{ $titulo ?? 'Módulo en Desarrollo' }}
        </h2>
        
        <p class="text-gray-600 max-w-lg mb-8 text-lg">
            Estamos trabajando arduamente en la sección de <strong class="text-[#10B981]">{{ $modulo ?? 'este módulo' }}</strong>. 
            <br>Pronto estará disponible con todas las funcionalidades.
        </p>
        
        <a href="{{ url()->previous() }}" class="inline-flex items-center px-6 py-3 bg-[#10B981] text-white font-semibold rounded-lg hover:bg-[#059669] transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver al Dashboard
        </a>
    </div>
@endsection
@extends('layouts.admin')
@section('header_title', 'Dashboard General')
@section('title', 'Dashboard Admin')

@section('content')
    <div class="bg-white p-4 sm:p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
        <div class="max-w-3xl">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#065F46] tracking-tight mb-2">¡Bienvenido al Panel de Administración!</h1>
            <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                Control y gestión completa de canchas sintéticas, horarios, reservas, torneos y configuración del sistema.
            </p>
        </div>
        
        <!-- Tarjetas de Acceso Rápido / Stats Responsive -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mt-6 sm:mt-8">
            <a href="{{ route('admin.reservas.index') }}" class="group bg-[#F9FAFB] hover:bg-[#ECFDF5] p-5 sm:p-6 rounded-xl border border-gray-100 hover:border-[#10B981]/40 shadow-xs hover:shadow-md transition-all flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-[#D1FAE5] group-hover:bg-[#10B981] group-hover:text-white flex items-center justify-center text-[#065F46] text-2xl transition-colors flex-shrink-0">
                    📅
                </div>
                <div>
                    <p class="text-xs sm:text-sm text-gray-500 font-medium">Gestión Operativa</p>
                    <p class="text-base sm:text-lg font-bold text-[#065F46]">Reservas</p>
                </div>
            </a>

            <a href="{{ route('admin.calendario') }}" class="group bg-[#F9FAFB] hover:bg-[#ECFDF5] p-5 sm:p-6 rounded-xl border border-gray-100 hover:border-[#10B981]/40 shadow-xs hover:shadow-md transition-all flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-[#D1FAE5] group-hover:bg-[#10B981] group-hover:text-white flex items-center justify-center text-[#065F46] text-2xl transition-colors flex-shrink-0">
                    📆
                </div>
                <div>
                    <p class="text-xs sm:text-sm text-gray-500 font-medium">Disponibilidad</p>
                    <p class="text-base sm:text-lg font-bold text-[#065F46]">Calendario</p>
                </div>
            </a>

            <a href="{{ route('admin.torneos.index') }}" class="group bg-[#F9FAFB] hover:bg-[#ECFDF5] p-5 sm:p-6 rounded-xl border border-gray-100 hover:border-[#10B981]/40 shadow-xs hover:shadow-md transition-all flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-[#D1FAE5] group-hover:bg-[#10B981] group-hover:text-white flex items-center justify-center text-[#065F46] text-2xl transition-colors flex-shrink-0">
                    🏆
                </div>
                <div>
                    <p class="text-xs sm:text-sm text-gray-500 font-medium">Campeonatos</p>
                    <p class="text-base sm:text-lg font-bold text-[#065F46]">Torneos y Partidos</p>
                </div>
            </a>
        </div>
    </div>
@endsection
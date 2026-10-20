@extends('layouts.trabajador')
@section('header_title', 'Dashboard')
@section('rol_nombre', 'Trabajador')
@section('title', 'Dashboard Trabajador')

@section('content')
    <div class="space-y-6">
        <!-- Banner de Bienvenida -->
        <div class="bg-gradient-to-r from-[#065F46] to-[#047857] text-white p-5 sm:p-8 rounded-2xl shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/30 text-emerald-100 mb-2">
                    🛠️ Panel del Trabajador
                </span>
                <h1 class="text-2xl sm:text-3xl font-bold">¡Bienvenido, {{ Auth::user()?->nombre ?? 'Trabajador' }}!</h1>
                <p class="mt-1 text-emerald-100 text-xs sm:text-sm max-w-xl">
                    Desde aquí puedes registrar reservas de clientes, verificar horarios ocupados y consultar el calendario en tiempo real.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('trabajador.reservas.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-white text-[#065F46] hover:bg-emerald-50 text-xs sm:text-sm font-bold rounded-xl shadow transition-all">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Nueva Reserva
                </a>
            </div>
        </div>

        <!-- Accesos Directos a Módulos -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
            <!-- Acceso a Reservas -->
            <a href="{{ route('trabajador.reservas.index') }}" class="group bg-white p-5 sm:p-6 rounded-2xl shadow-sm border border-gray-100 hover:border-emerald-300 hover:shadow-md transition-all flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shrink-0">
                    📅
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-gray-800 group-hover:text-[#065F46] transition-colors">
                        Gestión de Reservas
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Consulta reservas activas, registra nuevos turnos con cancha por defecto y cambia estados de confirmación.
                    </p>
                    <span class="inline-flex items-center text-xs font-semibold text-[#10B981] mt-3 group-hover:translate-x-1 transition-transform">
                        Ir a reservas &rarr;
                    </span>
                </div>
            </a>

            <!-- Acceso a Calendario -->
            <a href="{{ route('trabajador.calendario.index') }}" class="group bg-white p-5 sm:p-6 rounded-2xl shadow-sm border border-gray-100 hover:border-blue-300 hover:shadow-md transition-all flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shrink-0">
                    🗓️
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-gray-800 group-hover:text-blue-700 transition-colors">
                        Calendario General
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        Monitorea la ocupación semanal de la cancha, incluyendo partidos de torneos y bloqueos programados.
                    </p>
                    <span class="inline-flex items-center text-xs font-semibold text-blue-600 mt-3 group-hover:translate-x-1 transition-transform">
                        Ver calendario semanal &rarr;
                    </span>
                </div>
            </a>
        </div>
    </div>
@endsection
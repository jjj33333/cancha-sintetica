@extends('layouts.guest')

@section('title', 'Torneos')

@section('content')
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#ECFDF5] to-[#D1FAE5] py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-[#065F46] mb-3">🏆 Torneos</h1>
            <p class="text-gray-600">Consulta los torneos activos, próximos partidos y tablas de posiciones.</p>
        </div>
    </div>

    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Tabs de navegación -->
            <div class="flex flex-wrap gap-2 mb-10 border-b-2 border-gray-100 pb-4" x-data="{ tab: @js($tabInicial) }">

                <button @click="tab = 'posiciones'"
                    :class="tab === 'posiciones' ? 'bg-[#10B981] text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    class="px-5 py-2.5 rounded-lg font-semibold text-sm transition-all">
                    Tabla de Posiciones
                </button>
                <button @click="tab = 'calendario'"
                    :class="tab === 'calendario' ? 'bg-[#10B981] text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    class="px-5 py-2.5 rounded-lg font-semibold text-sm transition-all">
                    Calendario de Partidos
                </button>
                <!-- <button @click="tab = 'activos'"
                    :class="tab === 'activos' ? 'bg-[#10B981] text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    class="px-5 py-2.5 rounded-lg font-semibold text-sm transition-all">
                    Torneos Activos
                </button> -->

                <!-- TAB 1: Torneos Activos -->
                <!-- <div x-show="tab === 'activos'" x-transition class="w-full mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($torneos as $torneo)
                            <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all hover:-translate-y-1 overflow-hidden">
                                <div class="p-6">
                                    <h3 class="text-lg font-bold text-gray-800 mb-2 flex items-start gap-2">
                                        <span>
                                            @if($torneo->categoria === 'libre') 🏆
                                            @elseif($torneo->categoria === 'sub20') 🥇
                                            @elseif($torneo->categoria === 'femenino') ⭐
                                            @endif
                                        </span>
                                        <span>{{ $torneo->nombre }}</span>
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium text-gray-700">Categoría:</span> {{ ucfirst(str_replace('_', ' ', $torneo->categoria)) }}
                                    </p>
                                    <a href="{{ route('torneos', ['tab' => 'calendario', 'torneo_id' => $torneo->id]) }}"
                                       class="mt-4 block w-full text-center bg-[#ECFDF5] hover:bg-[#D1FAE5] text-[#065F46] font-semibold py-2 rounded-lg transition-colors text-sm">
                                        Ver partidos
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12 text-gray-400">
                                <p class="text-4xl mb-3">🏆</p>
                                <p class="font-medium">No hay torneos activos en este momento</p>
                            </div>
                        @endforelse
                    </div>
                </div> -->

                <!-- TAB 2: Calendario de Partidos -->
                <div x-show="tab === 'calendario'" x-transition class="w-full mt-6">
                    <!-- Encabezado y filtro por campeonato -->
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-[#065F46]">📅 Próximos Partidos Programados</h3>
                            <p class="text-sm text-gray-500 mt-1">ℹ️ Fecha y hora de los próximos encuentros del campeonato seleccionado.</p>
                        </div>

                        <form method="GET" action="{{ route('torneos') }}" class="w-full md:w-auto">
                            <input type="hidden" name="tab" value="calendario">
                            <label for="torneo_calendario" class="block text-xs font-medium text-gray-500 mb-1">Campeonato</label>
                            <select name="torneo_id" id="torneo_calendario" onchange="this.form.submit()"
                                    class="w-full md:min-w-[240px] px-3.5 sm:px-4 py-2.5 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm font-medium text-gray-700">
                                @forelse($torneos as $torneo)
                                    <option value="{{ $torneo->id }}" {{ $torneoCalendarioSeleccionado && $torneoCalendarioSeleccionado->id == $torneo->id ? 'selected' : '' }}>
                                        {{ $torneo->nombre }}
                                    </option>
                                @empty
                                    <option value="">Sin campeonatos</option>
                                @endforelse
                            </select>
                        </form>
                    </div>

                    <div class="space-y-4">
                        @forelse($partidosProximos as $partido)
                            <div class="bg-white rounded-xl border border-gray-100 p-5 hover:shadow-md transition-all flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <span class="text-2xl">
                                        @if($partido->torneo->categoria === 'libre') 🏆
                                        @elseif($partido->torneo->categoria === 'sub20') 🥇
                                        @else ⭐
                                        @endif
                                    </span>
                                    <div>
                                        <p class="font-bold text-gray-800">
                                            {{ $partido->equipoLocal->nombre ?? 'TBD' }} vs {{ $partido->equipoVisitante->nombre ?? 'TBD' }}
                                        </p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            📅 {{ $partido->fecha_partido->format('d/m/Y') }} | 🕐 {{ \Carbon\Carbon::parse($partido->hora_inicio)->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#DBEAFE] text-[#1E40AF] self-start md:self-auto">
                                    Programado
                                </span>
                            </div>
                        @empty
                            <div class="bg-white rounded-xl border border-dashed border-gray-200 p-10 text-center text-gray-400">
                                <p class="text-3xl mb-2">📅</p>
                                <p class="font-medium">No hay partidos programados próximamente para este campeonato.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- TAB 3: Tabla de Posiciones -->
                <div x-show="tab === 'posiciones'" x-transition class="w-full mt-6">
                    @forelse($tablasPosiciones as $torneoId => $equipos)
                        @php
                            $torneo = $torneos->firstWhere('id', $torneoId);
                        @endphp
                        @if($torneo)
                            <h3 class="text-xl font-bold text-[#065F46] mb-4">
                                @if($torneo->categoria === 'libre') 🏆
                                @elseif($torneo->categoria === 'sub20') 🥇
                                @endif
                                {{ $torneo->nombre }} - Grupo A
                            </h3>
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead>
                                            <tr class="bg-[#F9FAFB] border-b-2 border-gray-200">
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Equipo</th>
                                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">PJ</th>
                                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">PG</th>
                                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">PE</th>
                                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">PP</th>
                                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">GF</th>
                                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">GC</th>
                                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">DIF</th>
                                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">PTS</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50">
                                            @foreach($equipos as $index => $equipo)
                                                <tr class="hover:bg-[#ECFDF5] transition-colors {{ $index < 2 ? 'bg-[#F0FDF4]' : '' }}">
                                                    <td class="px-4 py-3 font-bold text-gray-700">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-3 font-semibold text-gray-800">{{ $equipo->nombre }}</td>
                                                    <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $equipo->partidos_jugados }}</td>
                                                    <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $equipo->partidos_ganados }}</td>
                                                    <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $equipo->partidos_empatados }}</td>
                                                    <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $equipo->partidos_perdidos }}</td>
                                                    <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $equipo->goles_favor }}</td>
                                                    <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $equipo->goles_contra }}</td>
                                                    <td class="px-4 py-3 text-center text-sm font-medium {{ $equipo->diferencia_goles > 0 ? 'text-[#10B981]' : ($equipo->diferencia_goles < 0 ? 'text-[#EF4444]' : 'text-gray-500') }}">
                                                        {{ $equipo->diferencia_formateada }}
                                                    </td>
                                                    <td class="px-4 py-3 text-center font-bold text-[#065F46]">{{ $equipo->puntos }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @empty
                        <p class="text-gray-400 text-center py-12">No hay tablas de posiciones disponibles aún.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
@extends('layouts.guest')

@section('title', 'Horarios y Disponibilidad')

@section('content')
    <!-- Header de sección -->
    <div class="bg-gradient-to-br from-[#ECFDF5] to-[#D1FAE5] py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-[#065F46] mb-3">🕐 Horarios y Disponibilidad</h1>
            <p class="text-gray-600 max-w-2xl mx-auto text-sm md:text-base">
                Consulta los horarios y disponibilidad de la cancha. Selecciona un día para ver cada bloque de 1 hora.
            </p>
            @if(!empty($contacto->telefono_principal))
            <p class="text-sm text-gray-700 mt-2">
                Para reservas, llámanos al 
                <a href="tel:{{ $contacto->telefono_principal }}" class="text-[#10B981] font-bold hover:underline">
                    {{ $contacto->telefono_principal }}
                </a>
            </p>
            @endif
        </div>
    </div>

    <!-- Contenedor Interactivo con Alpine.js -->
    <div x-data="{
        semanas: @js($semanas),
        semanaIndex: 0,
        fechaSeleccionada: @js($fechaInicialSeleccionada),
        
        get semanaActual() {
            return this.semanas[this.semanaIndex] || this.semanas[0];
        },
        
        get diaSeleccionado() {
            for (let s of this.semanas) {
                for (let d of s.dias) {
                    if (d.fecha === this.fechaSeleccionada) {
                        return d;
                    }
                }
            }
            return (this.semanaActual && this.semanaActual.dias && this.semanaActual.dias[0]) ? this.semanaActual.dias[0] : null;
        },

        seleccionarDia(dia) {
            this.fechaSeleccionada = dia.fecha;
        },

        anteriorSemana() {
            if (this.semanaIndex > 0) {
                this.semanaIndex--;
                const diasFechas = this.semanaActual.dias.map(d => d.fecha);
                if (!diasFechas.includes(this.fechaSeleccionada)) {
                    this.fechaSeleccionada = this.semanaActual.dias[0].fecha;
                }
            }
        },

        siguienteSemana() {
            if (this.semanaIndex < this.semanas.length - 1) {
                this.semanaIndex++;
                const diasFechas = this.semanaActual.dias.map(d => d.fecha);
                if (!diasFechas.includes(this.fechaSeleccionada)) {
                    this.fechaSeleccionada = this.semanaActual.dias[0].fecha;
                }
            }
        }
    }" class="py-8 bg-white">

        <!-- 1. Horario Semanal con desplazamiento -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
            <!-- Barra superior: Título y Navegación de Semanas -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6 pb-4 border-b border-gray-100">
                <div>
                    <h2 class="text-2xl font-bold text-[#065F46] flex items-center gap-2">
                        <span>🗓️</span> Horario Semanal
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                        Selecciona un día para consultar su disponibilidad detallada.
                    </p>
                </div>

                <!-- Controles de navegación de semanas -->
                <div class="flex items-center gap-2 bg-[#F9FAFB] p-1.5 rounded-xl border border-gray-200">
                    <button type="button" 
                            @click="anteriorSemana()" 
                            :disabled="semanaIndex === 0"
                            :class="semanaIndex === 0 ? 'opacity-40 cursor-not-allowed text-gray-400' : 'hover:bg-white text-gray-700 hover:text-[#065F46] shadow-sm'"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all flex items-center gap-1">
                        <span>←</span> <span class="hidden md:inline">Semana</span> Anterior
                    </button>

                    <div class="px-3 py-1 text-xs font-bold text-[#065F46] bg-white rounded-lg border border-gray-200 shadow-sm text-center min-w-[140px]">
                        <span x-text="semanaActual.etiqueta"></span>
                        <div class="text-[10px] text-gray-500 font-normal" x-text="semanaActual.rango"></div>
                    </div>

                    <button type="button" 
                            @click="siguienteSemana()" 
                            :disabled="semanaIndex >= semanas.length - 1"
                            :class="semanaIndex >= semanas.length - 1 ? 'opacity-40 cursor-not-allowed text-gray-400' : 'hover:bg-white text-gray-700 hover:text-[#065F46] shadow-sm'"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all flex items-center gap-1">
                        <span class="hidden md:inline">Semana</span> Siguiente <span>→</span>
                    </button>
                </div>
            </div>

            <!-- Grid de Tarjetas de los 7 Días -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-3">
                <template x-for="dia in semanaActual.dias" :key="dia.fecha">
                    <button type="button" 
                            @click="seleccionarDia(dia)"
                            class="rounded-xl border-2 p-3 text-center transition-all cursor-pointer flex flex-col justify-between focus:outline-none"
                            :class="fechaSeleccionada === dia.fecha ? 
                                'border-[#10B981] bg-[#ECFDF5] shadow-md ring-2 ring-[#10B981]/30 transform -translate-y-0.5' : 
                                'border-gray-200 bg-white hover:border-[#10B981]/50 hover:bg-gray-50/70'">
                        
                        <!-- Día y Estado Hoy -->
                        <div>
                            <div class="flex items-center justify-center gap-1">
                                <h3 class="font-bold text-sm" 
                                    :class="fechaSeleccionada === dia.fecha ? 'text-[#065F46]' : 'text-gray-800'" 
                                    x-text="dia.dia_nombre"></h3>
                                <span x-show="dia.es_hoy" class="text-[9px] font-bold bg-[#10B981] text-white px-1.5 py-0.2 rounded-full uppercase">Hoy</span>
                            </div>
                            
                            <!-- Fecha -->
                            <p class="text-xs font-medium text-gray-500 mt-1" x-text="dia.fecha_formato"></p>
                        </div>

                        <!-- Horario de Inicio y Fin -->
                        <div class="mt-3 pt-2 border-t border-gray-100/80">
                            <p class="text-xs font-semibold"
                               :class="dia.abierto ? (fechaSeleccionada === dia.fecha ? 'text-[#065F46]' : 'text-gray-700') : 'text-gray-400'"
                               x-text="dia.horario_texto"></p>
                        </div>

                        <!-- Indicador de selección activa -->
                        <div class="mt-2 text-[10px] font-bold"
                             :class="fechaSeleccionada === dia.fecha ? 'text-[#065F46]' : 'text-transparent'">
                            ● Seleccionado
                        </div>
                    </button>
                </template>
            </div>
        </div>

        <!-- 2. Calendario Detallado (Cuadros de 1 hora por día seleccionado) -->
        <div class="bg-[#F9FAFB] py-10 border-t border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Encabezado del día seleccionado y leyenda -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-4 border-b border-gray-200">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">📅</span>
                            <h2 class="text-2xl font-bold text-[#065F46]">
                                Calendario Detallado
                            </h2>
                        </div>
                        <p class="text-sm text-gray-600 mt-1" x-show="diaSeleccionado">
                            Disponibilidad para el <span class="font-bold text-gray-900" x-text="diaSeleccionado.fecha_completa"></span>
                        </p>
                    </div>

                    <!-- Leyenda de Estados -->
                    <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-xl border border-gray-200 shadow-sm text-xs">
                        <span class="text-gray-500 font-medium">Leyenda:</span>
                        <span class="inline-flex items-center gap-1.5 font-semibold text-[#065F46]">
                            <span class="w-3 h-3 rounded-full bg-[#10B981]"></span> Disponible
                        </span>
                        <span class="inline-flex items-center gap-1.5 font-semibold text-red-700">
                            <span class="w-3 h-3 rounded-full bg-red-500"></span> No disponible
                        </span>
                    </div>
                </div>

                <!-- Grid de Cuadros de 1 Hora -->
                <div x-show="diaSeleccionado && diaSeleccionado.abierto && diaSeleccionado.slots.length > 0">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3.5">
                        <template x-for="slot in diaSeleccionado.slots" :key="slot.hora_inicio">
                            <div class="p-4 rounded-xl border-2 flex flex-col items-center justify-center text-center transition-all min-h-[110px]"
                                 :class="slot.disponible ? 
                                     'border-[#10B981] bg-[#ECFDF5] text-[#065F46] shadow-sm hover:shadow-md' : 
                                     'border-red-200 bg-red-50/80 text-red-800 shadow-sm'">
                                
                                <!-- Hora de Inicio y Fin -->
                                <div class="text-base sm:text-lg font-bold tracking-tight" x-text="slot.texto_horario"></div>
                                
                                <!-- Estado y Color -->
                                <div class="mt-2.5">
                                    <span x-show="slot.disponible" 
                                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#D1FAE5] text-[#065F46] border border-[#10B981]/30">
                                        🟢 Disponible
                                    </span>
                                    <span x-show="!slot.disponible" 
                                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
                                        🔴 No disponible
                                    </span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Mensaje cuando la cancha está cerrada ese día -->
                <div x-show="diaSeleccionado && !diaSeleccionado.abierto" 
                     class="bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-500 shadow-sm max-w-lg mx-auto">
                    <p class="text-4xl mb-2">⛔</p>
                    <h3 class="text-lg font-bold text-gray-800 mb-1">Cancha Cerrada</h3>
                    <p class="text-sm text-gray-500">
                        La cancha no se encuentra abierta en este día. Selecciona otra fecha arriba para consultar los horarios disponibles.
                    </p>
                </div>

                <!-- Tarjeta informativa de reserva -->
                <div class="mt-8 bg-white rounded-xl p-5 border border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
                    <div class="flex items-center gap-3 text-center sm:text-left">
                        <span class="text-3xl">⚽</span>
                        <div>
                            <p class="text-sm font-bold text-gray-800">¿Encontraste un horario disponible?</p>
                            <p class="text-xs text-gray-500">Comunícate con la administración para confirmar tu reserva.</p>
                        </div>
                    </div>
                    @if(!empty($contacto->telefono_principal))
                    <a href="tel:{{ $contacto->telefono_principal }}" 
                       class="inline-flex items-center px-4 py-2 bg-[#10B981] hover:bg-[#059669] text-white text-xs font-semibold rounded-lg shadow-sm transition-all">
                        📞 Llamar: {{ $contacto->telefono_principal }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
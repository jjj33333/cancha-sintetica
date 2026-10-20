@extends('layouts.admin')

@section('header_title', 'Detalles del Equipo')
@section('title', $equipo->nombre . ' - Admin')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
            <div>
                <a href="{{ route('admin.equipos.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-[#10B981] transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a la lista
                </a>
                <h1 class="text-xl sm:text-2xl font-bold text-[#065F46] mt-2 flex items-center gap-3">
                    @if($equipo->logo)
                        <img src="{{ Storage::url($equipo->logo) }}" alt="{{ $equipo->nombre }}" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover">
                    @else
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#ECFDF5] flex items-center justify-center text-[#065F46] font-bold text-lg">
                            {{ substr($equipo->nombre, 0, 1) }}
                        </div>
                    @endif
                    <span class="break-words">{{ $equipo->nombre }}</span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">{{ $equipo->torneo->nombre ?? 'Sin torneo' }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.equipos.edit', $equipo) }}" class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-[#F59E0B] hover:bg-[#D97706] text-white text-sm font-semibold rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Editar Equipo
                </a>
            </div>
        </div>

        <!-- Mensaje de éxito -->
        @if(session('success'))
            <div class="mb-6 bg-[#D1FAE5] border border-[#10B981] text-[#065F46] px-4 py-3 rounded-lg flex items-center gap-3 text-sm">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Información del Equipo -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Información del Equipo</h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Nombre:</span>
                        <span class="font-medium text-gray-800 text-right">{{ $equipo->nombre }}</span>
                    </div>
                    @if($equipo->nombre_corto)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Abreviatura:</span>
                        <span class="font-medium text-gray-800">{{ $equipo->nombre_corto }}</span>
                    </div>
                    @endif
                    @if($equipo->color_uniforme)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Color Uniforme:</span>
                        <span class="font-medium text-gray-800">{{ $equipo->color_uniforme }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Estado:</span>
                        @php
                            $badgeClass = match($equipo->estado) {
                                'confirmado' => 'bg-[#D1FAE5] text-[#065F46]',
                                'registrado' => 'bg-[#FEF3C7] text-[#92400E]',
                                'campeon' => 'bg-[#FEF3C7] text-[#92400E]',
                                'subcampeon' => 'bg-[#DBEAFE] text-[#1E40AF]',
                                'eliminado' => 'bg-[#FEE2E2] text-[#991B1B]',
                                default => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                            {{ ucfirst($equipo->estado) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Capitán</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500">Nombre</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $equipo->nombre_capitan }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Teléfono</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $equipo->telefono_capitan }}</p>
                    </div>
                    @if($equipo->email_capitan)
                    <div>
                        <p class="text-xs text-gray-500">Email</p>
                        <p class="text-sm font-semibold text-gray-800 break-all">{{ $equipo->email_capitan }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sección de Jugadores -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4 border-b border-gray-100 pb-3">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800">⚽ Jugadores del Equipo ({{ $equipo->jugadores->where('esta_activo', true)->count() }})</h2>
                <button onclick="document.getElementById('modalJugador').classList.remove('hidden')" class="inline-flex items-center justify-center px-4 py-2 bg-[#10B981] hover:bg-[#059669] text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all w-full sm:w-auto">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Agregar Jugador
                </button>
            </div>

            <!-- Mobile View: Tarjetas de Jugadores (block md:hidden) -->
            <div class="block md:hidden space-y-3">
                @forelse($equipo->jugadores->where('esta_activo', true) as $jugador)
                    <div class="border border-gray-100 rounded-xl p-4 bg-gray-50/50 hover:bg-emerald-50/30 transition-colors">
                        <div class="flex justify-between items-start gap-2 mb-2">
                            <div class="flex items-center gap-2">
                                @if($jugador->numero_camiseta)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 text-sm font-bold text-gray-700 shadow-sm">
                                        {{ $jugador->numero_camiseta }}
                                    </span>
                                @endif
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm flex items-center gap-1.5 flex-wrap">
                                        {{ $jugador->nombre_completo }}
                                        @if($jugador->es_capitan)
                                            <span class="text-[10px] bg-[#FEF3C7] text-[#92400E] px-2 py-0.5 rounded-full font-semibold">Capitán</span>
                                        @endif
                                    </h4>
                                    @if($jugador->posicion)
                                        <p class="text-xs text-gray-500">{{ $jugador->posicion }}</p>
                                    @endif
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#D1FAE5] text-[#065F46]">
                                Activo
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs py-2 my-2 border-y border-gray-100">
                            <div>
                                <span class="text-gray-400 block text-[11px]">DPI:</span>
                                <span class="font-medium text-gray-700">{{ $jugador->dni ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 block text-[11px]">Teléfono:</span>
                                <span class="font-medium text-gray-700">{{ $jugador->telefono ?? '-' }}</span>
                            </div>
                            @if($jugador->email)
                            <div class="col-span-2">
                                <span class="text-gray-400 block text-[11px]">Email:</span>
                                <span class="font-medium text-gray-700 break-all">{{ $jugador->email }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-1">
                            <button type="button" onclick='editarJugador(@json($jugador))' class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-amber-50 hover:text-amber-600 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Editar
                            </button>
                            <form action="{{ route('admin.equipos.jugadores.destroy', [$equipo, $jugador]) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este jugador?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 bg-white border border-gray-200 rounded-lg hover:bg-red-50 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-400">
                        <p class="text-sm">No hay jugadores registrados en este equipo.</p>
                    </div>
                @endforelse
            </div>

            <!-- Desktop View: Tabla de Jugadores (hidden md:block) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#F9FAFB] border-b-2 border-gray-200">
                            <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">#</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Nombre</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">DPI</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Posición</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase text-center">Camiseta</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Teléfono</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Estado</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($equipo->jugadores->where('esta_activo', true) as $jugador)
                            <tr class="hover:bg-[#ECFDF5] transition-colors duration-150 group">
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $jugador->nombre_completo }}
                                        @if($jugador->es_capitan)
                                            <span class="ml-2 text-xs bg-[#FEF3C7] text-[#92400E] px-2 py-0.5 rounded-full">Capitán</span>
                                        @endif
                                    </div>
                                    @if($jugador->email)
                                        <div class="text-xs text-gray-500">{{ $jugador->email }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $jugador->dni ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $jugador->posicion ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($jugador->numero_camiseta)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#F3F4F6] text-sm font-bold text-gray-700">
                                            {{ $jugador->numero_camiseta }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $jugador->telefono ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#D1FAE5] text-[#065F46]">
                                        Activo
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                        <button type="button" onclick='editarJugador(@json($jugador))' class="p-2 text-gray-500 hover:text-[#F59E0B] hover:bg-[#FEF3C7] rounded-lg transition-colors" title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <form action="{{ route('admin.equipos.jugadores.destroy', [$equipo, $jugador]) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este jugador?');">
                                            @csrf
                                             @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-500 hover:text-[#EF4444] hover:bg-[#FEE2E2] rounded-lg transition-colors" title="Eliminar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-400">
                                    <p class="text-sm">No hay jugadores registrados en este equipo.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Agregar/Editar Jugador -->
    <div id="modalJugador" class="hidden fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="document.getElementById('modalJugador').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto z-10">
            <div class="bg-[#065F46] p-4 sm:p-6 text-center relative sticky top-0 z-10">
                <h3 class="text-xl sm:text-2xl font-bold text-white" id="modalTitle">Agregar Jugador</h3>
                <button onclick="document.getElementById('modalJugador').classList.add('hidden')" class="absolute top-4 right-4 text-white/70 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form id="formJugador" method="POST" class="p-4 sm:p-6 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="methodField" value="POST">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Nombre Completo <span class="text-red-500">*</span></label>
                        <input type="text" name="nombre_completo" id="jugador_nombre" required class="w-full px-3.5 py-2.5 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">DPI</label>
                        <input type="text" name="dni" id="jugador_dni" class="w-full px-3.5 py-2.5 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" id="jugador_fecha" class="w-full px-3.5 py-2.5 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Posición</label>
                        <select name="posicion" id="jugador_posicion" class="w-full px-3.5 py-2.5 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none bg-white">
                            <option value="">Seleccionar...</option>
                            <option value="Portero">Portero</option>
                            <option value="Defensa">Defensa</option>
                            <option value="Mediocampista">Mediocampista</option>
                            <option value="Delantero">Delantero</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Número de Camiseta</label>
                        <input type="number" name="numero_camiseta" id="jugador_numero" min="1" max="99" class="w-full px-3.5 py-2.5 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                        <input type="text" name="telefono" id="jugador_telefono" class="w-full px-3.5 py-2.5 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="jugador_email" class="w-full px-3.5 py-2.5 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
                    </div>

                    <div class="md:col-span-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="es_capitan" id="jugador_capitan" value="1" class="w-4 h-4 text-[#10B981] border-gray-300 rounded focus:ring-[#10B981]">
                            <span class="text-xs sm:text-sm font-medium text-gray-700">Es el capitán del equipo</span>
                        </label>
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('modalJugador').classList.add('hidden')" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-center text-sm">
                        Cancelar
                    </button>
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-[#10B981] hover:bg-[#059669] text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all text-center text-sm">
                        Guardar Jugador
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editarJugador(jugador) {
            document.getElementById('modalTitle').textContent = 'Editar Jugador';
            document.getElementById('methodField').value = 'PUT';
            document.getElementById('formJugador').action = '{{ route('admin.equipos.jugadores.update', [$equipo, ':id']) }}'.replace(':id', jugador.id);
            
            document.getElementById('jugador_nombre').value = jugador.nombre_completo || '';
            document.getElementById('jugador_dni').value = jugador.dni || '';
            document.getElementById('jugador_fecha').value = jugador.fecha_nacimiento || '';
            document.getElementById('jugador_posicion').value = jugador.posicion || '';
            document.getElementById('jugador_numero').value = jugador.numero_camiseta || '';
            document.getElementById('jugador_telefono').value = jugador.telefono || '';
            document.getElementById('jugador_email').value = jugador.email || '';
            document.getElementById('jugador_capitan').checked = Boolean(jugador.es_capitan);
            
            document.getElementById('modalJugador').classList.remove('hidden');
        }

        // Reset form when opening for new jugador
        document.querySelector('button[onclick*="modalJugador"]').addEventListener('click', function() {
            document.getElementById('modalTitle').textContent = 'Agregar Jugador';
            document.getElementById('methodField').value = 'POST';
            document.getElementById('formJugador').action = '{{ route('admin.equipos.jugadores.store', $equipo) }}';
            document.getElementById('formJugador').reset();
        });
    </script>
@endsection
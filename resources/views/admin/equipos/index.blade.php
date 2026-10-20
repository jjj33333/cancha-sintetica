@extends('layouts.admin')

@section('header_title', 'Gestión de Equipos y Jugadores')
@section('title', 'Equipos - Admin')

@section('content')
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#065F46]">👥 Equipos y Jugadores</h1>
            <p class="text-sm text-gray-500 mt-1">Administra todos los equipos inscritos en los torneos.</p>
        </div>
        <a href="{{ route('admin.equipos.create') }}" class="w-full sm:w-auto text-center justify-center inline-flex items-center px-4 py-2.5 bg-[#10B981] hover:bg-[#059669] text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nuevo Equipo
        </a>
    </div>

    <!-- Mensaje de éxito -->
    @if(session('success'))
        <div class="mb-6 bg-[#D1FAE5] border border-[#10B981] text-[#065F46] px-4 py-3 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" action="{{ route('admin.equipos.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre de equipo o capitán..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm">
            </div>
            <div class="sm:w-48">
                <select name="torneo_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm">
                    <option value="">Todos los torneos</option>
                    @foreach($torneos as $torneo)
                        <option value="{{ $torneo->id }}" {{ request('torneo_id') == $torneo->id ? 'selected' : '' }}>{{ $torneo->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:w-48">
                <select name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none text-base sm:text-sm">
                    <option value="">Todos los estados</option>
                    <option value="registrado" {{ request('estado') === 'registrado' ? 'selected' : '' }}>Registrado</option>
                    <option value="confirmado" {{ request('estado') === 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                    <option value="eliminado" {{ request('estado') === 'eliminado' ? 'selected' : '' }}>Eliminado</option>
                    <option value="campeon" {{ request('estado') === 'campeon' ? 'selected' : '' }}>Campeón</option>
                    <option value="subcampeon" {{ request('estado') === 'subcampeon' ? 'selected' : '' }}>Subcampeón</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 sm:flex-initial px-6 py-2 bg-[#065F46] hover:bg-[#047857] text-white font-medium rounded-lg transition-colors text-center">
                    Filtrar
                </button>
                @if(request('buscar') || request('torneo_id') || request('estado'))
                    <a href="{{ route('admin.equipos.index') }}" class="flex-1 sm:flex-initial px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors text-center">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Lista / Tabla de Equipos -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Vista Móvil: Tarjetas de Equipos -->
        <div class="block md:hidden divide-y divide-gray-100">
            @forelse($equipos as $equipo)
                @php
                    $badgeClass = match($equipo->estado) {
                        'confirmado' => 'bg-[#D1FAE5] text-[#065F46]',
                        'registrado' => 'bg-[#FEF3C7] text-[#92400E]',
                        'campeon' => 'bg-[#FEF3C7] text-[#92400E]',
                        'subcampeon' => 'bg-[#DBEAFE] text-[#1E40AF]',
                        'eliminado' => 'bg-[#FEE2E2] text-[#991B1B]',
                        default => 'bg-gray-100 text-gray-600',
                    };
                    $estadoTexto = match($equipo->estado) {
                        'confirmado' => 'Confirmado',
                        'registrado' => 'Registrado',
                        'campeon' => '🏆 Campeón',
                        'subcampeon' => '🥈 Subcampeón',
                        'eliminado' => 'Eliminado',
                        default => ucfirst($equipo->estado),
                    };
                @endphp
                <div class="p-4 space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-3">
                            @if($equipo->logo)
                                <img src="{{ Storage::url($equipo->logo) }}" alt="{{ $equipo->nombre }}" class="w-12 h-12 rounded-full object-cover">
                            @else
                                <div class="w-12 h-12 rounded-full bg-[#ECFDF5] flex items-center justify-center text-[#065F46] font-bold text-base">
                                    {{ substr($equipo->nombre, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <h3 class="font-bold text-gray-900 text-base">{{ $equipo->nombre }}</h3>
                                @if($equipo->nombre_corto)
                                    <span class="text-xs text-gray-500 font-medium">{{ $equipo->nombre_corto }}</span>
                                @endif
                                <p class="text-xs text-emerald-700 font-medium mt-0.5">🏆 {{ $equipo->torneo->nombre ?? 'Sin torneo' }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badgeClass }}">
                            {{ $estadoTexto }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs text-gray-600 bg-gray-50 p-2.5 rounded-lg">
                        <div>
                            <span class="text-gray-400 block">Capitán:</span>
                            <span class="font-medium text-gray-800">{{ $equipo->nombre_capitan }}</span>
                            @if($equipo->telefono_capitan)
                                <span class="text-gray-500 block">{{ $equipo->telefono_capitan }}</span>
                            @endif
                        </div>
                        <div>
                            <span class="text-gray-400 block">Jugadores activos:</span>
                            <span class="font-bold text-[#065F46] text-sm">{{ $equipo->jugadores->where('esta_activo', true)->count() }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-1 border-t border-gray-50">
                        <a href="{{ route('admin.equipos.show', $equipo) }}" class="px-3 py-1.5 bg-[#DBEAFE] text-[#1E40AF] text-xs font-semibold rounded-lg hover:bg-blue-200 transition-colors inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Ver / Jugadores
                        </a>
                        <a href="{{ route('admin.equipos.edit', $equipo) }}" class="px-3 py-1.5 bg-[#FEF3C7] text-[#92400E] text-xs font-semibold rounded-lg hover:bg-amber-200 transition-colors inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Editar
                        </a>
                        <form action="{{ route('admin.equipos.destroy', $equipo) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar/desactivar este equipo?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-gray-500 hover:text-[#EF4444] hover:bg-[#FEE2E2] rounded-lg transition-colors" title="Eliminar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <p class="text-gray-500 font-medium">No hay equipos registrados aún.</p>
                </div>
            @endforelse
        </div>

        <!-- Vista Escritorio: Tabla con Scroll Horizontal -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b-2 border-gray-200">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Equipo</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Capitán</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Teléfono</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Torneo</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Jugadores</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($equipos as $equipo)
                        <tr class="hover:bg-[#ECFDF5] transition-colors duration-150 group">
                            <!-- Equipo -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($equipo->logo)
                                        <img src="{{ Storage::url($equipo->logo) }}" alt="{{ $equipo->nombre }}" class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-[#ECFDF5] flex items-center justify-center text-[#065F46] font-bold text-sm">
                                            {{ substr($equipo->nombre, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $equipo->nombre }}</p>
                                        @if($equipo->nombre_corto)
                                            <p class="text-xs text-gray-500">{{ $equipo->nombre_corto }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Capitán -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $equipo->nombre_capitan }}</div>
                                @if($equipo->email_capitan)
                                    <div class="text-xs text-gray-500">{{ $equipo->email_capitan }}</div>
                                @endif
                            </td>
                            
                            <!-- Teléfono -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $equipo->telefono_capitan }}
                            </td>
                            
                            <!-- Torneo -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-700">{{ $equipo->torneo->nombre ?? 'N/A' }}</span>
                            </td>
                            
                            <!-- Jugadores -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#F3F4F6] text-sm font-medium text-gray-700">
                                    {{ $equipo->jugadores->where('esta_activo', true)->count() }}
                                </span>
                            </td>
                            
                            <!-- Estado -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $badgeClass = match($equipo->estado) {
                                        'confirmado' => 'bg-[#D1FAE5] text-[#065F46]',
                                        'registrado' => 'bg-[#FEF3C7] text-[#92400E]',
                                        'campeon' => 'bg-[#FEF3C7] text-[#92400E]',
                                        'subcampeon' => 'bg-[#DBEAFE] text-[#1E40AF]',
                                        'eliminado' => 'bg-[#FEE2E2] text-[#991B1B]',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                    $estadoTexto = match($equipo->estado) {
                                        'confirmado' => 'Confirmado',
                                        'registrado' => 'Registrado',
                                        'campeon' => '🏆 Campeón',
                                        'subcampeon' => '🥈 Subcampeón',
                                        'eliminado' => 'Eliminado',
                                        default => ucfirst($equipo->estado),
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                    {{ $estadoTexto }}
                                </span>
                            </td>
                            
                            <!-- Acciones -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.equipos.show', $equipo) }}" class="p-2 text-gray-500 hover:text-[#3B82F6] hover:bg-[#DBEAFE] rounded-lg transition-colors" title="Ver detalles y jugadores">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.equipos.edit', $equipo) }}" class="p-2 text-gray-500 hover:text-[#F59E0B] hover:bg-[#FEF3C7] rounded-lg transition-colors" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.equipos.destroy', $equipo) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar/desactivar este equipo?');">
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
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-400 mb-3">
                                    <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <p class="text-gray-500 font-medium">No hay equipos registrados aún.</p>
                                <p class="text-sm text-gray-400 mt-1">Comienza creando un nuevo equipo.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($equipos->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-[#F9FAFB]">
                {{ $equipos->links() }}
            </div>
        @endif
    </div>
@endsection
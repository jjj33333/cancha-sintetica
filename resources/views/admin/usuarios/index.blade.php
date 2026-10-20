@extends('layouts.admin')

@section('header_title', 'Gestión de Usuarios')
@section('title', 'Usuarios - Admin')

@section('content')
    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-[#065F46]">👨‍💼 Gestión de Usuarios</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">ℹ️ Administra las cuentas y roles: Admin, Organizador o Trabajador.</p>
        </div>
        <a href="{{ route('admin.usuarios.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-[#10B981] hover:bg-[#059669] text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Crear Usuario
        </a>
    </div>

    <!-- Mensajes -->
    @if(session('success'))
        <div class="mb-6 bg-[#D1FAE5] border border-[#10B981] text-[#065F46] p-3 sm:p-4 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-xs sm:text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-[#FEE2E2] border border-[#EF4444] text-[#991B1B] p-3 sm:p-4 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-xs sm:text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-4 mb-6">
        <form method="GET" action="{{ route('admin.usuarios.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre o email..." 
                       class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
            </div>
            <div class="sm:w-40">
                <select name="rol" class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
                    <option value="">Todos los roles</option>
                    <option value="admin" {{ request('rol') === 'admin' ? 'selected' : '' }}>Administrador</option>
                    <option value="organizador" {{ request('rol') === 'organizador' ? 'selected' : '' }}>Organizador</option>
                    <option value="trabajador" {{ request('rol') === 'trabajador' ? 'selected' : '' }}>Trabajador</option>
                    <option value="staff" {{ request('rol') === 'staff' ? 'selected' : '' }}>Staff</option>
                    <option value="viewer" {{ request('rol') === 'viewer' ? 'selected' : '' }}>Viewer</option>
                </select>
            </div>
            <div class="sm:w-32">
                <select name="estado" class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
                    <option value="">Todos</option>
                    <option value="activo" {{ request('estado') === 'activo' ? 'selected' : '' }}>Activos</option>
                    <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 sm:flex-none px-6 py-2 bg-[#065F46] hover:bg-[#047857] text-white text-sm font-medium rounded-lg transition-colors text-center">
                    Filtrar
                </button>
                @if(request('buscar') || request('rol') || request('estado'))
                    <a href="{{ route('admin.usuarios.index') }}" class="flex-1 sm:flex-none px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors text-center">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Vista de Usuarios (Tarjetas en Móvil / Tabla en Desktop) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Vista Móvil (Tarjetas) -->
        <div class="block md:hidden divide-y divide-gray-100">
            @forelse($usuarios as $usuario)
                @php
                    $rolBadge = match($usuario->rol) {
                        'admin' => 'bg-[#DBEAFE] text-[#1E40AF]',
                        'organizador' => 'bg-[#FEF3C7] text-[#92400E]',
                        'trabajador' => 'bg-[#D1FAE5] text-[#065F46]',
                        'staff' => 'bg-[#F3F4F6] text-[#374151]',
                        'viewer' => 'bg-[#F3F4F6] text-[#374151]',
                        default => 'bg-gray-100 text-gray-600',
                    };
                @endphp
                <div class="p-4 space-y-3 {{ !$usuario->esta_activo ? 'opacity-60 bg-gray-50/50' : '' }}">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#10B981] to-[#065F46] flex items-center justify-center text-white font-bold text-sm shrink-0">
                                {{ substr($usuario->nombre, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-sm">{{ $usuario->nombre }}</p>
                                <p class="text-xs text-gray-500 break-all">{{ $usuario->email }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold shrink-0 {{ $rolBadge }}">
                            {{ ucfirst($usuario->rol) }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-xs text-gray-600 bg-gray-50 p-2.5 rounded-lg">
                        <div>
                            <span class="text-gray-400 block text-[10px] uppercase font-semibold">Teléfono</span>
                            <span class="font-medium text-gray-700">{{ $usuario->telefono ?: 'No registrado' }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-gray-400 block text-[10px] uppercase font-semibold">Estado</span>
                            @if($usuario->esta_activo)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-[#D1FAE5] text-[#065F46]">
                                    ✅ Activo
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-[#FEE2E2] text-[#991B1B]">
                                    ❌ Inactivo
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                        <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="inline-flex items-center px-3 py-1.5 bg-amber-50 text-amber-700 hover:bg-amber-100 text-xs font-medium rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Editar
                        </a>
                        @if($usuario->id !== auth()->id())
                            <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de desactivar a este usuario?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 text-xs font-medium rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Desactivar
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <p class="text-sm text-gray-500 font-medium">No se encontraron usuarios.</p>
                </div>
            @endforelse
        </div>

        <!-- Vista Desktop (Tabla) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b-2 border-gray-200">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Rol</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Teléfono</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Estado</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($usuarios as $usuario)
                        <tr class="hover:bg-[#ECFDF5] transition-colors duration-150 group {{ !$usuario->esta_activo ? 'opacity-60' : '' }}">
                            <!-- Nombre -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#10B981] to-[#065F46] flex items-center justify-center text-white font-bold text-sm">
                                        {{ substr($usuario->nombre, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $usuario->nombre }}</p>
                                        <p class="text-xs text-gray-500">ID: {{ $usuario->id }}</p>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Email -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $usuario->email }}
                            </td>
                            
                            <!-- Rol -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $rolBadge = match($usuario->rol) {
                                        'admin' => 'bg-[#DBEAFE] text-[#1E40AF]',
                                        'organizador' => 'bg-[#FEF3C7] text-[#92400E]',
                                        'trabajador' => 'bg-[#D1FAE5] text-[#065F46]',
                                        'staff' => 'bg-[#F3F4F6] text-[#374151]',
                                        'viewer' => 'bg-[#F3F4F6] text-[#374151]',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $rolBadge }}">
                                    {{ ucfirst($usuario->rol) }}
                                </span>
                            </td>
                            
                            <!-- Teléfono -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $usuario->telefono ?: '-' }}
                            </td>
                            
                            <!-- Estado -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($usuario->esta_activo)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#D1FAE5] text-[#065F46]">
                                        ✅ Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#FEE2E2] text-[#991B1B]">
                                        ❌ Inactivo
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Acciones -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="p-2 text-gray-500 hover:text-[#F59E0B] hover:bg-[#FEF3C7] rounded-lg transition-colors" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    @if($usuario->id !== auth()->id())
                                        <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de desactivar a este usuario?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-500 hover:text-[#EF4444] hover:bg-[#FEE2E2] rounded-lg transition-colors" title="Desactivar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-400 mb-3">
                                    <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <p class="text-gray-500 font-medium">No se encontraron usuarios.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($usuarios->hasPages())
            <div class="px-4 sm:px-6 py-4 border-t border-gray-100 bg-[#F9FAFB]">
                {{ $usuarios->links() }}
            </div>
        @endif
    </div>
@endsection
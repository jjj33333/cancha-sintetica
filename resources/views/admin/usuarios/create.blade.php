@extends('layouts.admin')

@section('header_title', 'Crear Nuevo Usuario')
@section('title', 'Crear Usuario - Admin')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-4 sm:mb-6">
            <a href="{{ route('admin.usuarios.index') }}" class="inline-flex items-center text-xs sm:text-sm text-gray-600 hover:text-[#10B981] transition-colors">
                <svg class="w-4 h-4 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver a la lista
            </a>
            <h1 class="text-xl sm:text-2xl font-bold text-[#065F46] mt-2">👤 Crear Nuevo Usuario</h1>
        </div>

        <form method="POST" action="{{ route('admin.usuarios.store') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6 space-y-6" x-data="{ showPassword: false }">
            @csrf

            <!-- Información Personal -->
            <div class="border-b border-gray-100 pb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Información Personal</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="nombre" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Nombre Completo <span class="text-red-500">*</span></label>
                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required
                               class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('nombre') border-red-500 @enderror">
                        @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Correo Electrónico <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="telefono" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}" placeholder="Ej: 999-000"
                               class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('telefono') border-red-500 @enderror">
                        @error('telefono') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Seguridad y Rol -->
            <div class="border-b border-gray-100 pb-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Seguridad y Permisos</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="rol" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Rol del Usuario <span class="text-red-500">*</span></label>
                        <select name="rol" id="rol" required
                                class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none @error('rol') border-red-500 @enderror">
                            <option value="">Seleccionar rol...</option>
                            <option value="admin" {{ old('rol') === 'admin' ? 'selected' : '' }}>Administrador</option>
                            <option value="organizador" {{ old('rol') === 'organizador' ? 'selected' : '' }}>Organizador</option>
                            <option value="trabajador" {{ old('rol') === 'trabajador' ? 'selected' : '' }}>Trabajador</option>
                            <option value="staff" {{ old('rol') === 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="viewer" {{ old('rol') === 'viewer' ? 'selected' : '' }}>Viewer</option>
                        </select>
                        @error('rol') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="relative">
                        <label for="password" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Contraseña <span class="text-red-500">*</span></label>
                        <input :type="showPassword ? 'text' : 'password'" name="password" id="password" required
                               class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none pr-10 @error('password') border-red-500 @enderror">
                        <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-8 text-gray-400 hover:text-gray-600">
                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        </button>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="password_confirmation" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña <span class="text-red-500">*</span></label>
                        <input :type="showPassword ? 'text' : 'password'" name="password_confirmation" id="password_confirmation" required
                               class="w-full px-3.5 py-2 text-base sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none">
                    </div>
                </div>
            </div>

            <!-- Configuración -->
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Configuración</h2>
                <div class="flex items-center justify-between p-3 sm:p-4 bg-[#F9FAFB] rounded-lg gap-3">
                    <div>
                        <label class="text-xs sm:text-sm font-medium text-gray-700">✅ Usuario activo</label>
                        <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5">Si está desactivado, el usuario no podrá iniciar sesión.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="checkbox" name="esta_activo" value="1" {{ old('esta_activo', true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#10B981]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#10B981]"></div>
                    </label>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.usuarios.index') }}" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-center text-sm">
                    Cancelar
                </a>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-[#10B981] hover:bg-[#059669] text-white font-semibold rounded-lg shadow-sm hover:shadow-md transition-all text-center text-sm">
                    Crear Usuario
                </button>
            </div>
        </form>
    </div>
@endsection
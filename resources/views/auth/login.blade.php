@extends('layouts.guest')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-[#065F46] text-center mb-6">Iniciar Sesión</h2>
        
        <form method="POST" action="#">
            @csrf
            
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent">
            </div>
            
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Contraseña</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent">
            </div>
            
            <button type="submit" 
                class="w-full bg-[#10B981] hover:bg-[#059669] text-white font-semibold py-3 rounded-lg shadow-md transition-all">
                Entrar
            </button>
        </form>
        
        <div class="mt-6 text-center text-sm text-gray-600">
            <p>Credenciales de prueba:</p>
            <p class="mt-2">👤 Admin: admin / admin123</p>
            <p>👤 Organizador: organizador / org123</p>
            <p>👤 Trabajador: trabajador / trab123</p>
        </div>
    </div>
</div>
@endsection
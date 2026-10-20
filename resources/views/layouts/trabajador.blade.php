<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel') - Cancha Sintética</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F9FAFB] text-[#374151] font-sans" x-data="{ sidebarOpen: false }">
    
    <!-- Overlay para móvil (cierra el sidebar al hacer clic fuera) -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 z-40 bg-black/50 md:hidden"></div>

    <div class="flex min-h-screen">
        <!-- Componente Sidebar Trabajador -->
        <x-sidebar-trabajador />

        <!-- Contenido Principal -->
        <main class="flex-1 flex flex-col min-h-screen md:ml-0">
            <!-- Topbar (Solo visible en móvil para abrir el menú, y en desktop para info) -->
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30">
                <button @click="sidebarOpen = true" class="md:hidden text-gray-500 hover:text-[#065F46] focus:outline-none p-1.5 rounded-lg hover:bg-gray-100" aria-label="Abrir menú">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h2 class="text-lg sm:text-xl font-bold text-[#065F46] truncate max-w-[200px] sm:max-w-none">@yield('header_title', 'Panel del Trabajador')</h2>
                <div class="w-6 md:w-0"></div> <!-- Spacer para equilibrar el flex -->
            </header>

            <!-- Contenido de la vista -->
            <div class="flex-1 p-3.5 sm:p-6 md:p-8">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
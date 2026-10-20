<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cancha Sintética Deportiva')</title>
    
    <!-- Fuente Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS y JS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; color: #374151; }
        [x-cloak] { display: none !important; } /* Oculta el modal hasta que Alpine cargue */
    </style>
</head>
<body class="bg-[#F9FAFB] text-[#374151] flex flex-col min-h-screen" x-data="{ openLoginModal: false, mobileMenuOpen: false }">

    <!-- Navbar Pública -->
    <nav class="bg-[#065F46] text-white shadow-lg sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center gap-2">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-2 hover:opacity-95 transition-opacity min-w-0">
                    <span class="text-2xl flex-shrink-0">⚽</span>
                    <span class="font-bold text-lg sm:text-xl tracking-tight truncate max-w-[160px] sm:max-w-none">
                        {{ $contacto->nombre_negocio ?? 'Cancha Sintética' }}
                    </span>
                </a>
                
                <!-- Enlaces Desktop -->
                <div class="hidden md:flex space-x-6 lg:space-x-8">
                    <a href="{{ route('home') }}" class="hover:text-[#10B981] transition-colors font-medium py-1 border-b-2 {{ request()->routeIs('home') ? 'border-[#10B981] text-[#10B981]' : 'border-transparent text-gray-100' }}">Información</a>
                    <a href="{{ route('horarios') }}" class="hover:text-[#10B981] transition-colors font-medium py-1 border-b-2 {{ request()->routeIs('horarios') ? 'border-[#10B981] text-[#10B981]' : 'border-transparent text-gray-100' }}">Horarios</a>
                    <a href="{{ route('torneos') }}" class="hover:text-[#10B981] transition-colors font-medium py-1 border-b-2 {{ request()->routeIs('torneos') ? 'border-[#10B981] text-[#10B981]' : 'border-transparent text-gray-100' }}">Torneos</a>
                    <a href="{{ route('anuncios') }}" class="hover:text-[#10B981] transition-colors font-medium py-1 border-b-2 {{ request()->routeIs('anuncios') ? 'border-[#10B981] text-[#10B981]' : 'border-transparent text-gray-100' }}">Anuncios</a>
                    <a href="{{ route('contacto') }}" class="hover:text-[#10B981] transition-colors font-medium py-1 border-b-2 {{ request()->routeIs('contacto') ? 'border-[#10B981] text-[#10B981]' : 'border-transparent text-gray-100' }}">Contacto</a>
                </div>

                <!-- Botón Login y Menú Móvil -->
                <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                    <button @click="openLoginModal = true" class="bg-[#10B981] hover:bg-[#059669] text-white px-3 sm:px-5 py-2 rounded-lg text-sm sm:text-base font-semibold transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 cursor-pointer whitespace-nowrap">
                        <span class="hidden sm:inline">Iniciar Sesión</span>
                        <span class="sm:hidden">Ingresar</span>
                    </button>

                    <!-- Botón Hamburguesa Móvil -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" 
                            type="button" 
                            class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-emerald-100 hover:text-white hover:bg-[#047857] focus:outline-none transition-colors"
                            aria-label="Abrir menú de navegación">
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Menú móvil colapsable -->
        <div x-cloak 
             x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden border-t border-[#047857] bg-[#05513c]">
            <div class="px-4 py-3 space-y-1">
                <a href="{{ route('home') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('home') ? 'bg-[#047857] text-[#10B981]' : 'text-gray-100 hover:bg-[#047857] hover:text-[#10B981]' }}">
                    <span>🏟️</span> Información
                </a>
                <a href="{{ route('horarios') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('horarios') ? 'bg-[#047857] text-[#10B981]' : 'text-gray-100 hover:bg-[#047857] hover:text-[#10B981]' }}">
                    <span>📅</span> Horarios
                </a>
                <a href="{{ route('torneos') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('torneos') ? 'bg-[#047857] text-[#10B981]' : 'text-gray-100 hover:bg-[#047857] hover:text-[#10B981]' }}">
                    <span>🏆</span> Torneos
                </a>
                <a href="{{ route('anuncios') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('anuncios') ? 'bg-[#047857] text-[#10B981]' : 'text-gray-100 hover:bg-[#047857] hover:text-[#10B981]' }}">
                    <span>📢</span> Anuncios
                </a>
                <a href="{{ route('contacto') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('contacto') ? 'bg-[#047857] text-[#10B981]' : 'text-gray-100 hover:bg-[#047857] hover:text-[#10B981]' }}">
                    <span>📞</span> Contacto
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer Público -->
    <footer class="bg-[#065F46] text-gray-300 py-10 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-white font-bold text-lg mb-3 flex items-center gap-2">
                        <span>⚽</span> {{ $contacto->nombre_negocio ?? 'Cancha Sintética' }}
                    </h3>
                    <p class="text-sm text-emerald-100/90 leading-relaxed">{{ $contacto->direccion ?? 'Av. Los Deportistas 123, Chinique' }}</p>
                    @if(!empty($contacto->telefono_principal))
                        <p class="text-sm mt-2 text-emerald-100">📞 <a href="tel:{{ $contacto->telefono_principal }}" class="hover:underline hover:text-white">{{ $contacto->telefono_principal }}</a></p>
                    @endif
                </div>
                <div>
                    <h3 class="text-white font-bold text-lg mb-3">Enlaces Rápidos</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-[#10B981] transition-colors">Información</a></li>
                        <li><a href="{{ route('horarios') }}" class="hover:text-[#10B981] transition-colors">Horarios</a></li>
                        <li><a href="{{ route('torneos') }}" class="hover:text-[#10B981] transition-colors">Torneos</a></li>
                        <li><a href="{{ route('contacto') }}" class="hover:text-[#10B981] transition-colors">Contacto</a></li>
                    </ul>
                </div>
                <div class="sm:col-span-2 md:col-span-1">
                    <h3 class="text-white font-bold text-lg mb-3">Atención y Redes</h3>
                    <div class="flex space-x-4 mb-3">
                        @if(!empty($contacto->url_facebook))
                            <a href="{{ $contacto->url_facebook }}" class="hover:text-[#10B981] text-2xl transition-transform hover:scale-110" target="_blank" title="Facebook">📘</a>
                        @endif
                        @if(!empty($contacto->url_instagram))
                            <a href="{{ $contacto->url_instagram }}" class="hover:text-[#10B981] text-2xl transition-transform hover:scale-110" target="_blank" title="Instagram">📷</a>
                        @endif
                        @if(!empty($contacto->numero_whatsapp))
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contacto->numero_whatsapp) }}" class="hover:text-[#10B981] text-2xl transition-transform hover:scale-110" target="_blank" title="WhatsApp">💬</a>
                        @endif
                    </div>
                    @if(!empty($contacto->horario_atencion))
                        <p class="text-xs text-emerald-200/90">⏰ {{ $contacto->horario_atencion }}</p>
                    @endif
                </div>
            </div>
            <div class="border-t border-[#047857] mt-8 pt-6 text-center text-xs sm:text-sm text-emerald-200/70">
                <p>&copy; {{ date('Y') }} {{ $contacto->nombre_negocio ?? 'Cancha Sintética Deportiva' }}. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Modal de Login (Corregido) -->
    <div x-cloak x-show="openLoginModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Overlay oscuro -->
        <div x-show="openLoginModal" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="openLoginModal = false" 
             class="fixed inset-0 bg-black/50 backdrop-blur-sm">
        </div>
        
        <!-- Contenido del Modal -->
        <div x-show="openLoginModal" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] flex flex-col overflow-hidden z-10">
            
            <div class="bg-[#065F46] p-6 text-center relative flex-shrink-0">
                <h3 class="text-xl sm:text-2xl font-bold text-white">Bienvenido</h3>
                <p class="text-[#D1FAE5] text-xs sm:text-sm mt-1">Ingresa tus credenciales para continuar</p>
                <button @click="openLoginModal = false" class="absolute top-4 right-4 text-white/70 hover:text-white transition-colors" aria-label="Cerrar modal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('login.post') }}" class="p-6 space-y-4 overflow-y-auto">
                @csrf
                @if ($errors->any())
                    <div class="bg-[#FEE2E2] text-[#991B1B] p-3 rounded-lg text-sm text-center">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none transition-all">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <input type="password" name="password" id="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#10B981] focus:border-transparent outline-none transition-all">
                </div>

                <button type="submit" class="w-full bg-[#10B981] hover:bg-[#059669] text-white font-semibold py-3 rounded-lg shadow-md transition-all transform hover:-translate-y-0.5">
                    Entrar al Sistema
                </button>
            </form>
        </div>
    </div>
</body>
</html>
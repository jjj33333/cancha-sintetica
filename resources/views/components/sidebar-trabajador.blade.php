<aside 
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-[#065F46] text-white flex flex-col shadow-xl transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-auto"
>
    <div class="h-16 flex items-center justify-between px-6 border-b border-white/10">
        <div class="flex items-center">
            <span class="text-2xl mr-2">🛠️</span>
            <span class="font-bold text-lg tracking-tight">Panel Trabajador</span>
        </div>
        <button @click="sidebarOpen = false" class="md:hidden text-gray-300 hover:text-white focus:outline-none p-1 rounded-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto py-4 space-y-1">
        @php
            $menuItems = [
                ['route' => 'trabajador.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard'],
                ['route' => 'trabajador.reservas.index', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Reservas'],
                ['route' => 'trabajador.calendario.index', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Calendario General'],
            ];
        @endphp

        @foreach($menuItems as $item)
            @php
                $isActive = request()->routeIs($item['route']) 
                    || (str_contains($item['route'], 'reservas') && request()->routeIs('trabajador.reservas.*'))
                    || (str_contains($item['route'], 'calendario') && request()->routeIs('trabajador.calendario.*'));
            @endphp
            <a href="{{ route($item['route']) }}" 
               class="flex items-center px-5 py-3 text-sm font-medium transition-all duration-200 border-l-4 border-transparent
               {{ $isActive ? 'bg-white/15 text-white border-l-[#10B981]' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"></path>
                </svg>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="p-4 border-t border-white/10">
        <div class="flex items-center mb-3">
            <div class="w-8 h-8 rounded-full bg-[#10B981] flex items-center justify-center text-white font-bold text-sm">
                {{ substr(Auth::user()?->nombre ?? 'T', 0, 1) }}
            </div>
            <div class="ml-3">
                <p class="text-sm font-semibold text-white">{{ Auth::user()?->nombre ?? 'Trabajador' }}</p>
                <p class="text-xs text-gray-400 capitalize">{{ Auth::user()?->rol ?? 'trabajador' }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-red-300 bg-red-900/20 rounded-lg hover:bg-red-900/40 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Cerrar Sesión
            </button>
        </form>
    </div>
</aside>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Iniciar Sesión - Cancha Sintética</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F9FAFB] text-[#374151] font-sans flex flex-col">

    <header class="w-full">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-[#065F46] font-bold text-lg">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-[#065F46] text-white text-lg">⚽</span>
                <span>Cancha Sintética</span>
            </a>
            <a href="{{ route('home') }}" class="text-sm font-medium text-[#065F46] hover:text-[#047857] transition-colors">
                Volver al inicio
            </a>
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center px-4 py-8 sm:py-12">
        <div class="w-full max-w-md">

            @if (session('error'))
                <div class="mb-4 rounded-xl border border-[#FCA5A5] bg-[#FEE2E2] px-4 py-3 text-sm text-[#991B1B]" role="alert">
                    {{ session('error') }}
                </div>
            @elseif (request()->boolean('expirada'))
                <div class="mb-4 rounded-xl border border-[#FCD34D] bg-[#FEF3C7] px-4 py-3 text-sm text-[#92400E]" role="alert">
                    Tu sesión expiró por inactividad. Inicia sesión nuevamente para continuar.
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-[#065F46] px-6 sm:px-8 py-7 text-center">
                    <h1 class="text-2xl font-bold text-white">Iniciar Sesión</h1>
                    <p class="mt-1 text-sm text-[#D1FAE5]">Accede a tu panel de administración, organizador o trabajador.</p>
                </div>

                <form method="POST" action="{{ route('login.post') }}" class="px-6 sm:px-8 py-7 space-y-5" novalidate>
                    @csrf

                    @if (request()->filled('redirect'))
                        <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                    @endif

                    @if ($errors->any())
                        <div class="rounded-lg bg-[#FEE2E2] px-4 py-3 text-sm text-[#991B1B]" role="alert">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Correo electrónico</label>
                        <input type="email"
                               name="email"
                               id="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               autocomplete="username"
                               inputmode="email"
                               placeholder="tu@correo.com"
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm outline-none transition-all focus:border-transparent focus:ring-2 focus:ring-[#10B981]">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Contraseña</label>
                        <div class="relative">
                            <input type="password"
                                   name="password"
                                   id="password"
                                   required
                                   autocomplete="current-password"
                                   placeholder="••••••••"
                                   class="w-full rounded-lg border border-gray-300 pl-4 pr-11 py-2.5 text-sm outline-none transition-all focus:border-transparent focus:ring-2 focus:ring-[#10B981]">
                            <button type="button"
                                    id="toggle-password"
                                    aria-label="Mostrar contraseña"
                                    title="Mostrar contraseña"
                                    class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-[#065F46] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#10B981] rounded-r-lg transition-colors">
                                <svg id="icon-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="icon-eye-off" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <label class="flex items-center gap-2.5 cursor-pointer select-none">
                        <input type="hidden" name="remember" value="0">
                        <input type="checkbox"
                               name="remember"
                               value="1"
                               @checked(old('remember', '1') === '1')
                               class="h-4 w-4 rounded border-gray-300 text-[#10B981] focus:ring-[#10B981]">
                        <span class="text-sm text-gray-600">Mantener sesión iniciada</span>
                    </label>

                    <button type="submit"
                            class="w-full rounded-lg bg-[#10B981] py-3 text-sm font-semibold text-white shadow-md transition-all hover:bg-[#059669] focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#10B981]">
                        Entrar al Sistema
                    </button>

                    <p class="text-center text-xs text-gray-500">
                        ¿No tienes cuenta? Solicita acceso al administrador de la cancha.
                    </p>
                </form>
            </div>

            <p class="mt-6 text-center text-xs text-gray-500">
                <a href="{{ route('home') }}" class="font-medium text-[#065F46] hover:underline">← Regresar a la página pública</a>
            </p>
        </div>
    </main>

    <script>
        (function () {
            var boton = document.getElementById('toggle-password');
            var campo = document.getElementById('password');
            var ojo = document.getElementById('icon-eye');
            var ojoTachado = document.getElementById('icon-eye-off');

            if (!boton || !campo) return;

            boton.addEventListener('click', function () {
                var visible = campo.type === 'text';
                campo.type = visible ? 'password' : 'text';
                ojo.classList.toggle('hidden', !visible);
                ojoTachado.classList.toggle('hidden', visible);
                boton.setAttribute('aria-label', visible ? 'Mostrar contraseña' : 'Ocultar contraseña');
                boton.setAttribute('title', visible ? 'Mostrar contraseña' : 'Ocultar contraseña');
            });
        })();
    </script>
</body>
</html>

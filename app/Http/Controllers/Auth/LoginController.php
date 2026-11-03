<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Muestra la página de inicio de sesión.
     */
    public function showLoginForm(Request $request)
    {
        // Si ya hay una sesión activa no tiene sentido volver a mostrar el
        // formulario: se envía al panel que corresponde a su rol.
        if (Auth::check()) {
            return redirect()->to($this->panelUrl(Auth::user()));
        }

        return view('auth.login');
    }

    /**
     * Valida las credenciales e inicia la sesión.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // "Mantener sesión iniciada" activado por defecto: la cookie de
        // recordar permite recuperar la sesión incluso si la pestaña estuvo
        // inactiva más tiempo del configurado.
        $remember = $request->boolean('remember', true);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Forzamos la escritura de la sesión antes de redirigir. En
            // conexiones lentas el navegador puede pedir el panel antes de
            // que el servidor termine de guardar la sesión en base de datos;
            // guardarla aquí evita ese estado intermedio.
            $request->session()->save();

            $usuario = Auth::user();

            return redirect()->to(
                $this->destinoSeguro($request, $usuario, (string) $request->input('redirect', ''))
            );
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    /**
     * Latido de sesión usado por los paneles.
     *
     * Mantiene viva la sesión mientras la pestaña esté abierta y devuelve 401
     * (sin redirección) cuando la sesión ya no es válida, para que el panel
     * pueda llevar al usuario al login sin errores.
     */
    public function sesionActiva(Request $request)
    {
        if (! Auth::check()) {
            return response()->json(['autenticado' => false], 401);
        }

        // Renueva la marca de última actividad de la sesión.
        $request->session()->save();

        return response()->noContent();
    }

    /**
     * URL del panel según el rol del usuario.
     */
    private function panelUrl($usuario): string
    {
        return match ($usuario->rol ?? null) {
            'admin' => route('admin.dashboard'),
            'organizador' => route('organizador.dashboard'),
            'trabajador' => route('trabajador.dashboard'),
            default => route('home'),
        };
    }

    /**
     * Destino posterior al login.
     *
     * Prioriza la página desde la que se perdió la sesión (latido), luego la
     * URL "intended" que guarda el middleware "auth" y, si ninguna pertenece
     * al área del rol, el panel correspondiente.
     */
    private function destinoSeguro(Request $request, $usuario, string $solicitado = ''): string
    {
        $panel = $this->panelUrl($usuario);

        $segmento = match ($usuario->rol ?? null) {
            'admin' => 'admin',
            'organizador' => 'organizador',
            'trabajador' => 'trabajador',
            default => null,
        };

        if ($segmento === null) {
            return $panel;
        }

        $candidatos = [
            $solicitado,
            (string) $request->session()->pull('url.intended', ''),
        ];

        foreach ($candidatos as $destino) {
            if ($destino !== '' && $this->esDestinoDelRol($destino, $segmento)) {
                return $destino;
            }
        }

        return $panel;
    }

    /**
     * Comprueba que un destino sea interno y pertenezca al área del rol.
     */
    private function esDestinoDelRol(string $destino, string $segmento): bool
    {
        // Evita redirecciones a otro dominio (open redirect).
        if (str_starts_with($destino, '//') || str_contains($destino, '\\')) {
            return false;
        }

        $partes = parse_url($destino);

        if ($partes === false) {
            return false;
        }

        if (! empty($partes['host']) && strcasecmp($partes['host'], request()->getHost()) !== 0) {
            return false;
        }

        if (empty($partes['host']) && ! str_starts_with($destino, '/')) {
            return false;
        }

        $camino = $partes['path'] ?? '';

        // Coincidencia por segmento para tolerar subcarpetas de instalación
        // (por ejemplo /cancha-sintetica/public/organizador/...).
        return preg_match('#(^|/)' . preg_quote($segmento, '#') . '(/|$)#', $camino) === 1;
    }
}

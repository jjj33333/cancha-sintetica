<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Token CSRF caducado (formulario abierto mucho tiempo). Laravel
        // convierte TokenMismatchException en HttpException(419) antes de
        // ejecutar los renderizadores, por eso se comprueba el código 419.
        // En lugar de mostrar la pantalla de error se envía al login.
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, \Illuminate\Http\Request $request) {
            if ($e->getStatusCode() !== 419) {
                return null;
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Tu sesión expiró. Recarga la página e inténtalo de nuevo.',
                ], 419);
            }

            return redirect()->route('login', ['expirada' => 1])
                ->with('error', 'Tu sesión expiró por seguridad. Inicia sesión nuevamente.');
        });
    })->create();

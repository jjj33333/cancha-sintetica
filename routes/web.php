<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Publico\HomeController;
use App\Http\Controllers\Publico\HorariosController;
use App\Http\Controllers\Publico\TorneosController;
use App\Http\Controllers\Publico\AnunciosController;
use App\Http\Controllers\Publico\ContactoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Organizador\DashboardController as OrgDashboard;
use App\Http\Controllers\Admin\CalendarioController;
use App\Http\Controllers\Trabajador\DashboardController as TrabDashboard;

// ==========================================
// RUTAS PÚBLICAS
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/horarios', [HorariosController::class, 'index'])->name('horarios');
Route::get('/torneos', [TorneosController::class, 'index'])->name('torneos');
Route::get('/anuncios', [AnunciosController::class, 'index'])->name('anuncios');
Route::get('/contacto', [ContactoController::class, 'index'])->name('contacto');

// ==========================================
// AUTENTICACIÓN
// ==========================================
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ==========================================
// RUTAS PROTEGIDAS (Requieren Auth)
// ==========================================
Route::middleware('auth')->group(function () {
    
    // Dashboards Principales
    Route::get('/admin/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
    Route::get('/organizador/dashboard', [OrgDashboard::class, 'index'])->name('organizador.dashboard');
    Route::get('/trabajador/dashboard', [TrabDashboard::class, 'index'])->name('trabajador.dashboard');

    // --- Rutas Admin (Placeholders) ---
    // Apartado de reservas
    Route::get('/admin/reservas', [App\Http\Controllers\Admin\ReservasController::class, 'index'])->name('admin.reservas.index');
    Route::get('/admin/reservas-alias', fn() => redirect()->route('admin.reservas.index'))->name('admin.reservas');
    Route::get('/admin/reservas/create', [App\Http\Controllers\Admin\ReservasController::class, 'create'])->name('admin.reservas.create');
    Route::post('/admin/reservas', [App\Http\Controllers\Admin\ReservasController::class, 'store'])->name('admin.reservas.store');
    Route::get('/admin/reservas/{reserva}', [App\Http\Controllers\Admin\ReservasController::class, 'show'])->name('admin.reservas.show');
    Route::get('/admin/reservas/{reserva}/edit', [App\Http\Controllers\Admin\ReservasController::class, 'edit'])->name('admin.reservas.edit');
    Route::put('/admin/reservas/{reserva}', [App\Http\Controllers\Admin\ReservasController::class, 'update'])->name('admin.reservas.update');
    Route::delete('/admin/reservas/{reserva}', [App\Http\Controllers\Admin\ReservasController::class, 'destroy'])->name('admin.reservas.destroy');

    // Apartado de calendario
    Route::get('/admin/calendario', [App\Http\Controllers\Admin\CalendarioController::class, 'index'])->name('admin.calendario');    
    
    // Apartado de torneos
    Route::resource('/admin/torneos', App\Http\Controllers\Admin\TorneosController::class)->names('admin.torneos');
    Route::get('/admin/torneos-alias', fn() => redirect()->route('admin.torneos.index'))->name('admin.torneos');    
    
    // Apartado de equipos y jugadores
    Route::resource('/admin/equipos', App\Http\Controllers\Admin\EquiposController::class)->names('admin.equipos');
    Route::get('/admin/equipos-alias', fn() => redirect()->route('admin.equipos.index'))->name('admin.equipos');
    Route::post('/admin/equipos/{equipo}/jugadores', [App\Http\Controllers\Admin\JugadoresController::class, 'store'])->name('admin.equipos.jugadores.store');
    Route::put('/admin/equipos/{equipo}/jugadores/{jugador}', [App\Http\Controllers\Admin\JugadoresController::class, 'update'])->name('admin.equipos.jugadores.update');
    Route::delete('/admin/equipos/{equipo}/jugadores/{jugador}', [App\Http\Controllers\Admin\JugadoresController::class, 'destroy'])->name('admin.equipos.jugadores.destroy');    
    
    //Apartado de anuncios
    Route::resource('/admin/anuncios', App\Http\Controllers\Admin\AnunciosController::class)->names('admin.anuncios');
    Route::patch('/admin/anuncios/{anuncio}/toggle-visibilidad', [App\Http\Controllers\Admin\AnunciosController::class, 'toggleVisibilidad'])->name('admin.anuncios.toggle-visibilidad');   
    
    //Apartado de instalaciones
    Route::resource('/admin/instalaciones', App\Http\Controllers\Admin\InstalacionesController::class)->names('admin.instalaciones');

    // Aparado de tarifas
    Route::resource('/admin/tarifas', App\Http\Controllers\Admin\TarifasController::class)->names('admin.tarifas');
    
    // Apartado de bloqueos
    Route::resource('/admin/bloqueos', App\Http\Controllers\Admin\BloqueosController::class)->names('admin.bloqueos');
    
    // Apartado de galeria
    Route::resource('/admin/galeria', App\Http\Controllers\Admin\GaleriaController::class)->names('admin.galeria');
    
    // Apartado de estadisticas
    Route::get('/admin/estadisticas', [App\Http\Controllers\Admin\EstadisticasController::class, 'index'])->name('admin.estadisticas');
    Route::get('/admin/estadisticas-alias', fn() => redirect()->route('admin.estadisticas'))->name('admin.estadisticas.index');
    
    // Apartado del historial
    Route::get('/admin/historial', [App\Http\Controllers\Admin\HistorialController::class, 'index'])->name('admin.historial.index');
    Route::get('/admin/historial/{registro}', [App\Http\Controllers\Admin\HistorialController::class, 'show'])->name('admin.historial.show');
    Route::get('/admin/historial/reserva/{reservaId}', [App\Http\Controllers\Admin\HistorialController::class, 'historialReserva'])->name('admin.historial.reserva');
    
    // Apartado de usuarios
    Route::resource('/admin/usuarios', App\Http\Controllers\Admin\UsuariosController::class)->names('admin.usuarios');

    // Apartado de configuracion
    Route::get('/admin/configuracion', [App\Http\Controllers\Admin\ConfiguracionController::class, 'index'])->name('admin.configuracion.index');
    Route::put('/admin/configuracion', [App\Http\Controllers\Admin\ConfiguracionController::class, 'update'])->name('admin.configuracion.update');
    Route::post('/admin/configuracion/generar-respaldo', [App\Http\Controllers\Admin\ConfiguracionController::class, 'generarRespaldo'])->name('admin.configuracion.generar-respaldo');
    Route::get('/admin/configuracion/descargar-respaldo/{nombreArchivo}', [App\Http\Controllers\Admin\ConfiguracionController::class, 'descargarRespaldo'])->name('admin.configuracion.descargar-respaldo');
    Route::delete('/admin/configuracion/eliminar-respaldo/{nombreArchivo}', [App\Http\Controllers\Admin\ConfiguracionController::class, 'eliminarRespaldo'])->name('admin.configuracion.eliminar-respaldo');
    
    // Apartado de contacto
    Route::get('/admin/contacto', [App\Http\Controllers\Admin\ContactoController::class, 'index'])->name('admin.contacto.index');
    Route::put('/admin/contacto', [App\Http\Controllers\Admin\ContactoController::class, 'update'])->name('admin.contacto.update');
    Route::get('/admin/contacto/eliminar-logo', [App\Http\Controllers\Admin\ContactoController::class, 'eliminarLogo'])->name('admin.contacto.eliminar-logo');
    Route::get('/admin/contacto/eliminar-favicon', [App\Http\Controllers\Admin\ContactoController::class, 'eliminarFavicon'])->name('admin.contacto.eliminar-favicon');

    // --- Rutas Organizador ---
    Route::prefix('organizador')->name('organizador.')->group(function () {
        // Apartado de torneos - organizador
        Route::get('/torneos', [App\Http\Controllers\Organizador\MisTorneosController::class, 'index'])->name('torneos.index');
        Route::get('/torneos/{torneo}', [App\Http\Controllers\Organizador\MisTorneosController::class, 'show'])->name('torneos.show');
        Route::put('/torneos/{torneo}/cambiar-estado', [App\Http\Controllers\Organizador\MisTorneosController::class, 'cambiarEstado'])->name('torneos.cambiar-estado');
        
        // Apartado de equipos - organizador
        Route::get('/equipos', [App\Http\Controllers\Organizador\EquiposController::class, 'index'])->name('equipos.index');
        Route::get('/equipos/crear', [App\Http\Controllers\Organizador\EquiposController::class, 'create'])->name('equipos.create');
        Route::post('/equipos', [App\Http\Controllers\Organizador\EquiposController::class, 'store'])->name('equipos.store');
        Route::get('/equipos/{equipo}/editar', [App\Http\Controllers\Organizador\EquiposController::class, 'edit'])->name('equipos.edit');
        Route::put('/equipos/{equipo}', [App\Http\Controllers\Organizador\EquiposController::class, 'update'])->name('equipos.update');
        Route::delete('/equipos/{equipo}', [App\Http\Controllers\Organizador\EquiposController::class, 'destroy'])->name('equipos.destroy');
        
        // Apartado de jugadores - organizador
        Route::get('/jugadores', [App\Http\Controllers\Organizador\JugadoresController::class, 'index'])->name('jugadores.index');
        Route::get('/jugadores/crear', [App\Http\Controllers\Organizador\JugadoresController::class, 'create'])->name('jugadores.create');
        Route::post('/jugadores', [App\Http\Controllers\Organizador\JugadoresController::class, 'store'])->name('jugadores.store');
        Route::get('/jugadores/{jugador}/editar', [App\Http\Controllers\Organizador\JugadoresController::class, 'edit'])->name('jugadores.edit');
        Route::put('/jugadores/{jugador}', [App\Http\Controllers\Organizador\JugadoresController::class, 'update'])->name('jugadores.update');
        Route::delete('/jugadores/{jugador}', [App\Http\Controllers\Organizador\JugadoresController::class, 'destroy'])->name('jugadores.destroy');
        
        // Apartado de partidos - organizador
        Route::get('/partidos', [App\Http\Controllers\Organizador\PartidosController::class, 'index'])->name('partidos.index');
        Route::get('/partidos/crear', [App\Http\Controllers\Organizador\PartidosController::class, 'create'])->name('partidos.create');
        Route::post('/partidos', [App\Http\Controllers\Organizador\PartidosController::class, 'store'])->name('partidos.store');
        Route::get('/partidos/{partido}', [App\Http\Controllers\Organizador\PartidosController::class, 'show'])->name('partidos.show');
        Route::get('/partidos/{partido}/editar', [App\Http\Controllers\Organizador\PartidosController::class, 'edit'])->name('partidos.edit');
        Route::put('/partidos/{partido}', [App\Http\Controllers\Organizador\PartidosController::class, 'update'])->name('partidos.update');
        Route::put('/partidos/{partido}/registrar-resultado', [App\Http\Controllers\Organizador\PartidosController::class, 'registrarResultado'])->name('partidos.registrar-resultado');
        Route::delete('/partidos/{partido}', [App\Http\Controllers\Organizador\PartidosController::class, 'destroy'])->name('partidos.destroy');

        // Apartado de posiciones - organizador
        Route::get('/posiciones', [App\Http\Controllers\Organizador\PosicionesController::class, 'index'])->name('posiciones.index');
        Route::post('/posiciones/actualizar', [App\Http\Controllers\Organizador\PosicionesController::class, 'actualizar'])->name('posiciones.actualizar');
    });

    // --- Rutas Trabajador ---
    Route::prefix('trabajador')->name('trabajador.')->group(function () {
        // Apartado de reservas
        Route::get('/reservas', [App\Http\Controllers\Trabajador\ReservasController::class, 'index'])->name('reservas.index');
        Route::get('/reservas-alias', fn() => redirect()->route('trabajador.reservas.index'))->name('reservas');
        Route::get('/reservas/crear', [App\Http\Controllers\Trabajador\ReservasController::class, 'create'])->name('reservas.create');
        Route::post('/reservas', [App\Http\Controllers\Trabajador\ReservasController::class, 'store'])->name('reservas.store');
        Route::get('/reservas/{reserva}', [App\Http\Controllers\Trabajador\ReservasController::class, 'show'])->name('reservas.show');
        Route::get('/reservas/{reserva}/editar', [App\Http\Controllers\Trabajador\ReservasController::class, 'edit'])->name('reservas.edit');
        Route::put('/reservas/{reserva}', [App\Http\Controllers\Trabajador\ReservasController::class, 'update'])->name('reservas.update');
        Route::post('/reservas/{reserva}/cambiar-estado', [App\Http\Controllers\Trabajador\ReservasController::class, 'cambiarEstado'])->name('reservas.cambiar-estado');

        // Apartado del calendario
        Route::get('/calendario', [App\Http\Controllers\Trabajador\CalendarioController::class, 'index'])->name('calendario.index');
        Route::get('/calendario-alias', fn() => redirect()->route('trabajador.calendario.index'))->name('calendario');
    });
});
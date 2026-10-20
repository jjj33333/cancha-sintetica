<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Usuario;

class AutenticacionTest extends DuskTestCase
{
    /**
     * CP-AUTH-001: Inicio de sesión exitoso como Administrador.
     */
    public function test_inicio_sesion_administrador_exitoso(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Iniciar Sesión')
                    ->click('button:has-text("Iniciar Sesión")')
                    ->waitFor('input[name="email"]')
                    ->type('email', 'admin@cancha.com')
                    ->type('password', 'admin123')
                    ->press('button[type="submit"]')
                    ->waitForLocation('/admin/dashboard')
                    ->assertPathIs('/admin/dashboard')
                    ->assertSee('Panel de Administración');
        });
    }

    /**
     * CP-AUTH-002: Inicio de sesión exitoso como Organizador.
     */
    public function test_inicio_sesion_organizador_exitoso(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->click('button:has-text("Iniciar Sesión")')
                    ->waitFor('input[name="email"]')
                    ->type('email', 'carlos@org.com')
                    ->type('password', 'org123')
                    ->press('button[type="submit"]')
                    ->waitForLocation('/organizador/dashboard')
                    ->assertPathIs('/organizador/dashboard')
                    ->assertSee('Panel de Organizador');
        });
    }

    /**
     * CP-AUTH-003: Inicio de sesión exitoso como Trabajador.
     */
    public function test_inicio_sesion_trabajador_exitoso(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->click('button:has-text("Iniciar Sesión")')
                    ->waitFor('input[name="email"]')
                    ->type('email', 'maria@cancha.com')
                    ->type('password', 'trab123')
                    ->press('button[type="submit"]')
                    ->waitForLocation('/trabajador/dashboard')
                    ->assertPathIs('/trabajador/dashboard')
                    ->assertSee('Panel de Trabajador');
        });
    }

    /**
     * CP-AUTH-004: Validación de credenciales incorrectas.
     */
    public function test_credenciales_invalidas_muestra_error(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->click('button:has-text("Iniciar Sesión")')
                    ->waitFor('input[name="email"]')
                    ->type('email', 'invalido@cancha.com')
                    ->type('password', 'clave_erronea')
                    ->press('button[type="submit"]')
                    ->assertPathIs('/')
                    ->assertSee('credenciales no coinciden');
        });
    }

    /**
     * CP-AUTH-005: Cierre de sesión exitoso (Logout).
     */
    public function test_cierre_de_sesion_logout(): void
    {
        $this->browse(function (Browser $browser) {
            $admin = Usuario::where('email', 'admin@cancha.com')->first();

            $browser->loginAs($admin)
                    ->visit('/admin/dashboard')
                    ->assertSee('Cerrar Sesión')
                    ->press('button:has-text("Cerrar Sesión")')
                    ->waitForLocation('/')
                    ->assertPathIs('/')
                    ->assertSee('Iniciar Sesión');
        });
    }
}

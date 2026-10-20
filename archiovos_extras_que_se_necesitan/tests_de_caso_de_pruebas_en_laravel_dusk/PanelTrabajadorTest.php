<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Usuario;

class PanelTrabajadorTest extends DuskTestCase
{
    /**
     * CP-TRAB-001: Dashboard del Trabajador con reservas del día.
     */
    public function test_dashboard_trabajador(): void
    {
        $this->browse(function (Browser $browser) {
            $trabajador = Usuario::where('email', 'maria@cancha.com')->first();

            $browser->loginAs($trabajador)
                    ->visit('/trabajador/dashboard')
                    ->assertSee('Panel de Trabajador')
                    ->assertSee('Reservas de Hoy');
        });
    }

    /**
     * CP-TRAB-002: Gestión de Reservas manuales con DPI y Quetzales por Trabajador.
     */
    public function test_reservas_manuales_trabajador(): void
    {
        $this->browse(function (Browser $browser) {
            $trabajador = Usuario::where('email', 'maria@cancha.com')->first();

            $browser->loginAs($trabajador)
                    ->visit('/trabajador/reservas')
                    ->assertSee('Reservas')
                    ->assertSee('Q.')
                    ->visit('/trabajador/reservas/crear')
                    ->assertSee('Nueva Reserva')
                    ->assertSee('DPI');
        });
    }

    /**
     * CP-TRAB-004: Calendario de ocupación en modo consulta para el Trabajador.
     */
    public function test_calendario_ocupacion_trabajador(): void
    {
        $this->browse(function (Browser $browser) {
            $trabajador = Usuario::where('email', 'maria@cancha.com')->first();

            $browser->loginAs($trabajador)
                    ->visit('/trabajador/calendario')
                    ->assertSee('Calendario');
        });
    }
}

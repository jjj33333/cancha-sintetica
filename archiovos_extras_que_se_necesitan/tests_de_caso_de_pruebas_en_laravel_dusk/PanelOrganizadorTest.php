<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Usuario;
use App\Models\Torneo;
use App\Models\Equipo;

class PanelOrganizadorTest extends DuskTestCase
{
    /**
     * CP-ORG-001: Mis Torneos (Dashboard Organizador).
     */
    public function test_mis_torneos_organizador(): void
    {
        $this->browse(function (Browser $browser) {
            $org = Usuario::where('email', 'carlos@org.com')->first();

            $browser->loginAs($org)
                    ->visit('/organizador/torneos')
                    ->assertSee('Mis Torneos Asignados');
        });
    }

    /**
     * CP-ORG-002: Gestión de Equipos del torneo.
     */
    public function test_gestion_equipos_organizador(): void
    {
        $this->browse(function (Browser $browser) {
            $org = Usuario::where('email', 'carlos@org.com')->first();

            $browser->loginAs($org)
                    ->visit('/organizador/equipos')
                    ->assertSee('Equipos Registrados');
        });
    }

    /**
     * CP-ORG-003: Gestión de Jugadores con DPI.
     */
    public function test_gestion_jugadores_con_dpi_organizador(): void
    {
        $this->browse(function (Browser $browser) {
            $org = Usuario::where('email', 'carlos@org.com')->first();

            $browser->loginAs($org)
                    ->visit('/organizador/jugadores')
                    ->assertSee('Jugadores Registrados')
                    ->assertSee('DPI');
        });
    }

    /**
     * CP-ORG-004: Partidos y Resultados.
     */
    public function test_partidos_y_resultados_organizador(): void
    {
        $this->browse(function (Browser $browser) {
            $org = Usuario::where('email', 'carlos@org.com')->first();

            $browser->loginAs($org)
                    ->visit('/organizador/partidos')
                    ->assertSee('Partidos del Torneo');
        });
    }

    /**
     * CP-ORG-005: Tabla de Posiciones y Standings.
     */
    public function test_tabla_posiciones_organizador(): void
    {
        $this->browse(function (Browser $browser) {
            $org = Usuario::where('email', 'carlos@org.com')->first();

            $browser->loginAs($org)
                    ->visit('/organizador/posiciones')
                    ->assertSee('Tabla de Posiciones')
                    ->assertSee('Puntos');
        });
    }
}

<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Usuario;
use App\Models\Reserva;
use App\Models\Torneo;
use App\Models\Equipo;

class PanelAdministradorTest extends DuskTestCase
{
    /**
     * CP-ADM-001: Visualización del Dashboard del Administrador.
     */
    public function test_dashboard_administrador(): void
    {
        $this->browse(function (Browser $browser) {
            $admin = Usuario::where('email', 'admin@cancha.com')->first();

            $browser->loginAs($admin)
                    ->visit('/admin/dashboard')
                    ->assertSee('Panel de Administración')
                    ->assertSee('Reservas de Hoy')
                    ->assertSee('Canchas Activas');
        });
    }

    /**
     * CP-ADM-002: Gestión de Reservas con DPI y montos en Quetzales.
     */
    public function test_gestion_reservas_administrador(): void
    {
        $this->browse(function (Browser $browser) {
            $admin = Usuario::where('email', 'admin@cancha.com')->first();

            $browser->loginAs($admin)
                    ->visit('/admin/reservas')
                    ->assertSee('Gestión de Reservas')
                    ->assertSee('Q.')
                    ->visit('/admin/reservas/create')
                    ->assertSee('Nueva Reserva')
                    ->assertSee('DPI')
                    ->assertSee('Q.');
        });
    }

    /**
     * CP-ADM-003: Visualización del Calendario general de ocupación.
     */
    public function test_calendario_ocupacion_administrador(): void
    {
        $this->browse(function (Browser $browser) {
            $admin = Usuario::where('email', 'admin@cancha.com')->first();

            $browser->loginAs($admin)
                    ->visit('/admin/calendario')
                    ->assertSee('Calendario')
                    ->assertSee('Ocupación');
        });
    }

    /**
     * CP-ADM-004: Creación de Torneo con costo en Quetzales y organizador.
     */
    public function test_creacion_torneo_administrador(): void
    {
        $this->browse(function (Browser $browser) {
            $admin = Usuario::where('email', 'admin@cancha.com')->first();

            $browser->loginAs($admin)
                    ->visit('/admin/torneos/create')
                    ->assertSee('Nuevo Torneo')
                    ->assertSee('Costo de Inscripción (Q.)')
                    ->assertSee('Organizador Asignado');
        });
    }

    /**
     * CP-ADM-005: Gestión de Equipos y Jugadores con DPI.
     */
    public function test_equipos_y_jugadores_administrador(): void
    {
        $this->browse(function (Browser $browser) {
            $admin = Usuario::where('email', 'admin@cancha.com')->first();

            $browser->loginAs($admin)
                    ->visit('/admin/equipos')
                    ->assertSee('Equipos Registrados');
        });
    }

    /**
     * CP-ADM-006: Gestión de Anuncios y Noticias.
     */
    public function test_anuncios_administrador(): void
    {
        $this->browse(function (Browser $browser) {
            $admin = Usuario::where('email', 'admin@cancha.com')->first();

            $browser->loginAs($admin)
                    ->visit('/admin/anuncios')
                    ->assertSee('Anuncios y Noticias');
        });
    }

    /**
     * CP-ADM-007: Gestión de Instalaciones.
     */
    public function test_instalaciones_administrador(): void
    {
        $this->browse(function (Browser $browser) {
            $admin = Usuario::where('email', 'admin@cancha.com')->first();

            $browser->loginAs($admin)
                    ->visit('/admin/instalaciones')
                    ->assertSee('Instalaciones')
                    ->assertSee('Cancha');
        });
    }

    /**
     * CP-ADM-008: Creación de Tarifas en Quetzales (GTQ).
     */
    public function test_tarifas_administrador(): void
    {
        $this->browse(function (Browser $browser) {
            $admin = Usuario::where('email', 'admin@cancha.com')->first();

            $browser->loginAs($admin)
                    ->visit('/admin/tarifas/create')
                    ->assertSee('Nueva Tarifa')
                    ->assertSee('Q.')
                    ->assertSee('GTQ');
        });
    }

    /**
     * CP-ADM-009: Bloqueos de Cancha por mantenimiento.
     */
    public function test_bloqueos_cancha_administrador(): void
    {
        $this->browse(function (Browser $browser) {
            $admin = Usuario::where('email', 'admin@cancha.com')->first();

            $browser->loginAs($admin)
                    ->visit('/admin/bloqueos')
                    ->assertSee('Bloqueos de Cancha');
        });
    }

    /**
     * CP-ADM-010: Galería multimedia.
     */
    public function test_galeria_administrador(): void
    {
        $this->browse(function (Browser $browser) {
            $admin = Usuario::where('email', 'admin@cancha.com')->first();

            $browser->loginAs($admin)
                    ->visit('/admin/galeria')
                    ->assertSee('Galería');
        });
    }

    /**
     * CP-ADM-011: Estadísticas y reportes de ingresos en Quetzales.
     */
    public function test_estadisticas_administrador(): void
    {
        $this->browse(function (Browser $browser) {
            $admin = Usuario::where('email', 'admin@cancha.com')->first();

            $browser->loginAs($admin)
                    ->visit('/admin/estadisticas')
                    ->assertSee('Estadísticas')
                    ->assertSee('Q.');
        });
    }

    /**
     * CP-ADM-012: Historial y auditoría.
     */
    public function test_historial_auditoria_administrador(): void
    {
        $this->browse(function (Browser $browser) {
            $admin = Usuario::where('email', 'admin@cancha.com')->first();

            $browser->loginAs($admin)
                    ->visit('/admin/historial')
                    ->assertSee('Historial de Actividades');
        });
    }

    /**
     * CP-ADM-013: Gestión de Usuarios del sistema.
     */
    public function test_usuarios_administrador(): void
    {
        $this->browse(function (Browser $browser) {
            $admin = Usuario::where('email', 'admin@cancha.com')->first();

            $browser->loginAs($admin)
                    ->visit('/admin/usuarios')
                    ->assertSee('Usuarios del Sistema');
        });
    }

    /**
     * CP-ADM-014: Configuración general y respaldos (Guatemala / GTQ).
     */
    public function test_configuracion_administrador(): void
    {
        $this->browse(function (Browser $browser) {
            $admin = Usuario::where('email', 'admin@cancha.com')->first();

            $browser->loginAs($admin)
                    ->visit('/admin/configuracion')
                    ->assertSee('Configuración del Sistema')
                    ->assertSee('America/Guatemala')
                    ->assertSee('GTQ');
        });
    }

    /**
     * CP-ADM-015: Información de Contacto institucional de Guatemala.
     */
    public function test_contacto_institucional_administrador(): void
    {
        $this->browse(function (Browser $browser) {
            $admin = Usuario::where('email', 'admin@cancha.com')->first();

            $browser->loginAs($admin)
                    ->visit('/admin/contacto')
                    ->assertSee('Información de Contacto')
                    ->assertSee('Chinique')
                    ->assertSee('Guatemala');
        });
    }
}

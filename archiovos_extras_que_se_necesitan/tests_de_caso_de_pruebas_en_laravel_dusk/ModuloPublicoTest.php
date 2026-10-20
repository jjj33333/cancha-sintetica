<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ModuloPublicoTest extends DuskTestCase
{
    /**
     * CP-PUB-001: Carga y visualización de la página principal (Home).
     */
    public function test_carga_pagina_home(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Sobre Nuestra Cancha')
                    ->assertSee('Ubicación')
                    ->assertSee('Dimensiones')
                    ->assertSee('Chinique');
        });
    }

    /**
     * CP-PUB-002: Visualización de las tarjetas de Horario Semanal con día, fecha y rango de horas.
     */
    public function test_visualizacion_horario_semanal_con_fechas(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/horarios')
                    ->assertSee('Horario Semanal')
                    ->assertSee('Lunes')
                    ->assertSee('Martes')
                    ->assertSee('Miércoles')
                    ->assertSee('Jueves')
                    ->assertSee('Viernes')
                    ->assertSee('Sábado')
                    ->assertSee('Domingo')
                    ->assertDontSee('horas ocupadas');
        });
    }

    /**
     * CP-PUB-003: Navegación de semanas (+2 semanas adicionales) en Horarios.
     */
    public function test_navegacion_desplazamiento_de_semanas(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/horarios')
                    ->assertSee('Semana Actual')
                    ->click('button:has-text("Semana Siguiente")')
                    ->pause(300)
                    ->assertSee('Próxima Semana')
                    ->click('button:has-text("Semana Siguiente")')
                    ->pause(300)
                    ->assertSee('En 2 Semanas')
                    ->click('button:has-text("Semana Anterior")')
                    ->pause(300)
                    ->assertSee('Próxima Semana');
        });
    }

    /**
     * CP-PUB-004: Consulta de disponibilidad por bloques de 1 hora en Calendario Detallado.
     */
    public function test_disponibilidad_cuadros_una_hora_calendario_detallado(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/horarios')
                    ->assertSee('Calendario Detallado')
                    ->assertSee('Leyenda:')
                    ->assertSee('Disponible')
                    ->assertSee('No disponible');
        });
    }

    /**
     * CP-PUB-005: Visualización de Torneos públicos y costos en Quetzales (Q.).
     */
    public function test_visualizacion_torneos_y_moneda_quetzales(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/torneos')
                    ->assertSee('Torneos')
                    ->assertSee('Tabla de Posiciones')
                    ->assertSee('Q.');
        });
    }

    /**
     * CP-PUB-006: Visualización de página de Contacto con datos de Guatemala.
     */
    public function test_visualizacion_contacto_guatemala(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/contacto')
                    ->assertSee('Información de Contacto')
                    ->assertSee('Chinique')
                    ->assertSee('Guatemala')
                    ->assertSee('+502');
        });
    }
}

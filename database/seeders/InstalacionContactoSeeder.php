<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Instalacion;
use App\Models\InformacionContacto;
use Illuminate\Support\Facades\Schema;

class InstalacionContactoSeeder extends Seeder
{
    /**
     * Ejecuta los seeders de instalaciones e información de contacto.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Instalacion::truncate();
        InformacionContacto::truncate();
        Schema::enableForeignKeyConstraints();

        // ============================================
        // 1. CREAR LA INSTALACIÓN PRINCIPAL
        // ============================================
        $instalacion = Instalacion::create([
            'nombre' => 'Cancha Principal',
            'tipo' => 'sintetica',
            'descripcion' => 'Cancha de fútbol con césped sintético, ideal para disfrutar de un buen partido.',
            'capacidad' => 10,
            'dimensiones' => '40m x 20m',
            'tipo_superficie' => 'Césped sintético',
            'tiene_iluminacion' => true,
            'tiene_estacionamiento' => true,
            'tiene_casilleros' => true,
            'esta_activo' => true,
            'actualizado_por' => null,
        ]);

        // Segunda instalación (en proyecto/mantenimiento)
        Instalacion::create([
            'nombre' => 'Cancha 2 (Proyecto)',
            'tipo' => 'mixta',
            'descripcion' => 'Ven a despejar la mente.',
            'capacidad' => 8,
            'dimensiones' => '30m x 15m',
            'tipo_superficie' => 'Césped mixto',
            'tiene_iluminacion' => true,
            'tiene_estacionamiento' => true,
            'tiene_casilleros' => true,
            'esta_activo' => false,
            'actualizado_por' => null,
        ]);

        // ============================================
        // 2. CREAR LA INFORMACIÓN DE CONTACTO
        // ============================================
        InformacionContacto::create([
            'nombre_negocio' => 'Cancha Urizar',
            'ruc_nit' => '20123456789',
            'direccion' => 'Chinique',
            'ciudad' => 'Chinique',
            'pais' => 'Guatemala',
            'telefono_principal' => '+502 48700384',
            'telefono_secundario' => '+502 48700384',
            'email_principal' => '',
            'email_secundario' => '',
            'sitio_web' => '',
            'url_facebook' => '',
            'url_instagram' => '',
            'url_twitter' => null,
            'numero_whatsapp' => '+502 48700384',
            'enlace_google_maps' => '',
            'horario_atencion' => 'Lunes a Viernes: 06:00 - 23:00 | Sábados y Domingos: 08:00 - 22:00',
            'logo' => null,
            'favicon' => null,
            'esta_activo' => true,
            'actualizado_por' => null,
        ]);

        $this->command->info('Instalación e Información de Contacto creadas exitosamente.');
    }
}
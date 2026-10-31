<?php

namespace Database\Seeders;

use App\Models\Configuracion;
use App\Models\HorarioAtencion;
use App\Models\InformacionContacto;
use App\Models\Instalacion;
use App\Models\PlanTarifa;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

/**
 * Reinicia la base de datos dejando únicamente la información real de la cancha:
 * instalación activa, usuarios del sistema, horarios de atención, contacto,
 * tarifas vigentes y configuración general.
 *
 * No crea torneos, equipos, jugadores, partidos, reservas, bloqueos, anuncios,
 * galería, estadísticas ni ningún otro dato de prueba.
 *
 * Ejecutar con:
 *   php artisan db:seed --class=DatosBaseSeeder
 */
class DatosBaseSeeder extends Seeder
{
    /**
     * Tablas de datos que se vacían antes de cargar la base limpia.
     * Las vistas (vista_partidos_publicos, vista_reservas_diarias) no se tocan.
     */
    private const TABLAS_A_REINICIAR = [
        'anuncios',
        'bloqueos_cancha',
        'configuracion',
        'equipos',
        'estadisticas_diarias',
        'galeria_imagenes',
        'historial_reservas',
        'horarios_atencion',
        'informacion_contacto',
        'instalaciones',
        'jugadores',
        'notificaciones',
        'partidos',
        'planes_tarifas',
        'registros_actividad',
        'reservas',
        'sesiones',
        'tokens_restablecimiento',
        'torneos',
        'usuarios',
    ];

    public function run(): void
    {
        // ============================================
        // 0. REINICIO LIMPIO (SIN DATOS DE PRUEBA)
        // ============================================
        Schema::disableForeignKeyConstraints();

        foreach (self::TABLAS_A_REINICIAR as $tabla) {
            DB::table($tabla)->truncate();
        }

        Schema::enableForeignKeyConstraints();

        // ============================================
        // 1. INSTALACIÓN ACTIVA (SOLO UNA)
        // ============================================
        $instalacion = Instalacion::create([
            'nombre' => 'Cancha Principal',
            'tipo' => 'sintetica',
            'descripcion' => 'Cancha de fútbol con césped sintético, ideal para disfrutar de un buen partido.',
            'capacidad' => 10,
            'dimensiones' => '40m x 20m',
            'tipo_superficie' => 'Césped sintético',
            'tiene_iluminacion' => false,
            'tiene_estacionamiento' => true,
            'tiene_casilleros' => false,
            'esta_activo' => true,
            'actualizado_por' => null,
        ]);

        // ============================================
        // 2. USUARIOS DEL SISTEMA (3 ROLES)
        // ============================================
        // Credenciales documentadas en los casos de prueba:
        //   admin@gmail.com   / admincancha123
        //   kike@gmail.com     / kikecancha123
        //   usuario@gmail.com   / usuariocancha
        $admin = Usuario::create([
            'nombre' => 'Administrador',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admincancha123'),
            'rol' => 'admin',
            'telefono' => '',
            'esta_activo' => true,
        ]);

        Usuario::create([
            'nombre' => 'Carlos Organizador',
            'email' => 'kike@gmail.com',
            'password' => Hash::make('kikecancha123'),
            'rol' => 'organizador',
            'telefono' => '',
            'esta_activo' => true,
        ]);

        Usuario::create([
            'nombre' => 'usuario',
            'email' => 'usuario@gmail.com',
            'password' => Hash::make('usuariocancha123'),
            'rol' => 'trabajador',
            'telefono' => '',
            'esta_activo' => true,
        ]);

        // ============================================
        // 3. HORARIOS DE ATENCIÓN DE LA CANCHA
        // ============================================
        $horarios = [
            ['dia_semana' => 1, 'hora_apertura' => '06:00', 'hora_cierre' => '23:00'], // Lunes
            ['dia_semana' => 2, 'hora_apertura' => '06:00', 'hora_cierre' => '23:00'], // Martes
            ['dia_semana' => 3, 'hora_apertura' => '06:00', 'hora_cierre' => '23:00'], // Miércoles
            ['dia_semana' => 4, 'hora_apertura' => '06:00', 'hora_cierre' => '23:00'], // Jueves
            ['dia_semana' => 5, 'hora_apertura' => '06:00', 'hora_cierre' => '23:00'], // Viernes
            ['dia_semana' => 6, 'hora_apertura' => '08:00', 'hora_cierre' => '22:00'], // Sábado
            ['dia_semana' => 0, 'hora_apertura' => '08:00', 'hora_cierre' => '22:00'], // Domingo
        ];

        foreach ($horarios as $horario) {
            HorarioAtencion::create(array_merge($horario, [
                'instalacion_id' => $instalacion->id,
                'esta_activo' => true,
                'actualizado_por' => null,
            ]));
        }

        // ============================================
        // 4. TARIFAS VIGENTES DE LA CANCHA
        // ============================================
        $tarifas = [
            [
                'nombre' => 'Hora Normal',
                'tipo_dia' => 'semana',
                'hora_inicio' => '06:00',
                'hora_fin' => '17:00',
                'precio' => 75.00,
                'moneda' => 'GTQ',
            ],
            [
                'nombre' => 'Hora Prime',
                'tipo_dia' => 'semana',
                'hora_inicio' => '17:00',
                'hora_fin' => '23:00',
                'precio' => 100.00,
                'moneda' => 'GTQ',
            ],
        ];

        foreach ($tarifas as $tarifa) {
            PlanTarifa::create(array_merge($tarifa, [
                'instalacion_id' => $instalacion->id,
                'esta_activo' => true,
                'actualizado_por' => $admin->id,
            ]));
        }

        // ============================================
        // 5. INFORMACIÓN DE CONTACTO
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

        // ============================================
        // 6. CONFIGURACIÓN GENERAL DEL SISTEMA
        // ============================================
        $configuraciones = [
            ['clave' => 'nombre_sitio', 'valor' => 'Sport Urizar', 'tipo' => 'texto', 'grupo' => 'general', 'descripcion' => 'Nombre del sitio web', 'es_publico' => true],
            ['clave' => 'zona_horaria', 'valor' => 'America/Guatemala', 'tipo' => 'texto', 'grupo' => 'general', 'descripcion' => 'Zona horaria del sistema'],
            ['clave' => 'moneda', 'valor' => 'GTQ', 'tipo' => 'texto', 'grupo' => 'general', 'descripcion' => 'Moneda principal del sistema'],
            ['clave' => 'dias_maximos_anticipacion', 'valor' => '30', 'tipo' => 'numero', 'grupo' => 'reservas', 'descripcion' => 'Días máximos de anticipación para reservas'],
            ['clave' => 'horas_minimas_cancelar', 'valor' => '21', 'tipo' => 'numero', 'grupo' => 'reservas', 'descripcion' => 'Horas mínimas para cancelar una reserva'],
            ['clave' => 'email_contacto', 'valor' => 'contacto@cancha.com', 'tipo' => 'texto', 'grupo' => 'contacto', 'descripcion' => 'Email de contacto público', 'es_publico' => true],
            ['clave' => 'telefono_contacto', 'valor' => '+502 5555 1234', 'tipo' => 'texto', 'grupo' => 'contacto', 'descripcion' => 'Teléfono de contacto público', 'es_publico' => true],
            ['clave' => 'mantenimiento_activo', 'valor' => '0', 'tipo' => 'booleano', 'grupo' => 'sistema', 'descripcion' => 'Modo mantenimiento activo'],
        ];

        foreach ($configuraciones as $config) {
            Configuracion::create([
                'clave' => $config['clave'],
                'valor' => $config['valor'],
                'tipo' => $config['tipo'],
                'grupo' => $config['grupo'],
                'descripcion' => $config['descripcion'],
                'es_publico' => $config['es_publico'] ?? false,
                'esta_activo' => true,
                'actualizado_por' => $admin->id,
            ]);
        }

        $this->command->info('✅ Base de datos reiniciada con los datos de la cancha, sin datos de prueba.');
    }
}

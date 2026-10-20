<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HorarioAtencion;
use App\Models\Reserva;
use App\Models\Torneo;
use App\Models\Equipo;
use App\Models\Jugador;
use App\Models\Partido;
use App\Models\Anuncio;
use App\Models\Usuario;
use App\Models\BloqueoCancha;
use App\Models\PlanTarifa;
use App\Models\RegistroActividad;
use App\Models\HistorialReserva;
use App\Models\Configuracion;
use App\Models\Instalacion;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatosPublicosSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================
        // 0. LIMPIAR DATOS PREVIOS (REEMPLAZO LIMPIO)
        // ============================================
        Schema::disableForeignKeyConstraints();

        HistorialReserva::truncate();
        RegistroActividad::truncate();
        Partido::truncate();
        Jugador::truncate();
        Equipo::truncate();
        Torneo::truncate();
        Reserva::truncate();
        BloqueoCancha::truncate();
        PlanTarifa::truncate();
        HorarioAtencion::truncate();
        Usuario::truncate();

        Schema::enableForeignKeyConstraints();

        // Asegurar existencia de la instalación principal
        if (!Instalacion::where('id', 1)->exists()) {
            Instalacion::create([
                'id' => 1,
                'nombre' => 'Cancha Principal',
                'tipo' => 'sintetica',
                'descripcion' => 'Cancha de fútbol con césped sintético de última generación, ideal para partidos amateur y torneos oficiales. Cuenta con iluminación LED profesional para juegos nocturnos.',
                'capacidad' => 12,
                'dimensiones' => '40m x 20m',
                'tipo_superficie' => 'Césped sintético FIFA Quality Pro',
                'tiene_iluminacion' => true,
                'tiene_estacionamiento' => true,
                'tiene_casilleros' => true,
                'esta_activo' => true,
                'actualizado_por' => null,
            ]);
        }

        // ============================================
        // 1. USUARIOS DE PRUEBA
        // ============================================
        $admin = Usuario::create([
            'nombre' => 'Administrador',
            'email' => 'admin@cancha.com',
            'password' => Hash::make('admin123'),
            'rol' => 'admin',
            'telefono' => '+502 5555 0000',
            'esta_activo' => true,
        ]);

        $organizador = Usuario::create([
            'nombre' => 'Carlos Organizador',
            'email' => 'carlos@org.com',
            'password' => Hash::make('org123'),
            'rol' => 'organizador',
            'telefono' => '+502 5555 1111',
            'esta_activo' => true,
        ]);

        $trabajador = Usuario::create([
            'nombre' => 'María Trabajadora',
            'email' => 'maria@cancha.com',
            'password' => Hash::make('trab123'),
            'rol' => 'trabajador',
            'telefono' => '+502 5555 2222',
            'esta_activo' => true,
        ]);

        // ============================================
        // 2. HORARIOS DE ATENCIÓN
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
                'instalacion_id' => 1,
                'esta_activo' => true,
            ]));
        }

        // ============================================
        // 3. RESERVAS DE PRUEBA (1 de Octubre al 25 de Octubre de 2026)
        // ============================================
        $reservas = [
            [
                'codigo_reserva' => 'RES-20261001-01',
                'nombre_cliente' => 'Equipo Los Tigres GT',
                'telefono_cliente' => '+502 5555 1122',
                'email_cliente' => 'lostigres@gmail.com',
                'dni_cliente' => '2458 19283 0101',
                'fecha_reserva' => '2026-10-01',
                'hora_inicio' => '18:00',
                'hora_fin' => '20:00',
                'total_horas' => 2,
                'precio_total' => 140.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'efectivo',
                'notas' => 'Reserva con balón e iluminación incluida',
            ],
            [
                'codigo_reserva' => 'RES-20261002-01',
                'nombre_cliente' => 'Juan Carlos Morales',
                'telefono_cliente' => '+502 4433 2211',
                'email_cliente' => 'jcmorales@gmail.com',
                'dni_cliente' => '1982 37465 1401',
                'fecha_reserva' => '2026-10-02',
                'hora_inicio' => '19:00',
                'hora_fin' => '21:00',
                'total_horas' => 2,
                'precio_total' => 140.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'transferencia',
                'notas' => 'Comprobante de transferencia bancaria verificado',
            ],
            [
                'codigo_reserva' => 'RES-20261003-01',
                'nombre_cliente' => 'Club Deportivo Chinique',
                'telefono_cliente' => '+502 5890 1234',
                'email_cliente' => 'cdchinique@gmail.com',
                'dni_cliente' => '2341 89765 0101',
                'fecha_reserva' => '2026-10-03',
                'hora_inicio' => '08:00',
                'hora_fin' => '10:00',
                'total_horas' => 2,
                'precio_total' => 150.00,
                'estado' => 'completada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'efectivo',
                'notas' => 'Entrenamiento matutino fin de semana',
            ],
            [
                'codigo_reserva' => 'RES-20261003-02',
                'nombre_cliente' => 'Sofía Castillo Méndez',
                'telefono_cliente' => '+502 3012 3456',
                'email_cliente' => 'sofia.castillo@hotmail.com',
                'dni_cliente' => '3021 54890 1401',
                'fecha_reserva' => '2026-10-03',
                'hora_inicio' => '16:00',
                'hora_fin' => '18:00',
                'total_horas' => 2,
                'precio_total' => 150.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'tarjeta',
                'notas' => 'Partido amistoso femenino',
            ],
            [
                'codigo_reserva' => 'RES-20261004-01',
                'nombre_cliente' => 'FC Quetzal',
                'telefono_cliente' => '+502 5123 7890',
                'email_cliente' => 'fcquetzal@outlook.com',
                'dni_cliente' => '1789 65432 0101',
                'fecha_reserva' => '2026-10-04',
                'hora_inicio' => '10:00',
                'hora_fin' => '12:00',
                'total_horas' => 2,
                'precio_total' => 150.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'transferencia',
                'notas' => 'Partido de preparación torneo',
            ],
            [
                'codigo_reserva' => 'RES-20261005-01',
                'nombre_cliente' => 'Alejandro López',
                'telefono_cliente' => '+502 4567 8901',
                'email_cliente' => 'alopez@gmail.com',
                'dni_cliente' => '2678 12345 1401',
                'fecha_reserva' => '2026-10-05',
                'hora_inicio' => '07:00',
                'hora_fin' => '09:00',
                'total_horas' => 2,
                'precio_total' => 100.00,
                'estado' => 'completada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'efectivo',
                'notas' => 'Tarifa normal mañanera',
            ],
            [
                'codigo_reserva' => 'RES-20261005-02',
                'nombre_cliente' => 'Pedro Gómez Morales',
                'telefono_cliente' => '+502 5234 5678',
                'email_cliente' => 'pedro.gomez@yahoo.com',
                'dni_cliente' => '2109 87654 0101',
                'fecha_reserva' => '2026-10-05',
                'hora_inicio' => '18:00',
                'hora_fin' => '20:00',
                'total_horas' => 2,
                'precio_total' => 140.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'efectivo',
                'notas' => 'Hora prime noche',
            ],
            [
                'codigo_reserva' => 'RES-20261006-01',
                'nombre_cliente' => 'Deportivo San José',
                'telefono_cliente' => '+502 4321 8765',
                'email_cliente' => 'sanjose.dep@gmail.com',
                'dni_cliente' => '1954 62839 1401',
                'fecha_reserva' => '2026-10-06',
                'hora_inicio' => '19:00',
                'hora_fin' => '21:00',
                'total_horas' => 2,
                'precio_total' => 140.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'transferencia',
                'notas' => 'Solicitud de chalecos deportivos',
            ],
            [
                'codigo_reserva' => 'RES-20261007-01',
                'nombre_cliente' => 'María Fernanda Estrada',
                'telefono_cliente' => '+502 5678 9012',
                'email_cliente' => 'mafe.estrada@gmail.com',
                'dni_cliente' => '2845 91023 0101',
                'fecha_reserva' => '2026-10-07',
                'hora_inicio' => '17:00',
                'hora_fin' => '19:00',
                'total_horas' => 2,
                'precio_total' => 140.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'tarjeta',
                'notas' => 'Reserva semanal fija',
            ],
            [
                'codigo_reserva' => 'RES-20261008-01',
                'nombre_cliente' => 'Roberto Alvarado',
                'telefono_cliente' => '+502 4987 6543',
                'email_cliente' => 'ralvarado@empresa.gt',
                'dni_cliente' => '1632 74891 1401',
                'fecha_reserva' => '2026-10-08',
                'hora_inicio' => '20:00',
                'hora_fin' => '22:00',
                'total_horas' => 2,
                'precio_total' => 140.00,
                'estado' => 'pendiente',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pendiente',
                'metodo_pago' => null,
                'notas' => 'Pendiente de confirmación de pago al llegar',
            ],
            [
                'codigo_reserva' => 'RES-20261009-01',
                'nombre_cliente' => 'Los Galácticos FC',
                'telefono_cliente' => '+502 5345 6789',
                'email_cliente' => 'galacticosgt@gmail.com',
                'dni_cliente' => '2514 83920 0101',
                'fecha_reserva' => '2026-10-09',
                'hora_inicio' => '18:00',
                'hora_fin' => '20:00',
                'total_horas' => 2,
                'precio_total' => 140.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'transferencia',
                'notas' => 'Viernes de fútbol nocturno',
            ],
            [
                'codigo_reserva' => 'RES-20261010-01',
                'nombre_cliente' => 'Fernando Pérez',
                'telefono_cliente' => '+502 4210 9876',
                'email_cliente' => 'fperez@gmail.com',
                'dni_cliente' => '1876 54321 1401',
                'fecha_reserva' => '2026-10-10',
                'hora_inicio' => '09:00',
                'hora_fin' => '11:00',
                'total_horas' => 2,
                'precio_total' => 150.00,
                'estado' => 'completada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'efectivo',
                'notas' => 'Partido entre amigos de colegio',
            ],
            [
                'codigo_reserva' => 'RES-20261010-02',
                'nombre_cliente' => 'Atlético Chinique',
                'telefono_cliente' => '+502 5789 0123',
                'email_cliente' => 'atleticochinique@gmail.com',
                'dni_cliente' => '2901 23456 0101',
                'fecha_reserva' => '2026-10-10',
                'hora_inicio' => '17:00',
                'hora_fin' => '19:00',
                'total_horas' => 2,
                'precio_total' => 150.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'tarjeta',
                'notas' => 'Sábado por la tarde',
            ],
            [
                'codigo_reserva' => 'RES-20261011-01',
                'nombre_cliente' => 'Diego Estrada Morales',
                'telefono_cliente' => '+502 4654 3210',
                'email_cliente' => 'destrada@hotmail.com',
                'dni_cliente' => '2039 48576 1401',
                'fecha_reserva' => '2026-10-11',
                'hora_inicio' => '15:00',
                'hora_fin' => '17:00',
                'total_horas' => 2,
                'precio_total' => 150.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'transferencia',
                'notas' => 'Domingo familiar y deportivo',
            ],
            [
                'codigo_reserva' => 'RES-20261012-01',
                'nombre_cliente' => 'Carlos Mendoza',
                'telefono_cliente' => '+502 5891 2345',
                'email_cliente' => 'cmendoza@empresa.com',
                'dni_cliente' => '2748 39201 0101',
                'fecha_reserva' => '2026-10-12',
                'hora_inicio' => '08:00',
                'hora_fin' => '10:00',
                'total_horas' => 2,
                'precio_total' => 100.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'efectivo',
                'notas' => 'Inicio de semana laboral',
            ],
            [
                'codigo_reserva' => 'RES-20261013-01',
                'nombre_cliente' => 'Jorge Ramírez Soto',
                'telefono_cliente' => '+502 4125 6789',
                'email_cliente' => 'jramirez@gmail.com',
                'dni_cliente' => '1593 72840 1401',
                'fecha_reserva' => '2026-10-13',
                'hora_inicio' => '18:00',
                'hora_fin' => '20:00',
                'total_horas' => 2,
                'precio_total' => 140.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'transferencia',
                'notas' => 'Partida nocturna',
            ],
            [
                'codigo_reserva' => 'RES-20261014-01',
                'nombre_cliente' => 'Miguel Ángel Cifuentes',
                'telefono_cliente' => '+502 5436 7890',
                'email_cliente' => 'mcifuentes@gmail.com',
                'dni_cliente' => '2481 90234 0101',
                'fecha_reserva' => '2026-10-14',
                'hora_inicio' => '19:00',
                'hora_fin' => '21:00',
                'total_horas' => 2,
                'precio_total' => 140.00,
                'estado' => 'pendiente',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pendiente',
                'metodo_pago' => null,
                'notas' => 'Reserva telefónica',
            ],
            [
                'codigo_reserva' => 'RES-20261015-01',
                'nombre_cliente' => 'Byron Marroquín',
                'telefono_cliente' => '+502 4789 0123',
                'email_cliente' => 'bmarroquin@correo.gt',
                'dni_cliente' => '1928 37465 1401',
                'fecha_reserva' => '2026-10-15',
                'hora_inicio' => '20:00',
                'hora_fin' => '22:00',
                'total_horas' => 2,
                'precio_total' => 140.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'tarjeta',
                'notas' => 'Jueves prime time',
            ],
            [
                'codigo_reserva' => 'RES-20261016-01',
                'nombre_cliente' => 'Deportivo La Unión',
                'telefono_cliente' => '+502 5670 1234',
                'email_cliente' => 'dlaunion@gmail.com',
                'dni_cliente' => '2635 84920 0101',
                'fecha_reserva' => '2026-10-16',
                'hora_inicio' => '17:00',
                'hora_fin' => '19:00',
                'total_horas' => 2,
                'precio_total' => 140.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'transferencia',
                'notas' => 'Reserva fin de semana',
            ],
            [
                'codigo_reserva' => 'RES-20261017-01',
                'nombre_cliente' => 'Estuardo Samayoa',
                'telefono_cliente' => '+502 4345 6789',
                'email_cliente' => 'esamayoa@gmail.com',
                'dni_cliente' => '1847 29384 1401',
                'fecha_reserva' => '2026-10-17',
                'hora_inicio' => '10:00',
                'hora_fin' => '12:00',
                'total_horas' => 2,
                'precio_total' => 150.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'efectivo',
                'notas' => 'Sábado mañanero con amigos',
            ],
            [
                'codigo_reserva' => 'RES-20261018-01',
                'nombre_cliente' => 'Leones del Norte',
                'telefono_cliente' => '+502 5901 2345',
                'email_cliente' => 'leonesdelnorte@gmail.com',
                'dni_cliente' => '2958 10293 0101',
                'fecha_reserva' => '2026-10-18',
                'hora_inicio' => '16:00',
                'hora_fin' => '18:00',
                'total_horas' => 2,
                'precio_total' => 150.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'transferencia',
                'notas' => 'Domingo deportivo',
            ],
            [
                'codigo_reserva' => 'RES-20261019-01',
                'nombre_cliente' => 'Gustavo Adolfo Díaz',
                'telefono_cliente' => '+502 4561 2378',
                'email_cliente' => 'gdiaz@gmail.com',
                'dni_cliente' => '2183 94058 1401',
                'fecha_reserva' => '2026-10-19',
                'hora_inicio' => '19:00',
                'hora_fin' => '21:00',
                'total_horas' => 2,
                'precio_total' => 140.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'efectivo',
                'notas' => 'Lunes noche',
            ],
            [
                'codigo_reserva' => 'RES-20261020-01',
                'nombre_cliente' => 'Torneo Día de la Revolución',
                'telefono_cliente' => '+502 5238 9014',
                'email_cliente' => 'torneorevolucion@gmail.com',
                'dni_cliente' => '2736 48291 0101',
                'fecha_reserva' => '2026-10-20',
                'hora_inicio' => '14:00',
                'hora_fin' => '16:00',
                'total_horas' => 2,
                'precio_total' => 160.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'transferencia',
                'notas' => 'Tarifa de feriado nacional (20 de Octubre)',
            ],
            [
                'codigo_reserva' => 'RES-20261021-01',
                'nombre_cliente' => 'Josué Santiago',
                'telefono_cliente' => '+502 4892 3456',
                'email_cliente' => 'josue.santiago@gmail.com',
                'dni_cliente' => '1625 38495 1401',
                'fecha_reserva' => '2026-10-21',
                'hora_inicio' => '18:00',
                'hora_fin' => '20:00',
                'total_horas' => 2,
                'precio_total' => 140.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'tarjeta',
                'notas' => 'Miércoles de partido',
            ],
            [
                'codigo_reserva' => 'RES-20261022-01',
                'nombre_cliente' => 'Club Deportivo Aurora GT',
                'telefono_cliente' => '+502 5543 2109',
                'email_cliente' => 'auroragt@gmail.com',
                'dni_cliente' => '2847 19283 0101',
                'fecha_reserva' => '2026-10-22',
                'hora_inicio' => '20:00',
                'hora_fin' => '22:00',
                'total_horas' => 2,
                'precio_total' => 140.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'transferencia',
                'notas' => 'Jueves noche iluminación',
            ],
            [
                'codigo_reserva' => 'RES-20261023-01',
                'nombre_cliente' => 'Municipal Chinique',
                'telefono_cliente' => '+502 4678 9012',
                'email_cliente' => 'munichinique@gmail.com',
                'dni_cliente' => '1938 47562 1401',
                'fecha_reserva' => '2026-10-23',
                'hora_inicio' => '19:00',
                'hora_fin' => '21:00',
                'total_horas' => 2,
                'precio_total' => 140.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'efectivo',
                'notas' => 'Viernes previa de torneo',
            ],
            [
                'codigo_reserva' => 'RES-20261024-01',
                'nombre_cliente' => 'Comunicaciones Amigos FC',
                'telefono_cliente' => '+502 5712 3456',
                'email_cliente' => 'cremasamigos@gmail.com',
                'dni_cliente' => '2564 83920 0101',
                'fecha_reserva' => '2026-10-24',
                'hora_inicio' => '08:00',
                'hora_fin' => '10:00',
                'total_horas' => 2,
                'precio_total' => 150.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'transferencia',
                'notas' => 'Sábado mañana',
            ],
            [
                'codigo_reserva' => 'RES-20261024-02',
                'nombre_cliente' => 'Futbolistas de Occidente',
                'telefono_cliente' => '+502 4190 8765',
                'email_cliente' => 'futboloccidente@gmail.com',
                'dni_cliente' => '1726 39485 1401',
                'fecha_reserva' => '2026-10-24',
                'hora_inicio' => '17:00',
                'hora_fin' => '19:00',
                'total_horas' => 2,
                'precio_total' => 150.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'tarjeta',
                'notas' => 'Sábado tarde',
            ],
            [
                'codigo_reserva' => 'RES-20261025-01',
                'nombre_cliente' => 'Torneo Dominical Final',
                'telefono_cliente' => '+502 5389 0123',
                'email_cliente' => 'finaldominical@gmail.com',
                'dni_cliente' => '2819 40592 0101',
                'fecha_reserva' => '2026-10-25',
                'hora_inicio' => '09:00',
                'hora_fin' => '11:00',
                'total_horas' => 2,
                'precio_total' => 150.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'efectivo',
                'notas' => 'Partido de exhibición domingo',
            ],
            [
                'codigo_reserva' => 'RES-20261025-02',
                'nombre_cliente' => 'Equipo Los Tigres GT Clausura',
                'telefono_cliente' => '+502 5555 1122',
                'email_cliente' => 'lostigres@gmail.com',
                'dni_cliente' => '2458 19283 0101',
                'fecha_reserva' => '2026-10-25',
                'hora_inicio' => '18:00',
                'hora_fin' => '20:00',
                'total_horas' => 2,
                'precio_total' => 150.00,
                'estado' => 'confirmada',
                'tipo_reserva' => 'manual',
                'estado_pago' => 'pagado',
                'metodo_pago' => 'transferencia',
                'notas' => 'Clausura de mes de octubre',
            ],
        ];

        $reservasCreadas = [];
        foreach ($reservas as $reserva) {
            $reservasCreadas[] = Reserva::create(array_merge($reserva, [
                'instalacion_id' => 1,
                'creado_por' => $admin->id,
                'esta_activo' => true,
            ]));
        }

        // ============================================
        // 4. TORNEOS
        // ============================================
        $torneoCopa = Torneo::create([
            'nombre' => 'Copa Amistad 2026',
            'descripcion' => 'Torneo amistoso de fútbol 7 para equipos de la zona. Premios para los 3 primeros lugares.',
            'instalacion_id' => 1,
            'tipo_torneo' => 'liga',
            'tipo_deporte' => 'futbol',
            'categoria' => 'libre',
            'fecha_inicio' => '2026-09-01',
            'fecha_fin' => '2026-10-30',
            'inscripcion_inicio' => '2026-08-01',
            'inscripcion_fin' => '2026-08-28',
            'max_equipos' => 8,
            'min_equipos' => 4,
            'jugadores_por_equipo' => 10,
            'costo_inscripcion' => 200,
            'descripcion_premios' => '1er: Q. 1,000, 2do: Q. 500, 3er: Q. 250',
            'estado' => 'en_curso',
            'organizador_asignado_id' => $organizador->id,
            'creado_por' => $admin->id,
            'esta_activo' => true,
        ]);

        $torneoLiga = Torneo::create([
            'nombre' => 'Liga Sub-20',
            'descripcion' => 'Liga juvenil para jugadores menores de 20 años. Formato de liga a doble rueda.',
            'instalacion_id' => 1,
            'tipo_torneo' => 'liga',
            'tipo_deporte' => 'futbol',
            'categoria' => 'sub20',
            'fecha_inicio' => '2026-09-15',
            'fecha_fin' => '2026-11-15',
            'inscripcion_inicio' => '2026-08-15',
            'inscripcion_fin' => '2026-09-10',
            'max_equipos' => 6,
            'min_equipos' => 4,
            'jugadores_por_equipo' => 10,
            'costo_inscripcion' => 150,
            'descripcion_premios' => '1er: Q. 800, 2do: Q. 400',
            'estado' => 'inscripciones_abiertas',
            'organizador_asignado_id' => $organizador->id,
            'creado_por' => $admin->id,
            'esta_activo' => true,
        ]);

        $torneoFemenino = Torneo::create([
            'nombre' => 'Torneo Femenino',
            'descripcion' => 'Primer torneo femenino de la cancha. ¡Inscripciones abiertas próximamente!',
            'instalacion_id' => 1,
            'tipo_torneo' => 'fase_grupos',
            'tipo_deporte' => 'futbol',
            'categoria' => 'femenino',
            'fecha_inicio' => '2026-10-01',
            'fecha_fin' => '2026-11-30',
            'inscripcion_inicio' => '2026-09-01',
            'inscripcion_fin' => '2026-09-25',
            'max_equipos' => 4,
            'min_equipos' => 4,
            'jugadores_por_equipo' => 8,
            'costo_inscripcion' => 100,
            'descripcion_premios' => '1er: Q. 500, 2do: Q. 250',
            'estado' => 'borrador',
            'creado_por' => $admin->id,
            'esta_activo' => true,
        ]);

        // ============================================
        // 5. EQUIPOS - Copa Amistad
        // ============================================
        $equiposCopa = [
            ['nombre' => 'Los Tigres', 'nombre_capitan' => 'Pedro Gómez', 'telefono_capitan' => '+502 5555 1111', 'puntos' => 13, 'partidos_jugados' => 5, 'partidos_ganados' => 4, 'partidos_empatados' => 1, 'partidos_perdidos' => 0, 'goles_favor' => 15, 'goles_contra' => 5, 'estado' => 'confirmado'],
            ['nombre' => 'Águilas FC', 'nombre_capitan' => 'Luis Ruiz', 'telefono_capitan' => '+502 5555 2222', 'puntos' => 10, 'partidos_jugados' => 5, 'partidos_ganados' => 3, 'partidos_empatados' => 1, 'partidos_perdidos' => 1, 'goles_favor' => 12, 'goles_contra' => 7, 'estado' => 'confirmado'],
            ['nombre' => 'Leones Rojos', 'nombre_capitan' => 'Ana Torres', 'telefono_capitan' => '+502 5555 3333', 'puntos' => 8, 'partidos_jugados' => 5, 'partidos_ganados' => 2, 'partidos_empatados' => 2, 'partidos_perdidos' => 1, 'goles_favor' => 10, 'goles_contra' => 8, 'estado' => 'confirmado'],
            ['nombre' => 'Halcones', 'nombre_capitan' => 'Diego Mora', 'telefono_capitan' => '+502 5555 4444', 'puntos' => 4, 'partidos_jugados' => 5, 'partidos_ganados' => 1, 'partidos_empatados' => 1, 'partidos_perdidos' => 3, 'goles_favor' => 6, 'goles_contra' => 11, 'estado' => 'confirmado'],
            ['nombre' => 'Pumas', 'nombre_capitan' => 'Roberto Silva', 'telefono_capitan' => '+502 5555 5555', 'puntos' => 1, 'partidos_jugados' => 5, 'partidos_ganados' => 0, 'partidos_empatados' => 1, 'partidos_perdidos' => 4, 'goles_favor' => 4, 'goles_contra' => 16, 'estado' => 'confirmado'],
        ];

        $equiposCopaModels = [];
        foreach ($equiposCopa as $eq) {
            $equiposCopaModels[] = Equipo::create(array_merge($eq, [
                'torneo_id' => $torneoCopa->id,
                'esta_activo' => true,
            ]));
        }

        // Equipos - Liga Sub-20
        $equiposLiga = [
            ['nombre' => 'Águilas FC Sub-20', 'nombre_capitan' => 'Carlos Mendoza', 'telefono_capitan' => '+502 5555 6666', 'puntos' => 7, 'partidos_jugados' => 3, 'partidos_ganados' => 2, 'partidos_empatados' => 1, 'partidos_perdidos' => 0, 'goles_favor' => 8, 'goles_contra' => 3, 'estado' => 'confirmado'],
            ['nombre' => 'Leones Rojos Sub-20', 'nombre_capitan' => 'Fernando López', 'telefono_capitan' => '+502 5555 7777', 'puntos' => 6, 'partidos_jugados' => 3, 'partidos_ganados' => 2, 'partidos_empatados' => 0, 'partidos_perdidos' => 1, 'goles_favor' => 7, 'goles_contra' => 4, 'estado' => 'confirmado'],
            ['nombre' => 'Halcones Sub-20', 'nombre_capitan' => 'Jorge Ramírez', 'telefono_capitan' => '+502 5555 8888', 'puntos' => 4, 'partidos_jugados' => 3, 'partidos_ganados' => 1, 'partidos_empatados' => 1, 'partidos_perdidos' => 1, 'goles_favor' => 5, 'goles_contra' => 5, 'estado' => 'confirmado'],
            ['nombre' => 'Pumas Sub-20', 'nombre_capitan' => 'Miguel Soto', 'telefono_capitan' => '+502 5555 9999', 'puntos' => 0, 'partidos_jugados' => 3, 'partidos_ganados' => 0, 'partidos_empatados' => 0, 'partidos_perdidos' => 3, 'goles_favor' => 2, 'goles_contra' => 10, 'estado' => 'registrado'],
        ];

        $equiposLigaModels = [];
        foreach ($equiposLiga as $eq) {
            $equiposLigaModels[] = Equipo::create(array_merge($eq, [
                'torneo_id' => $torneoLiga->id,
                'esta_activo' => true,
            ]));
        }

        // ============================================
        // 6. PARTIDOS - Copa Amistad
        // ============================================
        $partidosCopa = [
            // Finalizados
            ['equipo_local_id' => $equiposCopaModels[0]->id, 'equipo_visitante_id' => $equiposCopaModels[1]->id, 'fecha_partido' => '2026-09-15', 'hora_inicio' => '18:00', 'marcador_local' => 3, 'marcador_visitante' => 1, 'estado' => 'finalizado'],
            ['equipo_local_id' => $equiposCopaModels[2]->id, 'equipo_visitante_id' => $equiposCopaModels[3]->id, 'fecha_partido' => '2026-09-17', 'hora_inicio' => '20:00', 'marcador_local' => 2, 'marcador_visitante' => 2, 'estado' => 'finalizado'],
            // Programados
            ['equipo_local_id' => $equiposCopaModels[0]->id, 'equipo_visitante_id' => $equiposCopaModels[2]->id, 'fecha_partido' => '2026-09-20', 'hora_inicio' => '10:00', 'estado' => 'programado'],
            ['equipo_local_id' => $equiposCopaModels[1]->id, 'equipo_visitante_id' => $equiposCopaModels[3]->id, 'fecha_partido' => '2026-09-22', 'hora_inicio' => '19:00', 'estado' => 'programado'],
            ['equipo_local_id' => $equiposCopaModels[4]->id, 'equipo_visitante_id' => $equiposCopaModels[0]->id, 'fecha_partido' => '2026-09-25', 'hora_inicio' => '20:00', 'estado' => 'programado'],
        ];

        foreach ($partidosCopa as $p) {
            Partido::create(array_merge($p, [
                'torneo_id' => $torneoCopa->id,
                'instalacion_id' => 1,
                'esta_activo' => true,
            ]));
        }

        // Partidos - Liga Sub-20
        $partidosLiga = [
            ['equipo_local_id' => $equiposLigaModels[0]->id, 'equipo_visitante_id' => $equiposLigaModels[2]->id, 'fecha_partido' => '2026-09-20', 'hora_inicio' => '15:00', 'estado' => 'programado'],
            ['equipo_local_id' => $equiposLigaModels[1]->id, 'equipo_visitante_id' => $equiposLigaModels[3]->id, 'fecha_partido' => '2026-09-27', 'hora_inicio' => '10:00', 'estado' => 'programado'],
        ];

        foreach ($partidosLiga as $p) {
            Partido::create(array_merge($p, [
                'torneo_id' => $torneoLiga->id,
                'instalacion_id' => 1,
                'esta_activo' => true,
            ]));
        }

        // ============================================
        // 7. ANUNCIOS
        // ============================================
        /*
        Anuncio::create([
            'titulo' => 'Inicio Copa Amistad 2026',
            'slug' => 'inicio-copa-amistad-2026',
            'contenido' => '¡La Copa Amistad 2026 ha comenzado! 5 equipos compiten por el título.',
            'tipo' => 'evento',
            'prioridad' => 'alta',
            'esta_publicado' => true,
            'mostrar_en_publico' => true,
            'publicado_en' => now(),
            'expira_en' => now()->addMonths(2),
            'creado_por' => $admin->id,
            'esta_activo' => true,
        ]);
        */

        // ============================================
        // 8. BLOQUEOS DE CANCHA
        // ============================================
        BloqueoCancha::create([
            'instalacion_id' => 1,
            'fecha_bloqueo' => '2026-09-25',
            'hora_inicio' => '08:00',
            'hora_fin' => '12:00',
            'motivo' => 'mantenimiento',
            'descripcion' => 'Cambio de césped zona norte',
            'creado_por' => $admin->id,
            'esta_activo' => true,
        ]);

        BloqueoCancha::create([
            'instalacion_id' => 1,
            'fecha_bloqueo' => '2026-10-20',
            'hora_inicio' => '00:00',
            'hora_fin' => '12:00',
            'motivo' => 'feriado',
            'descripcion' => 'Día de la Revolución (Feriado Nacional)',
            'creado_por' => $admin->id,
            'esta_activo' => true,
        ]);

        // ============================================
        // 9. PLANES TARIFARIOS
        // ============================================
        PlanTarifa::create([
            'nombre' => 'Hora Normal',
            'instalacion_id' => 1,
            'tipo_dia' => 'semana',
            'hora_inicio' => '06:00',
            'hora_fin' => '17:00',
            'precio' => 50.00,
            'moneda' => 'GTQ',
            'esta_activo' => true,
            'actualizado_por' => $admin->id,
        ]);

        PlanTarifa::create([
            'nombre' => 'Hora Prime',
            'instalacion_id' => 1,
            'tipo_dia' => 'semana',
            'hora_inicio' => '17:00',
            'hora_fin' => '23:00',
            'precio' => 70.00,
            'moneda' => 'GTQ',
            'esta_activo' => true,
            'actualizado_por' => $admin->id,
        ]);

        PlanTarifa::create([
            'nombre' => 'Fin de Semana',
            'instalacion_id' => 1,
            'tipo_dia' => 'fin_semana',
            'hora_inicio' => '08:00',
            'hora_fin' => '22:00',
            'precio' => 75.00,
            'moneda' => 'GTQ',
            'esta_activo' => true,
            'actualizado_por' => $admin->id,
        ]);

        PlanTarifa::create([
            'nombre' => 'Tarifa Feriado',
            'instalacion_id' => 1,
            'tipo_dia' => 'feriado',
            'hora_inicio' => '08:00',
            'hora_fin' => '22:00',
            'precio' => 80.00,
            'moneda' => 'GTQ',
            'esta_activo' => true,
            'actualizado_por' => $admin->id,
        ]);

        // ============================================
        // 10. REGISTROS DE ACTIVIDAD (HISTORIAL)
        // ============================================
        $registrosActividad = [
            [
                'usuario_id' => $admin->id,
                'accion' => 'Crear Reserva',
                'nombre_tabla' => 'reservas',
                'registro_id' => 1,
                'direccion_ip' => '192.168.1.100',
                'agente_usuario' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'esta_activo' => true,
                'created_at' => now()->subHours(2),
            ],
            [
                'usuario_id' => $admin->id,
                'accion' => 'Editar Torneo',
                'nombre_tabla' => 'torneos',
                'registro_id' => 1,
                'valores_anteriores' => ['estado' => 'borrador'],
                'valores_nuevos' => ['estado' => 'en_curso'],
                'direccion_ip' => '192.168.1.100',
                'agente_usuario' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'esta_activo' => true,
                'created_at' => now()->subHours(3),
            ],
            [
                'usuario_id' => $organizador->id,
                'accion' => 'Programar Partido',
                'nombre_tabla' => 'partidos',
                'registro_id' => 1,
                'direccion_ip' => '192.168.1.101',
                'agente_usuario' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
                'esta_activo' => true,
                'created_at' => now()->subHours(5),
            ],
            [
                'usuario_id' => $admin->id,
                'accion' => 'Eliminar Anuncio',
                'nombre_tabla' => 'anuncios',
                'registro_id' => 1,
                'direccion_ip' => '192.168.1.100',
                'agente_usuario' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'esta_activo' => true,
                'created_at' => now()->subHours(8),
            ],
            [
                'usuario_id' => $trabajador->id,
                'accion' => 'Crear Reserva',
                'nombre_tabla' => 'reservas',
                'registro_id' => 2,
                'direccion_ip' => '192.168.1.102',
                'agente_usuario' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0)',
                'esta_activo' => true,
                'created_at' => now()->subHours(10),
            ],
            [
                'usuario_id' => $admin->id,
                'accion' => 'Editar Instalación',
                'nombre_tabla' => 'instalaciones',
                'registro_id' => 1,
                'valores_anteriores' => ['tiene_iluminacion' => false],
                'valores_nuevos' => ['tiene_iluminacion' => true],
                'direccion_ip' => '192.168.1.100',
                'agente_usuario' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'esta_activo' => true,
                'created_at' => now()->subDays(1),
            ],
            [
                'usuario_id' => $organizador->id,
                'accion' => 'Crear Equipo',
                'nombre_tabla' => 'equipos',
                'registro_id' => 1,
                'direccion_ip' => '192.168.1.101',
                'agente_usuario' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
                'esta_activo' => true,
                'created_at' => now()->subDays(2),
            ],
            [
                'usuario_id' => $admin->id,
                'accion' => 'Login',
                'nombre_tabla' => null,
                'registro_id' => null,
                'direccion_ip' => '192.168.1.100',
                'agente_usuario' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'esta_activo' => true,
                'created_at' => now()->subDays(2)->subHours(4),
            ],
        ];

        foreach ($registrosActividad as $registro) {
            RegistroActividad::create($registro);
        }

        // Historial de cambios de reservas
        HistorialReserva::create([
            'reserva_id' => 1,
            'estado_anterior' => null,
            'estado_nuevo' => 'pendiente',
            'cambiado_por' => $admin->id,
            'motivo_cambio' => 'Reserva creada manualmente',
            'esta_activo' => true,
            'cambiado_en' => now()->subHours(2),
        ]);

        HistorialReserva::create([
            'reserva_id' => 1,
            'estado_anterior' => 'pendiente',
            'estado_nuevo' => 'confirmada',
            'cambiado_por' => $admin->id,
            'motivo_cambio' => 'Pago recibido en efectivo',
            'esta_activo' => true,
            'cambiado_en' => now()->subHours(1),
        ]);

        HistorialReserva::create([
            'reserva_id' => 2,
            'estado_anterior' => null,
            'estado_nuevo' => 'pendiente',
            'cambiado_por' => $trabajador->id,
            'motivo_cambio' => 'Reserva creada por teléfono',
            'esta_activo' => true,
            'cambiado_en' => now()->subHours(10),
        ]);

        // ============================================
        // 11. CONFIGURACIÓN INICIAL DEL SISTEMA
        // ============================================
        $configuracionesIniciales = [
            ['clave' => 'nombre_sitio', 'valor' => 'Cancha Sintética Deportiva', 'tipo' => 'texto', 'grupo' => 'general', 'descripcion' => 'Nombre del sitio web', 'es_publico' => true],
            ['clave' => 'zona_horaria', 'valor' => 'America/Guatemala', 'tipo' => 'texto', 'grupo' => 'general', 'descripcion' => 'Zona horaria del sistema'],
            ['clave' => 'moneda', 'valor' => 'GTQ', 'tipo' => 'texto', 'grupo' => 'general', 'descripcion' => 'Moneda principal del sistema'],
            ['clave' => 'dias_maximos_anticipacion', 'valor' => '30', 'tipo' => 'numero', 'grupo' => 'reservas', 'descripcion' => 'Días máximos de anticipación para reservas'],
            ['clave' => 'horas_minimas_cancelar', 'valor' => '24', 'tipo' => 'numero', 'grupo' => 'reservas', 'descripcion' => 'Horas mínimas para cancelar una reserva'],
            ['clave' => 'email_contacto', 'valor' => 'contacto@cancha.com', 'tipo' => 'texto', 'grupo' => 'contacto', 'descripcion' => 'Email de contacto público', 'es_publico' => true],
            ['clave' => 'telefono_contacto', 'valor' => '+502 5555 1234', 'tipo' => 'texto', 'grupo' => 'contacto', 'descripcion' => 'Teléfono de contacto público', 'es_publico' => true],
            ['clave' => 'mantenimiento_activo', 'valor' => '0', 'tipo' => 'booleano', 'grupo' => 'sistema', 'descripcion' => 'Modo mantenimiento activo'],
        ];

        foreach ($configuracionesIniciales as $config) {
            Configuracion::actualizar(
                $config['clave'],
                $config['valor'],
                $config['tipo'],
                $config['grupo'],
                $config['descripcion'],
                $config['es_publico'] ?? false
            );
        }

        $this->command->info('✅ Datos públicos y reservas del 1 al 25 de octubre creados exitosamente para Guatemala (GTQ).');
    }
}

// Para ejecutar este seeder se debe ejecutar la siguiente línea de código en la terminal:
// php artisan db:seed --class=DatosPublicosSeeder
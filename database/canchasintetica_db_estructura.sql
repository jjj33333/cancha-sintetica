-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-10-2026 a las 04:26:02
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `canchasintetica_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anuncios`
--

CREATE TABLE `anuncios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `contenido` longtext NOT NULL,
  `tipo` enum('noticia','promocion','alerta','evento','general') DEFAULT 'general',
  `prioridad` enum('baja','media','alta','urgente') DEFAULT 'media',
  `imagen` varchar(255) DEFAULT NULL,
  `esta_publicado` tinyint(1) DEFAULT 0,
  `mostrar_en_publico` tinyint(1) DEFAULT 1 COMMENT '1: Mostrar en web pública, 0: Ocultar',
  `publicado_en` timestamp NULL DEFAULT NULL,
  `expira_en` timestamp NULL DEFAULT NULL,
  `contador_vistas` int(11) DEFAULT 0,
  `esta_activo` tinyint(1) DEFAULT 1,
  `actualizado_por` bigint(20) UNSIGNED DEFAULT NULL,
  `creado_por` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bloqueos_cancha`
--

CREATE TABLE `bloqueos_cancha` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `instalacion_id` bigint(20) UNSIGNED NOT NULL,
  `fecha_bloqueo` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `motivo` enum('mantenimiento','evento','feriado','otro') NOT NULL,
  `descripcion` text DEFAULT NULL,
  `esta_activo` tinyint(1) DEFAULT 1,
  `actualizado_por` bigint(20) UNSIGNED DEFAULT NULL,
  `creado_por` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `clave` varchar(100) NOT NULL,
  `valor` longtext DEFAULT NULL,
  `tipo` enum('texto','numero','booleano','json','archivo') DEFAULT 'texto',
  `grupo` varchar(50) DEFAULT 'general',
  `descripcion` varchar(255) DEFAULT NULL,
  `es_publico` tinyint(1) DEFAULT 0,
  `esta_activo` tinyint(1) DEFAULT 1,
  `actualizado_por` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos`
--

CREATE TABLE `equipos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `torneo_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `nombre_corto` varchar(50) DEFAULT NULL,
  `nombre_capitan` varchar(255) NOT NULL,
  `telefono_capitan` varchar(20) NOT NULL,
  `email_capitan` varchar(255) DEFAULT NULL,
  `color_uniforme` varchar(100) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp NULL DEFAULT current_timestamp(),
  `estado` enum('registrado','confirmado','eliminado','campeon','subcampeon') DEFAULT 'registrado',
  `puntos` int(11) DEFAULT 0,
  `partidos_jugados` int(11) DEFAULT 0,
  `partidos_ganados` int(11) DEFAULT 0,
  `partidos_empatados` int(11) DEFAULT 0,
  `partidos_perdidos` int(11) DEFAULT 0,
  `goles_favor` int(11) DEFAULT 0,
  `goles_contra` int(11) DEFAULT 0,
  `esta_activo` tinyint(1) DEFAULT 1,
  `actualizado_por` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadisticas_diarias`
--

CREATE TABLE `estadisticas_diarias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fecha_estadistica` date NOT NULL,
  `instalacion_id` bigint(20) UNSIGNED NOT NULL,
  `total_reservas` int(11) DEFAULT 0,
  `total_horas_reservadas` decimal(6,2) DEFAULT 0.00,
  `total_ingresos` decimal(10,2) DEFAULT 0.00,
  `clientes_unicos` int(11) DEFAULT 0,
  `total_cancelaciones` int(11) DEFAULT 0,
  `esta_activo` tinyint(1) DEFAULT 1,
  `actualizado_por` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `galeria_imagenes`
--

CREATE TABLE `galeria_imagenes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `ruta_archivo` varchar(500) NOT NULL,
  `tipo_archivo` enum('imagen','video') DEFAULT 'imagen',
  `tipo_mime` varchar(100) DEFAULT NULL,
  `tamano_archivo` int(11) DEFAULT NULL,
  `categoria` enum('instalacion','torneo','evento','general') DEFAULT 'general',
  `tipo_relacionado` varchar(50) DEFAULT NULL,
  `id_relacionado` bigint(20) UNSIGNED DEFAULT NULL,
  `es_destacado` tinyint(1) DEFAULT 0,
  `orden_visualizacion` int(11) DEFAULT 0,
  `esta_activo` tinyint(1) DEFAULT 1,
  `actualizado_por` bigint(20) UNSIGNED DEFAULT NULL,
  `subido_por` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_reservas`
--

CREATE TABLE `historial_reservas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reserva_id` bigint(20) UNSIGNED NOT NULL,
  `estado_anterior` varchar(50) DEFAULT NULL,
  `estado_nuevo` varchar(50) NOT NULL,
  `cambiado_por` bigint(20) UNSIGNED DEFAULT NULL,
  `motivo_cambio` text DEFAULT NULL,
  `esta_activo` tinyint(1) DEFAULT 1,
  `cambiado_en` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios_atencion`
--

CREATE TABLE `horarios_atencion` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `instalacion_id` bigint(20) UNSIGNED NOT NULL,
  `dia_semana` tinyint(4) NOT NULL COMMENT '0=Domingo, 1=Lunes, ..., 6=Sábado',
  `hora_apertura` time NOT NULL,
  `hora_cierre` time NOT NULL,
  `esta_activo` tinyint(1) DEFAULT 1,
  `actualizado_por` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informacion_contacto`
--

CREATE TABLE `informacion_contacto` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre_negocio` varchar(255) NOT NULL,
  `ruc_nit` varchar(50) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `pais` varchar(100) DEFAULT 'Peru',
  `telefono_principal` varchar(20) NOT NULL,
  `telefono_secundario` varchar(20) DEFAULT NULL,
  `email_principal` varchar(255) NOT NULL,
  `email_secundario` varchar(255) DEFAULT NULL,
  `sitio_web` varchar(255) DEFAULT NULL,
  `url_facebook` varchar(255) DEFAULT NULL,
  `url_instagram` varchar(255) DEFAULT NULL,
  `url_twitter` varchar(255) DEFAULT NULL,
  `numero_whatsapp` varchar(20) DEFAULT NULL,
  `enlace_google_maps` text DEFAULT NULL,
  `horario_atencion` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `esta_activo` tinyint(1) DEFAULT 1,
  `actualizado_por` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instalaciones`
--

CREATE TABLE `instalaciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `tipo` enum('sintetica','natural','mixta','indoor','outdoor') DEFAULT 'sintetica',
  `descripcion` text DEFAULT NULL,
  `capacidad` int(11) NOT NULL DEFAULT 10,
  `dimensiones` varchar(50) DEFAULT NULL,
  `tipo_superficie` varchar(100) DEFAULT NULL,
  `tiene_iluminacion` tinyint(1) DEFAULT 1,
  `tiene_estacionamiento` tinyint(1) DEFAULT 0,
  `tiene_casilleros` tinyint(1) DEFAULT 0,
  `esta_activo` tinyint(1) DEFAULT 1,
  `actualizado_por` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jugadores`
--

CREATE TABLE `jugadores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `equipo_id` bigint(20) UNSIGNED NOT NULL,
  `nombre_completo` varchar(255) NOT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `posicion` varchar(50) DEFAULT NULL,
  `numero_camiseta` tinyint(4) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `es_capitan` tinyint(1) DEFAULT 0,
  `esta_activo` tinyint(1) DEFAULT 1,
  `actualizado_por` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tipo` varchar(100) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `mensaje` text NOT NULL,
  `datos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos`)),
  `leido_en` timestamp NULL DEFAULT NULL,
  `esta_activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partidos`
--

CREATE TABLE `partidos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `torneo_id` bigint(20) UNSIGNED NOT NULL,
  `equipo_local_id` bigint(20) UNSIGNED DEFAULT NULL,
  `equipo_visitante_id` bigint(20) UNSIGNED DEFAULT NULL,
  `fecha_partido` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `instalacion_id` bigint(20) UNSIGNED NOT NULL,
  `numero_jornada` int(11) DEFAULT NULL,
  `nombre_grupo` varchar(50) DEFAULT NULL,
  `marcador_local` tinyint(4) DEFAULT NULL,
  `marcador_visitante` tinyint(4) DEFAULT NULL,
  `estado` enum('programado','en_curso','finalizado','aplazado','cancelado') DEFAULT 'programado',
  `reservado_automatico` tinyint(1) DEFAULT 0 COMMENT '1: Cancha reservada automáticamente al crear el partido',
  `arbitro` varchar(255) DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `esta_activo` tinyint(1) DEFAULT 1,
  `actualizado_por` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planes_tarifas`
--

CREATE TABLE `planes_tarifas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `instalacion_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_dia` enum('semana','fin_semana','feriado') DEFAULT 'semana',
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `moneda` varchar(3) DEFAULT 'PEN',
  `esta_activo` tinyint(1) DEFAULT 1,
  `actualizado_por` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros_actividad`
--

CREATE TABLE `registros_actividad` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `accion` varchar(100) NOT NULL,
  `nombre_tabla` varchar(100) DEFAULT NULL,
  `registro_id` bigint(20) UNSIGNED DEFAULT NULL,
  `valores_anteriores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`valores_anteriores`)),
  `valores_nuevos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`valores_nuevos`)),
  `direccion_ip` varchar(45) DEFAULT NULL,
  `agente_usuario` text DEFAULT NULL,
  `esta_activo` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `codigo_reserva` varchar(50) NOT NULL,
  `instalacion_id` bigint(20) UNSIGNED NOT NULL,
  `nombre_cliente` varchar(255) NOT NULL,
  `telefono_cliente` varchar(20) DEFAULT NULL,
  `email_cliente` varchar(255) DEFAULT NULL,
  `dni_cliente` varchar(20) DEFAULT NULL,
  `fecha_reserva` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `total_horas` decimal(4,2) NOT NULL,
  `precio_total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','confirmada','cancelada','completada') DEFAULT 'confirmada',
  `tipo_reserva` enum('manual','torneo','bloqueo') DEFAULT 'manual' COMMENT 'Origen de la reserva',
  `partido_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Vínculo con partido de torneo',
  `torneo_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Vínculo con torneo',
  `estado_pago` enum('pendiente','pagado','reembolsado') DEFAULT 'pendiente',
  `metodo_pago` enum('efectivo','transferencia','tarjeta','otro') DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `esta_activo` tinyint(1) DEFAULT 1,
  `actualizado_por` bigint(20) UNSIGNED DEFAULT NULL,
  `creado_por` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesiones`
--

CREATE TABLE `sesiones` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tokens_restablecimiento`
--

CREATE TABLE `tokens_restablecimiento` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `torneos`
--

CREATE TABLE `torneos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `instalacion_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_torneo` enum('liga','eliminacion_directa','fase_grupos') DEFAULT 'liga',
  `tipo_deporte` varchar(50) DEFAULT 'futbol',
  `categoria` enum('libre','sub20','sub18','sub16','femenino','mixto') DEFAULT 'libre',
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `inscripcion_inicio` date NOT NULL,
  `inscripcion_fin` date NOT NULL,
  `max_equipos` int(11) NOT NULL,
  `min_equipos` int(11) NOT NULL,
  `jugadores_por_equipo` int(11) NOT NULL,
  `costo_inscripcion` decimal(10,2) DEFAULT NULL,
  `descripcion_premios` text DEFAULT NULL,
  `estado` enum('borrador','inscripciones_abiertas','en_curso','finalizado','cancelado') DEFAULT 'borrador',
  `reglas` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `organizador_asignado_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'ID del organizador asignado al torneo',
  `esta_activo` tinyint(1) DEFAULT 1,
  `actualizado_por` bigint(20) UNSIGNED DEFAULT NULL,
  `creado_por` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verificado_en` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','organizador','trabajador','staff','viewer') DEFAULT 'viewer' COMMENT 'Rol del usuario en el sistema',
  `telefono` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `esta_activo` tinyint(1) DEFAULT 1 COMMENT '1: Activo, 0: Inactivo (Bloqueado)',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_partidos_publicos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_partidos_publicos` (
`id` bigint(20) unsigned
,`fecha_partido` date
,`hora_inicio` time
,`estado` enum('programado','en_curso','finalizado','aplazado','cancelado')
,`nombre_torneo` varchar(255)
,`categoria_torneo` enum('libre','sub20','sub18','sub16','femenino','mixto')
,`nombre_equipo_local` varchar(255)
,`nombre_equipo_visitante` varchar(255)
,`nombre_instalacion` varchar(255)
,`reservado_automatico` tinyint(1)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_reservas_diarias`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_reservas_diarias` (
`id` bigint(20) unsigned
,`codigo_reserva` varchar(50)
,`nombre_cliente` varchar(255)
,`telefono_cliente` varchar(20)
,`fecha_reserva` date
,`hora_inicio` time
,`hora_fin` time
,`total_horas` decimal(4,2)
,`precio_total` decimal(10,2)
,`estado` enum('pendiente','confirmada','cancelada','completada')
,`tipo_reserva` enum('manual','torneo','bloqueo')
,`nombre_instalacion` varchar(255)
,`nombre_torneo` varchar(255)
,`nombre_equipo_local` varchar(255)
,`nombre_equipo_visitante` varchar(255)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_partidos_publicos`
--
DROP TABLE IF EXISTS `vista_partidos_publicos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_partidos_publicos`  AS SELECT `p`.`id` AS `id`, `p`.`fecha_partido` AS `fecha_partido`, `p`.`hora_inicio` AS `hora_inicio`, `p`.`estado` AS `estado`, `t`.`nombre` AS `nombre_torneo`, `t`.`categoria` AS `categoria_torneo`, `el`.`nombre` AS `nombre_equipo_local`, `ev`.`nombre` AS `nombre_equipo_visitante`, `i`.`nombre` AS `nombre_instalacion`, `p`.`reservado_automatico` AS `reservado_automatico` FROM ((((`partidos` `p` join `torneos` `t` on(`p`.`torneo_id` = `t`.`id`)) join `instalaciones` `i` on(`p`.`instalacion_id` = `i`.`id`)) left join `equipos` `el` on(`p`.`equipo_local_id` = `el`.`id`)) left join `equipos` `ev` on(`p`.`equipo_visitante_id` = `ev`.`id`)) WHERE `p`.`esta_activo` = 1 AND `t`.`esta_activo` = 1 ORDER BY `p`.`fecha_partido` ASC, `p`.`hora_inicio` ASC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_reservas_diarias`
--
DROP TABLE IF EXISTS `vista_reservas_diarias`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_reservas_diarias`  AS SELECT `r`.`id` AS `id`, `r`.`codigo_reserva` AS `codigo_reserva`, `r`.`nombre_cliente` AS `nombre_cliente`, `r`.`telefono_cliente` AS `telefono_cliente`, `r`.`fecha_reserva` AS `fecha_reserva`, `r`.`hora_inicio` AS `hora_inicio`, `r`.`hora_fin` AS `hora_fin`, `r`.`total_horas` AS `total_horas`, `r`.`precio_total` AS `precio_total`, `r`.`estado` AS `estado`, `r`.`tipo_reserva` AS `tipo_reserva`, `i`.`nombre` AS `nombre_instalacion`, `t`.`nombre` AS `nombre_torneo`, `el`.`nombre` AS `nombre_equipo_local`, `ev`.`nombre` AS `nombre_equipo_visitante` FROM (((((`reservas` `r` join `instalaciones` `i` on(`r`.`instalacion_id` = `i`.`id`)) left join `torneos` `t` on(`r`.`torneo_id` = `t`.`id`)) left join `partidos` `p` on(`r`.`partido_id` = `p`.`id`)) left join `equipos` `el` on(`p`.`equipo_local_id` = `el`.`id`)) left join `equipos` `ev` on(`p`.`equipo_visitante_id` = `ev`.`id`)) WHERE `r`.`fecha_reserva` = curdate() AND `r`.`esta_activo` = 1 ORDER BY `r`.`hora_inicio` ASC ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `anuncios`
--
ALTER TABLE `anuncios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `creado_por` (`creado_por`),
  ADD KEY `actualizado_por` (`actualizado_por`),
  ADD KEY `anuncios_publicado_index` (`esta_publicado`),
  ADD KEY `anuncios_publico_index` (`mostrar_en_publico`);

--
-- Indices de la tabla `bloqueos_cancha`
--
ALTER TABLE `bloqueos_cancha`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instalacion_id` (`instalacion_id`),
  ADD KEY `creado_por` (`creado_por`),
  ADD KEY `actualizado_por` (`actualizado_por`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clave` (`clave`),
  ADD KEY `actualizado_por` (`actualizado_por`);

--
-- Indices de la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_equipo_nombre` (`torneo_id`,`nombre`),
  ADD KEY `actualizado_por` (`actualizado_por`),
  ADD KEY `idx_equipos_torneo` (`torneo_id`,`estado`);

--
-- Indices de la tabla `estadisticas_diarias`
--
ALTER TABLE `estadisticas_diarias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_estadistica_diaria` (`fecha_estadistica`,`instalacion_id`),
  ADD KEY `instalacion_id` (`instalacion_id`),
  ADD KEY `actualizado_por` (`actualizado_por`);

--
-- Indices de la tabla `galeria_imagenes`
--
ALTER TABLE `galeria_imagenes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subido_por` (`subido_por`),
  ADD KEY `actualizado_por` (`actualizado_por`);

--
-- Indices de la tabla `historial_reservas`
--
ALTER TABLE `historial_reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reserva_id` (`reserva_id`),
  ADD KEY `cambiado_por` (`cambiado_por`);

--
-- Indices de la tabla `horarios_atencion`
--
ALTER TABLE `horarios_atencion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instalacion_id` (`instalacion_id`),
  ADD KEY `actualizado_por` (`actualizado_por`);

--
-- Indices de la tabla `informacion_contacto`
--
ALTER TABLE `informacion_contacto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `actualizado_por` (`actualizado_por`);

--
-- Indices de la tabla `instalaciones`
--
ALTER TABLE `instalaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `actualizado_por` (`actualizado_por`);

--
-- Indices de la tabla `jugadores`
--
ALTER TABLE `jugadores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipo_id` (`equipo_id`),
  ADD KEY `actualizado_por` (`actualizado_por`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `partidos`
--
ALTER TABLE `partidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipo_local_id` (`equipo_local_id`),
  ADD KEY `equipo_visitante_id` (`equipo_visitante_id`),
  ADD KEY `instalacion_id` (`instalacion_id`),
  ADD KEY `actualizado_por` (`actualizado_por`),
  ADD KEY `idx_partidos_torneo_fecha` (`torneo_id`,`fecha_partido`);

--
-- Indices de la tabla `planes_tarifas`
--
ALTER TABLE `planes_tarifas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instalacion_id` (`instalacion_id`),
  ADD KEY `actualizado_por` (`actualizado_por`);

--
-- Indices de la tabla `registros_actividad`
--
ALTER TABLE `registros_actividad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_reserva` (`codigo_reserva`),
  ADD KEY `partido_id` (`partido_id`),
  ADD KEY `torneo_id` (`torneo_id`),
  ADD KEY `creado_por` (`creado_por`),
  ADD KEY `actualizado_por` (`actualizado_por`),
  ADD KEY `reservas_fecha_index` (`fecha_reserva`),
  ADD KEY `reservas_estado_index` (`estado`),
  ADD KEY `reservas_tipo_index` (`tipo_reserva`),
  ADD KEY `idx_reservas_instalacion_fecha` (`instalacion_id`,`fecha_reserva`),
  ADD KEY `idx_reservas_cliente` (`telefono_cliente`,`email_cliente`);

--
-- Indices de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sesiones_usuario_id_index` (`user_id`),
  ADD KEY `sesiones_ultima_actividad_index` (`last_activity`);

--
-- Indices de la tabla `tokens_restablecimiento`
--
ALTER TABLE `tokens_restablecimiento`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `torneos`
--
ALTER TABLE `torneos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instalacion_id` (`instalacion_id`),
  ADD KEY `creado_por` (`creado_por`),
  ADD KEY `actualizado_por` (`actualizado_por`),
  ADD KEY `idx_torneos_organizador` (`organizador_asignado_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuarios_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `anuncios`
--
ALTER TABLE `anuncios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bloqueos_cancha`
--
ALTER TABLE `bloqueos_cancha`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estadisticas_diarias`
--
ALTER TABLE `estadisticas_diarias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `galeria_imagenes`
--
ALTER TABLE `galeria_imagenes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_reservas`
--
ALTER TABLE `historial_reservas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horarios_atencion`
--
ALTER TABLE `horarios_atencion`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `informacion_contacto`
--
ALTER TABLE `informacion_contacto`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `instalaciones`
--
ALTER TABLE `instalaciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jugadores`
--
ALTER TABLE `jugadores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `partidos`
--
ALTER TABLE `partidos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `planes_tarifas`
--
ALTER TABLE `planes_tarifas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `registros_actividad`
--
ALTER TABLE `registros_actividad`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `torneos`
--
ALTER TABLE `torneos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `anuncios`
--
ALTER TABLE `anuncios`
  ADD CONSTRAINT `anuncios_ibfk_1` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `anuncios_ibfk_2` FOREIGN KEY (`actualizado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `bloqueos_cancha`
--
ALTER TABLE `bloqueos_cancha`
  ADD CONSTRAINT `bloqueos_cancha_ibfk_1` FOREIGN KEY (`instalacion_id`) REFERENCES `instalaciones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bloqueos_cancha_ibfk_2` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bloqueos_cancha_ibfk_3` FOREIGN KEY (`actualizado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD CONSTRAINT `configuracion_ibfk_1` FOREIGN KEY (`actualizado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD CONSTRAINT `equipos_ibfk_1` FOREIGN KEY (`torneo_id`) REFERENCES `torneos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `equipos_ibfk_2` FOREIGN KEY (`actualizado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `estadisticas_diarias`
--
ALTER TABLE `estadisticas_diarias`
  ADD CONSTRAINT `estadisticas_diarias_ibfk_1` FOREIGN KEY (`instalacion_id`) REFERENCES `instalaciones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `estadisticas_diarias_ibfk_2` FOREIGN KEY (`actualizado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `galeria_imagenes`
--
ALTER TABLE `galeria_imagenes`
  ADD CONSTRAINT `galeria_imagenes_ibfk_1` FOREIGN KEY (`subido_por`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `galeria_imagenes_ibfk_2` FOREIGN KEY (`actualizado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `historial_reservas`
--
ALTER TABLE `historial_reservas`
  ADD CONSTRAINT `historial_reservas_ibfk_1` FOREIGN KEY (`reserva_id`) REFERENCES `reservas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `historial_reservas_ibfk_2` FOREIGN KEY (`cambiado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `horarios_atencion`
--
ALTER TABLE `horarios_atencion`
  ADD CONSTRAINT `horarios_atencion_ibfk_1` FOREIGN KEY (`instalacion_id`) REFERENCES `instalaciones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `horarios_atencion_ibfk_2` FOREIGN KEY (`actualizado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `informacion_contacto`
--
ALTER TABLE `informacion_contacto`
  ADD CONSTRAINT `informacion_contacto_ibfk_1` FOREIGN KEY (`actualizado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `instalaciones`
--
ALTER TABLE `instalaciones`
  ADD CONSTRAINT `instalaciones_ibfk_1` FOREIGN KEY (`actualizado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `jugadores`
--
ALTER TABLE `jugadores`
  ADD CONSTRAINT `jugadores_ibfk_1` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jugadores_ibfk_2` FOREIGN KEY (`actualizado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `partidos`
--
ALTER TABLE `partidos`
  ADD CONSTRAINT `partidos_ibfk_1` FOREIGN KEY (`torneo_id`) REFERENCES `torneos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `partidos_ibfk_2` FOREIGN KEY (`equipo_local_id`) REFERENCES `equipos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `partidos_ibfk_3` FOREIGN KEY (`equipo_visitante_id`) REFERENCES `equipos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `partidos_ibfk_4` FOREIGN KEY (`instalacion_id`) REFERENCES `instalaciones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `partidos_ibfk_5` FOREIGN KEY (`actualizado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `planes_tarifas`
--
ALTER TABLE `planes_tarifas`
  ADD CONSTRAINT `planes_tarifas_ibfk_1` FOREIGN KEY (`instalacion_id`) REFERENCES `instalaciones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `planes_tarifas_ibfk_2` FOREIGN KEY (`actualizado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `registros_actividad`
--
ALTER TABLE `registros_actividad`
  ADD CONSTRAINT `registros_actividad_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`instalacion_id`) REFERENCES `instalaciones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`partido_id`) REFERENCES `partidos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reservas_ibfk_3` FOREIGN KEY (`torneo_id`) REFERENCES `torneos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reservas_ibfk_4` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reservas_ibfk_5` FOREIGN KEY (`actualizado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `torneos`
--
ALTER TABLE `torneos`
  ADD CONSTRAINT `torneos_ibfk_1` FOREIGN KEY (`instalacion_id`) REFERENCES `instalaciones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `torneos_ibfk_2` FOREIGN KEY (`organizador_asignado_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `torneos_ibfk_3` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `torneos_ibfk_4` FOREIGN KEY (`actualizado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

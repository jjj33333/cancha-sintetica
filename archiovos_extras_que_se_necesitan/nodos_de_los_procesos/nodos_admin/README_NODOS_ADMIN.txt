================================================================================
GUÍA DE USO: NODOS DE PROCESOS - PANEL DE ADMINISTRACIÓN
SISTEMA DE GESTIÓN DE CANCHA SINTÉTICA DEPORTIVA
================================================================================
Fecha: Octubre 2026
Responsable: Josue
Formato: Lenguaje DOT (Graphviz)
Visualizador: Graphviz Online (https://dreampuf.github.io/GraphvizOnline/)
Regla de diseño estandarizada:
- Forma: Exclusivamente ÓVALOS / CÍRCULOS (shape=oval)
- Borde: Color azul (#1D4ED8)
- Fondo y relleno: Color blanco (#FFFFFF)
- Texto: Color negro (#000000)
- Enfoque: Procesos funcionales y comprensibles (sin términos técnicos de código)
- Título: Estandarizado sin el prefijo 'Diagrama de Proceso:'
================================================================================

ÍNDICE DE ARCHIVOS POR MÓDULO ADMINISTRATIVO:
--------------------------------------------------------------------------------
01. 01_admin_dashboard_principal.txt
    - Resumen del estado de la cancha, accesos rápidos a reservas, calendario
      y torneos, navegación por menú lateral y cierre de sesión.

02. 02_admin_gestion_reservas.txt
    - Tabla de reservas (ordenadas recientes primero), creación de nueva reserva
      (verificación de horario libre), consulta de ficha, edición y anulación.

03. 03_admin_calendario_ocupacion.txt
    - Vista mensual de eventos, navegación entre meses, cronograma detallado
      de horas por día, creación rápida sobre hora libre y detalle de eventos.

04. 04_admin_gestion_torneos.txt
    - Registro de nuevos campeonatos por categoría, validación de fechas,
      consulta de equipos y partidos, modificación de estados y eliminación.

05. 05_admin_gestion_equipos_jugadores.txt
    - Alta de clubes y delegados, administración de nóminas de jugadores,
      asignación de dorsales únicos, posiciones en cancha y modificaciones.

06. 06_admin_gestion_anuncios.txt
    - Publicación de noticias, alertas y promociones, clasificación por prioridad,
      carga de afiches, conmutación de visibilidad pública (toggle) y borrado.

07. 07_admin_bloqueos_cancha.txt
    - Inhabilitación de horas por mantenimiento, clima o eventos cívicos,
      detección de reservas en conflicto y liberación de horarios bloqueados.

08. 08_admin_gestion_tarifas.txt
    - Definición de precios por hora (diurno, nocturno con luz), tarifas para
      fines de semana, actualización de montos y habilitación de tarifas activas.

09. 09_admin_gestion_instalaciones.txt
    - Control de la ficha técnica de la cancha, estado del césped sintético,
      capacidad, servicios sanitarios, iluminación y reglas de uso.

10. 10_admin_galeria_imagenes.txt
    - Subida y categorización de fotografías de instalaciones y torneos,
      validación de imágenes y publicación en el portal público.

11. 11_admin_estadisticas_reportes.txt
    - Métricas financieras de ingresos, porcentaje de ocupación de la cancha,
      horarios de mayor demanda y filtros temporales para análisis gerencial.

12. 12_admin_historial_auditoria.txt
    - Bitácora cronológica de acciones de los colaboradores, trazabilidad
      de cambios en reservas y supervisión de seguridad del sistema.

13. 13_admin_gestion_usuarios.txt
    - Creación de cuentas para Administradores, Trabajadores y Organizadores,
      asignación de roles, validación de correo único, edición y suspensión.

14. 14_admin_configuracion_respaldos.txt
    - Ajustes generales del negocio y generación de copias de seguridad
      de la base de datos, descarga segura y limpieza de respaldos viejos.

15. 15_admin_informacion_contacto.txt
    - Actualización de teléfonos oficiales, WhatsApp, correo, páginas
      de Facebook e Instagram, dirección física y logotipo institucional.

--------------------------------------------------------------------------------
PASOS PARA VISUALIZAR EN GRAPHVIZ ONLINE:
--------------------------------------------------------------------------------
1. Ingresa en tu navegador a: https://dreampuf.github.io/GraphvizOnline/
2. Abre cualquiera de los 15 archivos .txt de esta carpeta.
3. Copia todo su contenido (Ctrl + A, Ctrl + C).
4. En Graphviz Online, borra el contenido de la izquierda y pega el texto (Ctrl + V).
5. El diagrama se dibujará al instante con todos sus óvalos estilizados y flechas.
6. Puedes exportarlo como imagen PNG o archivo vectorial SVG.
================================================================================

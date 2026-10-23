================================================================================
GUÍA DE USO: NODOS DE PROCESOS EN GRAPHVIZ ONLINE
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
- Enfoque: Procesos funcionales y comprensibles (sin código técnico)
================================================================================

ÍNDICE DE ARCHIVOS DE NODOS POR MÓDULO:
--------------------------------------------------------------------------------
1. 01_proceso_inicio_sesion_autenticacion.txt
   - Detalle: Apertura de ventana flotante de login, llenado de datos de acceso,
     validación de campos, comprobación en base de datos, mensajes de error
     amigables y redirección según rol (Panel Administrador, Panel Trabajador
     o Panel Organizador), además del cierre seguro de sesión.

2. 02_proceso_modulo_publico_inicio.txt
   - Detalle: Portal principal (Home), portada de bienvenida, botones de acción
     (Horarios, WhatsApp, Torneos), comodidades y servicios de la cancha,
     horarios de atención, galería de fotos y enlaces de pie de página.

3. 03_proceso_modulo_publico_horarios.txt
   - Detalle: Calendario semanal interactivo, navegación entre semanas previas
     y siguientes, selección de día, bloques de 1 hora clasificados en
     Disponible, Ocupado o Pasado, y canalización de reservas por WhatsApp
     o llamada telefónica.

4. 04_proceso_modulo_publico_torneos.txt
   - Detalle: Pestañas de torneos deportivos:
     * Torneos Activos: Categorías, cupos disponibles y organizador a cargo.
     * Calendario de Partidos: Encuentros programados, horarios y marcadores.
     * Tabla de Posiciones: Clasificación general, puntos y líderes.

5. 05_proceso_modulo_publico_anuncios.txt
   - Detalle: Listado de comunicados oficiales ordenados por importancia
     (Urgente, Importante, General), visualización de afiches o fotos promocionales,
     lectura de contenido y registro de visualizaciones.

6. 06_proceso_modulo_publico_contacto.txt
   - Detalle: Canales de comunicación directa: llamada telefónica, mensajería
     por WhatsApp, correo electrónico, perfiles en redes sociales (Facebook
     e Instagram) y ubicación geográfica en Google Maps con GPS.

--------------------------------------------------------------------------------
PASOS PARA VISUALIZAR Y EXPORTAR EN GRAPHVIZ ONLINE:
--------------------------------------------------------------------------------
1. Abre tu navegador y accede a:
   https://dreampuf.github.io/GraphvizOnline/

2. Abre cualquiera de los 6 archivos .txt en tu editor o bloc de notas.

3. Copia todo su contenido (Ctrl + A, Ctrl + C).

4. En Graphviz Online, borra el texto de la izquierda y pega tu código (Ctrl + V).

5. El diagrama se generará al instante con sus óvalos de borde azul, fondo blanco
   y texto negro.

6. Puedes descargar el resultado en imagen PNG o formato SVG desde los botones
   superiores de la herramienta.
================================================================================

================================================================================
GUÍA DE USO: NODOS DE PROCESOS - PANEL DEL TRABAJADOR
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

ÍNDICE DE ARCHIVOS POR MÓDULO DEL TRABAJADOR:
--------------------------------------------------------------------------------
01. 01_trabajador_dashboard_principal.txt
    - Control del turno operativo, accesos rápidos a reservas y calendario,
      visualización de partidos agendados y cierre seguro de turno.

02. 02_trabajador_gestion_reservas.txt
    - Lista de reservas recientes con filtros, registro simplificado (Cancha Principal
      y efectivo por defecto, teléfono opcional, precio Q100 predeterminado),
      ver comprobante, editar y anular.

03. 03_trabajador_calendario_ocupacion.txt
    - Consulta de cuadrícula mensual, inspección de horas del día (07:00 a 23:00),
      creación de reserva rápida sobre hora libre y visualización de horas ocupadas.

--------------------------------------------------------------------------------
PASOS PARA VISUALIZAR EN GRAPHVIZ ONLINE:
--------------------------------------------------------------------------------
1. Ingresa en tu navegador a: https://dreampuf.github.io/GraphvizOnline/
2. Abre cualquiera de los 3 archivos .txt de esta carpeta.
3. Copia todo su contenido (Ctrl + A, Ctrl + C).
4. En Graphviz Online, borra el contenido de la izquierda y pega el texto (Ctrl + V).
5. El diagrama se dibujará al instante con todos sus óvalos estilizados y flechas.
6. Puedes exportarlo como imagen PNG o archivo vectorial SVG.
================================================================================

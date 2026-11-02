==============================================================================
ÍNDICE DE DIAGRAMAS GRAPHVIZ (DOT) DEL SISTEMA DE CANCHA SINTÉTICA
Carpeta: archiovos_extras_que_se_necesitan\explicacion
==============================================================================

Todos los archivos en esta carpeta contienen código fuente en lenguaje DOT (Graphviz),
diseñado y optimizado para ser visualizado en herramientas en línea o visores locales:
- Visualizador recomendado en línea: https://dreampuf.github.io/GraphvizOnline/
- Visualizador interactivo alternativo: https://edotor.net/
- Extensión en VS Code: "Graphviz (dot) language support for Visual Studio Code"

------------------------------------------------------------------------------
LISTA DE ARCHIVOS Y DIAGRAMAS GENERADOS:
------------------------------------------------------------------------------

1. 01_arquitectura_general_del_proyecto.txt
   - Descripción: Diagrama de arquitectura global del sistema. Muestra los 4 perfiles
     de usuario (Público, Admin, Organizador, Trabajador), la capa de presentación
     Blade + Tailwind + Alpine, el servidor web y HTTP Kernel, los grupos de
     controladores, los modelos Eloquent y la persistencia en base de datos MySQL.

2. 02_funcionamiento_modulo_reservas.txt
   - Descripción: Diagrama paso a paso del flujo del módulo de reservas. Detalla la
     interacción entre vistas Blade (create.blade.php), componentes Alpine.js,
     enrutamiento (routes/web.php), middleware CSRF/Auth, validación FormRequest
     (StoreReservaTrabajadorRequest), lógica del controlador (ReservasController),
     modelo Eloquent (Reserva.php), consultas SQL (INSERT INTO reservas), auditoría,
     y actualización en tiempo real del calendario público y operativo.

3. 03_diagrama_grafico_de_requisitos.txt
   - Descripción: Diagrama visual y estructurado de los requisitos del software.
     Agrupa y desglosa los 12 Requisitos Funcionales (RF-01 al RF-12), los 5 Requisitos
     No Funcionales (RNF-01 al RNF-05: Usabilidad, Seguridad, Rendimiento, UI y
     Arquitectura), y las exclusiones explícitas del alcance del proyecto.

4. 04_estructura_carpetas_y_logica.txt
   - Descripción: Diagrama de la estructura de directorios del proyecto
     (app, routes, resources, database, public, config, storage, archiovos_extras).
     Explica qué contiene cada carpeta principal y la lógica de arquitectura detrás de
     esta organización (Patrón MVC, separación de responsabilidades por roles y seguridad).

5. 05_tecnologias_y_librerias.txt
   - Descripción: Diagrama del stack tecnológico integral con especificaciones y versiones:
     * Backend: PHP 8.2.12 (cli) + Laravel 10/11 + Composer 2.10.3
     * Base de Datos: SQL + MySQL / MariaDB (InnoDB, transacciones, PDO)
     * Frontend (Estructura): HTML5 + Blade (motor de plantillas)
     * Frontend (Estilos): CSS3 + Tailwind CSS v3.4 (Mobile-First)
     * Frontend (Interactividad): JavaScript + Alpine.js, Chart.js, FullCalendar,
       DataTables, Toastify, FilePond, Choices.js
     * Iconos: SVG + Heroicons
     * Entorno de Build: Node.js (v24.21.0), npm (11.19.0), Vite v5.0
     * Control de Versiones: Git

6. 06_narracion_del_proceso_diseno_desarrollo_implementacion.txt
   - Descripción: Diagrama narrativo del ciclo de vida del software (SDLC) dividido
     en sus 3 fases clave:
     * Fase 1 (Diseño): Entrevista con el dueño, especificación de requerimientos,
       diseño de arquitectura, modelo ER de base de datos y wireframes.
     * Fase 2 (Desarrollo): Configuración de entorno, migraciones y seeders idempotentes,
       autenticación y roles, CRUDs backend, maquetación Blade responsiva, interactividad
       y suite de pruebas automatizadas Dusk.
     * Fase 3 (Implementación): Optimización de caché para producción, despliegue en servidor,
       enlace simbólico de storage, pruebas de aceptación UAT y Go-Live.

7. 07_trazabilidad_cumplimiento_requisitos.txt
   - Descripción: Diagrama de matriz y flujo de trazabilidad que demuestra cómo se
     cumplen todos los requisitos especificados (RF-01 a RF-12 y RNF-01 a RNF-05),
     vinculando cada requerimiento con su componente de código fuente implementado, su caso
     de prueba automatizado en Laravel Dusk, y su estado de cumplimiento verificado al 100%.

8. 08_diagrama_cronograma_gantt_finalizado.txt
   - Descripción: Diagrama en Graphviz (DOT) del cronograma de Gantt del proyecto donde se
     indica que todas las 18 tareas (task1 a task18) y sus fases han finalizado al 100%,
     culminando en el hito de entrega oficial del proyecto (2026-10-21).

==============================================================================


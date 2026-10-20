<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ConfiguracionController extends Controller
{
    /**
     * Muestra la página de configuración.
     */
    public function index()
    {
        // Obtener todas las configuraciones agrupadas
        $configuraciones = Configuracion::activas()
            ->orderBy('grupo')
            ->orderBy('clave')
            ->get()
            ->groupBy('grupo');

        // Información de respaldos
        $ultimoRespaldo = $this->obtenerUltimoRespaldo();
        $respaldos = $this->listarRespaldos();

        return view('admin.configuracion.index', compact('configuraciones', 'ultimoRespaldo', 'respaldos'));
    }

    /**
     * Actualiza la configuración general.
     */
    public function update(Request $request)
    {
        $request->validate([
            'nombre_sitio' => ['required', 'string', 'max:255'],
            'zona_horaria' => ['required', 'string', 'max:100'],
            'moneda' => ['required', 'string', 'max:10'],
            'dias_maximos_anticipacion' => ['required', 'integer', 'min:1', 'max:365'],
            'horas_minimas_cancelar' => ['required', 'integer', 'min:1', 'max:168'],
            'email_contacto' => ['nullable', 'email', 'max:255'],
            'telefono_contacto' => ['nullable', 'string', 'max:50'],
            'mantenimiento_activo' => ['nullable', 'boolean'],
        ]);

        // Actualizar cada campo en la tabla configuracion
        Configuracion::actualizar('nombre_sitio', $request->nombre_sitio, 'texto', 'general', 'Nombre del sitio web', true);
        Configuracion::actualizar('zona_horaria', $request->zona_horaria, 'texto', 'general', 'Zona horaria del sistema');
        Configuracion::actualizar('moneda', $request->moneda, 'texto', 'general', 'Moneda principal del sistema');
        Configuracion::actualizar('dias_maximos_anticipacion', $request->dias_maximos_anticipacion, 'numero', 'reservas', 'Días máximos de anticipación para reservas');
        Configuracion::actualizar('horas_minimas_cancelar', $request->horas_minimas_cancelar, 'numero', 'reservas', 'Horas mínimas para cancelar una reserva');
        Configuracion::actualizar('email_contacto', $request->email_contacto, 'texto', 'contacto', 'Email de contacto público', true);
        Configuracion::actualizar('telefono_contacto', $request->telefono_contacto, 'texto', 'contacto', 'Teléfono de contacto público', true);
        Configuracion::actualizar('mantenimiento_activo', $request->boolean('mantenimiento_activo', false), 'booleano', 'sistema', 'Modo mantenimiento activo');

        return redirect()->route('admin.configuracion.index')
            ->with('success', 'Configuración actualizada exitosamente.');
    }

    /**
     * Genera un respaldo de la base de datos.
     */
    public function generarRespaldo()
    {
        try {
            $nombreArchivo = 'respaldo_' . Carbon::now()->format('Y_m_d_His') . '.sql';
            $rutaRespaldo = storage_path('app/respaldos/' . $nombreArchivo);

            // Crear directorio si no existe
            if (!is_dir(storage_path('app/respaldos'))) {
                mkdir(storage_path('app/respaldos'), 0755, true);
            }

            // Obtener credenciales de la BD
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port');
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');

            // Comando mysqldump
            $comando = "mysqldump --host={$host} --port={$port} --user={$username}";
            
            if (!empty($password)) {
                $comando .= " --password={$password}";
            }
            
            $comando .= " {$database} > {$rutaRespaldo}";

            // Ejecutar comando
            exec($comando, $salida, $codigoRetorno);

            if ($codigoRetorno === 0 && file_exists($rutaRespaldo)) {
                // Registrar en configuracion el último respaldo
                Configuracion::actualizar(
                    'ultimo_respaldo',
                    Carbon::now()->format('Y-m-d H:i:s'),
                    'texto',
                    'sistema',
                    'Fecha y hora del último respaldo'
                );

                return redirect()->route('admin.configuracion.index')
                    ->with('success', 'Respaldo generado exitosamente: ' . $nombreArchivo);
            } else {
                // Fallback: generar respaldo manual con Laravel
                return $this->generarRespaldoManual($nombreArchivo);
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.configuracion.index')
                ->with('error', 'Error al generar respaldo: ' . $e->getMessage());
        }
    }

    /**
     * Genera respaldo manual usando Laravel (fallback).
     */
    private function generarRespaldoManual($nombreArchivo)
    {
        try {
            $rutaRespaldo = storage_path('app/respaldos/' . $nombreArchivo);
            
            if (!is_dir(storage_path('app/respaldos'))) {
                mkdir(storage_path('app/respaldos'), 0755, true);
            }

            $archivo = fopen($rutaRespaldo, 'w');
            
            // Escribir header
            fwrite($archivo, "-- ============================================\n");
            fwrite($archivo, "-- RESPALDO DE BASE DE DATOS\n");
            fwrite($archivo, "-- Generado: " . Carbon::now()->format('Y-m-d H:i:s') . "\n");
            fwrite($archivo, "-- Base de datos: " . config('database.connections.mysql.database') . "\n");
            fwrite($archivo, "-- ============================================\n\n");

            // Obtener todas las tablas
            $tablas = DB::select('SHOW TABLES');
            $nombreColumna = 'Tables_in_' . config('database.connections.mysql.database');

            foreach ($tablas as $tabla) {
                $nombreTabla = $tabla->$nombreColumna;
                
                // Obtener estructura
                $estructura = DB::select("SHOW CREATE TABLE `{$nombreTabla}`");
                fwrite($archivo, "DROP TABLE IF EXISTS `{$nombreTabla}`;\n");
                fwrite($archivo, $estructura[0]->{'Create Table'} . ";\n\n");

                // Obtener datos
                $datos = DB::table($nombreTabla)->get();
                if ($datos->count() > 0) {
                    foreach ($datos as $fila) {
                        $valores = array_map(function($valor) {
                            if ($valor === null) return 'NULL';
                            if (is_numeric($valor)) return $valor;
                            return "'" . addslashes($valor) . "'";
                        }, (array) $fila);
                        
                        fwrite($archivo, "INSERT INTO `{$nombreTabla}` VALUES (" . implode(',', $valores) . ");\n");
                    }
                    fwrite($archivo, "\n");
                }
            }

            fclose($archivo);

            Configuracion::actualizar(
                'ultimo_respaldo',
                Carbon::now()->format('Y-m-d H:i:s'),
                'texto',
                'sistema',
                'Fecha y hora del último respaldo'
            );

            return redirect()->route('admin.configuracion.index')
                ->with('success', 'Respaldo generado exitosamente: ' . $nombreArchivo);
        } catch (\Exception $e) {
            return redirect()->route('admin.configuracion.index')
                ->with('error', 'Error al generar respaldo manual: ' . $e->getMessage());
        }
    }

    /**
     * Lista los respaldos disponibles.
     */
    private function listarRespaldos()
    {
        $rutaRespaldo = storage_path('app/respaldos');
        
        if (!is_dir($rutaRespaldo)) {
            return collect();
        }

        $archivos = glob($rutaRespaldo . '/*.sql');
        
        return collect($archivos)->map(function($archivo) {
            return [
                'nombre' => basename($archivo),
                'ruta' => $archivo,
                'tamano' => $this->formatearTamano(filesize($archivo)),
                'fecha' => Carbon::createFromTimestamp(filemtime($archivo))->format('d/m/Y H:i'),
            ];
        })->sortByDesc('fecha')->values();
    }

    /**
     * Obtiene el último respaldo.
     */
    private function obtenerUltimoRespaldo()
    {
        $fechaRespaldo = Configuracion::obtener('ultimo_respaldo');
        
        if ($fechaRespaldo) {
            return Carbon::parse($fechaRespaldo)->format('d/m/Y H:i');
        }
        
        return 'No hay respaldos registrados';
    }

    /**
     * Formatea el tamaño de archivo.
     */
    private function formatearTamano($bytes)
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }
        return number_format($bytes / 1024, 2) . ' KB';
    }

    /**
     * Descarga un respaldo específico.
     */
    public function descargarRespaldo($nombreArchivo)
    {
        $ruta = storage_path('app/respaldos/' . $nombreArchivo);
        
        if (!file_exists($ruta)) {
            return redirect()->route('admin.configuracion.index')
                ->with('error', 'El archivo de respaldo no existe.');
        }

        return response()->download($ruta);
    }

    /**
     * Elimina un respaldo.
     */
    public function eliminarRespaldo($nombreArchivo)
    {
        $ruta = storage_path('app/respaldos/' . $nombreArchivo);
        
        if (file_exists($ruta)) {
            unlink($ruta);
            return redirect()->route('admin.configuracion.index')
                ->with('success', 'Respaldo eliminado exitosamente.');
        }

        return redirect()->route('admin.configuracion.index')
            ->with('error', 'El archivo de respaldo no existe.');
    }
}
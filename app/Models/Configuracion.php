<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Configuracion extends Model
{
    protected $table = 'configuracion';

    protected $fillable = [
        'clave',
        'valor',
        'tipo',
        'grupo',
        'descripcion',
        'es_publico',
        'esta_activo',
        'actualizado_por',
    ];

    protected $casts = [
        'es_publico' => 'boolean',
        'esta_activo' => 'boolean',
    ];

    // Relaciones
    public function actualizadoPorUsuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'actualizado_por');
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('esta_activo', true);
    }

    public function scopeDeGrupo($query, $grupo)
    {
        return $query->where('grupo', $grupo);
    }

    public function scopePublicas($query)
    {
        return $query->where('es_publico', true);
    }

    // Método estático para obtener un valor por clave
    public static function obtener($clave, $default = null)
    {
        $config = self::where('clave', $clave)->where('esta_activo', true)->first();
        return $config ? $config->valor : $default;
    }

    // Método estático para actualizar o crear un valor
    public static function actualizar($clave, $valor, $tipo = 'texto', $grupo = 'general', $descripcion = null, $esPublico = false)
    {
        $config = self::where('clave', $clave)->first();
        
        if ($config) {
            $config->update([
                'valor' => $valor,
                'tipo' => $tipo,
                'grupo' => $grupo,
                'descripcion' => $descripcion,
                'es_publico' => $esPublico,
                'actualizado_por' => auth()->id(),
            ]);
        } else {
            self::create([
                'clave' => $clave,
                'valor' => $valor,
                'tipo' => $tipo,
                'grupo' => $grupo,
                'descripcion' => $descripcion,
                'es_publico' => $esPublico,
                'esta_activo' => true,
                'actualizado_por' => auth()->id(),
            ]);
        }
        
        return true;
    }
}
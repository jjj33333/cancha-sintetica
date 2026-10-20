<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partido extends Model
{
    protected $table = 'partidos';

    protected $fillable = [
        'torneo_id',
        'equipo_local_id',
        'equipo_visitante_id',
        'fecha_partido',
        'hora_inicio',
        'instalacion_id',
        'numero_jornada',
        'nombre_grupo',
        'marcador_local',
        'marcador_visitante',
        'estado',
        'reservado_automatico',
        'arbitro',
        'notas',
        'esta_activo',
        'actualizado_por',
    ];

    protected $casts = [
        'fecha_partido' => 'date',
        'esta_activo' => 'boolean',
        'reservado_automatico' => 'boolean',
    ];

    public function torneo(): BelongsTo
    {
        return $this->belongsTo(Torneo::class);
    }

    public function equipoLocal(): BelongsTo
    {
        return $this->belongsTo(Equipo::class, 'equipo_local_id');
    }

    public function equipoVisitante(): BelongsTo
    {
        return $this->belongsTo(Equipo::class, 'equipo_visitante_id');
    }

    public function instalacion(): BelongsTo
    {
        return $this->belongsTo(Instalacion::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('esta_activo', true);
    }

    public function scopeProximos($query)
    {
        return $query->where('estado', 'programado')
                     ->where('fecha_partido', '>=', now()->toDateString())
                     ->orderBy('fecha_partido')
                     ->orderBy('hora_inicio');
    }

    public function scopeFinalizados($query)
    {
        return $query->where('estado', 'finalizado')
                     ->orderByDesc('fecha_partido');
    }

    public function getMarcadorAttribute(): string
    {
        if ($this->estado === 'finalizado' && $this->marcador_local !== null) {
            return "{$this->marcador_local} - {$this->marcador_visitante}";
        }
        return '-';
    }
}
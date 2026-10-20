<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BloqueoCancha extends Model
{
    protected $table = 'bloqueos_cancha';

    protected $fillable = [
        'instalacion_id',
        'fecha_bloqueo',
        'hora_inicio',
        'hora_fin',
        'motivo',
        'descripcion',
        'esta_activo',
        'actualizado_por',
        'creado_por',
    ];

    protected $casts = [
        'fecha_bloqueo' => 'date',
        'esta_activo' => 'boolean',
    ];

    // Relaciones
    public function instalacion(): BelongsTo
    {
        return $this->belongsTo(Instalacion::class);
    }

    public function creadoPorUsuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'creado_por');
    }

    public function actualizadoPorUsuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'actualizado_por');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('esta_activo', true);
    }

    public function scopeDeInstalacion($query, $instalacionId)
    {
        return $query->where('instalacion_id', $instalacionId);
    }

    public function scopeDeFecha($query, $fecha)
    {
        return $query->where('fecha_bloqueo', $fecha);
    }

    public function scopeDeRango($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_bloqueo', [$fechaInicio, $fechaFin]);
    }

    public function scopeDeMotivo($query, $motivo)
    {
        return $query->where('motivo', $motivo);
    }

    // Accessors
    public function getMotivoIconoAttribute(): string
    {
        return match ($this->motivo) {
            'mantenimiento' => '🔧',
            'evento' => '🎉',
            'feriado' => '🏖️',
            'otro' => '📋',
            default => '⚠️',
        };
    }

    public function getMotivoTextoAttribute(): string
    {
        return match ($this->motivo) {
            'mantenimiento' => 'Mantenimiento',
            'evento' => 'Evento',
            'feriado' => 'Feriado',
            'otro' => 'Otro',
            default => ucfirst($this->motivo),
        };
    }

    public function getMotivoBadgeAttribute(): string
    {
        return match ($this->motivo) {
            'mantenimiento' => 'bg-[#FEF3C7] text-[#92400E]',
            'evento' => 'bg-[#DBEAFE] text-[#1E40AF]',
            'feriado' => 'bg-[#D1FAE5] text-[#065F46]',
            'otro' => 'bg-[#F3F4F6] text-[#374151]',
            default => 'bg-gray-100 text-gray-600',
        };
    }

    public function getHorarioFormateadoAttribute(): string
    {
        $inicio = \Carbon\Carbon::parse($this->hora_inicio)->format('H:i');
        $fin = \Carbon\Carbon::parse($this->hora_fin)->format('H:i');
        return "{$inicio} - {$fin}";
    }

    public function getEsDiaCompletoAttribute(): bool
    {
        $inicio = \Carbon\Carbon::parse($this->hora_inicio)->format('H:i');
        $fin = \Carbon\Carbon::parse($this->hora_fin)->format('H:i');
        return $inicio === '00:00' && $fin === '23:59';
    }

    public function getEstadoAttribute(): string
    {
        if (!$this->esta_activo) {
            return 'cancelado';
        }
        
        $hoy = \Carbon\Carbon::today();
        $fechaBloqueo = \Carbon\Carbon::parse($this->fecha_bloqueo);
        
        if ($fechaBloqueo->isPast()) {
            return 'completado';
        }
        
        return 'programado';
    }

    public function getEstadoBadgeAttribute(): string
    {
        return match ($this->estado) {
            'programado' => 'bg-[#DBEAFE] text-[#1E40AF]',
            'completado' => 'bg-[#D1FAE5] text-[#065F46]',
            'cancelado' => 'bg-[#FEE2E2] text-[#991B1B]',
            default => 'bg-gray-100 text-gray-600',
        };
    }

    public function getEstadoTextoAttribute(): string
    {
        return match ($this->estado) {
            'programado' => 'Programado',
            'completado' => 'Completado',
            'cancelado' => 'Cancelado',
            default => ucfirst($this->estado),
        };
    }
}
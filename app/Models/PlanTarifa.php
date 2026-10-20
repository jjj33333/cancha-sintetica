<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanTarifa extends Model
{
    protected $table = 'planes_tarifas';

    protected $fillable = [
        'nombre',
        'instalacion_id',
        'tipo_dia',
        'hora_inicio',
        'hora_fin',
        'precio',
        'moneda',
        'esta_activo',
        'actualizado_por',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'esta_activo' => 'boolean',
    ];

    // Relaciones
    public function instalacion(): BelongsTo
    {
        return $this->belongsTo(Instalacion::class);
    }

    public function actualizadoPorUsuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'actualizado_por');
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('esta_activo', true);
    }

    public function scopeDeInstalacion($query, $instalacionId)
    {
        return $query->where('instalacion_id', $instalacionId);
    }

    public function scopeDeTipoDia($query, $tipoDia)
    {
        return $query->where('tipo_dia', $tipoDia);
    }

    // Accessors
    public function getTipoDiaTextoAttribute(): string
    {
        return match ($this->tipo_dia) {
            'semana' => 'Lun - Vie',
            'fin_semana' => 'Sáb - Dom',
            'feriado' => 'Feriado',
            default => ucfirst($this->tipo_dia),
        };
    }

    public function getTipoDiaBadgeAttribute(): string
    {
        return match ($this->tipo_dia) {
            'semana' => 'bg-[#DBEAFE] text-[#1E40AF]',
            'fin_semana' => 'bg-[#FEF3C7] text-[#92400E]',
            'feriado' => 'bg-[#FEE2E2] text-[#991B1B]',
            default => 'bg-gray-100 text-gray-600',
        };
    }

    public function getHorarioFormateadoAttribute(): string
    {
        return \Carbon\Carbon::parse($this->hora_inicio)->format('H:i') . ' - ' . \Carbon\Carbon::parse($this->hora_fin)->format('H:i');
    }

    public function getPrecioFormateadoAttribute(): string
    {
        $moneda = match ($this->moneda) {
            'GTQ', 'PEN' => 'Q.',
            'USD' => '$',
            'EUR' => '€',
            default => $this->moneda ?: 'Q.',
        };
        return $moneda . ' ' . number_format($this->precio, 2);
    }

    public function getDuracionHorasAttribute(): float
    {
        $inicio = \Carbon\Carbon::parse($this->hora_inicio);
        $fin = \Carbon\Carbon::parse($this->hora_fin);
        return $inicio->diffInHours($fin) + ($inicio->diffInMinutes($fin) % 60) / 60;
    }
}
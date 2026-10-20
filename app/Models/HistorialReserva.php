<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialReserva extends Model
{
    protected $table = 'historial_reservas';

    public $timestamps = false;

    protected $fillable = [
        'reserva_id',
        'estado_anterior',
        'estado_nuevo',
        'cambiado_por',
        'motivo_cambio',
        'esta_activo',
        'cambiado_en',
    ];

    protected $casts = [
        'esta_activo' => 'boolean',
        'cambiado_en' => 'datetime',
    ];

    // Relaciones
    public function reserva(): BelongsTo
    {
        return $this->belongsTo(Reserva::class, 'reserva_id');
    }

    public function cambiadoPorUsuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'cambiado_por');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('esta_activo', true);
    }

    public function scopeDeReserva($query, $reservaId)
    {
        return $query->where('reserva_id', $reservaId);
    }

    // Accessors
    public function getEstadoBadgeAttribute(string $estado): string
    {
        return match ($estado) {
            'pendiente' => 'bg-[#FEF3C7] text-[#92400E]',
            'confirmada' => 'bg-[#D1FAE5] text-[#065F46]',
            'cancelada' => 'bg-[#FEE2E2] text-[#991B1B]',
            'completada' => 'bg-[#DBEAFE] text-[#1E40AF]',
            default => 'bg-gray-100 text-gray-600',
        };
    }

    public function getFechaFormateadaAttribute(): string
    {
        return $this->cambiado_en?->format('d/m/Y H:i') ?? '-';
    }
}
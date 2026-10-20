<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model
{
    protected $table = 'reservas';

    protected $fillable = [
        'codigo_reserva',
        'instalacion_id',
        'nombre_cliente',
        'telefono_cliente',
        'email_cliente',
        'dni_cliente',
        'fecha_reserva',
        'hora_inicio',
        'hora_fin',
        'total_horas',
        'precio_total',
        'estado',
        'tipo_reserva',
        'partido_id',
        'torneo_id',
        'estado_pago',
        'metodo_pago',
        'notas',
        'esta_activo',
        'actualizado_por',
        'creado_por',
    ];

    protected $casts = [
        'fecha_reserva' => 'date',
        'esta_activo' => 'boolean',
        'total_horas' => 'decimal:2',
        'precio_total' => 'decimal:2',
    ];

    public function instalacion(): BelongsTo
    {
        return $this->belongsTo(Instalacion::class);
    }

    public function torneo(): BelongsTo
    {
        return $this->belongsTo(Torneo::class);
    }

    public function partido(): BelongsTo
    {
        return $this->belongsTo(Partido::class);
    }

    public function creadoPorUsuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'creado_por');
    }

    public function scopeActivas($query)
    {
        return $query->where('esta_activo', true);
    }

    public function scopeConfirmadas($query)
    {
        return $query->whereIn('estado', ['confirmada', 'pendiente']);
    }

    public function scopeDeFecha($query, $fecha)
    {
        return $query->where('fecha_reserva', $fecha);
    }
}
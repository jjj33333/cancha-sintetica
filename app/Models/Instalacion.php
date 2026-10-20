<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Instalacion extends Model
{
    protected $table = 'instalaciones';

    protected $fillable = [
        'nombre',
        'tipo',
        'descripcion',
        'capacidad',
        'dimensiones',
        'tipo_superficie',
        'tiene_iluminacion',
        'tiene_estacionamiento',
        'tiene_casilleros',
        'esta_activo',
        'actualizado_por',
    ];

    protected $casts = [
        'tiene_iluminacion' => 'boolean',
        'tiene_estacionamiento' => 'boolean',
        'tiene_casilleros' => 'boolean',
        'esta_activo' => 'boolean',
        'capacidad' => 'integer',
    ];

    // Relaciones
    public function actualizadoPorUsuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'actualizado_por');
    }

    public function planesTarifas(): HasMany
    {
        return $this->hasMany(PlanTarifa::class);
    }

    public function horariosAtencion(): HasMany
    {
        return $this->hasMany(HorarioAtencion::class);
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }

    public function torneos(): HasMany
    {
        return $this->hasMany(Torneo::class);
    }

    // Scope para obtener solo las activas
    public function scopeActivas($query)
    {
        return $query->where('esta_activo', true);
    }
}
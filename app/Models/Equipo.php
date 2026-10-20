<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipo extends Model
{
    protected $table = 'equipos';

    protected $fillable = [
        'torneo_id',
        'nombre',
        'nombre_corto',
        'nombre_capitan',
        'telefono_capitan',
        'email_capitan',
        'color_uniforme',
        'logo',
        'estado',
        'puntos',
        'partidos_jugados',
        'partidos_ganados',
        'partidos_empatados',
        'partidos_perdidos',
        'goles_favor',
        'goles_contra',
        'esta_activo',
        'actualizado_por',
    ];

    protected $casts = [
        'esta_activo' => 'boolean',
    ];

    public function torneo(): BelongsTo
    {
        return $this->belongsTo(Torneo::class);
    }

    public function jugadores(): HasMany
    {
        return $this->hasMany(Jugador::class);
    }

    public function getDiferenciaGolesAttribute(): int
    {
        return $this->goles_favor - $this->goles_contra;
    }

    public function getDiferenciaFormateadaAttribute(): string
    {
        $dif = $this->diferencia_goles;
        if ($dif > 0) return '+' . $dif;
        return (string) $dif;
    }

    public function scopeActivos($query)
    {
        return $query->where('esta_activo', true);
    }
}
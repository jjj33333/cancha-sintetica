<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Torneo extends Model
{
    protected $table = 'torneos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'instalacion_id',
        'tipo_torneo',
        'tipo_deporte',
        'categoria',
        'fecha_inicio',
        'fecha_fin',
        'inscripcion_inicio',
        'inscripcion_fin',
        'max_equipos',
        'min_equipos',
        'jugadores_por_equipo',
        'costo_inscripcion',
        'descripcion_premios',
        'estado',
        'reglas',
        'imagen',
        'organizador_asignado_id',
        'esta_activo',
        'actualizado_por',
        'creado_por',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'inscripcion_inicio' => 'date',
        'inscripcion_fin' => 'date',
        'esta_activo' => 'boolean',
        'costo_inscripcion' => 'decimal:2',
    ];

    public function instalacion(): BelongsTo
    {
        return $this->belongsTo(Instalacion::class);
    }

    public function organizador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'organizador_asignado_id');
    }

    public function creadoPorUsuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'creado_por');
    }

    public function actualizadoPorUsuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'actualizado_por');
    }

    public function equipos(): HasMany
    {
        return $this->hasMany(Equipo::class);
    }

    public function partidos(): HasMany
    {
        return $this->hasMany(Partido::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('esta_activo', true);
    }

    public function scopeEnCurso($query)
    {
        return $query->where('estado', 'en_curso');
    }

    public function scopePublicos($query)
    {
        return $query->whereIn('estado', ['inscripciones_abiertas', 'en_curso']);
    }

    public function getEstadoBadgeAttribute(): string
    {
        return match ($this->estado) {
            'en_curso' => 'bg-[#D1FAE5] text-[#065F46]',
            'inscripciones_abiertas' => 'bg-[#FEF3C7] text-[#92400E]',
            'borrador' => 'bg-[#F3F4F6] text-[#374151]',
            'finalizado' => 'bg-[#DBEAFE] text-[#1E40AF]',
            'cancelado' => 'bg-[#FEE2E2] text-[#991B1B]',
            default => 'bg-[#F3F4F6] text-[#374151]',
        };
    }

    public function getEstadoTextoAttribute(): string
    {
        return match ($this->estado) {
            'en_curso' => 'En curso',
            'inscripciones_abiertas' => 'Inscripciones abiertas',
            'borrador' => 'Próximamente',
            'finalizado' => 'Finalizado',
            'cancelado' => 'Cancelado',
            default => ucfirst($this->estado),
        };
    }
}
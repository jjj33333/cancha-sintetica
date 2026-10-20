<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HorarioAtencion extends Model
{
    protected $table = 'horarios_atencion';

    protected $fillable = [
        'instalacion_id',
        'dia_semana',
        'hora_apertura',
        'hora_cierre',
        'esta_activo',
        'actualizado_por',
    ];

    protected $casts = [
        'esta_activo' => 'boolean',
        'dia_semana' => 'integer',
    ];

    public function instalacion(): BelongsTo
    {
        return $this->belongsTo(Instalacion::class);
    }

    public function getDiaNombreAttribute(): string
    {
        $dias = [
            0 => 'Domingo',
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
        ];
        return $dias[$this->dia_semana] ?? 'Desconocido';
    }

    public function scopeActivos($query)
    {
        return $query->where('esta_activo', true);
    }

    public function scopeOrdenados($query)
    {
        return $query->orderByRaw("CASE dia_semana WHEN 1 THEN 1 WHEN 2 THEN 2 WHEN 3 THEN 3 WHEN 4 THEN 4 WHEN 5 THEN 5 WHEN 6 THEN 6 WHEN 0 THEN 7 END");
    }
}
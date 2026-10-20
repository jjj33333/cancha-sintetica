<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jugador extends Model
{
    protected $table = 'jugadores';

    protected $fillable = [
        'equipo_id',
        'nombre_completo',
        'dni',
        'fecha_nacimiento',
        'posicion',
        'numero_camiseta',
        'telefono',
        'email',
        'es_capitan',
        'esta_activo',
        'actualizado_por',
    ];

    protected $casts = [
        'es_capitan' => 'boolean',
        'esta_activo' => 'boolean',
        'fecha_nacimiento' => 'date',
    ];

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class);
    }
}
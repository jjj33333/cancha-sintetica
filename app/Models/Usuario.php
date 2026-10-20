<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    // Indicamos explícitamente el nombre de la tabla en español
    protected $table = 'usuarios';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',
        'telefono',
        'avatar',
        'esta_activo',
    ];

    // Campos que deben ocultarse en arrays/JSON (seguridad)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Conversiones de tipo de datos
    protected $casts = [
        'email_verificado_en' => 'datetime',
        'password' => 'hashed',
        'esta_activo' => 'boolean',
    ];

    /**
     * Obtener el nombre para mostrar en la interfaz.
     */
    public function getNombreParaMostrarAttribute(): string
    {
        return $this->nombre ?: $this->email;
    }
}
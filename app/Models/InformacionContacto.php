<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InformacionContacto extends Model
{
    protected $table = 'informacion_contacto';

    protected $fillable = [
        'nombre_negocio',
        'ruc_nit',
        'direccion',
        'ciudad',
        'pais',
        'telefono_principal',
        'telefono_secundario',
        'email_principal',
        'email_secundario',
        'sitio_web',
        'url_facebook',
        'url_instagram',
        'url_twitter',
        'numero_whatsapp',
        'enlace_google_maps',
        'horario_atencion',
        'logo',
        'favicon',
        'esta_activo',
        'actualizado_por',
    ];

    protected $casts = [
        'esta_activo' => 'boolean',
    ];

    // Relación con el usuario que actualizó el registro
    public function actualizadoPorUsuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'actualizado_por');
    }

    // Scope para obtener el registro activo
    public function scopeActiva($query)
    {
        return $query->where('esta_activo', true);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistroActividad extends Model
{
    protected $table = 'registros_actividad';

    const UPDATED_AT = null;

    protected $fillable = [
        'usuario_id',
        'accion',
        'nombre_tabla',
        'registro_id',
        'valores_anteriores',
        'valores_nuevos',
        'direccion_ip',
        'agente_usuario',
        'esta_activo',
    ];

    protected $casts = [
        'valores_anteriores' => 'array',
        'valores_nuevos' => 'array',
        'esta_activo' => 'boolean',
    ];

    // Relaciones
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('esta_activo', true);
    }

    public function scopeDeUsuario($query, $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId);
    }

    public function scopeDeAccion($query, $accion)
    {
        return $query->where('accion', $accion);
    }

    public function scopeDeTabla($query, $tabla)
    {
        return $query->where('nombre_tabla', $tabla);
    }

    public function scopeDeRango($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
    }

    // Accessors
    public function getAccionBadgeAttribute(): string
    {
        return match (true) {
            str_contains($this->accion, 'Crear') => 'bg-[#D1FAE5] text-[#065F46]',
            str_contains($this->accion, 'Editar') || str_contains($this->accion, 'Actualizar') => 'bg-[#FEF3C7] text-[#92400E]',
            str_contains($this->accion, 'Eliminar') || str_contains($this->accion, 'Cancelar') => 'bg-[#FEE2E2] text-[#991B1B]',
            str_contains($this->accion, 'Programar') || str_contains($this->accion, 'Reservar') => 'bg-[#DBEAFE] text-[#1E40AF]',
            default => 'bg-[#F3F4F6] text-[#374151]',
        };
    }

    public function getAccionIconoAttribute(): string
    {
        return match (true) {
            str_contains($this->accion, 'Crear') => '➕',
            str_contains($this->accion, 'Editar') || str_contains($this->accion, 'Actualizar') => '✏️',
            str_contains($this->accion, 'Eliminar') || str_contains($this->accion, 'Cancelar') => '🗑️',
            str_contains($this->accion, 'Programar') || str_contains($this->accion, 'Reservar') => '📅',
            str_contains($this->accion, 'Login') => '🔐',
            str_contains($this->accion, 'Logout') => '',
            default => '📋',
        };
    }

    public function getFechaFormateadaAttribute(): string
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    public function getDetalleFormateadoAttribute(): string
    {
        if ($this->nombre_tabla && $this->registro_id) {
            return "{$this->nombre_tabla} #{$this->registro_id}";
        }
        return '-';
    }
}
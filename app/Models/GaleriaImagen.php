<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class GaleriaImagen extends Model
{
    protected $table = 'galeria_imagenes';

    protected $fillable = [
        'titulo',
        'descripcion',
        'ruta_archivo',
        'tipo_archivo',
        'tipo_mime',
        'tamano_archivo',
        'categoria',
        'tipo_relacionado',
        'id_relacionado',
        'es_destacado',
        'orden_visualizacion',
        'esta_activo',
        'actualizado_por',
        'subido_por',
    ];

    protected $casts = [
        'es_destacado' => 'boolean',
        'esta_activo' => 'boolean',
        'orden_visualizacion' => 'integer',
    ];

    // Relaciones
    public function subidoPorUsuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'subido_por');
    }

    public function actualizadoPorUsuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'actualizado_por');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('esta_activo', true);
    }

    public function scopeDestacados($query)
    {
        return $query->where('es_destacado', true);
    }

    public function scopeDeCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    public function scopeOrdenados($query)
    {
        return $query->orderByDesc('es_destacado')->orderBy('orden_visualizacion')->orderByDesc('created_at');
    }

    // Accessors
    public function getUrlArchivoAttribute(): ?string
    {
        return $this->ruta_archivo ? Storage::url($this->ruta_archivo) : null;
    }

    public function getTamanoFormateadoAttribute(): string
    {
        $bytes = $this->tamano_archivo;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }
        return number_format($bytes / 1024, 2) . ' KB';
    }

    public function getCategoriaBadgeAttribute(): string
    {
        return match ($this->categoria) {
            'instalacion' => 'bg-[#D1FAE5] text-[#065F46]',
            'torneo' => 'bg-[#FEF3C7] text-[#92400E]',
            'evento' => 'bg-[#DBEAFE] text-[#1E40AF]',
            'general' => 'bg-[#F3F4F6] text-[#374151]',
            default => 'bg-gray-100 text-gray-600',
        };
    }

    public function getCategoriaTextoAttribute(): string
    {
        return ucfirst($this->categoria);
    }
}
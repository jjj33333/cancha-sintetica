<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Anuncio extends Model
{
    protected $table = 'anuncios';

    protected $fillable = [
        'titulo',
        'slug',
        'contenido',
        'tipo',
        'prioridad',
        'imagen',
        'esta_publicado',
        'mostrar_en_publico',
        'publicado_en',
        'expira_en',
        'contador_vistas',
        'esta_activo',
        'actualizado_por',
        'creado_por',
    ];

    protected $casts = [
        'esta_publicado' => 'boolean',
        'mostrar_en_publico' => 'boolean',
        'esta_activo' => 'boolean',
        'publicado_en' => 'datetime',
        'expira_en' => 'datetime',
    ];

    public function creadoPorUsuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'creado_por');
    }

    public function scopePublicos($query)
    {
        return $query->where('mostrar_en_publico', true)
                     ->where('esta_activo', true)
                     ->where(function ($q) {
                         $q->where('esta_publicado', true)
                           ->orWhere('mostrar_en_publico', true);
                     })
                     ->where(function ($q) {
                         $q->whereNull('expira_en')
                           ->orWhere('expira_en', '>=', now()->startOfDay());
                     })
                     ->orderByRaw('COALESCE(publicado_en, created_at) DESC');
    }

    public function getTipoBadgeAttribute(): string
    {
        return match ($this->tipo) {
            'noticia' => 'bg-[#DBEAFE] text-[#1E40AF]',
            'promocion' => 'bg-[#D1FAE5] text-[#065F46]',
            'alerta' => 'bg-[#FEE2E2] text-[#991B1B]',
            'evento' => 'bg-[#FEF3C7] text-[#92400E]',
            'general' => 'bg-[#EDE9FE] text-[#5B21B6]',
            default => 'bg-[#F3F4F6] text-[#374151]',
        };
    }

    public function getTipoIconoAttribute(): string
    {
        return match ($this->tipo) {
            'noticia' => '📰',
            'promocion' => '🏷️',
            'alerta' => '⚠️',
            'evento' => '🎉',
            'general' => '📢',
            default => '📢',
        };
    }

    public function getPrioridadBadgeAttribute(): string
    {
        return match ($this->prioridad) {
            'urgente' => 'bg-[#FEE2E2] text-[#991B1B] border border-[#FECACA]',
            'alta' => 'bg-[#FEF3C7] text-[#92400E] border border-[#FDE68A]',
            'media' => 'bg-[#DBEAFE] text-[#1E40AF] border border-[#BFDBFE]',
            'baja' => 'bg-[#F3F4F6] text-[#4B5563] border border-gray-200',
            default => 'bg-[#F3F4F6] text-[#374151]',
        };
    }
}
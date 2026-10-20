<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreAnuncioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        // Generar slug automáticamente si no se proporciona
        if (!$this->filled('slug') && $this->filled('titulo')) {
            $this->merge([
                'slug' => Str::slug($this->titulo) . '-' . Str::random(5),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:anuncios,slug'],
            'contenido' => ['required', 'string'],
            'tipo' => ['required', 'in:noticia,promocion,alerta,evento,general'],
            'prioridad' => ['required', 'in:baja,media,alta,urgente'],
            'imagen' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'esta_publicado' => ['nullable', 'boolean'],
            'mostrar_en_publico' => ['nullable', 'boolean'],
            'publicado_en' => ['nullable', 'date'],
            'expira_en' => ['nullable', 'date', 'after:publicado_en'],
            'esta_activo' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'El título del anuncio es obligatorio.',
            'titulo.max' => 'El título no debe superar los 255 caracteres.',
            'contenido.required' => 'El contenido del anuncio es obligatorio.',
            'tipo.required' => 'El tipo de anuncio es obligatorio.',
            'tipo.in' => 'El tipo seleccionado no es válido.',
            'prioridad.required' => 'La prioridad es obligatoria.',
            'prioridad.in' => 'La prioridad seleccionada no es válida.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.max' => 'La imagen no debe superar los 2MB.',
            'expira_en.after' => 'La fecha de expiración debe ser posterior a la fecha de publicación.',
            'slug.unique' => 'El slug ya está en uso.',
        ];
    }
}
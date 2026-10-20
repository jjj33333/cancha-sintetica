<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGaleriaImagenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'titulo' => ['nullable', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'categoria' => ['required', 'in:instalacion,torneo,evento,general'],
            'es_destacado' => ['nullable', 'boolean'],
            'orden_visualizacion' => ['nullable', 'integer', 'min:0'],
            'esta_activo' => ['nullable', 'boolean'],
        ];

        // El archivo es obligatorio solo al crear (store)
        if ($this->isMethod('post')) {
            $rules['archivo'] = ['required', 'file', 'mimes:jpeg,png,jpg,gif,webp,mp4,webm', 'max:10240']; // Max 10MB
        } else {
            $rules['archivo'] = ['nullable', 'file', 'mimes:jpeg,png,jpg,gif,webp,mp4,webm', 'max:10240'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'categoria.required' => 'La categoría es obligatoria.',
            'categoria.in' => 'La categoría seleccionada no es válida.',
            'archivo.required' => 'Debes seleccionar un archivo (imagen o video).',
            'archivo.file' => 'El archivo debe ser un archivo válido.',
            'archivo.mimes' => 'El archivo debe ser una imagen (JPG, PNG, GIF, WEBP) o video (MP4, WEBM).',
            'archivo.max' => 'El archivo no debe superar los 10MB.',
        ];
    }
}
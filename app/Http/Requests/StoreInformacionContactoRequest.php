<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInformacionContactoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre_negocio' => ['required', 'string', 'max:255'],
            'ruc_nit' => ['nullable', 'string', 'max:50'],
            'direccion' => ['nullable', 'string', 'max:500'],
            'ciudad' => ['nullable', 'string', 'max:100'],
            'pais' => ['nullable', 'string', 'max:100'],
            'telefono_principal' => ['required', 'string', 'max:20'],
            'telefono_secundario' => ['nullable', 'string', 'max:20'],
            'email_principal' => ['required', 'email', 'max:255'],
            'email_secundario' => ['nullable', 'email', 'max:255'],
            'sitio_web' => ['nullable', 'url', 'max:255'],
            'url_facebook' => ['nullable', 'url', 'max:255'],
            'url_instagram' => ['nullable', 'url', 'max:255'],
            'url_twitter' => ['nullable', 'url', 'max:255'],
            'numero_whatsapp' => ['nullable', 'string', 'max:20'],
            'enlace_google_maps' => ['nullable', 'url', 'max:500'],
            'horario_atencion' => ['nullable', 'string', 'max:500'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg,webp', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,ico,webp', 'max:512'],
            'esta_activo' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_negocio.required' => 'El nombre del negocio es obligatorio.',
            'telefono_principal.required' => 'El teléfono principal es obligatorio.',
            'email_principal.required' => 'El email principal es obligatorio.',
            'email_principal.email' => 'El email principal no es válido.',
            'email_secundario.email' => 'El email secundario no es válido.',
            'sitio_web.url' => 'El sitio web debe ser una URL válida.',
            'url_facebook.url' => 'La URL de Facebook debe ser válida.',
            'url_instagram.url' => 'La URL de Instagram debe ser válida.',
            'url_twitter.url' => 'La URL de Twitter debe ser válida.',
            'enlace_google_maps.url' => 'El enlace de Google Maps debe ser una URL válida.',
            'logo.image' => 'El logo debe ser una imagen.',
            'logo.max' => 'El logo no debe superar los 2MB.',
            'favicon.image' => 'El favicon debe ser una imagen.',
            'favicon.max' => 'El favicon no debe superar los 512KB.',
        ];
    }
}
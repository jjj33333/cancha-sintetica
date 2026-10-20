<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEquipoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'torneo_id' => ['required', 'exists:torneos,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'nombre_corto' => ['nullable', 'string', 'max:50'],
            'nombre_capitan' => ['required', 'string', 'max:255'],
            'telefono_capitan' => ['required', 'string', 'max:20'],
            'email_capitan' => ['nullable', 'email', 'max:255'],
            'color_uniforme' => ['nullable', 'string', 'max:100'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'estado' => ['required', 'in:registrado,confirmado,eliminado,campeon,subcampeon'],
        ];
    }

    public function messages(): array
    {
        return [
            'torneo_id.required' => 'El torneo es obligatorio.',
            'torneo_id.exists' => 'El torneo seleccionado no existe.',
            'nombre.required' => 'El nombre del equipo es obligatorio.',
            'nombre.unique' => 'Ya existe un equipo con este nombre en el torneo.',
            'nombre_capitan.required' => 'El nombre del capitán es obligatorio.',
            'telefono_capitan.required' => 'El teléfono del capitán es obligatorio.',
            'email_capitan.email' => 'El email del capitán no es válido.',
            'logo.image' => 'El logo debe ser una imagen.',
            'logo.max' => 'El logo no debe superar los 2MB.',
        ];
    }
}
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEquipoOrganizadorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (!$this->has('torneo_id') && $this->route('equipo')) {
            $this->merge([
                'torneo_id' => $this->route('equipo')->torneo_id,
            ]);
        }
    }

    public function rules(): array
    {
        $torneoId = $this->route('torneo_id') ?? $this->torneo_id;

        return [
            'torneo_id' => ['required', 'exists:torneos,id'],
            'nombre' => [
                'required', 
                'string', 
                'max:255',
                Rule::unique('equipos', 'nombre')->where('torneo_id', $torneoId)->ignore($this->route('equipo')?->id)
            ],
            'nombre_corto' => ['nullable', 'string', 'max:50'],
            'nombre_capitan' => ['required', 'string', 'max:255'],
            'telefono_capitan' => ['required', 'string', 'max:20'],
            'email_capitan' => ['nullable', 'email', 'max:255'],
            'color_uniforme' => ['nullable', 'string', 'max:100'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
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
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado seleccionado no es válido.',
        ];
    }
}
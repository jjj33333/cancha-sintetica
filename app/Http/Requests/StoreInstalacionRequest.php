<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstalacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'tipo' => ['required', 'in:sintetica,natural,mixta,indoor,outdoor'],
            'descripcion' => ['nullable', 'string'],
            'capacidad' => ['required', 'integer', 'min:1', 'max:100'],
            'dimensiones' => ['nullable', 'string', 'max:50'],
            'tipo_superficie' => ['nullable', 'string', 'max:100'],
            'tiene_iluminacion' => ['nullable', 'boolean'],
            'tiene_estacionamiento' => ['nullable', 'boolean'],
            'tiene_casilleros' => ['nullable', 'boolean'],
            'esta_activo' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la instalación es obligatorio.',
            'nombre.max' => 'El nombre no debe superar los 255 caracteres.',
            'tipo.required' => 'El tipo de instalación es obligatorio.',
            'tipo.in' => 'El tipo seleccionado no es válido.',
            'capacidad.required' => 'La capacidad es obligatoria.',
            'capacidad.integer' => 'La capacidad debe ser un número entero.',
            'capacidad.min' => 'La capacidad mínima es 1.',
            'capacidad.max' => 'La capacidad máxima es 100.',
            'dimensiones.max' => 'Las dimensiones no deben superar los 50 caracteres.',
        ];
    }
}
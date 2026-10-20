<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Si es una actualización, ignoramos el email del usuario actual para la regla unique
        $usuarioId = $this->route('usuario')?->id;

        return [
            'nombre' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'email', 
                'max:255', 
                Rule::unique('usuarios', 'email')->ignore($usuarioId)
            ],
            'password' => $usuarioId 
                ? ['nullable', 'string', 'min:8', 'confirmed'] // Opcional al editar
                : ['required', 'string', 'min:8', 'confirmed'], // Obligatorio al crear
            'rol' => ['required', 'in:admin,organizador,trabajador,staff,viewer'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'esta_activo' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'rol.required' => 'El rol es obligatorio.',
            'rol.in' => 'El rol seleccionado no es válido.',
        ];
    }
}
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJugadorOrganizadorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (!$this->has('equipo_id') && $this->route('jugador')) {
            $this->merge([
                'equipo_id' => $this->route('jugador')->equipo_id,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'equipo_id' => ['required', 'exists:equipos,id'],
            'nombre_completo' => ['required', 'string', 'max:255'],
            'dni' => ['nullable', 'string', 'max:20'],
            'fecha_nacimiento' => ['nullable', 'date', 'before:today'],
            'posicion' => ['nullable', 'in:Portero,Defensa,Mediocampista,Delantero'],
            'numero_camiseta' => ['nullable', 'integer', 'min:1', 'max:99', Rule::unique('jugadores', 'numero_camiseta')->where('equipo_id', $this->equipo_id)->ignore($this->route('jugador')?->id)],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'es_capitan' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'equipo_id.required' => 'El equipo es obligatorio.',
            'equipo_id.exists' => 'El equipo seleccionado no existe.',
            'nombre_completo.required' => 'El nombre completo es obligatorio.',
            'nombre_completo.max' => 'El nombre no debe superar los 255 caracteres.',
            'dni.max' => 'El DNI no debe superar los 20 caracteres.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'posicion.in' => 'La posición seleccionada no es válida.',
            'numero_camiseta.integer' => 'El número de camiseta debe ser un número.',
            'numero_camiseta.min' => 'El número mínimo es 1.',
            'numero_camiseta.max' => 'El número máximo es 99.',
            'numero_camiseta.unique' => 'Este número de camiseta ya está en uso en el equipo.',
            'email.email' => 'El email no es válido.',
        ];
    }
}
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlanTarifaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'instalacion_id' => ['required', 'exists:instalaciones,id'],
            'tipo_dia' => ['required', 'in:semana,fin_semana,feriado'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin' => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'precio' => ['required', 'numeric', 'min:0', 'max:99999.99'],
            'moneda' => ['nullable', 'string', 'max:3'],
            'esta_activo' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la tarifa es obligatorio.',
            'nombre.max' => 'El nombre no debe superar los 255 caracteres.',
            'instalacion_id.required' => 'La instalación es obligatoria.',
            'instalacion_id.exists' => 'La instalación seleccionada no existe.',
            'tipo_dia.required' => 'El tipo de día es obligatorio.',
            'tipo_dia.in' => 'El tipo de día seleccionado no es válido.',
            'hora_inicio.required' => 'La hora de inicio es obligatoria.',
            'hora_inicio.date_format' => 'La hora de inicio debe tener formato HH:MM.',
            'hora_fin.required' => 'La hora de fin es obligatoria.',
            'hora_fin.date_format' => 'La hora de fin debe tener formato HH:MM.',
            'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número.',
            'precio.min' => 'El precio no puede ser negativo.',
            'precio.max' => 'El precio no puede superar 99,999.99.',
        ];
    }
}
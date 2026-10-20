<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBloqueoCanchaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'instalacion_id' => ['required', 'exists:instalaciones,id'],
            'fecha_bloqueo' => ['required', 'date'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin' => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'motivo' => ['required', 'in:mantenimiento,evento,feriado,otro'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'dia_completo' => ['nullable', 'boolean'],
            'esta_activo' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'instalacion_id.required' => 'La instalación es obligatoria.',
            'instalacion_id.exists' => 'La instalación seleccionada no existe.',
            'fecha_bloqueo.required' => 'La fecha del bloqueo es obligatoria.',
            'fecha_bloqueo.date' => 'La fecha no es válida.',
            'hora_inicio.required' => 'La hora de inicio es obligatoria.',
            'hora_inicio.date_format' => 'La hora de inicio debe tener formato HH:MM.',
            'hora_fin.required' => 'La hora de fin es obligatoria.',
            'hora_fin.date_format' => 'La hora de fin debe tener formato HH:MM.',
            'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'motivo.required' => 'El motivo del bloqueo es obligatorio.',
            'motivo.in' => 'El motivo seleccionado no es válido.',
            'descripcion.max' => 'La descripción no debe superar los 1000 caracteres.',
        ];
    }
}
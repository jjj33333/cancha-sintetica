<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePartidoOrganizadorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (!$this->has('torneo_id') && $this->route('partido')) {
            $this->merge([
                'torneo_id' => $this->route('partido')->torneo_id,
            ]);
        }
    }

    public function rules(): array
    {
        $fechaRule = ($this->isMethod('PUT') || $this->isMethod('PATCH'))
            ? ['required', 'date']
            : ['required', 'date', 'after_or_equal:today'];

        return [
            'torneo_id' => ['required', 'exists:torneos,id'],
            'equipo_local_id' => ['required', 'exists:equipos,id', 'different:equipo_visitante_id'],
            'equipo_visitante_id' => ['required', 'exists:equipos,id'],
            'fecha_partido' => $fechaRule,
            'hora_inicio' => ['required', 'date_format:H:i'],
            'instalacion_id' => ['required', 'exists:instalaciones,id'],
            'numero_jornada' => ['nullable', 'integer', 'min:1'],
            'nombre_grupo' => ['nullable', 'string', 'max:50'],
            'arbitro' => ['nullable', 'string', 'max:255'],
            'notas' => ['nullable', 'string', 'max:1000'],
            // Campos para registrar resultado (opcionales al crear, requeridos al finalizar)
            'marcador_local' => ['nullable', 'integer', 'min:0', 'max:99'],
            'marcador_visitante' => ['nullable', 'integer', 'min:0', 'max:99'],
            'estado' => ['required', 'in:programado,en_curso,finalizado,aplazado,cancelado'],
        ];
    }

    public function messages(): array
    {
        return [
            'torneo_id.required' => 'El torneo es obligatorio.',
            'torneo_id.exists' => 'El torneo seleccionado no existe.',
            'equipo_local_id.required' => 'El equipo local es obligatorio.',
            'equipo_local_id.exists' => 'El equipo local no existe.',
            'equipo_local_id.different' => 'El equipo local y visitante deben ser diferentes.',
            'equipo_visitante_id.required' => 'El equipo visitante es obligatorio.',
            'equipo_visitante_id.exists' => 'El equipo visitante no existe.',
            'fecha_partido.required' => 'La fecha del partido es obligatoria.',
            'fecha_partido.date' => 'La fecha no es válida.',
            'fecha_partido.after_or_equal' => 'La fecha no puede ser anterior a hoy.',
            'hora_inicio.required' => 'La hora de inicio es obligatoria.',
            'hora_inicio.date_format' => 'La hora debe tener formato HH:MM.',
            'instalacion_id.required' => 'La cancha es obligatoria.',
            'instalacion_id.exists' => 'La cancha seleccionada no existe.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado seleccionado no es válido.',
            'marcador_local.integer' => 'El marcador local debe ser un número.',
            'marcador_local.min' => 'El marcador no puede ser negativo.',
            'marcador_visitante.integer' => 'El marcador visitante debe ser un número.',
            'marcador_visitante.min' => 'El marcador no puede ser negativo.',
        ];
    }
}
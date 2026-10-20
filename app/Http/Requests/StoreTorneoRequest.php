<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTorneoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'instalacion_id' => ['required', 'exists:instalaciones,id'],
            'tipo_torneo' => ['required', 'in:liga,eliminacion_directa,fase_grupos'],
            'categoria' => ['required', 'in:libre,sub20,sub18,femenino,mixto'],
            'fecha_inicio' => ['required', 'date', 'after_or_equal:today'],
            'fecha_fin' => ['required', 'date', 'after:fecha_inicio'],
            'inscripcion_inicio' => ['required', 'date', 'before_or_equal:fecha_inicio'],
            'inscripcion_fin' => ['required', 'date', 'before:fecha_inicio'],
            'max_equipos' => ['required', 'integer', 'min:2', 'max:50'],
            'min_equipos' => ['required', 'integer', 'min:2', 'lte:max_equipos'],
            'jugadores_por_equipo' => ['required', 'integer', 'min:5', 'max:30'],
            'costo_inscripcion' => ['nullable', 'numeric', 'min:0'],
            'descripcion_premios' => ['nullable', 'string'],
            'reglas' => ['nullable', 'string'],
            'organizador_asignado_id' => ['nullable', 'exists:usuarios,id'],
            'estado' => ['required', 'in:borrador,inscripciones_abiertas,en_curso,finalizado,cancelado'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del torneo es obligatorio.',
            'fecha_inicio.after_or_equal' => 'La fecha de inicio debe ser hoy o en el futuro.',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
            'inscripcion_fin.before' => 'El cierre de inscripciones debe ser antes del inicio del torneo.',
            'min_equipos.lte' => 'El mínimo de equipos no puede ser mayor al máximo.',
        ];
    }
}
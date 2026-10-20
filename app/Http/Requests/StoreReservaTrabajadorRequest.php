<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservaTrabajadorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'instalacion_id' => ['nullable', 'exists:instalaciones,id'],
            'nombre_cliente' => ['required', 'string', 'max:255'],
            'telefono_cliente' => ['nullable', 'string', 'max:20'],
            'email_cliente' => ['nullable', 'email', 'max:255'],
            'dni_cliente' => ['nullable', 'string', 'max:20'],
            'fecha_reserva' => ['required', 'date'],
            'hora_inicio' => ['required'],
            'hora_fin' => ['required', 'after:hora_inicio'],
            'precio_total' => ['nullable', 'numeric', 'min:0', 'max:99999.99'],
            'estado' => ['required', 'in:pendiente,confirmada,cancelada,completada'],
            'estado_pago' => ['nullable', 'in:pendiente,pagado,reembolsado'],
            'metodo_pago' => ['nullable', 'in:efectivo,transferencia,tarjeta,otro'],
            'notas' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_cliente.required' => 'El nombre del cliente es obligatorio.',
            'nombre_cliente.max' => 'El nombre no debe superar los 255 caracteres.',
            'fecha_reserva.required' => 'La fecha de reserva es obligatoria.',
            'hora_inicio.required' => 'La hora de inicio es obligatoria.',
            'hora_fin.required' => 'La hora de fin es obligatoria.',
            'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'precio_total.numeric' => 'El precio debe ser un número.',
            'precio_total.min' => 'El precio no puede ser negativo.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado seleccionado no es válido.',
        ];
    }
}
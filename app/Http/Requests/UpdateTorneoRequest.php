<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTorneoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = app(StoreTorneoRequest::class)->rules();
        // Quitamos la validación de fecha para permitir editar torneos en curso
        unset($rules['fecha_inicio'], $rules['fecha_fin']);
        return $rules;
    }
}
<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCitaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre_cliente' => ['required', 'string', 'max:125'],
            'apellido_cliente' => ['required', 'string', 'max:255'],
            'telefono_cliente' => ['nullable', 'string', 'max:20'],
            'servicio_id' => ['required', 'exists:servicios,id'],
            'barbero_id' => ['required', 'exists:users,id'],
            'fecha_cita' => ['required', 'date', 'after_or_equal:today'],
            'hora_cita' => ['required', 'date_format:H:i'],
            'notas' => ['nullable']
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_cliente.required' => 'Debes ingresar un nombre para el cliente',
            'nombre_cliente.max' => 'El nombre del cliente no puede exceder los 125 caracteres',
            'apellido_cliente.required' => 'Debes ingresar un apellido para el cliente',
            'apellido_cliente.max' => 'El apellido del cliente no puede exceder los 125 caracteres',
            'servicio_id.required' => 'Debes seleccionar un servicio',
            'barbero_id.required' => 'Debes seleccionar un barbero',
            'fecha_cita.required' => 'Debes ingresar una fecha para la cita',
            'fecha_cita.after_or_equal' => 'La fecha de la cita no puede ser anterior a hoy',
            'hora_cita.required' => 'Debes ingresar una hora para la cita'
        ];
    }
}

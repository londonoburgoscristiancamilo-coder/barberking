<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCitaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id' => ['required', 'integer', 'exists:clientes,id'],
            'servicio_id' => ['required', 'integer', 'exists:servicios,id'],
            'barbero_id' => ['required', 'integer', 'exists:users,id'],
            'fecha_cita' => ['required', 'date_format:Y-m-d'],
            'hora_cita' => ['required', 'date_format:H:i'],
            'notas' => ['nullable', 'string', 'max:1000']
        ];
    }

    public function messages(): array
    {
        return [
            'cliente_id.required' => 'Debes seleccionar un cliente.',
            'cliente_id.integer' => 'El cliente seleccionado no es válido.',
            'cliente_id.exists' => 'El cliente seleccionado no existe.',

            'servicio_id.required' => 'Debes seleccionar un servicio.',
            'servicio_id.integer' => 'El servicio seleccionado no es válido.',
            'servicio_id.exists' => 'El servicio seleccionado no existe.',

            'barbero_id.required' => 'Debes seleccionar un barbero.',
            'barbero_id.integer' => 'El barbero seleccionado no es válido.',
            'barbero_id.exists' => 'El barbero seleccionado no existe.',

            'fecha_cita.required' => 'Debes seleccionar una fecha para la cita.',
            'fecha_cita.date_format' => 'La fecha de la cita no tiene un formato válido.',

            'hora_cita.required' => 'Debes seleccionar una hora para la cita.',
            'hora_cita.date_format' => 'La hora seleccionada no tiene un formato válido.',

            'notas.string' => 'Las notas deben contener texto válido.',
            'notas.max' => 'Las notas no pueden superar los 1000 caracteres.'
        ];
    }
}
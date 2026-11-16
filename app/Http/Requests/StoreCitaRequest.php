<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCitaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_cliente' => ['required', 'in:existente,nuevo'],

            'cliente_id' => [
                'required_if:tipo_cliente,existente',
                'nullable',
                'integer',
                'exists:clientes,id'
            ],

            'documento_identidad' => [
                'required_if:tipo_cliente,nuevo',
                'nullable',
                'string',
                'regex:/^[0-9]{5,10}$/',
                Rule::unique('clientes', 'documento_identidad')
            ],

            'nombres' => [
                'required_if:tipo_cliente,nuevo',
                'nullable',
                'string',
                'max:125'
            ],

            'apellidos' => [
                'required_if:tipo_cliente,nuevo',
                'nullable',
                'string',
                'max:125'
            ],

            'telefono' => ['nullable', 'string', 'max:20'],

            'servicio_id' => ['required', 'integer', 'exists:servicios,id'],
            'barbero_id' => ['required', 'integer', 'exists:users,id'],
            'fecha_cita' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'hora_cita' => ['required', 'date_format:H:i'],
            'notas' => ['nullable', 'string', 'max:1000']
        ];
    }

    public function messages(): array
    {
        return [
            'tipo_cliente.required' => 'Debes seleccionar el tipo de cliente.',
            'tipo_cliente.in' => 'El tipo de cliente seleccionado no es válido.',

            'cliente_id.required_if' => 'Debes seleccionar un cliente registrado.',
            'cliente_id.integer' => 'El cliente seleccionado no es válido.',
            'cliente_id.exists' => 'El cliente seleccionado no existe.',

            'documento_identidad.required_if' => 'Debes ingresar la cédula de ciudadanía.',
            'documento_identidad.string' => 'La cédula debe ser un texto válido.',
            'documento_identidad.regex' => 'La cédula debe contener entre 5 y 10 dígitos numéricos.',
            'documento_identidad.unique' => 'Ya existe un cliente registrado con esta cédula.',

            'nombres.required_if' => 'Debes ingresar los nombres del cliente.',
            'nombres.string' => 'Los nombres del cliente no son válidos.',
            'nombres.max' => 'Los nombres no pueden superar los 125 caracteres.',

            'apellidos.required_if' => 'Debes ingresar los apellidos del cliente.',
            'apellidos.string' => 'Los apellidos del cliente no son válidos.',
            'apellidos.max' => 'Los apellidos no pueden superar los 125 caracteres.',

            'telefono.string' => 'El teléfono debe ser un texto válido.',
            'telefono.max' => 'El teléfono no puede superar los 20 caracteres.',

            'servicio_id.required' => 'Debes seleccionar un servicio.',
            'servicio_id.integer' => 'El servicio seleccionado no es válido.',
            'servicio_id.exists' => 'El servicio seleccionado no existe.',

            'barbero_id.required' => 'Debes seleccionar un barbero.',
            'barbero_id.integer' => 'El barbero seleccionado no es válido.',
            'barbero_id.exists' => 'El barbero seleccionado no existe.',

            'fecha_cita.required' => 'Debes seleccionar una fecha para la cita.',
            'fecha_cita.date_format' => 'La fecha de la cita no tiene un formato válido.',
            'fecha_cita.after_or_equal' => 'La fecha de la cita no puede ser anterior a hoy.',

            'hora_cita.required' => 'Debes seleccionar una hora para la cita.',
            'hora_cita.date_format' => 'La hora seleccionada no tiene un formato válido.',

            'notas.string' => 'Las notas deben contener texto válido.',
            'notas.max' => 'Las notas no pueden superar los 1000 caracteres.'
        ];
    }
}
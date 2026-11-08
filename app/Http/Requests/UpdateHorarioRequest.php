<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHorarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'barbero_id' => ['required', 'exists:users,id'],
            'fecha' => ['required', 'date'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin' => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'hora_inicio_descanso' => ['nullable', 'date_format:H:i', 'required_with:hora_fin_descanso', 'after:hora_inicio'],
            'hora_fin_descanso' => ['nullable', 'date_format:H:i', 'required_with:hora_inicio_descanso', 'after:hora_inicio_descanso', 'before:hora_fin'],
        ];
    }

    public function messages(): array
    {
        return [
            'barbero_id.required' => 'Debes seleccionar un barbero.',
            'barbero_id.exists' => 'El barbero seleccionado no es válido.',

            'fecha.required' => 'Debes seleccionar una fecha.',
            'fecha.date' => 'La fecha ingresada no tiene un formato válido.',

            'hora_inicio.required' => 'Debes ingresar la hora de inicio.',
            'hora_inicio.date_format' => 'La hora de inicio no tiene un formato válido.',

            'hora_fin.required' => 'Debes ingresar la hora de finalización.',
            'hora_fin.date_format' => 'La hora de finalización no tiene un formato válido.',
            'hora_fin.after' => 'La hora de finalización debe ser posterior a la hora de inicio.',

            'hora_inicio_descanso.date_format' => 'La hora de inicio del descanso no tiene un formato válido.',
            'hora_inicio_descanso.required_with' => 'Debes ingresar el inicio del descanso.',
            'hora_inicio_descanso.after' => 'El descanso debe comenzar después del inicio de la jornada.',

            'hora_fin_descanso.date_format' => 'La hora de finalización del descanso no tiene un formato válido.',
            'hora_fin_descanso.required_with' => 'Debes ingresar la finalización del descanso.',
            'hora_fin_descanso.after' => 'La finalización del descanso debe ser posterior a su inicio.',
            'hora_fin_descanso.before' => 'El descanso debe finalizar antes de terminar la jornada.',
        ];
    }
}
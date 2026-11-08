<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServicioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->precio) {
            $this->merge([
                'precio' => str_replace(['.', ','], '', $this->precio)
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'nombre'   => ['required', 'string', 'max:255'],
            'duracion_minutos' => ['required', 'integer', 'in:30,45,60,90'],
            'precio'   => ['required', 'numeric', 'min:0'],
            'notas'    => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'   => 'El nombre del servicio es obligatorio.',
            'duracion_minutos.required' => 'Debes seleccionar la duración del servicio.',
            'precio.required'   => 'El precio es obligatorio.',
            'precio.numeric'    => 'El precio debe ser un valor numérico válido.',
        ];
    }
}

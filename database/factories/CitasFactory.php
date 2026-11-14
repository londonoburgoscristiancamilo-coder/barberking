<?php

namespace Database\Factories;

use App\Models\Citas;
use App\Models\Servicios;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CitasFactory extends Factory
{
    protected $model = Citas::class;

    public function definition(): array
    {
        return [
            'nombre_cliente' => $this->faker->firstName(),
            'apellido_cliente' => $this->faker->lastName(),
            'telefono_cliente' => fake()->numerify('3#########'),
            'servicio_id' => Servicios::inRandomOrder()->value('id'),
            'barbero_id' => User::inRandomOrder()->value('id'),
            'fecha_cita' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'hora_cita' => $this->faker->time('H:i:s'), 
            'notas' => $this->faker->optional()->realText(100),
            'estado' => $this->faker->randomElement(['pendiente', 'confirmado', 'cancelado', 'completado']),
        ];
    }
}
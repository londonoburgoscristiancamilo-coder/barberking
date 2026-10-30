<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Servicios;
use Illuminate\Database\Seeder;

class ServiciosSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = [
            [
                'nombre' => 'Corte clásico',
                'precio' => 20000,
                'duracion_minutos' => 30,
                'descripcion' => 'Corte de cabello tradicional a tijera o máquina.'
            ],
            [
                'nombre' => 'Arreglo de barba',
                'precio' => 18000,
                'duracion_minutos' => 30,
                'descripcion' => 'Perfilado, recorte y alineación de barba.'
            ],
            [
                'nombre' => 'Corte + barba',
                'precio' => 25000,
                'duracion_minutos' => 45,
                'descripcion' => 'Servicio completo de corte de cabello y arreglo de barba.'
            ],
            [
                'nombre' => 'Corte degradado',
                'precio' => 22000,
                'duracion_minutos' => 45,
                'descripcion' => 'Corte moderno con técnica de degradado (fade).'
            ],
            [
                'nombre' => 'Afeitado clásico',
                'precio' => 15000,
                'duracion_minutos' => 20,
                'descripcion' => 'Afeitado tradicional al ras con toalla caliente y navaja.'
            ],
        ];

        foreach ($servicios as $servicio) {
            Servicios::updateOrCreate(
                ['nombre' => $servicio['nombre']],
                $servicio
            );
        }
    }
}

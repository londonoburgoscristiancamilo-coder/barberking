<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Horarios;
use App\Models\User;
use Carbon\Carbon;

class HorariosSeeder extends Seeder
{
    public function run(): void
    {
        $barberos = User::pluck('id')->toArray();

        if (empty($barberos)) {
            return;
        }

        $barbero1 = $barberos[0];
        $barbero2 = $barberos[1] ?? $barberos[0];

        // Obtener el lunes de esta semana y el lunes de la próxima semana
        $lunesEstaSemana = Carbon::now()->startOfWeek();
        $lunesProximaSemana = Carbon::now()->addWeek()->startOfWeek();

        $horarios = [
            // --- ESTA SEMANA ---
            [
                'barbero_id' => $barbero1,
                'fecha' => $lunesEstaSemana->copy()->toDateString(), // Lunes
                'hora_inicio' => '09:00:00',
                'hora_fin' => '18:00:00',
                'hora_inicio_descanso' => '13:00:00',
                'hora_fin_descanso' => '14:00:00',
            ],
            [
                'barbero_id' => $barbero2,
                'fecha' => $lunesEstaSemana->copy()->toDateString(), // Lunes
                'hora_inicio' => '10:00:00',
                'hora_fin' => '19:00:00',
                'hora_inicio_descanso' => '14:00:00',
                'hora_fin_descanso' => '15:00:00',
            ],
            [
                'barbero_id' => $barbero1,
                'fecha' => $lunesEstaSemana->copy()->addDay()->toDateString(), // Martes
                'hora_inicio' => '09:00:00',
                'hora_fin' => '18:00:00',
                'hora_inicio_descanso' => '00:00:00',   // Sin horario de Descanso
                'hora_fin_descanso' => '00:00:00',      // Sin horario de Descanso
            ],
            [
                'barbero_id' => $barbero2,
                'fecha' => $lunesEstaSemana->copy()->addDays(2)->toDateString(), // Miércoles
                'hora_inicio' => '10:00:00',
                'hora_fin' => '19:00:00',
                'hora_inicio_descanso' => '13:00:00',
                'hora_fin_descanso' => '14:00:00',
            ],

            // --- PRÓXIMA SEMANA ---
            [
                'barbero_id' => $barbero1,
                'fecha' => $lunesProximaSemana->copy()->toDateString(), // Lunes próximo
                'hora_inicio' => '09:00:00',
                'hora_fin' => '18:00:00',
                'hora_inicio_descanso' => '13:00:00',
                'hora_fin_descanso' => '14:00:00',
            ],
            [
                'barbero_id' => $barbero2,
                'fecha' => $lunesProximaSemana->copy()->addDay()->toDateString(), // Martes próximo
                'hora_inicio' => '10:00:00',
                'hora_fin' => '19:00:00',
                'hora_inicio_descanso' => '14:00:00',
                'hora_fin_descanso' => '15:00:00',
            ],
        ];

        foreach ($horarios as $horario) {
            Horarios::create($horario);
        }
    }
}
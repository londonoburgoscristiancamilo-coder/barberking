<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $ejecutado = DB::table('seed_runs')
                ->where('name', 'initial_seed')
                ->exists();

            if ($ejecutado) {
                $this->command->info(
                    'Los datos iniciales ya fueron cargados.'
                );

                return;
            }

            // Crea 5 usuarios de prueba
            User::factory(5)->create();

            if (!User::where('email', 'admin@barberking.com')->exists()) {
                User::factory()->create([
                    'name' => 'Admin Barber',
                    'email' => 'admin@barberking.com',
                    'password' => bcrypt('1234554321'),
                ]);
            }

            $this->call([
                ServiciosSeeder::class,
                HorariosSeeder::class,
                CitasSeeder::class,
            ]);

            DB::table('seed_runs')->insert([
                'name' => 'initial_seed',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }
}

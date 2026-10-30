<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crea 10 usuarios de prueba
        User::factory(10)->create();

        if (!User::where('email', 'admin@barberking.com')->exists()) {
            User::factory()->create([
                'name' => 'Admin Barber',
                'email' => 'admin@barberking.com',
            ]);
        }

        $this->call([
            ServiciosSeeder::class,
        ]);
    }
}
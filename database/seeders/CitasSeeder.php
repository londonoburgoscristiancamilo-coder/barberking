<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Citas;

class CitasSeeder extends Seeder
{
    public function run(): void
    {
        Citas::factory(30)->create();
    }
}
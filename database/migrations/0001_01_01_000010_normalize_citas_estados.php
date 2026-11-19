<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Normalizar los estados históricos de las citas.
     */
    public function up(): void
    {
        DB::table('citas')
            ->whereRaw('LOWER(TRIM(estado)) = ?', ['confirmado'])
            ->update(['estado' => 'confirmada']);

        DB::table('citas')
            ->whereRaw('LOWER(TRIM(estado)) = ?', ['completado'])
            ->update(['estado' => 'completada']);

        DB::table('citas')
            ->whereRaw('LOWER(TRIM(estado)) = ?', ['cancelado'])
            ->update(['estado' => 'cancelada']);
    }
};

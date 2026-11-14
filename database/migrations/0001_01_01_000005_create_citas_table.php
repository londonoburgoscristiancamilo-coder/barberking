<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_cliente', 125);
            $table->string('apellido_cliente', 255);
            $table->foreignId('servicio_id')->constrained('servicios')->onDelete('cascade');
            $table->foreignId('barbero_id')->constrained('users')->onDelete('cascade');
            $table->date('fecha_cita');
            $table->time('hora_cita');
            $table->text('notas')->nullable();
            $table->string('estado')->default('pendiente');
            $table->string('telefono_cliente', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
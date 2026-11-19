<?php

use App\Http\Controllers\Api\BotController;
use App\Http\Middleware\VerifyBotApiKey;
use Illuminate\Support\Facades\Route;

Route::prefix('bot')->middleware(VerifyBotApiKey::class)->group(function () {
    // Servicios y barberos
    Route::get('/servicios', [BotController::class, 'getServicios']);
    Route::get('/barberos', [BotController::class, 'getBarberos']);

    // Clientes
    Route::post('/clientes/buscar', [BotController::class, 'getCliente']);
    Route::post('/clientes', [BotController::class, 'storeCliente']);

    // Citas
    Route::post('/disponibilidad', [BotController::class, 'getDisponibilidad']);
    Route::post('/citas/consultar', [BotController::class, 'getCitasCliente']);
    Route::post('/citas', [BotController::class, 'storeCita']);
    Route::post('/citas/cancelar', [BotController::class, 'cancelarCita']);
});

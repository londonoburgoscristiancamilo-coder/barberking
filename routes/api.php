<?php

use App\Http\Controllers\Api\BotController;
use Illuminate\Support\Facades\Route;

Route::prefix('bot')->group(function () {
    Route::get('/servicios', [BotController::class, 'getServicios']);
    Route::get('/barberos', [BotController::class, 'getBarberos']);
    Route::post('/disponibilidad', [BotController::class, 'getDisponibilidad']);
    Route::post('/citas', [BotController::class, 'storeCita']);
});
<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Citas;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Configurar idioma en español para fechas
        Carbon::setLocale('es');
        
        $hoy = Carbon::now()->toDateString(); // Formato YYYY-MM-DD
        
        // Saludo y fecha formateada (Ej: "Sábado, 3 de noviembre")
        $nombreUsuario = Auth::user()->name ?? 'Administrador';
        $fechaActual = ucfirst(Carbon::now()->isoFormat('dddd, D [de] MMMM'));

        // Citas de hoy
        $citasHoyCount = Citas::whereDate('fecha_cita', $hoy)->count();

        // Citas por atender
        $porAtenderCount = Citas::whereDate('fecha_cita', $hoy)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->count();

        // Ingresos previstos
        $ingresosPrevistos = Citas::whereDate('fecha_cita', $hoy)
            ->with('servicio')
            ->get()
            ->sum(function ($cita) {
                return $cita->servicio->precio ?? 0;
            });

        return view('dashboard', compact(
            'nombreUsuario',
            'fechaActual',
            'citasHoyCount',
            'porAtenderCount',
            'ingresosPrevistos'
        ));
    }
}
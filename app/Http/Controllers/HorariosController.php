<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Horarios;
use App\Models\User;

class HorariosController extends Controller
{
    public function index()
    {
        // Establecer Carbon en español
        Carbon::setLocale('es');

        // Definir la semana actual (Lunes a Domingo) o usar fechas dinámicas
        $inicioSemana = Carbon::now()->startOfWeek();  // Lunes
        $finSemana = Carbon::now()->endOfWeek();       // Domingo

        // Formatear el subtítulo dinámico (ej: "Turnos de trabajo · 2 - 8 de noviembre de 2026")
        $subtitulo = "Turnos de trabajo · " . $inicioSemana->format('j') . " - " . $finSemana->format('j \d\e F \d\e Y');

        // Filtrar los horarios que correspondan a las fechas de esta semana
        $horarios = Horarios::with('barbero')
            ->whereBetween('fecha', [$inicioSemana->toDateString(), $finSemana->toDateString()])
            ->orderBy('fecha', 'asc')
            ->get();

        return view('horarios.index', compact('horarios', 'subtitulo'));
    }

    public function create()
    {
        // Obtenemos los barberos (usuarios)
        $barberos = User::all(); 
        return view('horarios.create', compact('barberos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barbero_id' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after:hora_inicio',
            'hora_inicio_descanso' => 'nullable',
            'hora_fin_descanso' => 'nullable|after:hora_inicio_descanso',
        ]);

        // Preparamos los datos y rellenamos los descansos si están vacíos
        $data = $request->all();
        $data['hora_inicio_descanso'] = $request->filled('hora_inicio_descanso') ? $request->hora_inicio_descanso : '00:00:00';
        $data['hora_fin_descanso'] = $request->filled('hora_fin_descanso') ? $request->hora_fin_descanso : '00:00:00';

        Horarios::create($data);

        return redirect()->route('horarios.index')->with('success', 'Horario creado exitosamente.');
    }

    public function show(string $id)
    {
        
    }

    public function edit(string $id)
    {
        
    }

    public function update(Request $request, string $id)
    {
        
    }

    public function destroy(string $id)
    {
        
    }
}

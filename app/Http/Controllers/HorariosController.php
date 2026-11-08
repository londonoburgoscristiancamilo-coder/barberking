<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Horarios;
use App\Http\Requests\StoreHorarioRequest;
use App\Http\Requests\UpdateHorarioRequest;

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
        $subtitulo = "Turnos de trabajo · " . $inicioSemana->format('j') . " - " . $finSemana->translatedFormat('j \d\e F \d\e Y');

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

    public function store(StoreHorarioRequest $request)
    {
        $data = $request->validated();

        // Si no existe descanso, guardamos valores predeterminados
        $data['hora_inicio_descanso'] = $request->filled('hora_inicio_descanso') ? $request->hora_inicio_descanso : '00:00:00';
        $data['hora_fin_descanso'] = $request->filled('hora_fin_descanso') ? $request->hora_fin_descanso : '00:00:00';

        Horarios::create($data);

        return redirect()->route('horarios.index')->with('success', 'Horario creado exitosamente.');
    }

    public function show(string $id)
    {

    }

    public function edit(Horarios $horario)
    {
        $barberos = User::all();

        return view('horarios.edit', compact('horario', 'barberos'));
    }

    public function update(UpdateHorarioRequest $request, Horarios $horario)
    {
        $data = $request->validated();

        // Si no existe descanso, guardamos valores predeterminados
        $data['hora_inicio_descanso'] = $request->filled('hora_inicio_descanso') ? $request->hora_inicio_descanso : '00:00:00';
        $data['hora_fin_descanso'] = $request->filled('hora_fin_descanso') ? $request->hora_fin_descanso : '00:00:00';

        $horario->update($data);

        return redirect()->route('horarios.index')->with('success', 'Horario actualizado exitosamente.');
    }

    public function destroy(Horarios $horario)
    {
        $horario->delete();

        return redirect()->route('horarios.index')->with('success', 'El horario ha sido eliminado correctamente.');
    }
}
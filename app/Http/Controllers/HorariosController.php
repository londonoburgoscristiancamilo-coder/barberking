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

        // Obtener la semana actual de lunes a domingo
        $hoy = Carbon::today();

        $inicioSemana = $hoy->copy()->startOfWeek();
        $finSemana = $hoy->copy()->endOfWeek();

        // Construir el subtítulo dinámico
        $subtitulo = "Turnos de trabajo · " . $inicioSemana->format('j') . " - " . $finSemana->translatedFormat('j \d\e F \d\e Y');

        // Consultar los horarios de la semana actual
        $horarios = Horarios::with('barbero')
            ->whereBetween('fecha', [
                $inicioSemana->toDateString(),
                $finSemana->toDateString()
            ])
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

        $data['hora_inicio_descanso'] = $request->input('hora_inicio_descanso') ?: null;
        $data['hora_fin_descanso'] = $request->input('hora_fin_descanso') ?: null;

        $horario = Horarios::create($data);

        $nombreBarbero = $horario->barbero->name;

        return redirect()->route('horarios.index')
            ->with('success', "Horario de {$nombreBarbero} creado correctamente.");
    }

    public function edit(Horarios $horario)
    {
        $barberos = User::all();

        return view('horarios.edit', compact('horario', 'barberos'));
    }

    public function update(UpdateHorarioRequest $request, Horarios $horario)
    {
        $data = $request->validated();

        $data['hora_inicio_descanso'] = $request->input('hora_inicio_descanso') ?: null;
        $data['hora_fin_descanso'] = $request->input('hora_fin_descanso') ?: null;

        $horario->update($data);

        $nombreBarbero = $horario->barbero->name;

        return redirect()->route('horarios.index')
            ->with('success', "Horario de {$nombreBarbero} actualizado correctamente.");
    }

    public function destroy(Horarios $horario)
    {
        $nombreBarbero = $horario->barbero->name;

        $horario->delete();

        return redirect()->route('horarios.index')
            ->with('success', "Horario de {$nombreBarbero} eliminado correctamente.");
    }
}
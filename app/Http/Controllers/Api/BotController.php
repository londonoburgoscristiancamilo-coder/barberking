<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Citas;
use App\Models\Horarios;
use App\Models\Servicios;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BotController extends Controller
{
    // 1. Catálogo de Servicios
    public function getServicios()
    {
        $servicios = Servicios::select('id', 'nombre', 'precio', 'duracion_minutos')->get();
        return response()->json($servicios);
    }

    // 2. Lista de Barberos
    public function getBarberos()
    {
        $barberos = User::select('id', 'name')->get();
        return response()->json($barberos);
    }

    // 3. Consultar Disponibilidad Exacta
    public function getDisponibilidad(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'servicio_id' => 'required|exists:servicios,id',
            'barbero_id' => 'required|exists:users,id'
        ]);

        $fecha = Carbon::parse($request->fecha);
        $servicio = Servicios::find($request->servicio_id);
        $duracion = $servicio->duracion_minutos;

        // Buscar turno del barbero ese día
        $horario = Horarios::where('barbero_id', $request->barbero_id)
            ->whereDate('fecha', $fecha)
            ->first();

        if (!$horario) {
            return response()->json([]); // No trabaja ese día
        }

        // Buscar citas que ya están ocupadas
        $citas = Citas::where('barbero_id', $request->barbero_id)
            ->whereDate('fecha_cita', $fecha)
            ->whereIn('estado', ['confirmado', 'pendiente'])
            ->with('servicio')
            ->get();

        $citasOcupadas = [];
        foreach ($citas as $cita) {
            $inicio = Carbon::parse($fecha->toDateString() . ' ' . $cita->hora_cita);
            $fin = $inicio->copy()->addMinutes($cita->servicio->duracion_minutos);
            $citasOcupadas[] = ['inicio' => $inicio, 'fin' => $fin];
        }

        $inicioTurno = Carbon::parse($fecha->toDateString() . ' ' . $horario->hora_inicio);
        $finTurno = Carbon::parse($fecha->toDateString() . ' ' . $horario->hora_fin);
        $inicioDescanso = $horario->hora_inicio_descanso ? Carbon::parse($fecha->toDateString() . ' ' . $horario->hora_inicio_descanso) : null;
        $finDescanso = $horario->hora_fin_descanso ? Carbon::parse($fecha->toDateString() . ' ' . $horario->hora_fin_descanso) : null;

        $horasDisponibles = [];
        $actual = $inicioTurno->copy();

        // Calcular los bloques libres saltando de 30 en 30 minutos
        while ($actual->copy()->addMinutes($duracion)->lte($finTurno)) {
            $finEstimado = $actual->copy()->addMinutes($duracion);
            $solapa = false;

            if ($inicioDescanso && $finDescanso) {
                if ($actual->lt($finDescanso) && $finEstimado->gt($inicioDescanso)) {
                    $solapa = true;
                }
            }

            if (!$solapa) {
                foreach ($citasOcupadas as $ocupada) {
                    if ($actual->lt($ocupada['fin']) && $finEstimado->gt($ocupada['inicio'])) {
                        $solapa = true;
                        break;
                    }
                }
            }

            if (!$solapa) {
                $horasDisponibles[] = $actual->format('H:i');
            }

            $actual->addMinutes(30);
        }

        return response()->json($horasDisponibles);
    }

    // 4. Guardar la Cita Final
    public function storeCita(Request $request)
    {
        $request->validate([
            'pushName' => 'required|string',
            'telefono_cliente' => 'required|string',
            'servicio_id' => 'required|exists:servicios,id',
            'barbero_id' => 'required|exists:users,id',
            'fecha_cita' => 'required|date',
            'hora_cita' => 'required|date_format:H:i'
        ]);

        $partesNombre = explode(' ', trim($request->pushName), 2);
        $nombre = $partesNombre[0];
        $apellido = $partesNombre[1] ?? 'WA';

        $cita = Citas::create([
            'nombre_cliente' => $nombre,
            'apellido_cliente' => $apellido,
            'telefono_cliente' => $request->telefono_cliente,
            'servicio_id' => $request->servicio_id,
            'barbero_id' => $request->barbero_id,
            'fecha_cita' => $request->fecha_cita,
            'hora_cita' => $request->hora_cita,
            'notas' => 'Agendado vía Bot de WhatsApp',
            'estado' => 'confirmado'
        ]);

        return response()->json([
            'success' => true,
            'mensaje' => 'Cita guardada correctamente',
            'cita_id' => $cita->id
        ]);
    }
}

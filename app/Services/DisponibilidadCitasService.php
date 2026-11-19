<?php

namespace App\Services;

use App\Models\Citas;
use App\Models\Horarios;
use App\Models\Servicios;
use Carbon\Carbon;

class DisponibilidadCitasService
{
    public function obtenerHoras(string $fecha, int $servicioId, int $barberoId, ?int $excluirCitaId = null): array
    {
        $fecha = Carbon::parse($fecha)->toDateString();
        $servicio = Servicios::findOrFail($servicioId);
        $duracion = (int) $servicio->duracion_minutos;

        if ($duracion <= 0) {
            return [];
        }

        // Buscar el horario de trabajo del barbero
        $horario = Horarios::where('barbero_id', $barberoId)
            ->whereDate('fecha', $fecha)
            ->first();

        if (!$horario) {
            return [];
        }

        // Consultar las citas que ocupan espacios
        $citas = Citas::with('servicio')
            ->where('barbero_id', $barberoId)
            ->whereDate('fecha_cita', $fecha)
            ->whereIn('estado', ['pendiente', 'confirmado', 'confirmada'])
            ->when($excluirCitaId, function ($query) use ($excluirCitaId) {
                $query->where('id', '!=', $excluirCitaId);
            })
            ->get();

        $citasOcupadas = [];

        foreach ($citas as $cita) {
            if (!$cita->servicio) {
                continue;
            }

            $inicio = Carbon::parse($fecha . ' ' . $cita->hora_cita);
            $fin = $inicio->copy()->addMinutes($cita->servicio->duracion_minutos);

            $citasOcupadas[] = [
                'inicio' => $inicio,
                'fin' => $fin
            ];
        }

        $inicioTurno = Carbon::parse($fecha . ' ' . $horario->hora_inicio);
        $finTurno = Carbon::parse($fecha . ' ' . $horario->hora_fin);

        $inicioDescanso = $horario->hora_inicio_descanso
            ? Carbon::parse($fecha . ' ' . $horario->hora_inicio_descanso)
            : null;

        $finDescanso = $horario->hora_fin_descanso
            ? Carbon::parse($fecha . ' ' . $horario->hora_fin_descanso)
            : null;

        $horasDisponibles = [];
        $actual = $inicioTurno->copy();
        $ahora = Carbon::now();

        // Comprobar posibles inicios cada 30 minutos
        while ($actual->copy()->addMinutes($duracion)->lte($finTurno)) {
            $finEstimado = $actual->copy()->addMinutes($duracion);
            $solapa = false;

            // No ofrecer horarios del pasado
            if ($actual->lte($ahora)) {
                $actual->addMinutes(30);
                continue;
            }

            // Comprobar el descanso del barbero
            if ($inicioDescanso && $finDescanso) {
                if ($actual->lt($finDescanso) && $finEstimado->gt($inicioDescanso)) {
                    $solapa = true;
                }
            }

            // Comprobar las citas existentes
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

        return $horasDisponibles;
    }
}

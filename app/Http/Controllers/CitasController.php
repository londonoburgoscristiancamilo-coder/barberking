<?php

namespace App\Http\Controllers;


use App\Models\Citas;
use App\Models\Horarios;
use App\Models\Servicios;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests\StoreCitaRequest;

class CitasController extends Controller
{
    public function index()
    {
        $citas = Citas::with(['servicio', 'barbero'])
            ->orderBy('fecha_cita', 'asc')
            ->orderBy('hora_cita', 'asc')
            ->get();

        return view('citas.index', compact('citas'));
    }

    public function create()
    {
        $servicios = Servicios::all();
        $barberos = User::all();

        return view('citas.create', compact('servicios', 'barberos'));
    }



    public function store(StoreCitaRequest $request)
    {
        Citas::create([
            'nombre_cliente'   => $request->nombre_cliente,
            'apellido_cliente' => $request->apellido_cliente,
            'servicio_id'      => $request->servicio_id,
            'barbero_id'       => $request->barbero_id,
            'fecha_cita'       => $request->fecha_cita,
            'hora_cita'        => $request->hora_cita,
            'notas'            => $request->notas,
        ]);

        return redirect()->route('citas.index')->with('success', 'Cita creada exitosamente.');
    }

    public function edit(string $id)
    {
        $citas = Citas::findOrFail($id);
        $servicios = Servicios::all();
        $barberos = User::all();

        return view('citas.edit', compact('citas', 'servicios', 'barberos'));
    }

    public function update(Request $request, string $id)
    {

        $cita = Citas::findOrFail($id);

        $cita->update([
            'nombre_cliente'   => $request->nombre_cliente,
            'apellido_cliente' => $request->apellido_cliente,
            'telefono_cliente' => $request->telefono_cliente,
            'servicio_id'      => $request->servicio_id,
            'barbero_id'       => $request->barbero_id,
            'fecha_cita'       => $request->fecha_cita,
            'hora_cita'        => $request->hora_cita,
            'notas'            => $request->notas,
        ]);

        return redirect()->route('citas.index')->with('success', 'Cita actualizada exitosamente.');
    }

    public function destroy(Citas $cita) {
        $cita->delete();

        return redirect()->route('citas.index')
            ->with('success', 'La cita ha sido eliminado correctamente.');
    }

    public function obtenerDisponibilidad(Request $request)
    {
        if (!$request->fecha_cita || !$request->barbero_id || !$request->servicio_id) {
            return response()->json([]);
        }

        $fecha = Carbon::parse($request->fecha_cita)->format('Y-m-d');
        $servicio = Servicios::findOrFail($request->servicio_id);
        $duracion = $servicio->duracion_minutos;

        $horario = Horarios::where('barbero_id', $request->barbero_id)
            ->whereDate('fecha', $fecha)
            ->first();

        if (!$horario) {
            return response()->json([]);
        }

        $citas = Citas::where('barbero_id', $request->barbero_id)
            ->whereDate('fecha_cita', $fecha)
            ->where('estado', '!=', 'C')
            ->get();

        $citasOcupadas = [];
        foreach ($citas as $cita) {
            $inicio = Carbon::parse($cita->fecha_cita);
            $servicioCita = Servicios::find($cita->servicio_id);
            $fin = $inicio->copy()->addMinutes($servicioCita->duracion_minutos);

            $citasOcupadas[] = ['inicio' => $inicio, 'fin' => $fin];
        }

        $inicioTurno = Carbon::parse($fecha . ' ' . $horario->hora_inicio);
        $finTurno = Carbon::parse($fecha . ' ' . $horario->hora_fin);

        $inicioDescanso = $horario->hora_inicio_descanso ? Carbon::parse($fecha . ' ' . $horario->hora_inicio_descanso) : null;
        $finDescanso = $horario->hora_fin_descanso ? Carbon::parse($fecha . ' ' . $horario->hora_fin_descanso) : null;

        $horasDisponibles = [];
        $actual = $inicioTurno->copy();

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
}

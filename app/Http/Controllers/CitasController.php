<?php

namespace App\Http\Controllers;

use App\Models\Citas;
use App\Models\Clientes;
use App\Models\Servicios;
use App\Models\User;
use App\Services\DisponibilidadCitasService;
use App\Http\Requests\StoreCitaRequest;
use App\Http\Requests\UpdateCitaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CitasController extends Controller
{
    public function index()
    {
        $citas = Citas::with(['cliente', 'servicio', 'barbero'])
            ->orderBy('fecha_cita', 'asc')
            ->orderBy('hora_cita', 'asc')
            ->get();

        return view('citas.index', compact('citas'));
    }

    public function create()
    {
        $clientes = Clientes::orderBy('nombres')->orderBy('apellidos')->get();
        $servicios = Servicios::all();
        $barberos = User::all();

        return view('citas.create', compact('clientes', 'servicios', 'barberos'));
    }

    public function store(StoreCitaRequest $request, DisponibilidadCitasService $disponibilidad)
    {
        $datos = $request->validated();

        DB::transaction(function () use ($datos, $disponibilidad) {
            // Bloquear las reservas del barbero durante la operación
            User::whereKey($datos['barbero_id'])->lockForUpdate()->firstOrFail();

            $horas = $disponibilidad->obtenerHoras(
                $datos['fecha_cita'],
                (int) $datos['servicio_id'],
                (int) $datos['barbero_id']
            );

            if (!in_array($datos['hora_cita'], $horas, true)) {
                throw ValidationException::withMessages([
                    'hora_cita' => 'La hora seleccionada ya no está disponible.'
                ]);
            }

            if ($datos['tipo_cliente'] === 'nuevo') {
                $cliente = Clientes::create([
                    'documento_identidad' => $datos['documento_identidad'],
                    'nombres' => $datos['nombres'],
                    'apellidos' => $datos['apellidos'],
                    'telefono' => $datos['telefono'] ?? null
                ]);

                $clienteId = $cliente->id;
            } else {
                $clienteId = $datos['cliente_id'];
            }

            Citas::create([
                'cliente_id' => $clienteId,
                'servicio_id' => $datos['servicio_id'],
                'barbero_id' => $datos['barbero_id'],
                'fecha_cita' => $datos['fecha_cita'],
                'hora_cita' => $datos['hora_cita'],
                'notas' => $datos['notas'] ?? null,
                'estado' => 'pendiente'
            ]);
        });

        return redirect()->route('citas.index')
            ->with('success', 'Cita creada exitosamente.');
    }

    public function edit(string $id)
    {
        $citas = Citas::with(['cliente', 'servicio', 'barbero'])->findOrFail($id);
        $clientes = Clientes::orderBy('nombres')->orderBy('apellidos')->get();
        $servicios = Servicios::all();
        $barberos = User::all();

        return view('citas.edit', compact('citas', 'clientes', 'servicios', 'barberos'));
    }

    public function update(UpdateCitaRequest $request, string $id, DisponibilidadCitasService $disponibilidad)
    {
        $datos = $request->validated();

        DB::transaction(function () use ($id, $datos, $disponibilidad) {
            User::whereKey($datos['barbero_id'])->lockForUpdate()->firstOrFail();

            $cita = Citas::findOrFail($id);

            $horas = $disponibilidad->obtenerHoras(
                $datos['fecha_cita'],
                (int) $datos['servicio_id'],
                (int) $datos['barbero_id'],
                (int) $cita->id
            );

            $fechaActual = $cita->fecha_cita->format('Y-m-d');
            $horaActual = substr($cita->hora_cita, 0, 5);

            $sinCambiosDeHorario =
                $fechaActual === $datos['fecha_cita'] &&
                $horaActual === $datos['hora_cita'] &&
                (int) $cita->servicio_id === (int) $datos['servicio_id'] &&
                (int) $cita->barbero_id === (int) $datos['barbero_id'];

            if (!$sinCambiosDeHorario && !in_array($datos['hora_cita'], $horas, true)) {
                throw ValidationException::withMessages([
                    'hora_cita' => 'La hora seleccionada no está disponible.'
                ]);
            }

            $cita->update([
                'cliente_id' => $datos['cliente_id'],
                'servicio_id' => $datos['servicio_id'],
                'barbero_id' => $datos['barbero_id'],
                'fecha_cita' => $datos['fecha_cita'],
                'hora_cita' => $datos['hora_cita'],
                'notas' => $datos['notas'] ?? null
            ]);
        });

        return redirect()->route('citas.index')
            ->with('success', 'Cita actualizada exitosamente.');
    }

    public function destroy(Citas $cita)
    {
        $cita->delete();

        return redirect()->route('citas.index')
            ->with('success', 'La cita ha sido eliminada correctamente.');
    }

    public function obtenerDisponibilidad(Request $request, DisponibilidadCitasService $disponibilidad)
    {
        $datos = $request->validate([
            'fecha_cita' => 'required|date_format:Y-m-d',
            'servicio_id' => 'required|integer|exists:servicios,id',
            'barbero_id' => 'required|integer|exists:users,id',
            'excluir_cita_id' => 'nullable|integer|exists:citas,id'
        ]);

        $horas = $disponibilidad->obtenerHoras(
            $datos['fecha_cita'],
            (int) $datos['servicio_id'],
            (int) $datos['barbero_id'],
            isset($datos['excluir_cita_id']) ? (int) $datos['excluir_cita_id'] : null
        );

        return response()->json($horas);
    }

    public function actualizarEstado(Request $request, Citas $cita)
    {
        // Validar el estado seleccionado
        $datos = $request->validate([
            'estado' => [
                'required',
                Rule::in([
                    'pendiente',
                    'confirmada',
                    'completada',
                    'cancelada'
                ])
            ]
        ]);

        // Evitar actualizaciones innecesarias
        if ($cita->estado === $datos['estado']) {
            return redirect()->route('citas.index');
        }

        // Actualizar únicamente el estado de la cita
        $cita->estado = $datos['estado'];
        $cita->save();

        return redirect()->route('citas.index')
            ->with('success', 'El estado de la cita se actualizó correctamente.');
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Citas;
use App\Models\Clientes;
use App\Models\Servicios;
use App\Models\User;
use App\Services\DisponibilidadCitasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    // 3. Consultar Cliente por Documento
    public function getCliente(Request $request)
    {
        $datos = $request->validate([
            'documento_identidad' => ['required', 'string', 'regex:/^[0-9]{5,10}$/']
        ]);

        $cliente = Clientes::where('documento_identidad', $datos['documento_identidad'])->first();

        if (!$cliente) {
            return response()->json([
                'encontrado' => false,
                'mensaje' => 'El cliente no se encuentra registrado'
            ]);
        }

        return response()->json([
            'encontrado' => true,
            'cliente' => $cliente->only([
                'id',
                'documento_identidad',
                'nombres',
                'apellidos',
                'telefono'
            ])
        ]);
    }

    // 4. Registrar un Nuevo Cliente
    public function storeCliente(Request $request)
    {
        $datos = $request->validate([
            'documento_identidad' => ['required', 'string', 'regex:/^[0-9]{5,10}$/', 'unique:clientes,documento_identidad'],
            'nombres' => 'required|string|max:125',
            'apellidos' => 'required|string|max:125',
            'telefono' => 'nullable|string|max:20'
        ]);

        $cliente = Clientes::create($datos);

        return response()->json([
            'success' => true,
            'mensaje' => 'Cliente registrado correctamente',
            'cliente' => $cliente->only([
                'id',
                'nombres',
                'apellidos'
            ])
        ], 201);
    }

    // 5. Consultar Disponibilidad Exacta
    public function getDisponibilidad(Request $request, DisponibilidadCitasService $disponibilidad)
    {
        $datos = $request->validate([
            'fecha' => 'required|date_format:Y-m-d',
            'servicio_id' => 'required|integer|exists:servicios,id',
            'barbero_id' => 'required|integer|exists:users,id'
        ]);

        $horas = $disponibilidad->obtenerHoras(
            $datos['fecha'],
            (int) $datos['servicio_id'],
            (int) $datos['barbero_id']
        );

        return response()->json($horas);
    }


    // 6. Consultar Citas de un Cliente
    public function getCitasCliente(Request $request)
    {
        $datos = $request->validate([
            'cliente_id' => 'required|integer|exists:clientes,id'
        ]);

        $citas = Citas::with([
                'servicio:id,nombre,precio,duracion_minutos',
                'barbero:id,name'
            ])
            ->where('cliente_id', $datos['cliente_id'])
            ->orderBy('fecha_cita')
            ->orderBy('hora_cita')
            ->get([
                'id',
                'cliente_id',
                'servicio_id',
                'barbero_id',
                'fecha_cita',
                'hora_cita',
                'estado'
            ]);

        return response()->json($citas);
    }

    // 7. Guardar la Cita Final
    public function storeCita(Request $request, DisponibilidadCitasService $disponibilidad)
    {
        $datos = $request->validate([
            'cliente_id' => 'required|integer|exists:clientes,id',
            'servicio_id' => 'required|integer|exists:servicios,id',
            'barbero_id' => 'required|integer|exists:users,id',
            'fecha_cita' => 'required|date_format:Y-m-d|after_or_equal:today',
            'hora_cita' => 'required|date_format:H:i',
            'notas' => 'nullable|string|max:1000'
        ]);

        return DB::transaction(function () use ($datos, $disponibilidad) {
            User::whereKey($datos['barbero_id'])->lockForUpdate()->firstOrFail();

            $horas = $disponibilidad->obtenerHoras(
                $datos['fecha_cita'],
                (int) $datos['servicio_id'],
                (int) $datos['barbero_id']
            );

            if (!in_array($datos['hora_cita'], $horas, true)) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'La hora seleccionada ya no está disponible'
                ], 409);
            }

            $cita = Citas::create([
                'cliente_id' => $datos['cliente_id'],
                'servicio_id' => $datos['servicio_id'],
                'barbero_id' => $datos['barbero_id'],
                'fecha_cita' => $datos['fecha_cita'],
                'hora_cita' => $datos['hora_cita'],
                'notas' => $datos['notas'] ?? 'Agendado vía Bot de WhatsApp',
                'estado' => 'confirmado'
            ]);

            return response()->json([
                'success' => true,
                'mensaje' => 'Cita guardada correctamente',
                'cita_id' => $cita->id
            ], 201);
        });
    }

    // 8. Cancelar una cita
    public function cancelarCita(Request $request)
    {
        $datos = $request->validate([
            'cita_id' => ['required', 'integer'],
            'cliente_id' => ['required', 'integer'],
            'documento_identidad' => ['required', 'string'],
            'telefono_whatsapp' => ['required', 'string'],
        ]);

        return DB::transaction(function () use ($datos) {

            $cita = Citas::with('cliente')
                ->lockForUpdate()
                ->find($datos['cita_id']);

            if (!$cita || !$cita->cliente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cita no encontrada.',
                ], 404);
            }

            $cliente = $cita->cliente;

            // Verificar que la cita pertenezca al cliente.
            if (
                (int) $cita->cliente_id !== (int) $datos['cliente_id'] ||
                (string) $cliente->documento_identidad !==
                    (string) $datos['documento_identidad']
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado para cancelar esta cita.',
                ], 403);
            }

            // Verificar el teléfono de WhatsApp.
            $telefonoCliente = preg_replace(
                '/\D/',
                '',
                (string) $cliente->telefono
            );

            $telefonoWhatsApp = preg_replace(
                '/\D/',
                '',
                $datos['telefono_whatsapp']
            );

            if (
                strlen($telefonoCliente) < 10 ||
                strlen($telefonoWhatsApp) < 10 ||
                substr($telefonoCliente, -10) !==
                    substr($telefonoWhatsApp, -10)
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'El teléfono no coincide.',
                ], 403);
            }

            // Solo se permiten estados cancelables.
            $estado = mb_strtolower(trim($cita->estado));

            if (!in_array($estado, [
                'pendiente',
                'confirmado',
                'confirmada',
            ], true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta cita no puede cancelarse.',
                ], 409);
            }

            // No permitir cancelar citas que ya comenzaron.
            $inicio = Carbon::parse(
                Carbon::parse($cita->fecha_cita)->format('Y-m-d')
                . ' ' . $cita->hora_cita,
                'America/Bogota'
            );

            if ($inicio->lessThanOrEqualTo(
                Carbon::now('America/Bogota')
            )) {
                return response()->json([
                    'success' => false,
                    'message' => 'La cita ya comenzó o pasó.',
                ], 409);
            }

            // Cancelar sin eliminar el registro.
            $cita->estado = 'Cancelado';
            $cita->save();

            return response()->json([
                'success' => true,
                'cancelada' => true,
                'cita_id' => $cita->id,
                'estado' => $cita->estado,
                'message' => 'Cita cancelada correctamente.',
            ]);
        });
    }
}

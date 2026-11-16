<?php

namespace App\Http\Controllers;

use App\Models\Servicios;
use App\Http\Requests\StoreServicioRequest;
use App\Http\Requests\UpdateServicioRequest;

class ServiciosController extends Controller
{
    public function index()
    {
        $servicios = Servicios::orderBy('nombre', 'desc')->get();
                
        return view('servicios.index', compact('servicios'));
    }

    public function create()
    {
        return view('servicios.create');
    }

    public function store(StoreServicioRequest $request)
    {
        $data = $request->safe()->only([
            'nombre',
            'duracion_minutos',
            'precio',
            'descripcion'
        ]);

        $servicio = Servicios::create($data);

        return redirect()->route('servicios.index')
            ->with('success', "Servicio {$servicio->nombre} creado correctamente.");
    }

    public function edit(Servicios $servicio)
    {
        return view('servicios.edit', compact('servicio'));
    }

    public function update(UpdateServicioRequest $request, Servicios $servicio)
    {
        $data = $request->safe()->only([
            'nombre',
            'duracion_minutos',
            'precio',
            'descripcion'
        ]);

        $servicio->update($data);

        return redirect()->route('servicios.index')
            ->with('success', "Servicio {$servicio->nombre} actualizado correctamente.");
    }

    public function destroy(Servicios $servicio)
    {
        $nombreServicio = $servicio->nombre;

        $servicio->delete();

        return redirect()->route('servicios.index')
            ->with('success', "Servicio {$nombreServicio} eliminado correctamente.");
    }
}
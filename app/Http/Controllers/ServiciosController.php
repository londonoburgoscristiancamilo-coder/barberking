<?php

namespace App\Http\Controllers;

use App\Models\Servicios;
use Illuminate\Http\Request;
use App\Http\Requests\StoreServicioRequest;
use App\Http\Requests\UpdateServicioRequest;

class ServiciosController extends Controller
{
    public function index()
    {
        $servicios = Servicios::orderBy("nombre", 'desc')->get();
        return view('servicios.index', compact('servicios'));
    }

    public function create()
    {
        return view('servicios.create');
    }

    public function store(StoreServicioRequest $request)
    {

    
        Servicios::create([
            'nombre'           => $request->nombre,
            'duracion_minutos' => $request->duracion_minutos,
            'precio'           => $request->precio,
            'descripcion'      => $request->descripcion,
        ]);

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio creado exitosamente.');
    }

    public function edit(Servicios $servicio)
    {
        return view('servicios.edit', compact('servicio'));
    }

    public function update(UpdateServicioRequest $request, Servicios $servicio)
    {

        $servicio->update([
            'nombre'           => $request->nombre,
            'duracion_minutos' => $request->duracion_minutos,
            'precio'           => $request->precio,
            'descripcion'      => $request->notas,
        ]);

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio actualizado exitosamente.');
    }

    public function destroy(Servicios $servicio)
    {
        $servicio->delete();

        return redirect()->route('servicios.index')
            ->with('success', 'El servicio ha sido eliminado correctamente.');
    }
}

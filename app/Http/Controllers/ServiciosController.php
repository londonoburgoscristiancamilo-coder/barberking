<?php

namespace App\Http\Controllers;

use App\Models\Servicios;
use Illuminate\Http\Request;

class ServiciosController extends Controller
{
    public function index()
    {
        $servicios = \App\Models\Servicios::all();
    
        return view('servicios.index', compact('servicios'));
    }

    public function create()
    {
        return view('servicios.create');
    }

    public function store(Request $request)
    {
        
    }

    public function show(Servicios $servicios)
    {
        
    }

    public function edit(Servicios $servicios)
    {
        
    }

    public function update(Request $request, Servicios $servicios)
    {
        
    }

    public function destroy(Servicios $servicios)
    {
        
    }
}

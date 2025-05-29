<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use Illuminate\Support\Facades\DB;


class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::all();
        return view('servicios.index', compact('servicios'));
    }

    public function create()
    {
        return view('servicios.create');
    }

    public function validarNombre(Request $request)
    {
        $nombre = $request->query('nombre');

        $existe = DB::table('servicios')
            ->where('nombre_servicio', $nombre)
            ->exists();

        return response()->json(['disponible' => !$existe]);
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_servicio' => 'unique:servicios,nombre_servicio|required|string|max:150',
            'descripcion' => 'nullable|string',
        ]);

        Servicio::create($validated);
        return redirect()->route('servicios.index')->with('success', 'Servicio creado exitosamente.');
    }

    public function show($id)
    {
        $servicio = Servicio::findOrFail($id);
        return view('servicios.show', compact('servicio'));
    }

    public function edit($id)
    {
        $servicio = Servicio::findOrFail($id);
        return view('servicios.edit', compact('servicio'));
    }

    public function update(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);

        $validated = $request->validate([
            'nombre_servicio' => 'unique:servicios,nombre_servicio|required|string|max:150',
            'descripcion' => 'nullable|string',
        ]);

        $servicio->update($validated);
        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->delete();

        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado correctamente.');
    }
}

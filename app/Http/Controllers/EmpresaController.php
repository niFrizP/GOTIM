<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;

class EmpresaController extends Controller
{
    // Mostrar todas las empresas
    public function index()
    {
        $empresas = Empresa::all();
        return view('empresas.index', compact('empresas'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        return view('empresas.create');
    }

    // Guardar nueva empresa
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_empresa' => 'required|string|max:255|unique:empresas,nombre_empresa',
            'rut_empresa' => 'required|string|max:20|unique:empresas,rut_empresa',
            'giro' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
        ]);

        $validated['estado'] = 'activo'; // Por defecto, activa

        Empresa::create($validated);

        return redirect()->route('empresas.index')->with('success', 'Empresa creada exitosamente.');
    }

    // Mostrar detalles de una empresa
    public function show(string $id)
    {
        $empresa = Empresa::findOrFail($id);
        return view('empresas.show', compact('empresa'));
    }

    // Mostrar formulario de edición
    public function edit(string $id)
    {
        $empresa = Empresa::findOrFail($id);
        return view('empresas.edit', compact('empresa'));
    }

    // Actualizar empresa
    public function update(Request $request, string $id)
    {
        $empresa = Empresa::findOrFail($id);

        $validated = $request->validate([
            'nombre_empresa' => 'required|string|max:255|unique:empresas,nombre_empresa,' . $empresa->id_empresa . ',id_empresa',
            'rut_empresa' => 'required|string|max:20|unique:empresas,rut_empresa,' . $empresa->id_empresa . ',id_empresa',
            'giro' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
        ]);

        $empresa->update($validated);

        return redirect()->route('empresas.index')->with('success', 'Empresa actualizada correctamente.');
    }

    // Inhabilitar (soft delete lógico)
    public function destroy(string $id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->estado = 'inhabilitado';
        $empresa->save();

        return redirect()->route('empresas.index')->with('success', 'Empresa inhabilitada correctamente.');
    }

    // Reactivar empresa
    public function reactivar(string $id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->estado = 'activo';
        $empresa->save();

        return redirect()->route('empresas.index')->with('success', 'Empresa reactivada correctamente.');
    }

    // Buscar empresa por RUT
    public function buscarPorRut(Request $request)
    {
        $request->validate(['rut_empresa' => 'required|string|max:20']);

        $empresa = Empresa::where('rut_empresa', $request->rut_empresa)->first();

        if ($empresa) {
            return response()->json($empresa);
        } else {
            return response()->json(['message' => 'Empresa no encontrada'], 404);
        }
    }
}

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
    public function create(Request $request)
    {
        if ($request->query('popup') == 1) {
            return view('empresas._form'); // Este es un form parcial, sin layout
        }

        return view('empresas.create');
    }


    // Guardar nueva empresa
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_emp'      => 'required|string|max:255|unique:empresas,nom_emp',
            'rut_empresa'  => 'required|string|max:20|unique:empresas,rut_empresa',
            'telefono'     => 'nullable|string|max:20',
            'razon_social' => 'nullable|string|max:255',
            'giro'         => 'nullable|string|max:255',
        ]);
        $validated['estado'] = 'activo'; // Por defecto, activa

        $empresa = Empresa::create($validated);

        // Si petición AJAX (modal), responder JSON con los datos básicos
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'empresa' => [
                    'id'            => $empresa->id_empresa,
                    'nombre'        => $empresa->nom_emp,
                    'razon_social'  => $empresa->razon_social,
                    'giro'          => $empresa->giro,
                    'rut_empresa'   => $empresa->rut_empresa,
                ]
            ]);
        }

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
            'nom_emp'      => 'required|string|max:255|unique:empresas,nom_emp,' . $empresa->id_empresa . ',id_empresa',
            'rut_empresa'  => 'required|string|max:20|unique:empresas,rut_empresa,' . $empresa->id_empresa . ',id_empresa',
            'telefono'     => 'nullable|string|max:20',
            'razon_social' => 'nullable|string|max:255',
            'giro'         => 'nullable|string|max:255',
            'direccion'    => 'nullable|string|max:255',
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

    // Buscar empresa por RUT (respuesta JSON)
    public function ComprobarPorRut($rut)
    {
        $rut = strtoupper(str_replace('.', '', $rut));

        $empresa = Empresa::whereRaw("REPLACE(UPPER(rut_empresa), '.', '') = ?", [$rut])->first();

        if ($empresa) {
            return response()->json([
                'existe'        => true,
                'id_empresa'    => $empresa->id_empresa,
                'razon_social'  => $empresa->razon_social,
                'giro'          => $empresa->giro,
                'nom_emp'       => $empresa->nom_emp
            ]);
        } else {
            return response()->json(['existe' => false]);
        }
    }

    // Validar nombre de empresa
    public function comprobarNombre(Request $request)
    {
        $request->validate(['nom_emp' => 'required|string|max:255']);

        $existe = Empresa::where('nom_emp', $request->nom_emp)->exists();

        return response()->json(['existe' => $existe]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::all();
        return view('clientes.index', compact('clientes')); // Correcto
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_cliente' => 'required|string|max:255',
            'apellido_cliente' => 'required|string|max:255',
            'email' => 'required|email|unique:clientes,email',
            'rut' => 'required|string|max:20|unique:clientes,rut',
            'nro_contacto' => 'required|string|max:20',
            'direccion' => 'nullable|string|max:255',
        ]);

        Cliente::create($validated);
        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cliente = Cliente::findOrFail($id);

        $validated = $request->validate([
            'nombre_cliente' => 'required|string|max:255',
            'apellido_cliente' => 'required|string|max:255',
            'email' => 'required|email|unique:clientes,email,' . $cliente->id_cliente . ',id_cliente',
            'rut' => 'required|string|max:20|unique:clientes,rut,' . $cliente->id_cliente . ',id_cliente',
            'nro_contacto' => 'required|string|max:20',
            'direccion' => 'nullable|string|max:255',
        ]);

        $cliente->update($validated);
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }
    
    // Método para inhabilitar cliente
    public function destroy($id_cliente)
    {
        $cliente = Cliente::findOrFail($id_cliente);
        $cliente->estado = 'inhabilitado';
        $cliente->save();

        return redirect()->route('clientes.index')->with('success', 'Cliente inhabilitado correctamente.');
    }

    // Método para reactivar cliente
    public function reactivar($id_cliente)
    {
        $cliente = Cliente::findOrFail($id_cliente);
        $cliente->estado = 'activo';
        $cliente->save();

        return redirect()->route('clientes.index')->with('success', 'Cliente reactivado correctamente.');
    }
    // Método para buscar cliente por RUT
    public function buscarPorRut(Request $request)
    {
        $request->validate([
            'rut' => 'required|string|max:20',
        ]);

        $cliente = Cliente::where('rut', $request->rut)->first();

        if ($cliente) {
            return response()->json($cliente);
        } else {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }
    }
    // Método para buscar cliente por nombre
    public function buscarPorNombre(Request $request)
    {
        $request->validate([
            'nombre_cliente' => 'required|string|max:255',
        ]);

        $clientes = Cliente::where('nombre_cliente', 'like', '%' . $request->nombre_cliente . '%')->get();

        if ($clientes->isNotEmpty()) {
            return response()->json($clientes);
        } else {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }
    } 
}

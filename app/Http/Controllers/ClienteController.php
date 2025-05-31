<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Ciudad;
use App\Models\Region;
use App\Models\Empresa;

/**
 * Controlador para manejar las operaciones relacionadas con los clientes.
 */
class ClienteController extends Controller
{
    // Muestra la lista de clientes
    public function index()
    {
        $clientes = Cliente::with(['empresa', 'region', 'ciudad'])->get();
        return view('clientes.index', compact('clientes'));
    }

    // Muestra el formulario de creación de un nuevo cliente
    public function create()
    {
        $regiones = Region::all();
        $ciudades = Ciudad::all();
        $empresas = Empresa::where('estado', 'activo')->get(); // Solo activas
        return view('clientes.create', compact('regiones', 'ciudades', 'empresas'));
    }

    // Almacena un nuevo cliente
    public function store(Request $request)
    {
        // Valida los datos del formulario
        $validated = $request->validate([
            'id_empresa' => 'required|exists:empresas,id_empresa',
            'nombre_cliente' => 'required|string|max:255',
            'apellido_cliente' => 'required|string|max:255',
            'razon_social' => 'nullable|string|max:255',
            'giro' => 'nullable|string|max:255',
            'id_region' => 'required|integer|exists:regiones,id_region',
            'id_ciudad' => 'required|integer|exists:ciudades,id_ciudad',
            'rut' => 'required|string|max:20|unique:clientes,rut',
            'email' => 'required|email|unique:clientes,email',
            'direccion' => 'nullable|string|max:255',
            'nro_contacto' => 'required|string|max:20',
            'tipo_cliente' => 'required|in:natural,empresa',
        ]);

        // Verifica si el RUT ya existe
        Cliente::create($validated);
        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente.');
    }

    // Muestra los detalles de un cliente específico
    public function show(string $id)
    {
        $cliente = Cliente::with(['empresa', 'region', 'ciudad'])->findOrFail($id);
        return view('clientes.show', compact('cliente'));
    }

    // Muestra el formulario de edición para un cliente específico
    public function edit(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        $regiones = Region::all();
        $ciudades = Ciudad::all();
        $empresas = Empresa::where('estado', 'activo')->get();
        return view('clientes.edit', compact('cliente', 'regiones', 'ciudades', 'empresas'));
    }

    // Actualiza un cliente existente
    public function update(Request $request, string $id)
    {
        $cliente = Cliente::findOrFail($id);

        $validated = $request->validate([
            'id_empresa' => 'required|exists:empresas,id_empresa',
            'nombre_cliente' => 'required|string|max:255',
            'apellido_cliente' => 'required|string|max:255',
            'razon_social' => 'nullable|string|max:255',
            'giro' => 'nullable|string|max:255',
            'id_region' => 'required|integer|exists:regiones,id_region',
            'id_ciudad' => 'required|integer|exists:ciudades,id_ciudad',
            'email' => 'required|email|unique:clientes,email,' . $cliente->id_cliente . ',id_cliente',
            'rut' => 'required|string|max:20|unique:clientes,rut,' . $cliente->id_cliente . ',id_cliente',
            'nro_contacto' => 'required|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'tipo_cliente' => 'required|in:natural,empresa',
        ]);

        $cliente->update($validated);
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    // Inhabilita un cliente
    public function destroy($id_cliente)
    {
        $cliente = Cliente::findOrFail($id_cliente);
        $cliente->estado = 'inhabilitado';
        $cliente->save();

        return redirect()->route('clientes.index')->with('success', 'Cliente inhabilitado correctamente.');
    }

    // Reactiva un cliente inhabilitado
    public function reactivar($id_cliente)
    {
        $cliente = Cliente::findOrFail($id_cliente);
        $cliente->estado = 'activo';
        $cliente->save();

        return redirect()->route('clientes.index')->with('success', 'Cliente reactivado correctamente.');
    }

    // Busca clientes por RUT
    public function buscarPorRut(Request $request)
    {
        $request->validate(['rut' => 'required|string|max:20']);

        $cliente = Cliente::where('rut', $request->rut)->first();

        if ($cliente) {
            return response()->json($cliente);
        } else {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }
    }

    // Busca clientes por nombre
    public function buscarPorNombre(Request $request)
    {
        $request->validate(['nombre_cliente' => 'required|string|max:255']);

        $clientes = Cliente::where('nombre_cliente', 'like', '%' . $request->nombre_cliente . '%')->get();

        if ($clientes->isNotEmpty()) {
            return response()->json($clientes);
        } else {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }
    }

    // Obtiene las ciudades de una región específica
    public function obtenerCiudades($region_id)
    {
        $ciudades = Ciudad::where('id_region', $region_id)->get();
        return response()->json($ciudades);
    }

    // Obtiene todas las regiones
    public function obtenerRegiones()
    {
        $regiones = Region::all();
        return response()->json($regiones);
    }
}

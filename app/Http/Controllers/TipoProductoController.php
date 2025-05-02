<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoProducto;

class TipoProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tiposProductos = TipoProducto::orderBy('nombre')->paginate(10); // Paginación de 10 por página
        return view('tipo_productos.index', compact('tiposProductos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tipo_productos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:tipo_productos,nombre',
            'descripcion' => 'nullable|string|max:255',
        ]);

        TipoProducto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado' => true,
        ]);

        return redirect()->route('tipo_productos.index')->with('success', 'Tipo de producto creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tipo = TipoProducto::with('productos')->findOrFail($id);
        $productos = $tipo->productos()->paginate(10); // Paginación de 10 por página
        return view('tipo_productos.show', compact('tipo', 'productos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tipo = TipoProducto::findOrFail($id);
        if (!$tipo->estado) {
            return redirect()->route('tipo_productos.index')->with('error', 'Este tipo está inactivo y no puede editarse.');
        }
        return view('tipo_productos.edit', compact('tipo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tipo = TipoProducto::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100|unique:tipo_productos,nombre,' . $tipo->tipo_producto_id . ',tipo_producto_id',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $tipo->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('tipo_productos.index')->with('success', 'Tipo de producto actualizado correctamente');
    }

    // Desactivar tipo de producto
    public function desactivar($id)
    {
        $tipo = TipoProducto::findOrFail($id);
        $tipo->estado = 0;
        $tipo->save();

        return redirect()->route('tipo_productos.index')->with('success', 'Tipo de producto desactivado correctamente.');
    }

    // Activar tipo de producto
    public function activar($id)
    {
        $tipo = TipoProducto::findOrFail($id);
        $tipo->estado = 1;
        $tipo->save();

        return redirect()->route('tipo_productos.index')->with('success', 'Tipo de producto activado correctamente.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\TipoProducto;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::all();
        return view('productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::all();
        $tiposProducto = TipoProducto::all();
        return view('productos.create', compact('categorias', 'tiposProducto'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_producto' => 'required|string|max:255',
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'tipo_producto_id' => 'nullable|exists:tipo_productos,tipo_producto_id',
            'marca' => 'nullable|string|max:255',
            'modelo' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'codigo' => [
                'required',
                'regex:/^(\d{8}|\d{13})$/',
                'unique:productos,codigo',
            ],
            'imagen' => 'nullable|image|max:2048',
        ]);

        // Manejo del archivo de imagen
        if ($request->hasFile('imagen')) {
            $ruta = $request->file('imagen')->store('imagenes_productos', 'public');
            $validated['imagen'] = $ruta;
        }

        Producto::create($validated);
        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = Categoria::all();
        $tiposProducto = TipoProducto::all();
        return view('productos.edit', compact('producto', 'categorias', 'tiposProducto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $producto = Producto::findOrFail($id);

        $validated = $request->validate([
            'nombre_producto' => 'required|string|max:255',
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'tipo_producto_id' => 'nullable|exists:tipo_productos,tipo_producto_id',
            'marca' => 'nullable|string|max:255',
            'modelo' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'codigo' => [
                'required',
                'regex:/^(\d{8}|\d{13})$/',
                'unique:productos,codigo,' . $producto->id_producto . ',id_producto',
            ],
            'imagen' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $ruta = $request->file('imagen')->store('imagenes_productos', 'public');
            $validated['imagen'] = $ruta;
        }


        $producto->update($validated);
        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * metodo para inabilitar los producto
     */


    // Método para buscar Producto por nombre 
    public function buscarPorNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $productos = Producto::where('nombre_producto', 'like', "%$nombre%")->get();
        return view('productos.index', compact('productos'));
    }



    /*Obtener el tipo producto*/
    public function obtenertipoproducto($tipo_producto_id)
    {
        $tipo_producto = TipoProducto::where('tipo_producto_id', $tipo_producto_id)->get();
        return response()->json($tipo_producto);
    }

    /*Obtener Categoria*/
    public function obtenercategoria($id_categoria)
    {
        $categorias = Categoria::where('id_categoria', $id_categoria)->get();
        return response()->json($categorias);
    }

    // Activar el producto
    public function reactivar($id_producto)
    {
        $producto = Producto::findOrFail($id_producto);
        $producto->estado = '1';
        $producto->save();

        return redirect()->route('productos.index')->with('success', 'Producto habilitado correctamente.');
    }
    // Desactivar el producto
    public function destroy($id_producto)
    {
        $producto = Producto::findOrFail($id_producto);
        $producto->estado = '0';
        $producto->save();

        return redirect()->route('productos.index')->with('success', 'Producto inhabilitado correctamente.');
    }

    public function validarCodigo(Request $request)
    {
        $codigo = $request->query('codigo');

        $existe = DB::where('codigo', $codigo)->exists();

        return response()->json(['disponible' => !$existe]);
    }
}

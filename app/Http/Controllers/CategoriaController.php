<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    // Mostrar todas las categorías activas
    public function index()
    {
        $categorias = Categoria::where('estado', 'Activa')->get();
        return view('categorias.index', compact('categorias'));
    }

    // Mostrar el formulario para crear una nueva categoría
    public function create()
    {
        return view('categorias.create');
    }
    // Validar y guardar una nueva categoría
    public function validarNombre(Request $request)
    {
        $nombre = $request->query('nombre');

        $existe = DB::table('categorias')
            ->where('nombre_categoria', $nombre)
            ->exists();

        return response()->json(['disponible' => !$existe]);
    }

    // Guardar una nueva categoría
    public function store(Request $request)
    {
        $request->validate([
            'nombre_categoria' => 'unique:categorias,nombre_categoria|required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
        ]);

        Categoria::create($request->only(['nombre_categoria', 'descripcion']));

        return redirect()->route('categorias.index')->with('success', 'Categoría creada con éxito.');
    }

    // Mostrar una categoría específica
    public function show($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('categorias.show', compact('categoria'));
    }

    // Mostrar el formulario para editar una categoría
    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('categorias.edit', compact('categoria'));
    }

    // Actualizar una categoría existente
    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);
        if ($categoria->deleted_at) {
            return redirect()->route('categorias.index')->with('error', 'No se puede editar una categoría inactiva.');
        }

        $request->validate([
            'nombre_categoria' => 'unique:categorias,nombre_categoria,' . $id . '|required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
        ]);

        $categoria->update($request->only(['nombre_categoria', 'descripcion']));
        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada con éxito.');
    }

    // Desactivar una categoría
    public function desactivar($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->estado = 'Inactiva';
        $categoria->save();

        return redirect()->route('categorias.index')->with('success', 'Categoría desactivada correctamente.');
    }


    // Reactivar una categoría
    public function reactivar($id)
    {
        $categoria = Categoria::findOrFail($id);
        if ($categoria->estado !== 'Inactiva') {
            return redirect()->route('categorias.index')->with('error', 'La categoría no está inactiva.');
        }
        $categoria->estado = 'Activa';
        $categoria->save();

        return redirect()->route('categorias.index')->with('success', 'Categoría reactivada correctamente.');
    }

    // Mostrar todas las categorías inactivas
    public function inactivas()
    {
        $categorias = Categoria::where('estado', 'Inactiva')->get();
        return view('categorias.inactivas', compact('categorias'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\HistorialInventario;
use Illuminate\Support\Facades\Auth;

class InventarioController extends Controller
{
    public function index()
    {
        $inventarios = Inventario::with('producto')->get();
        return view('inventario.index', compact('inventarios'));
    }

    public function create()
    {
        $productos = Producto::where('estado', true)->get();
        return view('inventario.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_producto' => 'required|exists:productos,id_producto',
            'cantidad' => 'required|integer|min:1',
            'fecha_ingreso' => 'nullable|date',
            'fecha_salida' => 'nullable|date|after_or_equal:fecha_ingreso',
        ]);

        Inventario::create($validated);
        return redirect()->route('inventario.index')->with('success', 'Registro de inventario creado.');
    }

    public function show($id)
    {
        $inventario = Inventario::with('producto')->findOrFail($id);
        return view('inventario.show', compact('inventario'));
    }

    public function edit($id)
    {
        $inventario = Inventario::findOrFail($id);
        $productos = Producto::where('estado', true)->get();
        return view('inventario.edit', compact('inventario', 'productos'));
    }

    public function update(Request $request, $id)
    {
        $inventario = Inventario::findOrFail($id);

        $validated = $request->validate([
            'id_producto' => 'required|exists:productos,id_producto',
            'cantidad' => 'required|integer|min:1',
            'fecha_ingreso' => 'nullable|date',
            'fecha_salida' => 'nullable|date|after_or_equal:fecha_ingreso',
        ]);

        $campos = ['id_producto', 'cantidad', 'fecha_ingreso', 'fecha_salida'];

        foreach ($campos as $campo) {
            $valorAnterior = $inventario->$campo;
            $valorNuevo = $validated[$campo] ?? null;

            if ($valorAnterior != $valorNuevo) {
                HistorialInventario::create([
                    'id_inventario' => $inventario->id_inventario,
                    'id_producto' => $inventario->id_producto,
                    'id_responsable' => Auth::id(),
                    'campo_modificado' => $campo,
                    'valor_anterior' => $valorAnterior,
                    'valor_nuevo' => $valorNuevo,
                    'fecha_modificacion' => now(),
                ]);
            }
        }

        $inventario->update($validated);

        return redirect()->route('inventario.index')->with('success', 'Inventario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $inventario = Inventario::findOrFail($id);
        $inventario->estado = 'eliminado';
        $inventario->save();

        return redirect()->route('inventario.index')->with('success', 'Registro de inventario inhabilitado.');
    }

    public function historial(Request $request)
    {
        $query = HistorialInventario::with(['inventario.producto', 'responsable']);

        if ($request->filled('producto')) {
            $query->whereHas('inventario.producto', function ($q) use ($request) {
                $q->where('descripcion', 'like', '%' . $request->producto . '%');
            });
        }

        if ($request->filled('responsable')) {
            $query->whereHas('responsable', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->responsable . '%');
            });
        }

        if ($request->filled('campo')) {
            $query->where('campo_modificado', $request->campo);
        }

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_modificacion', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha_modificacion', '<=', $request->fecha_fin);
        }

        $historial = $query->orderBy('fecha_modificacion', 'desc')->paginate(15)->withQueryString();

        return view('inventario.historial_inventario', compact('historial'));
    }

    public function desactivar($id)
    {
        $inventario = Inventario::findOrFail($id);
        $inventario->estado = 'eliminado';
        $inventario->save();

        return redirect()->route('inventario.index')->with('success', 'Registro de inventario desactivado.');
    }
    public function reactivar($id)
    {
        $inventario = Inventario::findOrFail($id);
        $inventario->estado = 'activo';
        $inventario->save();

        return redirect()->route('inventario.index')->with('success', 'Registro de inventario reactivado.');
    }
}

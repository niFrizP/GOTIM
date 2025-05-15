<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\HistorialInventario;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

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

            $isFecha = in_array($campo, ['fecha_ingreso', 'fecha_salida']);

            if ($isFecha) {
                $anteriorFormateado = $valorAnterior ? Carbon::parse($valorAnterior)->format('Y-m-d') : null;
                $nuevoFormateado = $valorNuevo ? Carbon::parse($valorNuevo)->format('Y-m-d') : null;

                if ($anteriorFormateado !== $nuevoFormateado) {
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
            } else {
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

        // Filtros
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

        // Obtener todos los registros para agruparlos
        $historialSinPaginar = $query->orderBy('fecha_modificacion', 'desc')->get();

        // Agrupar por fecha + responsable
        $agrupado = $historialSinPaginar->groupBy(function ($item) {
            $fecha = Carbon::parse($item->fecha_modificacion)->format('Y-m-d H:i:s');
            $responsable = $item->responsable->nombre ?? 'Sistema';
            return $fecha . '|' . $responsable;
        });

        // Mapeo de nombres amigables para los campos
        $nombreCampos = [
            'id_producto' => 'Producto',
            'cantidad' => 'Cantidad',
            'fecha_ingreso' => 'Fecha ingreso',
            'fecha_salida' => 'Fecha salida',
        ];

        // Consolidar cambios
        $historialAgrupado = $agrupado->map(function ($grupo) use ($nombreCampos) {
            return [
                'producto' => $grupo->first()->inventario->producto->nombre_producto ?? 'Producto eliminado',
                'campos' => $grupo->pluck('campo_modificado')->unique()->map(function ($campo) use ($nombreCampos) {
                    return $nombreCampos[$campo] ?? ucfirst(str_replace('_', ' ', $campo));
                })->values()->all(),

                'descripciones' => $grupo->map(function ($item) {
                    $campo = $item->campo_modificado;
                    $anterior = $item->valor_anterior;
                    $nuevo = $item->valor_nuevo;

                    switch ($campo) {
                        case 'id_producto':
                            $anteriorNombre = optional(\App\Models\Producto::find($anterior))->descripcion ?? $anterior;
                            $nuevoNombre = optional(\App\Models\Producto::find($nuevo))->descripcion ?? $nuevo;
                            return "<strong>Producto</strong> de <em>{$anteriorNombre}</em> a <em>{$nuevoNombre}</em>";

                        case 'cantidad':
                            return "<strong>Cantidad</strong> de <em>{$anterior}</em> a <em>{$nuevo}</em>";

                        case 'fecha_ingreso':
                        case 'fecha_salida':
                            try {
                                $anteriorF = $anterior ? \Carbon\Carbon::parse($anterior)->format('d-m-Y') : 'Sin fecha';
                                $nuevoF = $nuevo ? \Carbon\Carbon::parse($nuevo)->format('d-m-Y') : 'Sin fecha';
                                return "<strong>" . ucfirst(str_replace('_', ' ', $campo)) . "</strong> de <em>{$anteriorF}</em> a <em>{$nuevoF}</em>";
                            } catch (\Exception $e) {
                                return "<strong>" . ucfirst(str_replace('_', ' ', $campo)) . "</strong> modificado.";
                            }

                        default:
                            return "<strong>" . ucfirst(str_replace('_', ' ', $campo)) . "</strong> de <em>{$anterior}</em> a <em>{$nuevo}</em>";
                    }
                }),

                'responsable' => $grupo->first()->responsable->nombre ?? 'Sistema',
                'fecha_modificacion' => Carbon::parse($grupo->first()->fecha_modificacion)->format('d-m-Y H:i:s'),
            ];
        })->values();
        // Paginación manual
        $page = request()->get('page', 1);
        $perPage = 15;
        $currentPageItems = $historialAgrupado->slice(($page - 1) * $perPage, $perPage)->values();
        $historial = new LengthAwarePaginator(
            $currentPageItems,
            $historialAgrupado->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
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

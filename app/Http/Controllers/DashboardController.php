<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\OT;
use App\Models\Producto;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Filtros individuales para las tarjetas
        $filtros = [
            'ordenes' => $request->get('ordenes_filtro', 'año'),
            'completadas' => $request->get('completadas_filtro', 'año'),
            'clientes' => $request->get('clientes_filtro', 'total'),
        ];

        // Filtro global
        $filtro = $request->input('filtro', 'año');

        // Tarjetas
        $totalCliente = $this->filtrarClientes($filtros['clientes']);
        $totalOrden = $this->filtrarOT($filtros['ordenes']);
        $completedOrden = $this->filtrarOT($filtros['completadas'], 'Finalizada');
        

        // Query base para aplicar en los gráficos
        $query = OT::query();

        if ($filtro !== 'total') {
            if ($filtro === 'semana') {
                $query->whereBetween('fecha_entrega', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($filtro === 'mes') {
                $query->whereMonth('fecha_entrega', now()->month)
                    ->whereYear('fecha_entrega', now()->year);
            } elseif ($filtro === 'año') {
                $query->whereYear('fecha_entrega', now()->year);
            }
        }

        // Gráficos
        $ordersByMonth = (clone $query)
            ->select(DB::raw('COUNT(*) as count'), DB::raw('MONTHNAME(fecha_entrega) as month'))
            ->groupBy(DB::raw('MONTH(fecha_entrega)'), DB::raw('MONTHNAME(fecha_entrega)'))
            ->orderBy(DB::raw('MONTH(fecha_entrega)'))
            ->pluck('count', 'month');

        $ordersByStatus = (clone $query)
            ->with('estadoOT')
            ->get()
            ->groupBy(fn($ot) => $ot->estadoOT->nombre_estado)
            ->map->count();

        $productCategories = Producto::with('categoria')
            ->get()
            ->groupBy(fn($producto) => optional($producto->categoria)->nombre_categoria)
            ->map->count();

        $lowStockProducts = DB::table('inventario')
            ->join('productos', 'inventario.id_producto', '=', 'productos.id_producto')
            ->where('inventario.cantidad', '<', 3)
            ->select('productos.id_producto', 'productos.nombre_producto', 'inventario.cantidad')
            ->get();

        $responsableOrders = (clone $query)
            ->with('responsable')
            ->get()
            ->groupBy(fn($ot) => optional($ot->responsable)->nombre . ' ' . optional($ot->responsable)->apellido)
            ->map->count();

        $ordersByClient = (clone $query)
            ->with('cliente')
            ->get()
            ->groupBy(fn($ot) => optional($ot->cliente)->nombre . ' ' . optional($ot->cliente)->apellido)
            ->map->count();

        return view('dashboard', compact(
            'totalCliente',
            'totalOrden',
            'completedOrden',
            'ordersByMonth',
            'ordersByStatus',
            'productCategories',
            'lowStockProducts',
            'responsableOrders',
            'ordersByClient',
            'filtros',
            'filtro'
        ));
    }

    private function filtrarOT(string $filtro, ?string $estado = null): int
    {
        $query = OT::query();

        if ($estado) {
            $query->whereHas('estadoOT', function ($q) use ($estado) {
                $q->where('nombre_estado', $estado);
            });
        }

        if ($filtro === 'semana') {
            $query->whereBetween('fecha_entrega', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filtro === 'mes') {
            $query->whereMonth('fecha_entrega', now()->month)
                ->whereYear('fecha_entrega', now()->year);
        } elseif ($filtro === 'año') {
            $query->whereYear('fecha_entrega', now()->year);
        }

        return $query->count();
    }

    private function filtrarClientes(string $filtro): int
    {
        // Para filtrar por fecha de creación en el futuro
        return DB::table('clientes')->count();
    }
}

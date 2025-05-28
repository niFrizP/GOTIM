<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\OT;
use App\Models\EstadoOT;
use App\Models\Producto;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // // Tarjetas
        $totalCliente = DB::table('Clientes')->count();

        $totalOrden = DB::table('ot')->count();

        $completedOrden = OT::whereHas('estadoOT', function ($query) {
            $query->where('nombre_estado', 'Completada');
        })->count();

        // Órdenes por mes (usando fecha_entrega)
        $ordersPerMonth = DB::table('ot')
            ->select(DB::raw('COUNT(*) as count'), DB::raw('MONTHNAME(fecha_entrega) as month'))
            ->groupBy(DB::raw('MONTH(fecha_entrega)'), DB::raw('MONTHNAME(fecha_entrega)'))
            ->orderBy(DB::raw('MONTH(fecha_entrega)'))
            ->pluck('count', 'month');

        $ordersByStatus = OT::with('estadoOT')
            ->get()
            ->groupBy(fn($ot) => $ot->estadoOT->nombre_estado)
            ->map->count();

        $productCategories = Producto::with('categoria')
            ->get()
            ->groupBy(fn($producto) => $producto->categoria->nombre_categoria)
            ->map->count();

        $lowStockProducts = DB::table('inventario')
            ->join('productos', 'inventario.id_producto', '=', 'productos.id_producto')
            ->where('inventario.cantidad', '<', 3)
            ->select('productos.id_producto', 'productos.nombre_producto', 'inventario.cantidad')
            ->get();

        $responsableOrders = OT::with('responsable')
            ->get()
            ->groupBy(fn($ot) => optional($ot->responsable)->nombre . ' ' . optional($ot->responsable)->apellido)
            ->map->count();


        return view('dashboard', compact(
            'totalCliente',
            'totalOrden',
            'completedOrden',
            'ordersPerMonth',
            'ordersByStatus',
            'productCategories',
            'lowStockProducts',
            'responsableOrders'
        ));
    }
}

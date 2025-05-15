<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OT;
use App\Models\Cliente;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total órdenes
        $totalOT = OT::count();

        // Órdenes en estado "Evaluación"
        $otEnEvaluacion = OT::whereHas('estadoOT', function ($query) {
            $query->where('nombre_estado', 'Evaluación');
        })->count();

        // Órdenes en estado "Completada"
        $otCompletadas = OT::whereHas('estadoOT', function ($query) {
            $query->where('nombre_estado', 'Completada');
        })->count();

        // Total clientes
        $totalClientes = Cliente::count();

        // Productos más usados en órdenes (con conteo en detalleProductos)
        $productosMasUsados = Producto::withCount('detalleProductos')
            ->orderByDesc('detalle_productos_count')
            ->take(5)
            ->get()
            ->map(function ($p) {
                return [
                    'nombre_producto' => $p->nombre_producto,
                    'usos' => $p->detalle_productos_count
                ];
            });

        // Productos con stock menor a 3
        $productosBajoStock = Producto::where('stock', '<', 3)->get();

        return view('dashboard', compact(
            'totalOT',
            'otEnEvaluacion',
            'otCompletadas',
            'totalClientes',
            'productosMasUsados',
            'productosBajoStock'
        ));
    }
}

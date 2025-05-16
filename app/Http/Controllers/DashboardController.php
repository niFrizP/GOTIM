<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
// Tarjetas
    $totalUsers = DB::table('usuarios')->count();
    $totalOrders = DB::table('ot')->count();
    $completedOrders = DB::table('ot')
        ->join('estado_ot', 'ot.id_estado', '=', 'estado_ot.id_estado')
        ->where('estado_ot.nombre_estado', 'Completada') // Ajusta según el valor real
        ->count();

    // Órdenes por mes (usando fecha_entrega)
    $ordersPerMonth = DB::table('ot')
        ->select(DB::raw('COUNT(*) as count'), DB::raw('MONTHNAME(fecha_entrega) as month'))
        ->groupBy(DB::raw('MONTH(fecha_entrega)'))
        ->orderBy(DB::raw('MONTH(fecha_entrega)'))
        ->pluck('count', 'month');

    $labels = $ordersPerMonth->keys();
    $data = $ordersPerMonth->values();

    return view('dashboard', compact('totalUsers', 'totalOrders', 'completedOrders', 'labels', 'data'));
        
    }


}

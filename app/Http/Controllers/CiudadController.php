<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use Illuminate\Http\Request;

class CiudadController extends Controller
{
    /**
     * Retorna las ciudades según la región seleccionada.
     */
    public function getCiudadesPorRegion($regionId)
    {
        $ciudades = Ciudad::where('id_region', $regionId)->get();

        return response()->json($ciudades);
    }
}

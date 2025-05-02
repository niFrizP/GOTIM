<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $table = 'inventario'; // importante porque Laravel pluraliza por defecto
    protected $primaryKey = 'id_inventario';
    public $timestamps = true;

    protected $fillable = [
        'id_producto',
        'cantidad',
        'fecha_ingreso',
        'fecha_salida',
        'estado',
    ];

    // Relación con Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}

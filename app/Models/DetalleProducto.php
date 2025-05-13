<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleProducto extends Model
{
    use HasFactory;

    protected $table = 'detalle_producto';
    protected $primaryKey = 'id_detalle_producto';
    public $timestamps = false;

    protected $fillable = [
        'id_ot',
        'id_producto',
        'id_inventario',
        'cantidad',
    ];

    /**
     * Relaciones
     */

    // Relación con OT (muchos a uno)
    public function ot()
    {
        return $this->belongsTo(OT::class, 'id_ot');
    }

    // Relación con Producto (muchos a uno)
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    // Relación con Inventario (muchos a uno)
    public function inventario()
    {
        return $this->belongsTo(Inventario::class, 'id_inventario');
    }
}

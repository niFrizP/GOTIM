<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialInventario extends Model
{
    use HasFactory;

    protected $table = 'historial_inventario';
    protected $primaryKey = 'id_historial';
    public $timestamps = false;

    protected $fillable = [
        'id_inventario',
        'id_producto',
        'id_responsable',
        'campo_modificado',
        'valor_anterior',
        'valor_nuevo',
        'fecha_modificacion',
    ];

    // Relación con el inventario
    public function inventario()
    {
        return $this->belongsTo(\App\Models\Inventario::class, 'id_inventario');
    }

    // Relación con el producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    // Relación con el usuario responsable
    public function responsable()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_responsable');
    }
}

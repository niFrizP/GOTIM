<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TipoProducto;
use App\Models\Categoria;
use App\Models\Inventario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\DetalleProducto;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';
    protected $primaryKey = 'id_producto';

    protected $fillable = [
        'nombre_producto',
        'id_categoria',
        'tipo_producto_id',
        'marca',
        'modelo',
        'descripcion',
        'codigo',
        'imagen',
        'estado',
    ];

    // Relaciones

    public function tipoProducto()
    {
        return $this->belongsTo(TipoProducto::class, 'tipo_producto_id');
    }

    public function detalleProductos()
    {
        return $this->hasMany(DetalleProducto::class, 'id_producto', 'id_producto');
    }


    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }
    // En App\Models\Producto.php
    public function inventario()
    {
        return $this->hasMany(Inventario::class, 'id_producto', 'id_producto');
    }
}

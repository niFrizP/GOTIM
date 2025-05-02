<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class TipoProducto extends Model
{
    protected $table = 'tipo_productos';
    protected $primaryKey = 'tipo_producto_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado'
    ];

    // Relación con productos --Descomentar en el futuro
    public function productos()
    {
        return $this->hasMany(Producto::class, 'tipo_producto_id', 'tipo_producto_id');
    }
}

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

    /**
     * Relaciones
     */

    // Relación con productos 
    public function productos()
    {
        return $this->hasMany(Producto::class, 'tipo_producto_id', 'tipo_producto_id', 'id_categoria');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }


}

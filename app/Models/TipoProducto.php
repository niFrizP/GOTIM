<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoProducto extends Model
{
    protected $table = 'tipo_productos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo'
    ];

    /**
     * Relaciones
    */

    // Relación con productos --Descomentar en el futuro
    public function productos() {
    return $this->hasMany(Producto::class);
    }

    
}
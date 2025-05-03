<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TipoProducto;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }  
    
}

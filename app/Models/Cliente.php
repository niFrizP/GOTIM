<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\OT;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';

    protected $fillable = [
        'nombre_cliente',
        'apellido_cliente',
        'rut',
        'email',
        'direccion',
        'nro_contacto',
    ];

    /**
     * Relaciones
     */

     
    // Un cliente tiene muchas órdenes de trabajo
    public function ordenesTrabajo()
    {
        return $this->hasMany(OT::class, 'id_cliente', 'id_cliente');
    }
}
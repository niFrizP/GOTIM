<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\OT;
use App\Models\Region;
use App\Models\Ciudad;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';

    protected $fillable = [
        'nombre_cliente',
        'apellido_cliente',
        'razon_social',
        'giro',
        'rut',
        'email',
        'direccion',
        'nro_contacto',
        'id_region',
        'id_ciudad',
    ];

    /**
     * Relaciones
     */


    // Un cliente tiene muchas órdenes de trabajo
    public function ordenesTrabajo()
    {
        return $this->hasMany(OT::class, 'id_cliente', 'id_cliente');
    }
    // Un cliente pertenece a una región
    public function region()
    {
        return $this->belongsTo(Region::class, 'id_region', 'id_region');
    }
    // Un cliente pertenece a una ciudad
    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'id_ciudad', 'id_ciudad');
    }
}

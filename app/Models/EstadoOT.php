<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\OT; 

class EstadoOT extends Model
{
    use HasFactory;

    protected $table = 'estado_ot';
    protected $primaryKey = 'id_estado';

    protected $fillable = [
        'nombre_estado',
        'descripcion',
    ];

    /**
     * Relaciones
     */

    // Un estado puede tener muchas órdenes de trabajo
    public function ordenesTrabajo()
    {
        return $this->hasMany(OT::class, 'id_estado', 'id_estado');
    }
}

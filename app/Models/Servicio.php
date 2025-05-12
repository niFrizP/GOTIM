<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_servicio';

    protected $fillable = [
        'nombre_servicio',
        'descripcion',
    ];

    /**
     * Relación con órdenes de trabajo (muchos a muchos)
     */
    public function ordenesTrabajo()
    {
        return $this->belongsToMany(\App\Models\OT::class, 'servicios_ot', 'id_servicio', 'id_ot')
            ->withTimestamps();
    }
}

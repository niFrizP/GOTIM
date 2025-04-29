<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    protected $table = 'ciudades';
    protected $primaryKey = 'id_ciudad';
    protected $fillable = [
        'nombre_ciudad',
        'id_region',
    ];

    /**
     * Relaciones
     */
    // Una ciudad pertenece a una región
    public function region()
    {
        return $this->belongsTo(Region::class, 'id_region');
    }
}

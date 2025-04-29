<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'regiones';
    protected $primaryKey = 'id_region';
    protected $fillable = [
        'nombre_region',
    ];

    /**
     * Relaciones
     */
    // Una región tiene muchas ciudades
    public function ciudades()
    {
        return $this->hasMany(Ciudad::class, 'id_region');
    }
}

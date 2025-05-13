<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleOT extends Model
{
    use HasFactory;

    protected $table = 'detalle_ot';
    protected $primaryKey = 'id_detalle';
    public $timestamps = false;

    protected $fillable = [
        'id_ot',
        'descripcion_actividad',
    ];

    /**
     * Relaciones
     */

    // Relación con OT (muchos a uno)
    public function ot()
    {
        return $this->belongsTo(OT::class, 'id_ot');
    }
}

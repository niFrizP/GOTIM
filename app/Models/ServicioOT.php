<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioOT extends Model
{
    use HasFactory;

    protected $table = 'servicios_ot';
    protected $primaryKey = 'id_servicio_ot';

    protected $fillable = [
        'id_servicio',
        'id_ot',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio');
    }

    public function ot()
    {
        return $this->belongsTo(OT::class, 'id_ot');
    }
}

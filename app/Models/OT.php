<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OT extends Model
{
    use HasFactory;

    protected $table = 'ot';
    protected $primaryKey = 'id_ot';

    protected $fillable = [
        'id_cliente',
        'id_responsable',
        'id_estado',
        'fecha_creacion',
        'estado',
    ];

    /**
     * Relaciones
     */

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function responsable()
    {
        return $this->belongsTo(user::class, 'id_responsable', 'id_usuario');
    }

    public function estadoOT()
    {
        return $this->belongsTo(EstadoOT::class, 'id_estado', 'id_estado');
    }

    public function detalles()
    {
        /*return $this->hasMany(DetalleOT::class, 'id_ot', 'id_ot');*/
    }

    public function historial()
    {
        /* return $this->hasMany(HistorialOT::class, 'id_ot', 'id_ot');*/
    }
}

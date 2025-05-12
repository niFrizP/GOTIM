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
        'fecha_entrega',
        'fecha_creacion',
        'estado',
    ];

    protected $casts = [
        'fecha_creacion' => 'date',
        'fecha_entrega'  => 'date:Y-m-d',
    ];

    /**
     * Relaciones
     */

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'id_responsable');
    }

    public function estadoOT()
    {
        return $this->belongsTo(EstadoOT::class, 'id_estado');
    }

    public function detalleOT()
    {
        return $this->hasMany(DetalleOT::class, 'id_ot');
    }
    public function descripcionActividad()
    {
        return optional($this->detalleOT->first())->descripcion_actividad;
    }

    // Relación para los productos (DetalleProducto)
    public function productos()
    {
        return $this->hasMany(DetalleProducto::class, 'id_ot');
    }

    // Alias para compatibilidad con tu controlador (detalleProductos())
    public function detalleProductos()
    {
        return $this->productos();
    }

    public function archivosAdjuntos()
    {
        return $this->hasMany(ArchivoAdjuntoOT::class, 'id_ot');
    }

    public function servicios()
    {
        return $this->belongsToMany(
            Servicio::class,     // modelo relacionado
            'servicios_ot',      // tabla pivote
            'id_ot',             // FK de OT en la pivote
            'id_servicio'        // FK de Servicio en la pivote
        )->withTimestamps();
    }
    public function historial()
    {
        return $this->hasMany(HistorialOT::class, 'id_ot');
    }
}

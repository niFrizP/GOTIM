<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivoAdjuntoOT extends Model
{
    use HasFactory;

    protected $table = 'archivosadjuntos_ot'; // nombre de la tabla
    protected $primaryKey = 'id_archivo';      // clave primaria

    protected $fillable = [
        'id_ot',
        'ruta_archivo',
        'tipo_archivo',
        'nombre_original',
    ];

    /**
     * Relación: un archivo pertenece a una OT
     */
    public function ot()
    {
        return $this->belongsTo(\App\Models\OT::class, 'id_ot', 'id_ot');
    }
}

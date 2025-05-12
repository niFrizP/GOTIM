<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistorialOT extends Model
{
    use HasFactory;

    protected $table = 'historial_ot';
    protected $primaryKey = 'id_historial_ot';
    protected $casts = [
        'fecha_modificacion' => 'datetime:Y-m-d H:i:s',
    ];


    protected $fillable = [
        'id_ot',
        'id_responsable',
        'campo_modificado',
        'valor_anterior',
        'valor_nuevo',
        'fecha_modificacion',
    ];

    // Relaciones
    public function ot()
    {
        return $this->belongsTo(OT::class, 'id_ot');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_responsable');
    }
}

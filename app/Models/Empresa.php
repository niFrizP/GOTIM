<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresas';

    protected $primaryKey = 'id_empresa';

    protected $fillable = [
        'nom_emp',
        'telefono',
        'razon_social',
        'rut_empresa',
        'giro',
        'direccion',
        'id_region',
        'id_ciudad'
    ];

    // Una empresa puede tener muchos clientes
    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'id_empresa', 'id_empresa');
    }

    // Relación con región
    public function region()
    {
        return $this->belongsTo(Region::class, 'id_region', 'id_region');
    }

    // Relación con ciudad
    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'id_ciudad', 'id_ciudad');
    }
}

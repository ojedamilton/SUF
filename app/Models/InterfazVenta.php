<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterfazVenta extends Model
{
    use HasFactory;

    protected $table='interfaz_ventas';

    protected $fillable = [
        'id',
        'nro_local',
        'nro_contrato',
        'pos_local',
        'id_pto_vta',
        'ruta',
        'id_empresa',
        'created_at',
        'updated_at'
    ];

}

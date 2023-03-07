<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $table='empresas';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'nombreEmpresa',
        'cuitEmpresa',
        'direccionEmpresa',
        'idTipoEmpresa',
        'estadoEmpresa',
        'razonSocial',
        'ingresosBrutosEmpresa',
        'telEmpresa',
        'inicioActividades'
    ];
}

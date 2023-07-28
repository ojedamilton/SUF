<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $table='compras';

    protected $fillable = [
        'id',
        'numeroCompra',
        'fechaCompra',
        'estadoCompra',
        'idProveedor',
        'idValor',
        'idUsuario',
        'idEmpresa',
        'totalCompra',
        'descuento'
    ];
}

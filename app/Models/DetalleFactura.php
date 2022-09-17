<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleFactura extends Model
{
    use HasFactory;

    protected $table='detallesFacturas';

    protected $fillable = [
        'id',
        'precioVenta',
        'cantidadArticulo',
        'idFactura',
        'idArticulo',
        'totalDetalle'
    ];
}

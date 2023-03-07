<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PuntoVenta;
use App\Models\TipoFactura;

class Factura extends Model
{
    use HasFactory;

    protected $table='facturas';

    protected $fillable = [
        'id',
        'fechaFactura',
        'estadoFactura',
        'idCliente',
        'idValor',
        'idUsuario',
        'totalFactura',
        'idPuntoVenta',
        'idTipoFactura'
    ];

    public function puntoventa(){
       
        return $this->belongsTo(PuntoVenta::class,'idPuntoVenta');
    }
    public function tipofactura(){
       
        return $this->belongsTo(TipoFactura::class,'idTipoFactura');
    }
}

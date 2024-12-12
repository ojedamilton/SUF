<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Factura;

class PuntoVenta extends Model
{
    use HasFactory;

    protected $table='puntoventa';

    protected $fillable = [
        'id',
        'numPuntoVenta'
    ];

    public function facturas(){
        return $this->hasMany(Factura::class,'idpuntoVenta');
    }

    public function notascredito(){
        return $this->hasMany(NotaCredito::class,'idPuntoVenta');
    }

    public function interfazventas(){
        return $this->hasMany(InterfazVenta::class,'idPuntoVenta');
    }
}

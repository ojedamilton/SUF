<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Factura;

class PuntoVenta extends Model
{
    use HasFactory;

    protected $table='puntoVenta';

    protected $fillable = [
        'id',
        'numeroPuntoVenta'
    ];

    public function facturas (){
        return $this->hasMany(Factura::class,'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Factura;

class TipoFactura extends Model
{
    use HasFactory;

    protected $table='tipoFacturas';

    public $primaryKey='idTipoFactura';

    protected $fillable = [
        'idTipoFactura',
        'tipoFactura'
    ];

    public function facturas (){
        return $this->hasMany(Factura::class,'idTipoFactura');
    }
}

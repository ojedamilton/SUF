<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Proveedor;
use App\Models\DetalleCompra;


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

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'idProveedor');
    }

    public function detallescompra(){
       
        return $this->hasMany(DetalleCompra::class,'idCompra');
    }
}

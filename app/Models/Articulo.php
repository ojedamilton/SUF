<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetallesFactura;
use App\Models\Proveedor;

class Articulo extends Model
{
    use HasFactory;
    
    protected $table='articulos';

    protected $fillable = [
        'id',
        'nombreArticulo',
        'precio',
        'precioCompra',
        'idCategoria',
        'estadoArticulo',

    ];

    public function detallesFactura()
    {
        return $this->hasMany(DetalleFactura::class, 'id');
    }

    public function stock()
    {
        return $this->hasOne(Stock::class, 'idArticulo');
    }

    public function proveedores(){

        return $this->belongsToMany(Proveedor::class,'articulo_proveedores','idArticulo','idProveedor');
    }
}

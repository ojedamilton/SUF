<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Articulo;

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

    public function articulo()
    {
        //dd($this);
        return $this->belongsTo(Articulo::class, 'idArticulo');
    }
}

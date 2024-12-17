<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Articulo;
use Illuminate\Database\Eloquent\SoftDeletes;


class DetalleCompra extends Model
{
    use HasFactory, SoftDeletes;

    protected $table='detallescompras';

    protected $fillable = [
        'id',
        'precioCompra',
        'cantidadArticulo',
        'idCompra',
        'idArticulo',
        'totalDetalle'
    ];

    public function articulo()
    {
        //dd($this);
        return $this->belongsTo(Articulo::class, 'idArticulo');
    }

    public function compra()
    {
        return $this->belongsTo(Compra::class, 'idCompra');
    }
}

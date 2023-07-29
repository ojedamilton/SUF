<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    // Indicamos cual es la tabla del modelo:
    protected $table = 'stocks';

    // Indicamos los campos que se pueden cargar de manera masiva:
    protected $fillable = [
        'idArticulo',
        'idProveedor',
        'cantidad',
        'cantidadMinima',
        'cantidadBackup',
    ];

    // Generamos las relaciones con el modelo Articulo.
    public function articulo(){
        return $this->belongsTo(Articulo::class, 'idArticulo');
    }
    // Generamos las relaciones con el modelo Proveedor.
    public function proveedor(){
        return $this->belongsTo(Proveedor::class, 'idProveedor');
    }
}

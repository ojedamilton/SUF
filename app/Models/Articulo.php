<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    use HasFactory;
    
    protected $table='articulos';

    protected $fillable = [
        'id',
        'precio',
        'nombreArticulo',
        'stock',
        'idCategoria',
        'estadoValor',
    ];
}

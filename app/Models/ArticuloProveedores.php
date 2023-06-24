<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticuloProveedores extends Model
{
    use HasFactory;

    protected $table = 'articulo_proveedores';

    protected $fillable = [
        'idArticulo',
        'idProveedor',
    ];
}

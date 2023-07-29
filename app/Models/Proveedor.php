<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table='proveedors';

    protected $fillable = [
        'id',
        'nombreProveedor',
        'apellidoProveedor',
        'cuitProveedor',
        'emailProveedor',
        'direccionProveedor',
        'telefonoProveedor',
        'estadoProveedor',
        'id_empresa',
    ];
}

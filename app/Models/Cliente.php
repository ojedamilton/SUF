<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Persona
{
    use HasFactory;

    protected $table='clientes';
   
    protected $fillable = [
        'id',
        'nombreCliente',
        'apellidoCliente',
        'dniCliente',
        'emailCliente',
        'direccionCliente',
        'telefonoCliente',
        'estadoCliente',
        'idEmpresa'
    ];
}

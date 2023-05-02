<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class usuarioEmpresa extends Model
{
    use HasFactory;

    protected $table='usuariosEmpresas';

    protected $fillable = [
        'idUsuario',
        'idEmpresa',
        'estado'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGrupo extends Model
{
    use HasFactory;

    protected $table='usuarioGrupos';

    protected $fillable = [
        'idUsuario',
        'idGrupo',
        'estadoUsuarioGrupos'
    ];
}

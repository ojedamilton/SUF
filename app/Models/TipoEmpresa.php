<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEmpresa extends Model
{
    use HasFactory;

    protected $table='tiposempresas';

    protected $fillable = [
        'id',
        'nombreTipoEmpresa',
        'estadoTipoEmpresa'
    ];
}
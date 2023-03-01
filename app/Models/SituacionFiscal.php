<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SituacionFiscal extends Model
{
    use HasFactory;

    protected $table='situacionfiscal';

    protected $fillable = [
        'id',
        'nombreSituacion',
        'estadoSituacion'
    ];
}
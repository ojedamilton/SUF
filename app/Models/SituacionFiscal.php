<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente;


class SituacionFiscal extends Model
{
    use HasFactory;

    protected $table='situacionFiscal';

    protected $fillable = [
        'id',
        'nombreSituacion',
        'estadoSituacion'
    ];

    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'idSituacion');
    }
}
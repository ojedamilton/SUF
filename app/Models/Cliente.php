<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SituacionFiscal;


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
        'idEmpresa',
        'idSituacion',
        'razonSocial',
    ];

    public function situacion()
    {
        return $this->belongsTo(SituacionFiscal::class, 'idSituacion');
    }
}

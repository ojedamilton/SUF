<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PuntoVenta;
use App\Models\TipoFactura;
use App\Models\Cliente;
use App\Models\DetalleNotaCredito;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class NotaCredito extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $table='notascredito';

    protected $fillable = [
        'id',
        'numeroNotaCredito',
        'fechaNotaCredito',
        'estadoNotaCredito',
        'idCliente',
        'idValor',
        'idUsuario',
        'idEmpresa',
        'totalNotaCredito',
        'idpuntoVenta',
        'idTipoFactura',
        'descuento'
    ];

    public function puntoventa(){
       
        return $this->belongsTo(PuntoVenta::class,'idpuntoVenta');
    }
    
    public function tipofactura(){
       
        return $this->belongsTo(TipoFactura::class,'idTipoFactura');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente');
    }

    public function detallesnotacredito(){
       
        return $this->hasMany(DetalleNotaCredito::class,'idNotaCredito');
    }
}

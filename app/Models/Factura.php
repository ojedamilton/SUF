<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PuntoVenta;
use App\Models\TipoFactura;
use App\Models\Cliente;
use App\Models\DetalleFactura;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Factura extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $table='facturas';

    protected $auditInclude = [
        'id',
        'fechaFactura',
        'estadoFactura',
        'idCliente',
        'idValor',
        'idUsuario',
        'totalFactura',
        'idpuntoVenta',
        'idTipoFactura',
        'created_at',
        'fechaModificacion',
        'descuento'
    ];

    protected $fillable = [
        'id',
        'fechaFactura',
        'estadoFactura',
        'idCliente',
        'idValor',
        'idUsuario',
        'totalFactura',
        'idpuntoVenta',
        'idTipoFactura',
        'created_at',
        'fechaModificacion',
        'descuento'
    ];

    public function puntoventa(){
       
        return $this->belongsTo(PuntoVenta::class,'idPuntoVenta');
    }
    
    public function tipofactura(){
       
        return $this->belongsTo(TipoFactura::class,'idTipoFactura');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente');
    }

    public function detallesfactura(){
       
        return $this->hasMany(DetalleFactura::class,'idFactura');
    }
}

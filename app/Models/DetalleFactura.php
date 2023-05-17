<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Articulo;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class DetalleFactura extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $table='detallesFacturas';
 
    protected $auditInclude = [
        'id',
        'precioVenta',
        'cantidadArticulo',
        'idFactura',
        'idArticulo',
        'totalDetalle'
    ];
    
    protected $fillable = [
        'id',
        'precioVenta',
        'cantidadArticulo',
        'idFactura',
        'idArticulo',
        'totalDetalle'
    ];

    public function articulo()
    {
        //dd($this);
        return $this->belongsTo(Articulo::class, 'idArticulo');
    }
}

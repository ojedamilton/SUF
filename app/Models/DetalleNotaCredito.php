<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Articulo;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class DetalleNotaCredito extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $table='detallesnotascredito';
    
    protected $fillable = [
        'id',
        'precioVenta',
        'cantidadArticulo',
        'idNotaCredito',
        'idArticulo',
        'totalDetalle'
    ];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'idArticulo');
    }

    public function notacredito()
    {
        return $this->belongsTo(NotaCredito::class, 'idFactura');
    }
}

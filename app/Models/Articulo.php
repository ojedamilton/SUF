<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetallesFactura;

class Articulo extends Model
{
    use HasFactory;
    
    protected $table='articulos';

    protected $fillable = [
        'id',
        'precio',
        'nombreArticulo',
        'idCategoria',
        'estadoValor',
    ];

    public function detallesFactura()
    {
        return $this->hasMany(DetalleFactura::class, 'id');
    }

    public function stock()
    {
        return $this->hasOne(Stock::class, 'idArticulo');
    }
}

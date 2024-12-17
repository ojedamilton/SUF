<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Proveedor;
use App\Models\DetalleCompra;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compra extends Model
{
    use HasFactory, SoftDeletes;

    protected $table='compras';

    protected $fillable = [
        'id',
        'numeroCompra',
        'fechaCompra',
        'estadoCompra',
        'idProveedor',
        'idValor',
        'idUsuario',
        'idEmpresa',
        'totalCompra',
        'descuento'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'idProveedor');
    }

    public function detallescompra(){
       
        return $this->hasMany(DetalleCompra::class,'idCompra');
    }

    public function delete()
    {
        // Eliminar lógicamente los detalles relacionados
        $this->detallescompra()->each(function ($detalle) {
            $detalle->delete(); // SoftDeletes en DetalleCompra
        });

        // Eliminar lógicamente la compra
        parent::delete();
    }
    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUsuario');

    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'idEmpresa');
    }

    public function valor()
    {
        return $this->belongsTo(Valor::class, 'idValor');
    }
}

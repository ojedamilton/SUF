<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    // Indicamos cual es la tabla del modelo:
    protected $table = 'stocks';

    // Indicamos los campos que se pueden cargar de manera masiva:
    protected $fillable = [
        'idArticulo',
        'idProveedor',
        'cantidad',
        'cantidadMinima',
        'idEmpresa'
    ];

    // Generamos las relaciones con el modelo Articulo.
    public function articulo(){
        return $this->belongsTo(Articulo::class, 'idArticulo');
    }

    // Consultar disponibilidad de un articulo.
    public static function consultarDisponibilidad($detalles)
    {
        foreach ($detalles as $detalle) {
            $stock = self::where('idArticulo', $detalle['idArticulo'])->first();

            if (!$stock || $stock->cantidad < $detalle['cantidadArticulo']) {
                return [
                    'exito' => false,
                    'articulo' => $detalle['nombre']
                ];
            }
        }

        return ['exito' => true];
    }
}

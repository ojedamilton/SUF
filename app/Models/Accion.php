<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accion extends Model
{
    use HasFactory;

    protected $table='acciones';

    protected $fillable = [
        'id',
        'nombreAccion',
        'descripcionAccion',
        'estadoAccion',
    ];

    public function acciones()
    {
        return $this->belongsToMany(Accion::class, 'grupoacciones', 'idAccion', 'idGrupo');
    }

}

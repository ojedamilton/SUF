<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoAcciones extends Model
{
    use HasFactory;

    protected $table='grupoacciones';

    protected $fillable = [
        'id',
        'idGrupo',
        'idAccion'
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'idGrupo');
    }

    public function accion()
    {
        return $this->belongsTo(Accion::class, 'idAccion');
    }

}

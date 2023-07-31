<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nombreGrupos',
        'descripcionGrupos'
    ];

    public function acciones()
    {
        return $this->belongsToMany(Accion::class, 'grupoacciones', 'idGrupo', 'idAccion');
    }
}

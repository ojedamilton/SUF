<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table='clientes';

    protected $clientes = ['cliente1','cliente2','cliente3'];
    
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}

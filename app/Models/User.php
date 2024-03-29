<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Persona;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    protected $pwd='';
    
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'apellido',
        'email',
        'password',
        'estadoUsuario',
        'pwd'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPwd($pwd){

        $this->pwd = $pwd;
    }

    public function getPwd(){

       return $this->pwd;
    }

    /**
     * Get relation between User and empresas in pivot table = "usuariosempresas"
     *
     */
    public function empresas(){

        return $this->belongsToMany(Empresa::class,'usuariosempresas','idUsuario','idEmpresa');
    }

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'usuariogrupos','idUsuario','idGrupo');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model {


    private $nombre;
    private $apellido;
    private $email;
    private $telefono;
    private $direccion;
   


    static public function saludar(){
        return "Hello from Persona";
    }
 

}
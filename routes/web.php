<?php

use Illuminate\Support\Facades\Route;
use App\Mail\NotificacionRegistro;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ValorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\FacturaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/hola', function () {
    return 'welcome';
});
Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

 /*  RUTAS -> Usuarios */
 Route::get('/usuarios',[UserController::class,'index'])->name('usuario');
 Route::get('/notificacioncorreo',[UserController::class,'sendMail'])->name('sendMail');

 /*  RUTAS -> Valores */
 Route::get('/valores',[ValorController::class,'getAllValores'])->name('valores');

 /*  RUTAS -> Clientes */
 Route::get('/clientes',[ClienteController::class,'getAllClientes'])->name('clientes');

 // RUTAS -> Articulos

 Route::get('/articulos',[ArticuloController::class,'getAllArticulos'])->name('articulos');

 // RUTAS -> Facturacion 

Route::post('/facturar', [FacturaController::class,'store'])->name('facturar');
       
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
use App\Http\Controllers\GrupoController;
use Illuminate\Support\Facades\Auth;

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
    {
        if (Auth::check()) {
            return view('home');
        }
        else {
            return view('auth.login');
        }
    }
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

/*  RUTAS -> Usuarios */
Route::get('/usuarios',[UserController::class,'index'])->name('usuario');
Route::get('/notificacioncorreo',[UserController::class,'sendMail'])->name('sendMail');
Route::post('/crearusuario', [UserController::class,'store'])->name('crearusuario');
Route::get('/showUserAuth',[UserController::class,'showUserAuth'])->name('showUserAuth');

/*  RUTAS -> Valores */
Route::get('/valores',[ValorController::class,'getAllValores'])->name('valores');

/*  RUTAS -> Clientes */
Route::get('/clientes',[ClienteController::class,'getAllClientes'])->name('clientes');

/* RUTAS -> Articulos */
Route::get('/articulos',[ArticuloController::class,'getAllArticulos'])->name('articulos');

/* RUTAS -> Grupos */
Route::get('/grupos', [GrupoController::class,'index'])->name('grupos');

/* RUTAS -> Facturacion */
Route::post('/facturar', [FacturaController::class,'store'])->name('facturar');
Route::get('/allfacturas',[FacturaController::class,'getAllFacturas'])->name('allfacturas');
Route::post('/detallesbyid',[FacturaController::class,'getDetallesById'])->name('detallesbyid');
<?php

use Illuminate\Support\Facades\Route;
use App\Mail\NotificacionRegistro;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ValorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\SituacionFiscalController;
use App\Http\Controllers\TipoEmpresaController;
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

// Rutas de Autenticacion - Login
// Se envia arreglo desactivando el registro , reset y verificación.
Auth::routes(['login' => true,
    'register' => true,
    'reset' => false,
    'verify' => false,
]); 

// Inicio Login con middleware Guest(Si estamos autenticados no permite ir al login)
Route::middleware('guest')->get('/', function () {
   return view('auth.login');  
});

// Grupo de Middleware Auth - Rutas Autenticadas
Route::middleware('auth:web')->group(function(){

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    // Empresa
    Route::get('/userEmpresa', [EmpresaController::class,'userEmpresa'])->name('userempresa');
    Route::post('/createEmpresa', [EmpresaController::class,'createEmpresa'])->name('createempresa');

    // Facturacion
    Route::post('/facturar', [FacturaController::class,'store'])->name('facturar');
    Route::post('/detallesbyid',[FacturaController::class,'getDetallesById'])->name('detallesbyid');
    Route::post('/getfacturasbyid',[FacturaController::class,'getFacturasById'])->name('facturasbyid');
    Route::post('/descargarFactura',[FacturaController::class,'descargarFactura'])->name('descargarfactura');

    // Usuarios
    Route::get('/notificacioncorreo',[UserController::class,'sendMail'])->name('sendMail');
    Route::get('/showUserAuth',[UserController::class,'showUserAuth'])->name('showUserAuth');

    // Clientes
    Route::get('/clientes',[ClienteController::class,'getAllClientes'])->name('clientes');
    Route::post('/clienteTipoFactura', [ClienteController::class,'clienteTipoFactura'])->name('clientetipofactura');
    Route::post('/crearcliente', [ClienteController::class,'crearCliente'])->name('crearcliente');
    Route::put('/updateCliente', [ClienteController::class,'update'])->name('updateCliente');
    Route::post('/deleteCliente', [ClienteController::class,'destroy'])->name('deleteCliente');

    //Situacion Fiscal
    Route::get('/situacionfiscal',[SituacionFiscalController::class,'getAllSituacionFiscal'])->name('situacionfiscal');
});

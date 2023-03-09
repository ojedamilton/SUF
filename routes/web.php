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

/* RUTAS -> Empresa */
Route::get('/empresas', [EmpresaController::class,'getAllEmpresas'])->name('empresas');
Route::get('/userEmpresa', [EmpresaController::class,'userEmpresa'])->name('userempresa');
Route::post('/createEmpresa', [EmpresaController::class,'createEmpresa'])->name('createempresa');

/* RUTAS -> Tipo Empresa */
Route::get('/tiposempresas', [TipoEmpresaController::class,'getAllTipoEmpresa'])->name('tiposempresas');
Route::get('/tipoFacturaEmpresa', [TipoEmpresaController::class,'getTipoFacturaEmpresa'])->name('tipofacturaempresa');

/* RUTAS -> Facturacion */
Route::post('/facturar', [FacturaController::class,'store'])->name('facturar');
Route::get('/allfacturas',[FacturaController::class,'getAllFacturas'])->name('allfacturas');
Route::post('/detallesbyid',[FacturaController::class,'getDetallesById'])->name('detallesbyid');
Route::post('/getfacturasbyid',[FacturaController::class,'getFacturasById'])->name('facturasbyid');
Route::post('/descargarFactura',[FacturaController::class,'descargarFactura'])->name('descargarfactura');
Route::get('/reporteventas',[FacturaController::class,'reporteVentas'])->name('reporteventas');


/* RUTAS -> Compras */
Route::post('/comprar', [FacturaController::class,'store'])->name('comprar');

/* RUTAS -> Articulos */
Route::get('/articulos',[ArticuloController::class,'getAllArticulos'])->name('articulos');

/*  RUTAS -> Usuarios */
Route::get('/usuarios',[UserController::class,'index'])->name('usuario');
Route::get('/notificacioncorreo',[UserController::class,'sendMail'])->name('sendMail');
Route::post('/crearusuario', [UserController::class,'store'])->name('crearusuario');
Route::get('/showUserAuth',[UserController::class,'showUserAuth'])->name('showUserAuth');

/* RUTAS -> Grupos */
Route::get('/grupos', [GrupoController::class,'index'])->name('grupos');

/*  RUTAS -> Clientes */
Route::get('/clientes',[ClienteController::class,'getAllClientes'])->name('clientes');
Route::post('/clienteTipoFactura', [ClienteController::class,'clienteTipoFactura'])->name('clientetipofactura');
Route::post('/crearcliente', [ClienteController::class,'crearCliente'])->name('crearcliente');
/*  RUTAS -> Situacion Fiscal */
Route::get('/situacionfiscal',[SituacionFiscalController::class,'getAllSituacionFiscal'])->name('situacionfiscal');


/*  RUTAS -> Proveedores */
Route::get('/proveedor',[ProveedorController::class,'getAllProveedores'])->name('proveedor');
// Route::get('/crearproveedor',[ProveedorController::class,'store'])->name('crearproveedor');

/*  RUTAS -> Valores */
Route::get('/valores',[ValorController::class,'getAllValores'])->name('valores');
Route::post('/crearvalores',[ValorController::class,'store'])->name('crearvalores');
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\ValorController;
use App\Http\Controllers\TipoEmpresaController;

/*
|--------------------------------------------------------------------------
| API Routes - Public
|--------------------------------------------------------------------------
|
*/

// Articulos
Route::get('/articulos',[ArticuloController::class,'getAllArticulos'])->name('articulos');

/*
|--------------------------------------------------------------------------
| API Routes - Privates
|--------------------------------------------------------------------------
|
*/

// Grupo de Middleware Auth - Rutas Autenticadas
Route::middleware('auth:sanctum')->group(function(){
    // Medios de Pago
    Route::get('/valores',[ValorController::class,'getAllValores'])->name('valores');
    Route::post('/crearvalores',[ValorController::class,'store'])->name('crearvalores');
     // Tipo Empresa 
    Route::get('/tiposempresas', [TipoEmpresaController::class,'getAllTipoEmpresa'])->name('tiposempresas');
    Route::get('/tipoFacturaEmpresa', [TipoEmpresaController::class,'getTipoFacturaEmpresa'])->name('tipofacturaempresa');
});
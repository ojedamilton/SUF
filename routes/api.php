<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\ValorController;
use App\Http\Controllers\TipoEmpresaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProveedorController;

/*
|--------------------------------------------------------------------------
| API Routes - Public
|--------------------------------------------------------------------------
| Se pueden acceder desde un cliente externo sin registrarse 
*/

// Articulos
Route::get('/articulos',[ArticuloController::class,'getAllArticulos'])->name('articulos');
 // Empresas
 Route::get('/empresas', [EmpresaController::class,'getAllEmpresas'])->name('empresas');
 
/*
|--------------------------------------------------------------------------
| API Routes - Privates
|--------------------------------------------------------------------------
| Grupo de Middleware Auth - Rutas Autenticadas
*/
 
Route::middleware('auth:sanctum')->group(function(){
    // Empresas
    Route::put('/updateEmpresa', [EmpresaController::class,'update'])->name('updateEmpresa');
    Route::post('/deleteEmpresa', [EmpresaController::class,'destroy'])->name('deleteEmpresa');
    // Medios de Pago
    Route::get('/valores',[ValorController::class,'getAllValores'])->name('valores');
    Route::post('/crearvalores',[ValorController::class,'store'])->name('crearvalores');
    Route::put('/updateValor', [ValorController::class,'update'])->name('updateValor');
    Route::post('/deleteValor', [ValorController::class,'destroy'])->name('deleteValor');
    // Categorias
    Route::get('/categoria',[CategoriaController::class,'getAllCategoria'])->name('categoria');
    Route::post('/crearcategoria',[CategoriaController::class,'store'])->name('crearcategoria');
    Route::put('/updateCategoria', [CategoriaController::class,'update'])->name('updateCategoria');
    Route::post('/deleteCategoria', [CategoriaController::class,'destroy'])->name('deleteCategoria');
    // Tipo Empresa
    Route::get('/tiposempresas', [TipoEmpresaController::class,'getAllTipoEmpresa'])->name('tiposempresas');
    Route::get('/tipoFacturaEmpresa', [TipoEmpresaController::class,'getTipoFacturaEmpresa'])->name('tipofacturaempresa');
    // Usuarios
    Route::get('/usuarios',[UserController::class,'index'])->name('usuario');
    Route::post('/crearusuario', [UserController::class,'store'])->name('crearusuario');
    Route::post('/updateUsuario', [UserController::class,'update'])->name('updateUsuario');
    Route::post('/deleteUsuario', [UserController::class,'destroy'])->name('deleteUsuario');
    // Facturacion
    Route::get('/allfacturas',[FacturaController::class,'getAllFacturas'])->name('allfacturas');
    // Proveedor
    Route::get('/proveedores',[ProveedorController::class,'index'])->name('proveedores');
    Route::post('/createProveedor',[ProveedorController::class,'store'])->name('crearproveedor');
    Route::put('/updateProveedor',[ProveedorController::class,'update'])->name('editarproveedor');
    Route::post('/deleteProveedor',[ProveedorController::class,'destroy'])->name('eliminarproveedor');
   
});
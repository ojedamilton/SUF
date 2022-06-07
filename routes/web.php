<?php

use Illuminate\Support\Facades\Route;
use App\Mail\NotificacionRegistro;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Mail;

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

 /*  RUTAS -> Usuarios */
 Route::get('/usuarios',[UserController::class,'index'])->name('usuario');

 Route::get('/notificacioncorreo',[UserController::class,'sendMail'])->name('sendMail');
       
<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return view home con la empresa logueada
     */
    public function index()
    {
        try {
            $empresas = Empresa::findOrFail(Auth::user()->idEmpresa);
            return view('home', compact('empresas'));
        } catch (\Throwable $th) {
            // retorno al logout con el mensaje de error
            Auth::logout();
            // redirecciono al login con el mensaje de error
            return redirect()->route('login')->withErrors(['empresa' => 'No se ha encontrado una empresa Asociada']);
        }
    }
}

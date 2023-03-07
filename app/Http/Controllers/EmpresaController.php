<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;

class EmpresaController extends Controller
{
    public function getAllEmpresas(Request $request){
   
        // Si quieren Ingresar sin un request , redirecciona al home
        if(!$request->ajax())return redirect('/home');
    
        $buscar= $request->buscar;
    
        $empresas=Empresa::where('nombreEmpresa','like','%'.$buscar.'%')
                            ->orWhere('cuitEmpresa','like','%'.$buscar.'%')
                            ->orderBy('nombreEmpresa','asc')
                            ->paginate(3);
    
        return[
            'pagination'=>[
                'total'=>$empresas->total(),
                'current_page'=>$empresas->currentPage(),
                'per_page'=>$empresas->perPage(),
                'last_page'=>$empresas->lastPage(),
                'from'=>$empresas->firstItem(),
                'to'=>$empresas->lastItem(),
            ],
            'empresas'=>$empresas,
        ];
    
    
        }
        
        public function UserEmpresa(){
           
            $userEmpresa = Empresa::find(Auth::user()->idEmpresa);
    
            return [
    
               'userEmpresa'=>$userEmpresa
            ];
         }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Mail\NotificacionRegistro;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Si quieren Ingresar sin un request , redirecciona al home 
           if(!$request->ajax())return redirect('/');

           $buscar= $request->buscar;
           if ($buscar=='') {
               $users=User::with('grupos')
                            ->orderBy('id','desc')
                            ->paginate(10);
           }else{
               $users=User::with('grupos')
                           ->where('username','like','%'.$buscar.'%')
                           ->orWhere('name','like','%'.$buscar.'%')
                           ->orderBy('name','asc')
                           ->paginate(10);
           } 
           return[
               'pagination'=>[
                   'total'=>$users->total(),
                   'current_page'=>$users->currentPage(),
                   'per_page'=>$users->perPage(),
                   'last_page'=>$users->lastPage(),
                   'from'=>$users->firstItem(),
                   'to'=>$users->lastItem(),
               ],
               'users'=>$users,
           ];
       
    }
    
    public function sendMail(){
        $correo= new NotificacionRegistro; 
        Mail::to('ojedamiltonezequiel@gmail.com')->send($correo);
        return 'Success Send Correo';
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

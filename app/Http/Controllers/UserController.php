<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserGrupo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Mail\NotificacionRegistro;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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
    /**
     * Sendmail to new user 
     * @param $string correo
     * @return void 
     */
    public function sendMail($usuario,$password){    

        $correo= new NotificacionRegistro($usuario,$password);
        Mail::to($usuario)->send($correo);   
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Valido Backend 
        $validator = Validator::make(
            $request->all(),[
            'nombre'=>'required|max:10',
            'apellido'=>'required',
            'grupo'=>'required',
            'password'=>'required',
            'email'=>'required|unique:users|email',
        ],[
            'email.unique'=>'El email ya existe',
            'email.required'=>'El email es requerido',
            'email.email'=>'El email tiene formato erroneo',
            'nombre.required'=>'El nombre es requerido',
            'password.required'=>'La contraseña es requerida',
            'apellido.required'=>'El apellido es requerido',
            'grupo.unique'=>'El grupo es requerido'
        ]);
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->getMessageBag(),"status"=>401]);
        }
        //dd($request->all());
        // Comienzo Transaccion
        DB::beginTransaction();
        try {
            // Creo usuario en la tabla
            $user=User::create([
                "name"=>$request->nombre,
                "apellido"=>$request->apellido,
                "email"=>$request->email,
                "password"=>bcrypt($request->password),
                "estadoUsuario"=>1
            ]);
            //recorrer grupo/s
            $arrGrupo=[];
            foreach ($request->grupo as $grupo) {
                $g["idUsuario"]=$user->id;
                $g["idGrupo"]=$grupo;
                $g["estadoUsuarioGrupos"]=1;
                $g["created_at"]=Carbon::now();
                $g["updated_at"]=Carbon::now();
                $arrGrupo[]=$g;
            }
            // insert a tabla usergrupo 
            $userGrupo=UserGrupo::insert($arrGrupo);
            // Envio correos
            $this->sendMail($request->email,$request->password);
            DB::commit();
            return response()->json(["success"=>"El usuario se ha creado correctamente","status"=>201]);
        } catch (\Throwable $th) {
            DB::rollBack();
            // Logeo errores
            Log::error($th->getMessage());
            return response()->json(["errors"=>"No se pudo crear el usuario","status"=>401]);
        }
       
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

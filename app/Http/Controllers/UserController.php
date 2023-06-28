<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserGrupo;
use App\Models\usuarioEmpresa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Mail\NotificacionRegistro;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Repositories\UserRepository;

class UserController extends Controller
{
    private $userRepository;
    
    public function __construct(UserRepository $userRepository){

        $this->userRepository = $userRepository;

    }

    public function index(Request $request)
    {
        try {

            $buscar= $request->buscar;

            $users = $this->userRepository->getUsersByEmpresa(Auth::user()->idEmpresa, $buscar);

            return response()->json([
                'success'=>true,
                'message'=>'Listado de Usuarios',
                'users'=>$users,
                'pagination'=>[
                    'total'=>$users->total(),
                    'current_page'=>$users->currentPage(),
                    'per_page'=>$users->perPage(),
                    'last_page'=>$users->lastPage(),
                    'from'=>$users->firstItem(),
                    'to'=>$users->lastItem(),
                ],
            ],200);
        } catch (\Throwable $th) {

            Log::error($th->getMessage());
            return response()->json([
                'success'=>false,
                'message'=>'Error al listar Usuarios',
                'users'=>null,
                'pagination'=>[
                    'total'=>1,
                    'current_page'=>1,
                    'per_page'=>1,
                    'last_page'=>1,
                    'from'=>1,
                    'to'=>1,
                ],
            ],500);    
        }


    }
    /**
     * Sendmail to new user 
     * 
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
            'selectedEmpresa'=>'required'
        ],[
            'email.unique'=>'El email ya existe',
            'email.required'=>'El email es requerido',
            'email.email'=>'El email tiene formato erroneo',
            'nombre.required'=>'El nombre es requerido',
            'password.required'=>'La contraseña es requerida',
            'apellido.required'=>'El apellido es requerido',
            'grupo.unique'=>'El grupo es requerido',
            'selectedEmpresa'=>'La empresa es requerida'
        ]);
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->getMessageBag(),"status"=>401]);
        }
        //dd($request->all());
        // Comienzo Transaccion
       DB::beginTransaction();
        try { 
            
            // Creo usuario en la tabla
            $user = new User();
            $user->name=$request->nombre;
            $user->apellido=$request->apellido;
            $user->email=$request->email;
            $user->password=bcrypt($request->password);
            $user->estadoUsuario=1;
            $user->setPwd($request->password);
            $user->save();

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
            $userGrupo = UserGrupo::insert($arrGrupo);

            // Seteo Multiempresa
            $selectedEmpresaIds = array_map(function($empresa) use ($user) {
                                $selectedEmpresaIds['idUsuario']=$user->id;
                                $selectedEmpresaIds['idEmpresa']=$empresa['id'];
                                $selectedEmpresaIds['estado']=1;
                                return $selectedEmpresaIds; 
                            }, $request->selectedEmpresa);

            $userEmpresas = usuarioEmpresa::insert($selectedEmpresaIds); 

            // Envio correos
            //$this->sendMail($request->email,$request->password);
             DB::commit();
            return response()->json([
                "success"=>true,
                "message"=>"El usuario se ha creado correctamente"
            ],201);
        } catch (\Throwable $th) {
            DB::rollBack();
            // Logeo errores
            Log::error($th->getMessage());
            return response()->json([
                "success"=>false,
                "message"=>"No se pudo crear el usuario"
                ],500);
        }
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showUserAuth()
    {
       return response()->json([
                        "id"=>Auth::user()->id,
                        "name"=>Auth::user()->name,
                    ],200);
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

<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\GrupoAcciones;
use App\Models\User;
use App\Models\UserGrupo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class GrupoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Si quieren Ingresar sin un request , redirecciona al home 
        //if(!$request->ajax())return redirect('/home');

        try {

            $grupos = Grupo::with('acciones:id,nombreAccion,descripcionAccion')
                ->select('id', 'nombreGrupo', 'descripcionGrupo')
                ->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Grupos cargados correctamente',
                'grupos' => $grupos,
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los grupos',
                'grupos' => null,
            ], 500);
        }
           
       
    }

    public function grupoAcciones(){

        try {

            $gruposAcciones = GrupoAcciones::with('grupos','acciones')->get();
            
            return response()->json([
                'success' => true,
                'message' => 'GruposAcciones cargados correctamente',
                'gruposAcciones' => $gruposAcciones,
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los grupos y acciones',
                'grupos' => null,
            ], 500);
        }
    }
    public function updateGrupoAcciones(Request $request){

        try {
            $grupo = Grupo::find($request->idGrupo);
            
            $arrAcciones=[];
            foreach ($request->permisos as $permiso) {
                $arrAcciones[$permiso] = [
                    'idGrupo' => $grupo->id,
                    'estadoGrupoAccion' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }

            $grupo->acciones()->sync($arrAcciones);
             
            /* $grupoAcciones = Grupo::where('idGrupo',$request->idGrupo)->get();
            $grupoAcciones->each->delete();
            $grupoAcciones = GrupoAcciones::create($request->all()); */
            return response()->json([
                'success' => true,
                'message' => 'Grupo Acciones actualizado correctamente',
                'grupoAcciones' => $grupo,
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el grupo y acciones',
                'grupos' => null,
            ], 500);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\Models\Grupo  $grupo
     * @return \Illuminate\Http\Response
     */
    public function show(Grupo $grupo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Grupo  $grupo
     * @return \Illuminate\Http\Response
     */
    public function edit(Grupo $grupo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Grupo  $grupo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Grupo $grupo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Grupo  $grupo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grupo $grupo)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\ArticuloProveedores;
use App\Models\Proveedor;
use App\Models\Stock;
use App\Repositories\ArticuloRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ArticuloController extends Controller
{

    private $articuloRepository;
    
    public function __construct(ArticuloRepository $articuloRepository){
     
    $this->articuloRepository = $articuloRepository;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display a listing of the articles.
     * 
     * @param  \Illuminate\Http\Request  $buscar
     * @return \Illuminate\Http\Response $articulos
     */
    public function getAllArticulos(Request $request) {

        $buscar= $request->buscar;

        try {

            $articulo=$this->articuloRepository->all($buscar,Auth::user()->idEmpresa);

            $buscar= $request->buscar;
            // if (!$buscar) {
            //     $articulo=Articulo::with('stock')
            //         ->orderBy('id','asc')->paginate(5);
            // }else{
            //     $articulo=Articulo::with('stock')
            //         ->where('nombreArticulo','like','%'.$buscar.'%')
            //         ->orWhere('id','like','%'.$buscar.'%')
            //         ->orderBy('nombreArticulo','asc')
            //         ->paginate(5);     
            // }
              
            return response()->json([
                    'success'=>true,
                    'message'=>'Listado de Articulos',
                    'articulos'=>$articulo,
                    'pagination'=>[
                        'total'=>$articulo->total(),
                        'current_page'=>$articulo->currentPage(),
                        'per_page'=>$articulo->perPage(),
                        'last_page'=>$articulo->lastPage(),
                        'from'=>$articulo->firstItem(),
                        'to'=>$articulo->lastItem(),
                    ],
            ],200); 
            
        } catch (\Throwable $th) {

            Log::info($th->getMessage());

            return response()->json([
                'success'=>false,
                'message'=>'No se encontraron Articulos',
                'articulos'=>null,
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

    public function getProveedoresByArticulo($idArticulo)
    {
        try {
            $articulo = Articulo::findOrFail($idArticulo);
            $proveedores = $articulo->proveedores()
                                ->where('estadoProveedor', 1)
                                ->get()
                                ->map(function ($proveedor) {
                                return [
                                    'id' => $proveedor->id,
                                    'nombreProveedor' => $proveedor->nombreProveedor,
                                ];
                            });

            return response()->json([
                'proveedores' => $proveedores,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener los proveedores por articulo: ' . $e->getMessage(),
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
         // Valido Backend
         $validator = Validator::make(
            $request->all(),
            [
                'nombreArticulo'=>'required|max:50',
                'precioCompra'=>'required|numeric',
                'precio'=>'required',
                'idCategoria'=>'required',
                'selectedProveedor'=>'required'
            ],[
                'nombreArticulo.required'=>'El nombre es requerido',
                'nombreArticulo.max'=>'El nombre no debe superar los 50 caracteres',
                'precioCompra'=>'precio Compra es requerido',
                'precioCompra.numeric'=>'precio Compra debe ser numerico',
                'precio'=>'precio venta es requerido',
                'idCategoria'=>'La Categoria es requerida',
                'selectedProveedor'=>'El Proveedor es requerido',
            ]
        );

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->getMessageBag(),"status"=>401]);
        }

        // Comienzo Transaccion
        DB::beginTransaction();
        
        try { 
            // Creo Articulo en la tabla
            $articulo = new Articulo();
            $articulo->nombrearticulo=$request->nombre;
            $articulo->precio=$request->precioV;
            $articulo->precioCompra=$request->precioC;
            $articulo->idCategoria=$request->categoriaId;
            $articulo->estadoArticulo=1;
            $articulo->idEmpresa=Auth::user()->idEmpresa;
            $articulo->save();

            // Relaciono con Tabla Pivot Articulo-Proveedor

            // $proveedor = new ArticuloProveedores();
            // $proveedor->idArticulo=$articulo->id;
            // $proveedor->idProveedor=$request->proveedorId;
            // $proveedor->save();

            // Seteo Multiproveedores
            $selectedProveedorIds = array_map(function($proveedor) use ($articulo) {
                    $selectedProveedorIds['idArticulo']=$articulo->id;
                    $selectedProveedorIds['idProveedor']=$proveedor['id'];
                    // $selectedProveedorIds['estado']=1;
                    return $selectedProveedorIds; 
                }, $request->selectedProveedor);

            $articuloProovedors = ArticuloProveedores::insert($selectedProveedorIds); 


            // Inserto Stock en mi modelo Stock
            $stock = new Stock();
            $stock->idArticulo=$articulo->id;
            $stock->cantidad=$request->stock;
            $stock->cantidadMinima=$request->stockMinimo;
            $stock->save();

            DB::commit();
            return response()->json(["success"=>"El Articulo se ha creado correctamente","status"=>201]);
        } catch (\Throwable $th) {
            DB::rollBack();
            // Logeo errores
            Log::error($th->getMessage());
            return response()->json(["errors"=>"No se pudo crear el Articulo","status"=>500]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Articulo  $articulo
     * @return \Illuminate\Http\Response
     */
    public function show(Articulo $articulo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Articulo  $articulo
     * @return \Illuminate\Http\Response
     */
    public function edit(Articulo $articulo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Articulo  $articulo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Articulo $articulos)
    {
        $articulos = Articulo::find($request->idArticulo);
    
        // Valido Backend
        $validator = Validator::make(
            $request->all(),
            [
                'nombreArticulo'=>'required|max:50',
                'precio'=>'required|numeric',
                'precioCompra'=>'required',
                'idCategoria'=>'required',
                'selectedProveedor'=>'required'
            ],[
                'nombreArticulo.required'=>'El nombre es requerido',
                'nombreArticulo.max'=>'El nombre no debe superar los 50 caracteres',
                'precioCompra'=>'precio Compra es requerido',
                'precioCompra.numeric'=>'precio Compra debe ser numerico',
                'precio'=>'precio venta es requerido',
                'idCategoria'=>'La Categoria es requerida',
                'selectedProveedor'=>'El Proveedor es requerido',
            ]
        );
    
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->getMessageBag(), "success" => false], 401);
        }
    
        // Comienzo de la transacción
        DB::beginTransaction();
        try {
            // Actualizo datos del articulo
    
            $articulos->nombreArticulo = $request->nombreArticulo;
            $articulos->precio = $request->precio;
            $articulos->precioCompra = $request->precioCompra;
            $articulos->idCategoria = $request->idCategoria;
            $articulos->save();
    
    
            // Actualizo los proveedores del articulo
            $selectedProveedorIds = [];
            foreach ($request->selectedProveedor as $proveedor) {
                $selectedProveedorIds[$proveedor['id']] = [
                    'idArticulo' => $articulos->id,
                    // 'estado' => 1,
                ];
            }

            $articulos->proveedores()->sync($selectedProveedorIds);


            DB::commit();
            return response()->json([
                "success" => true,
                "message" => "El articulo se ha actualizado correctamente",
                "articulos"=>$articulos
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            // Logeo errores
            Log::error($th->getMessage());
            return response()->json([
                "success" => false,
                "message" => "No se pudo actualizar el articulo"
            ], 500);
        }
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Articulo  $articulo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // Comienzo Transaccion
        DB::beginTransaction();

        try { 
            $articulos = Articulo::find($request->idArticulo);
            $articulos->estadoArticulo = 0;
            $articulos->save();
            DB::commit();
            return response()->json([
                "success"=>true,
                "message"=>"El articulo se ha eliminado correctamente",
                "articulos"=>$articulos
            ],200);

        } catch (\Throwable $th) {
            DB::rollBack();
            // Logeo errores
            Log::error($th->getMessage());
            return response()->json([
                "success"=>false,
                "errors"=>"No se pudo eliminar el articulo"
            ],500);
        }
    }
}

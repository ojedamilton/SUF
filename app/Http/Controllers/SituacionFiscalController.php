<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SituacionFiscal;

class SituacionFiscalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllSituacionFiscal(Request $request)
    {
        // Si quieren Ingresar sin un request , redirecciona al home
        if(!$request->ajax())return redirect('/home');

        $situacionfiscal=SituacionFiscal::all();  //query que le pega al modelo

        return[
            'situacionfiscal'=>$situacionfiscal,
        ];
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

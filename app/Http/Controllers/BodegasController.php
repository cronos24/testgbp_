<?php

namespace App\Http\Controllers;

use App\Bodega;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class BodegasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {       

        $bodegas = Bodega::with('user')->orderBy('created_at', 'asc')->get();         
        return response()->json($bodegas);
        
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //dd($request->all());
         $rules=[
            'nombre' => 'required',
            'id_responsable' => 'required'
            ];
        $messages=[
            'required' => 'El campo <strong> ":attribute" </strong> es obligatorio.',
            ];
        $attributes=[
            'nombre'=>'Nombre',
            'id_responsable' => 'Responsable'
            ];

        $validator=Validator::make($request->all(),$rules,$messages,$attributes);    

        if($validator->fails())
        {
            $response['status'] = 'fail';
            $response['response'] = $validator->messages();
        }else{           

            try {

                       
                $Bodegas = new Bodega([
                    'nombre' => $request->nombre,
                    'id_responsable' => $request->id_responsable,
                    'estado' => 1
                ]);

                if ($Bodegas->save()) {                    
                    $response['status'] = 'success';
                }else{
                    $response['status'] = 'fail';
                }


                $response['response'] = Bodega::with('user')->where('id',$Bodegas->id)->first();
                
                
            } catch (\Throwable $th) {
                $response['status'] = 'fail';
                $response['response'] = ['Error General'=>[print_r($th->getMessage(), true)]];
            }

           

            
        }
   

        return response()->json($response);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bodegas  $Bodegas
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Bodegas = Bodega::find($id);
        return response()->json($Bodegas);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bodegas  $Bodegas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $Bodegas = Bodega::find($id);
        $Bodegas->update($request->all());

        return response()->json($Bodegas);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bodegas  $Bodegas
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Bodegas = Bodega::find($id);
        $Bodegas->delete();

        return response()->json('Bodega deleted!');
    }
}

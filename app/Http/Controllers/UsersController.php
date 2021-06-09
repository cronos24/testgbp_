<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {       

        $users = User::all()->sortBy("nombre");      
        return response()->json($users);
        
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules=[
            'nombre' => 'required',
            'foto' => 'required|mimes:jpg,jpeg,png,pdf|max:2048'
            ];
        $messages=[
            'required' => 'El campo <strong> ":attribute" </strong> es obligatorio.',
            ];
        $attributes=[
            'nombre'=>'Nombre',
            'foto' => 'Foto'
            ];

        $validator=Validator::make($request->all(),$rules,$messages,$attributes);    

        if($validator->fails())
        {
            $response['status'] = 'fail';
            $response['response'] = $validator->messages();
        }else{           

            try {

                $file_name = time().'_'.$request->foto->getClientOriginalName();               
                
          
                $users = new User([
                    'nombre' => $request->nombre,
                    'foto' => $file_name,
                    'estado' => 1
                ]);

                if ($users->save()) {
                    $request->file('foto')->storeAs('uploads/users/foto', $file_name, 'public');
                    $response['status'] = 'success';
                }else{
                    $response['status'] = 'fail';
                }

                $response['response'] = $users;
                
                
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
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $users = Users::find($id);
        return response()->json($users);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $users = Users::find($id);
        $users->update($request->all());

        return response()->json($users);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $users = Users::find($id);
        $users->delete();

        return response()->json('User deleted!');
    }
}

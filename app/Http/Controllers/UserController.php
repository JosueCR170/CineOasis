<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //
    public function index()
    {
        $data=User::all();
        $data=$data->load('tarjetas');
        $response=array(
            "status"=>200,
            "message"=>"Todos los registros de la categoria",
            "data"=>$data
        );
        return response()->json($response,200);
    }

    public function store(Request $request){
        $data_input=$request->input('data',null);
        if($data_input){
            $data=json_decode($data_input,true);
            $data=array_map('trim',$data);
            $rules=[
                'name'=>'required|alpha',
                'apellido'=>'required|alpha',
                'email'=>'required|email',
                'password'=>'required|alpha_dash',
                'fechaNacimiento'=>'required|date',
                'permisoAdmin'=>'required|boolean'
            ];
            $isValid=\validator($data,$rules);
            if(!$isValid->fails()){
                $user=new User();
                $user->name=$data['name'];
                $user->apellido=$data['apellido'];
                $user->email=$data['email'];
                $user->password=$data['password'];
                $user->fechaNacimiento=$data['fechaNacimiento'];
                $user->permisoAdmin=$data['permisoAdmin'];
                $user->save();
                $response=array(
                    'status'=>201,
                    'message'=>'usuario creado',
                    'user'=>$user
                );
            }else{
                $response=array(
                    'status'=>406,
                    'message'=>'Datos inválidos',
                    'error'=>$isValid->errors()
                );
            }
        }else{
            $response=array(
                'status'=>400,
                'message'=>'No se encontró el objeto data'
            );
        }
        return response()->json($response,$response['status']);
    }

    
    public function show($id){
        $data=User::find($id);
        if(is_object($data)){
            $data=$data->load('tarjetas');
            $response=array(
                'status'=>200,
                'message'=>'Datos del usuario',
                'user'=>$data
            );
        }else{
            $response=array(
                'status'=>404,
                'message'=>'Recurso no encontrado'
            );
        }
        return response()->json($response,$response['status']);
    }

    public function destroy($id){
        if(isset($id)){
            $deleted=User::where('id',$id)->delete();
            if($deleted)
            {
                $response=array(
                    'status'=>200,
                    'message'=>'Usuario eliminado'
                );
            }else{
                $response=array(
                    'status'=>400,
                    'message'=>'No se pudo eliminar el recurso, compruebe que exista'
                );
            }
        }else{
            $response=array(
                'status'=>406,
                'message'=>'Falta el identificador del recurso a eliminar'
            );
        }
        return response()->json($response,$response['status']);
    }

    //patch
    public function update(Request $request, $id) {
        $user = User::find($id);
    
        if (!$user) {
            $response = [
                'status' => 404,
                'message' => 'Usuario no encontrado'
            ];
            return response()->json($response, $response['status']);
        }
    
        $data_input = $request->input('data', null);
        $data_input = json_decode($data_input, true);
    
        if (!$data_input) {
            $response = [
                'status' => 400,
                'message' => 'No se encontró el objeto data. No hay datos que modificar'
            ];
            return response()->json($response, $response['status']);
        }
    
        $rules = [
            'name' => 'alpha',
            'apellido' => 'alpha',
            'email' => 'email',
            'password' => 'alpha_dash',
            'fechaNacimiento' => 'date',
            'permisoAdmin' => 'boolean'
        ];
    
        $validator = \validator($data_input, $rules);
    
        if ($validator->fails()) {
            $response = [
                'status' => 406,
                'message' => 'Datos inválidos',
                'error' => $validator->errors()
            ];
            return response()->json($response, $response['status']);
        }
    
        if(isset($data_input['name'])) { $user->name = $data_input['name']; }
        if(isset($data_input['apellido'])) { $user->apellido = $data_input['apellido']; }
        if(isset($data_input['email'])) { $user->email = $data_input['email']; }
        if(isset($data_input['password'])) { $user->password = $data_input['password']; }
        if(isset($data_input['fechaNacimiento'])) { $user->fechaNacimiento = $data_input['fechaNacimiento']; }
        if(isset($data_input['permisoAdmin'])) { $user->permisoAdmin = $data_input['permisoAdmin']; }

        $user->save();
    
        $response = [
            'status' => 201,
            'message' => 'Usuario actualizado',
            'user' => $user
        ];
    
        return response()->json($response, $response['status']);
    }
    
}

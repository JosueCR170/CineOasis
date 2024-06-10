<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Helpers\JwtAuth;

class UserController extends Controller
{
    public function index(Request $request)//listo
    {
        $jwt = new JwtAuth();
        if (!$jwt->checkToken($request->header('bearertoken'), true)->permisoAdmin) {
            $response = array(
                'status' => 406,
                'menssage' => 'No tienes permiso de administrador'

            );
        } else {
        $data=User::all();
        $response=array(
            "status"=>200,
            "message"=>"Todos los registros de los usuarios",
            "data"=>$data
        );
    }
        return response()->json($response,200);
    }

    public function store(Request $request)
    {
        $data_input = $request->input('data', null);
        if ($data_input) {
            $data = json_decode($data_input, true);
            if ($data !== null) {
                $data = array_map('trim', $data);
                $rules = [
                    'name' => 'required|alpha|max:30',
                    'apellido' => 'required|alpha|max:40',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|alpha_dash',
                    'fechaNacimiento' => 'required|date',
                    'permisoAdmin' => 'required|boolean',
                    'imagen'=>'string'
                ];
                $validator = validator($data, $rules);
                if (!$validator->fails()) {
                    $user = new User();
                    $user->name = $data['name'];
                    $user->apellido = $data['apellido'];
                    $user->email = $data['email'];
                    $user->password = hash('sha256', $data['password']);
                    $user->fechaNacimiento = $data['fechaNacimiento'];
                    $user->permisoAdmin = $data['permisoAdmin'];
                    $user->imagen = $data['imagen'];
                    $user->save();
                    $response = [
                        'status' => 201,
                        'message' => 'Usuario creado exitosamente',
                        'user' => $user
                    ];
                } else {
                    $response = [
                        'status' => 406,
                        'message' => 'Datos inválidos',
                        'error' => $validator->errors()
                    ];
                }
            } else {
                $response = [
                    'status' => 400,
                    'message' => 'No se proporcionaron datos válidos',
                ];
            }
        } else {
            $response = [
                'status' => 400,
                'message' => 'No se encontró el objeto de datos (data)'
            ];
        }
    
        // Devolver la respuesta JSON
        return response()->json($response, $response['status']);
    }
    
    public function show($id){//listo
        $data=User::find($id);
        if(is_object($data)){
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
            $user=User::find($id);
            if (!$user) {
                return response()->json(['status' => 404, 'message' => 'Usuario no encontrado'], 404);
            }
            //eliminar imagen si existe
            if ($user->imagen) {
                $filename = $user->imagen;
                if (\Storage::disk('usuarios')->exists($filename)) {
                    if (!\Storage::disk('usuarios')->delete($filename)) {
                        return response()->json(['status' => 500, 'message' => 'Error al eliminar la imagen del usuario'], 500);
                    }
                }
            }
            if($user->delete())
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
            'name'=>'alpha|max:30',
            'apellido'=>'alpha|max:40',
            'email'=>'email|unique:users,email',
            'password'=>'alpha_dash',
            'fechaNacimiento'=>'date',
            'permisoAdmin'=>'boolean',
            'imagen'=>'string'
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
        if(isset($data_input['password'])) { $user->password = hash('sha256', $data_input['password']); }
        if(isset($data_input['fechaNacimiento'])) { $user->fechaNacimiento = $data_input['fechaNacimiento']; }
        if(isset($data_input['permisoAdmin'])) { $user->permisoAdmin = $data_input['permisoAdmin']; }
        if(isset($data_input['imagen'])) { $user->imagen = $data_input['imagen']; }

        $user->save();
    
        $response = [
            'status' => 201,
            'message' => 'Usuario actualizado',
            'user' => $user
        ];
    
        return response()->json($response, $response['status']);
    }
    
    public function login(Request $request){ //listo
        $data_input = $request->input('data', null);
        $data = json_decode($data_input, true);
    
       
        if ($data !== null) {
            $data = array_map('trim', $data);
        } else {
            
            $response = array(
                'status' => 400,
                'message' => 'No se proporcionaron datos válidos',
            );
            return response()->json($response, 400);
        }
    
        $rules = ['email' => 'required', 'password' => 'required'];
        $isValid = \validator($data, $rules);
    
        if (!$isValid->fails()) {
            $jwt = new JwtAuth();
            $response = $jwt->getToken($data['email'], $data['password']);
            return response()->json($response);
        } else {
            $response = array(
                'status' => 406,
                'message' => 'Error en la validación de los datos',
                'errors' => $isValid->errors(),
            );
            return response()->json($response, 406);
        }
    }
    
    public function getIdentity(Request $request){//listo
        $jwt=new JwtAuth();
        $token=$request->header('bearertoken');
        if(isset($token)){
            $response=$jwt->checkToken($token,true);
        }else{
            $response=array(
                'status'=>404,
                'message'=>'token (bearertoken) no encontrado',
            );
        }
        return response()->json($response);
    }

    public function uploadUserImage(Request $request)
    {
        $isValid=\Validator::make($request->all(),['file'=>'required|mimes:jpg,png,jpeg,svg']);
        if(!$isValid->fails()){
            $image=$request->file('file');
            $filename = \Str::uuid() . "." . $image->getClientOriginalExtension();
            \Storage::disk('usuarios')->put($filename,\File::get($image));
            $response=array(
                'status'=>201,
                'message'=>'Imagen guardada',
                'filename'=>$filename,
            );
        }else{
            $response=array(
                'status'=>406,
                'message'=>'Error: no se encontró el archivo',
                'errors'=>$isValid->errors(),
            );
        }
        return response()->json($response,$response['status']);
    }

    public function updateUserImage(Request $request, string $filename)
    {
        $isValid=\Validator::make($request->all(),['file'=>'required|mimes:jpg,png,jpeg,svg']);
        if(!$isValid->fails()){
            $image=$request->file('file');
            \Storage::disk('usuarios')->put($filename, \File::get($image));
            $response=array(
                'status'=>201,
                'message'=>'Imagen actualizada',
                'filename'=>$filename,
            );
        }else{
            $response=array(
                'status'=>406,
                'message'=>'Error: no se encontró el archivo',
                'errors'=>$isValid->errors(),
            );
        }
        return response()->json($response,$response['status']);
    }

    public function getUserImage($filename){
        if(isset($filename)){
            $exist=\Storage::disk('usuarios')->exists($filename);
            if($exist){
                $file=\Storage::disk('usuarios')->get($filename);
                return new Response($file,200);
            }else{
                $response=array(
                    'status'=>404,
                    'message'=>'No existe la imagen',
                );
            }
        }else{
            $response=array(
                'status'=>406,
                'message'=>'No se definió el nombre de la imagen',
            );
        }
        return response()->json($response,$response['status']);
    }

    public function destroyImage($filename){
        if(isset($filename)){
            $exist=\Storage::disk('usuarios')->exists($filename);
            if($exist){
            \Storage::disk('usuarios')->delete($filename);
            
            $response = array(
                'status' => 200,
                'message' => 'Imagen eliminada'
            );
        } else {
            $response = array(
                'status' => 404,
                'message' => 'No existe la imagen'
            );
        }
    }else{
        $response=array(
            'status'=>406,
            'message'=>'No se definió el nombre de la imagen',
        );}
        return response()->json($response, $response['status']);
    }
}

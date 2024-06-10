<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Comida;
use App\Helpers\JwtAuth;

class ComidaController extends Controller
{
    public function index()
    {
        $data=Comida::all();
        //$data=$data->load('detallesCombo');
        $response=array(
            "status"=>200,
            "message"=>"Todos los registros de las comidas",
            "data"=>$data
        );
        return response()->json($response,200);
    }

    public function store(Request $request){
        $jwt = new JwtAuth();
        if (!$jwt->checkToken($request->header('bearertoken'), true)->permisoAdmin) {
            $response = array(
                'status' => 406,
                'menssage' => 'No tienes permiso de administrador'
            );
        } else {
        $data_input=$request->input('data',null);
        if($data_input){
            $data=json_decode($data_input,true);
            $data=array_map('trim',$data);
            $rules=[
                'nombre'=>'required|max:40|string',
                'precio'=>'required|decimal:0,4',
                'comida'=>'required|string'
            ];
            $isValid=\validator($data,$rules);
            if(!$isValid->fails()){
                $comida=new Comida();
                $comida->nombre=$data['nombre'];
                $comida->precio=$data['precio'];
                $comida->imagen=$data['imagen'];
                $comida->save();
                $response=array(
                    'status'=>201,
                    'message'=>'Comida creada',
                    'Comida'=>$comida
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
        }
        return response()->json($response,$response['status']);
    }

    
    public function show($id){
        $data=Comida::find($id);
        if(is_object($data)){
            $response=array(
                'status'=>200,
                'message'=>'Datos de la comida',
                'Comida'=>$data
            );
        }else{
            $response=array(
                'status'=>404,
                'message'=>'Recurso no encontrado'
            );
        }
        return response()->json($response,$response['status']);
    }

    public function destroy(Request $request,$id){
        $jwt = new JwtAuth();
        if (!$jwt->checkToken($request->header('bearertoken'), true)->permisoAdmin) {
            $response = array(
                'status' => 406,
                'menssage' => 'No tienes permiso de administrador'
            );
        } else {

        if(isset($id)){
            $comida=Comida::find($id);
            if (!$comida) {
                return response()->json(['status' => 404, 'message' => 'Comida no encontrada'], 404);
            }
            //eliminar imagen si existe
            if ($comida->imagen) {
                $filename = $comida->imagen;
                if (\Storage::disk('comidas')->exists($filename)) {
                    if (!\Storage::disk('comidas')->delete($filename)) {
                        return response()->json(['status' => 500, 'message' => 'Error al eliminar la imagen de la comida'], 500);
                    }
                }
            }
            if($comida->delete())
            {
                $response=array(
                    'status'=>200,
                    'message'=>'Comida eliminada'
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
        }}
        return response()->json($response,$response['status']);
    }

    //patch
    public function update(Request $request, $id) {
        $jwt = new JwtAuth();
        if (!$jwt->checkToken($request->header('bearertoken'), true)->permisoAdmin) {
            $response = array(
                'status' => 406,
                'menssage' => 'No tienes permiso de administrador'
            );
        } else {
        $comida = Comida::find($id);
    
        if (!$comida) {
            $response = [
                'status' => 404,
                'message' => 'Comida no encontrada'
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
            'nombre'=>'max:40|string',
            'precio'=>'decimal:0,4'
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
    
        if(isset($data_input['nombre'])) { $comida->nombre = $data_input['nombre']; }
        if(isset($data_input['precio'])) { $comida->precio = $data_input['precio']; }
        
        $comida->save();
    
        $response = [
            'status' => 201,
            'message' => 'Comida actualizada',
            'Comida' => $comida
        ];
        }
        return response()->json($response, $response['status']);
    }

    public function uploadImage(Request $request)
    {
        $isValid=\Validator::make($request->all(),['file'=>'required|mimes:jpg,png,jpeg,svg']);
        if(!$isValid->fails()){
            $image=$request->file('file');
            $filename = \Str::uuid() . "." . $image->getClientOriginalExtension();
            \Storage::disk('comidas')->put($filename,\File::get($image));
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

    public function updateImage(Request $request, string $filename)
    {
        $isValid=\Validator::make($request->all(),['file'=>'required|mimes:jpg,png,jpeg,svg']);
        if(!$isValid->fails()){
            $image=$request->file('file');
            \Storage::disk('comidas')->put($filename, \File::get($image));
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

    public function getImage($filename){
        if(isset($filename)){
            $exist=\Storage::disk('comidas')->exists($filename);
            if($exist){
                $file=\Storage::disk('comidas')->get($filename);
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
            $exist=\Storage::disk('comidas')->exists($filename);
            if($exist){
            \Storage::disk('comidas')->delete($filename);
            
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

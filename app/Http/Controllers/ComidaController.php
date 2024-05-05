<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comida;

class ComidaController extends Controller
{
    public function index()
    {
        $data=Comida::all();
        $data=$data->load('detallesCombo');
        $response=array(
            "status"=>200,
            "message"=>"Todos los registros de las funciones",
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
                'nombre'=>'required|max:40',
                'precio'=>'required|regex:/^\d{1,4}(\.\d{1,2})?$/'
            ];
            $isValid=\validator($data,$rules);
            if(!$isValid->fails()){
                $comida=new Comida();
                $comida->nombre=$data['nombre'];
                $comida->precio=$data['precio'];
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

    public function destroy($id){
        if(isset($id)){
            $deleted=Comida::where('id',$id)->delete();
            if($deleted)
            {
                $response=array(
                    'status'=>200,
                    'message'=>'Comida eliminado'
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
            'nombre'=>'max:40',
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
            'message' => 'Comida actualizado',
            'Comida' => $comida
        ];
    
        return response()->json($response, $response['status']);
    }
}

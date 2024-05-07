<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sala;

class SalaController extends Controller
{
    //
    public function index()
    {
        $data=Sala::all();
        $data=$data->load('asientos');
        $response=array(
            "status"=>200,
            "message"=>"Todos los registros de las salas",
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
                'nombreSala'=>'required|string',
                'capacidad'=>'required|integer',
            ];
            $isValid=\validator($data,$rules);
            if(!$isValid->fails()){
                $sala=new Sala();
                $sala->nombreSala=$data['nombreSala'];
                $sala->capacidad=$data['capacidad'];
                $sala->save();
                $response=array(
                    'status'=>201,
                    'message'=>'Sala creada',
                    'sala'=>$sala
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
        $data=Sala::find($id);
        if(is_object($data)){
            $data=$data->load('asientos');
            $response=array(
                'status'=>200,
                'message'=>'Datos de la sala',
                'sala'=>$data
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
            $deleted=Sala::where('id',$id)->delete();
            if($deleted)
            {
                $response=array(
                    'status'=>200,
                    'message'=>'Sala eliminada'
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
        $sala = Sala::find($id);
    
        if (!$sala) {
            $response = [
                'status' => 404,
                'message' => 'Sala no encontrada'
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
            'nombreSala'=>'string',
                'capacidad'=>'integer',
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
    
        if(isset($data_input['nombreSala'])) { $sala->nombreSala = $data_input['nombreSala']; }
        if(isset($data_input['capacidad'])) { $sala->capacidad = $data_input['capacidad']; }

        $sala->save();
    
        $response = [
            'status' => 201,
            'message' => 'Sala actualizada',
            'sala' => $sala
        ];
    
        return response()->json($response, $response['status']);
    }

}

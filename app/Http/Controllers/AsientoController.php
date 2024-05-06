<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asiento;

class AsientoController extends Controller
{
    //
    public function index()
    {
        $data=Asiento::all();
        $response=array(
            "status"=>200,
            "message"=>"Todos los registros del asiento",
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
                'idSala'=>'required',
                'numero'=>'required|numeric',
                'fila'=>'required|alpha_num',
                'estado'=>'required',
            ];
            $isValid=\validator($data,$rules);
            if(!$isValid->fails()){
                $asiento=new Asiento();
                $asiento->idSala = $data['idSala'];
                $asiento->numero=$data['numero'];
                $asiento->fila=$data['fila'];
                $asiento->estado = $data['estado'] ? 1 : 0;
                $asiento->save();
                $response=array(
                    'status'=>201,
                    'message'=>'Asiento creado',
                    'user'=>$asiento
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
        $data=Asiento::find($id);
        if(is_object($data)){
            $response=array(
                'status'=>200,
                'message'=>'Datos del asiento',
                'asiento$asiento'=>$data
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
            $deleted=Asiento::where('id',$id)->delete();
            if($deleted)
            {
                $response=array(
                    'status'=>200,
                    'message'=>'Asiento eliminado'
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
        $asiento = asiento::find($id);
    
        if (!$asiento) {
            $response = [
                'status' => 404,
                'message' => 'asiento no encontrado'
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
            'idSala'=>'required',
            'numero'=>'required|numeric',
            'fila'=>'required|alpha_num',
            'estado'=>'required',
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
        if(isset($data_input['idSala'])) { $asiento->idSala = $data_input['idSala']; }
        if(isset($data_input['numero'])) { $asiento->numero = $data_input['numero']; }
        if(isset($data_input['fila'])) { $asiento->fila = $data_input['fila']; }
        if(isset($data_input['estado'])) { $asiento->estado = $data['estado'] ? 1 : 0; }

        $asiento->save();
    
        $response = [
            'status' => 201,
            'message' => 'asiento actualizado',
            'user' => $asiento
        ];
    
        return response()->json($response, $response['status']);
    }



}

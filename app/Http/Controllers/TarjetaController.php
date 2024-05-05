<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarjeta;

class TarjetaController extends Controller
{
    //
    public function index()
    {   
        $data=Tarjeta::all();
        $response=array(
            "status"=>200,
            "message"=>"Todos los registros de las tarjetas",
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
                'idUsuario'=>'required|numeric',
                'numero'=>'required|numeric',
                'fechaVencimiento'=>'required|date',
                'codigo'=>'required|numeric'
            ];
            $isValid=\validator($data,$rules);
            if(!$isValid->fails()){
                $tarjeta=new Tarjeta();
                $tarjeta->idUsuario=$data['idUsuario'];
                $tarjeta->numero=$data['numero'];
                $tarjeta->fechaVencimiento=$data['fechaVencimiento'];
                $tarjeta->codigo=$data['codigo'];
                $tarjeta->save();
                $response=array(
                    'status'=>201,
                    'message'=>'tarjeta creada',
                    'tarjeta'=>$tarjeta
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
        $data=Tarjeta::find($id);
        if(is_object($data)){
            $response=array(
                'status'=>200,
                'message'=>'Datos de la tarjeta',
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
            $deleted=Tarjeta::where('id',$id)->delete();
            if($deleted)
            {
                $response=array(
                    'status'=>200,
                    'message'=>'Tarjeta eliminada'
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
        $tarjeta = Tarjeta::find($id);
    
        if (!$tarjeta) {
            $response = [
                'status' => 404,
                'message' => 'Tarjeta no encontrada'
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
            'numero'=>'numeric|size:25',
            'fechaVencimiento'=>'date',
            'codigo'=>'numeric|size:5'
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
    
        if(isset($data_input['numero'])) { $tarjeta->numero = $data_input['numero']; }
        if(isset($data_input['fechaVencimiento'])) { $tarjeta->fechaVencimiento = $data_input['fechaVencimiento']; }
        if(isset($data_input['codigo'])) { $tarjeta->codigo = $data_input['codigo']; }

        $tarjeta->save();
    
        $response = [
            'status' => 201,
            'message' => 'Usuario actualizado',
            'tarjeta' => $tarjeta
        ];
    
        return response()->json($response, $response['status']);
    }
    
}

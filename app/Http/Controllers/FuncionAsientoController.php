<?php

namespace App\Http\Controllers;

use App\Models\FuncionAsiento;
use Illuminate\Http\Request;
use App\Helpers\JwtAuth;

class FuncionAsientoController extends Controller
{
    //
    public function index()
    {
        $data=FuncionAsiento::all();
        $data=$data->load('asiento');
        $response=array(
            "status"=>200,
            "message"=>"Todos los registros de los asientos en las funciones",
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
                'idFuncion'=>'required|exists:funciones,id',
                'idAsiento'=>'required|exists:asientos,id',
                'estado'=>'required|boolean'
            ];
            $isValid=\validator($data,$rules);
            if(!$isValid->fails()){
                $funcionAsiento=new FuncionAsiento();
                $funcionAsiento->idFuncion=$data['idFuncion'];
                $funcionAsiento->idAsiento=$data['idAsiento'];
                $funcionAsiento->estado=$data['estado'];
                $funcionAsiento->save();
                $response=array(
                    'status'=>201,
                    'message'=>'funcionAsiento creada',
                    'funcionAsiento'=>$funcionAsiento
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
        $data=FuncionAsiento::find($id);
        if(is_object($data)){
            $data=$data->load('asiento');
            $response=array(
                'status'=>200,
                'message'=>'Datos de funcion asiento',
                'funcionAsiento'=>$data
            );
        }else{
            $response=array(
                'status'=>404,
                'message'=>'Recurso no encontrado'
            );
        }
        return response()->json($response,$response['status']);
    }

    public function destroy(Request $request, $id){
        $jwt = new JwtAuth();
        if (!$jwt->checkToken($request->header('bearertoken'), true)->permisoAdmin) {
            $response = array(
                'status' => 406,
                'menssage' => 'No tienes permiso de administrador'
            );
        } else {
        if(isset($id)){
            $deleted=FuncionAsiento::where('id',$id)->delete();
            if($deleted)
            {
                $response=array(
                    'status'=>200,
                    'message'=>'funcionAsiento eliminado'
                );
            }else{
                $response=array(
                    'status'=>400,
                    'message'=>'No se pudo eliminar funcionAsiento, compruebe que exista'
                );
            }
        }else{
            $response=array(
                'status'=>406,
                'message'=>'Falta el identificador de funcionAsiento a eliminar'
            );
        }
    }
        return response()->json($response,$response['status']);
    }


    public function update(Request $request, $id) {
        $funcionAsiento = FuncionAsiento::find($id);
    
        if (!$funcionAsiento) {
            $response = [
                'status' => 404,
                'message' => 'funcionAsiento no encontrado'
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
            'estado'=>'boolean'
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
    
        if(isset($data_input['estado'])) { $funcionAsiento->estado = $data_input['estado']; }
        
        $funcionAsiento->save();
    
        $response = [
            'status' => 201,
            'message' => 'funcionAsiento actualizado',
            'funcionAsiento' => $funcionAsiento
        ];
    
        return response()->json($response, $response['status']);
    }
}

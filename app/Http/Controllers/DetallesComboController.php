<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetallesCombo;

class DetallesComboController extends Controller
{
    //
    public function index()
    {
        $data=DetallesCombo::all();
        $data=$data->load('comida');
        $response=array(
            "status"=>200,
            "message"=>"Todos los registros de los detalles combo",
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
                'idTicket'=>'required|exists:tickets,id',
                'idComida'=>'required|exists:comida,id',
                'cantidad'=>'required|integer',
                'subtotal'=>'required|decimal:0,4',
                'descuento'=>'required|decimal:0,4',
                'impuesto'=>'required|decimal:0,4'
            ];
            $isValid=\validator($data,$rules);
            if(!$isValid->fails()){
                $combo=new DetallesCombo();
                $combo->idTicket=$data['idTicket'];
                $combo->idComida=$data['idComida'];
                $combo->cantidad=$data['cantidad'];
                $combo->subtotal=$data['subtotal'];
                $combo->descuento=$data['descuento'];
                $combo->impuesto=$data['impuesto'];
                $combo->save();
                $response=array(
                    'status'=>201,
                    'message'=>'combo creado',
                    'combo'=>$combo
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
        $data=DetallesCombo::find($id);
        if(is_object($data)){
            $data=$data->load('comida');
            $response=array(
                'status'=>200,
                'message'=>'Datos del combo',
                'combo'=>$data
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
            $deleted=DetallesCombo::where('id',$id)->delete();
            if($deleted)
            {
                $response=array(
                    'status'=>200,
                    'message'=>'Combo eliminado'
                );
            }else{
                $response=array(
                    'status'=>400,
                    'message'=>'No se pudo eliminar el combo, compruebe que exista'
                );
            }
        }else{
            $response=array(
                'status'=>406,
                'message'=>'Falta el identificador del combo a eliminar'
            );
        }
        return response()->json($response,$response['status']);
    }

    //patch
    public function update(Request $request, $id) {
        $combo = DetallesCombo::find($id);
    
        if (!$combo) {
            $response = [
                'status' => 404,
                'message' => 'combo no encontrado'
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
                'idComida'=>'exists:comida,id',
                'cantidad'=>'integer',
                'subtotal'=>'decimal:0,4',
                'descuento'=>'decimal:0,4',
                'impuesto'=>'decimal:0,4'
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
    
        if(isset($data_input['idComida'])) { $combo->idComida = $data_input['idComida']; }
        if(isset($data_input['cantidad'])) { $combo->cantidad = $data_input['cantidad']; }
        if(isset($data_input['subtotal'])) { $combo->subtotal = $data_input['subtotal']; }
        if(isset($data_input['descuento'])) { $combo->descuento = $data_input['descuento']; }
        if(isset($data_input['impuesto'])) { $combo->impuesto = $data_input['impuesto']; }
        
        $combo->save();
    
        $response = [
            'status' => 201,
            'message' => 'combo actualizado',
            'combo' => $combo
        ];
    
        return response()->json($response, $response['status']);
    }
}

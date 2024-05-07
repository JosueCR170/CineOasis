<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetallesTicket;

class DetallesTicketController extends Controller
{
    //
    public function index()
    {
        $data=DetallesTicket::all();
        $data=$data->load('asientos');
        $response=array(
            "status"=>200,
            "message"=>"Todos los registros de los detalles de tickets",
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
                'idTicket'=>'required|exists:tickets, id',
                'idAsiento'=>'required|exists:asientos, id',
                'subtotal'=>'required|decimal:0,4',
                'descuento'=>'decimal:0,4',
                'impuesto'=>'decimal:0,4'
            ];
            $isValid=\validator($data,$rules);
            if(!$isValid->fails()){
                $detallesTicket=new DetallesTicket();
                $detallesTicket->idTicket=$data['idTicket'];
                $detallesTicket->idAsiento=$data['idAsiento'];
                $detallesTicket->subtotal=$data['subtotal'];
                $detallesTicket->descuento=$data['descuento'];
                $detallesTicket->impuesto=$data['impuesto'];
                $detallesTicket->save();
                $response=array(
                    'status'=>201,
                    'message'=>'detallesTicket creada',
                    'Comida'=>$detallesTicket
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
        $data=DetallesTicket::find($id);
        if(is_object($data)){
            $data=$data->load('asientos');
            $response=array(
                'status'=>200,
                'message'=>'Datos de los detalles ticket',
                'detallesTicket'=>$data
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
            $deleted=DetallesTicket::where('id',$id)->delete();
            if($deleted)
            {
                $response=array(
                    'status'=>200,
                    'message'=>'detallesTicket eliminado'
                );
            }else{
                $response=array(
                    'status'=>400,
                    'message'=>'No se pudo eliminar el detallesTicket, compruebe que exista'
                );
            }
        }else{
            $response=array(
                'status'=>406,
                'message'=>'Falta el identificador del detallesTicket a eliminar'
            );
        }
        return response()->json($response,$response['status']);
    }

    //patch
    public function update(Request $request, $id) {
        $detallesTicket = DetallesTicket::find($id);
    
        if (!$detallesTicket) {
            $response = [
                'status' => 404,
                'message' => 'detallesTicket no encontrado'
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
            'idTicket'=>'exists:tickets, id',
            'idAsiento'=>'exists:asientos, id',
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
    
        if(isset($data_input['idTicket'])) { $detallesTicket->idTicket = $data_input['idTicket']; }
        if(isset($data_input['idAsiento'])) { $detallesTicket->idAsiento = $data_input['idAsiento']; }
        if(isset($data_input['subtotal'])) { $detallesTicket->subtotal = $data_input['subtotal']; }
        if(isset($data_input['descuento'])) { $detallesTicket->descuento = $data_input['descuento']; }
        if(isset($data_input['impuesto'])) { $detallesTicket->impuesto = $data_input['impuesto']; }
        
        $detallesTicket->save();
    
        $response = [
            'status' => 201,
            'message' => 'detallesTicket actualizado',
            'detallesTicket' => $detallesTicket
        ];
    
        return response()->json($response, $response['status']);
    }
}

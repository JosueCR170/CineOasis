<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    //
    public function index()
    {   
        $data=Ticket::all();
        $response=array(
            "status"=>200,
            "message"=>"Todos los registros de los tickets",
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
                'idUsuario'=>'required|exists:users,id',
                'idFuncion'=>'required|exists:funciones,id',
                'cantEntradas'=>'required|integer',
                'fechaCompra'=>'required|date',
                'precioTotal'=>'required|decimal:0,4|integer'
            ];
            $isValid=\validator($data,$rules);
            if(!$isValid->fails()){
                $ticket=new Ticket();
                $ticket->idUsuario=$data['idUsuario'];
                $ticket->idFuncion=$data['idFuncion'];
                $ticket->cantEntradas=$data['cantEntradas'];
                $ticket->fechaCompra=$data['fechaCompra'];
                $ticket->precioTotal=$data['precioTotal'];
                $ticket->save();
                $response=array(
                    'status'=>201,
                    'message'=>'ticket creado',
                    'tarjeta'=>$ticket
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
        $data=Ticket::find($id);
        if(is_object($data)){
            $response=array(
                'status'=>200,
                'message'=>'Datos del ticket',
                'tarjeta'=>$data
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
            $deleted=Ticket::where('id',$id)->delete();
            if($deleted)
            {
                $response=array(
                    'status'=>200,
                    'message'=>'Ticket eliminado'
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
        $ticket = Ticket::find($id);
    
        if (!$ticket) {
            $response = [
                'status' => 404,
                'message' => 'ticket no encontrado'
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
            'idUsuario'=>'exists:users,id',
            'idFuncion'=>'exists:funciones,id',
            'cantEntradas'=>'integer',
            'fechaCompra'=>'date',
            'precioTotal'=>'decimal:0,4|integer'
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
    
        if(isset($data_input['idUsuario'])) { $ticket->idUsuario = $data_input['idUsuario']; }
        if(isset($data_input['idFuncion'])) { $ticket->idFuncion = $data_input['idFuncion']; }
        if(isset($data_input['cantEntradas'])) { $ticket->cantEntradas = $data_input['cantEntradas']; }
        if(isset($data_input['fechaCompra'])) { $ticket->fechaCompra = $data_input['fechaCompra']; }
        if(isset($data_input['precioTotal'])) { $ticket->precioTotal = $data_input['precioTotal']; }

        $ticket->save();
    
        $response = [
            'status' => 201,
            'message' => 'Usuario actualizado',
            'ticket' => $ticket
        ];
    
        return response()->json($response, $response['status']);
    }
}

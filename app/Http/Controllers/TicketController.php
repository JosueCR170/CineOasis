<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    //
    public function index()
    {   
        $data=Ticket::all();
        $data = Ticket::with('funcion')->get();
        $data = Ticket::with('detallesTicket')->get();
        $data = Ticket::with('detallesCombo')->get();
        $data = Ticket::with('usuario')->get();
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
                'idFuncion'=>'required|exists:funciones,id',
                'fechaCompra'=>'required|date',
                'precioTotal'=>'required|decimal:0,4|integer'
            ];
            $isValid=\validator($data,$rules);
            if(!$isValid->fails()){
                $ticket=new Ticket();
                $jwt=new JwtAuth();
                $ticket->idUsuario=$jwt->checkToken($request->header('bearertoken'),true)->iss;
                $ticket->idFuncion=$data['idFuncion'];
                $ticket->fechaCompra=$data['fechaCompra'];
                $ticket->precioTotal=$data['precioTotal'];
                $ticket->save();
                $response=array(
                    'status'=>201,
                    'message'=>'ticket creado',
                    'ticket'=>$ticket
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
            $data = Ticket::with('funcion')->get();
        $data = Ticket::with('detallesTicket')->get();
        $data = Ticket::with('detallesCombo')->get();
        $data = Ticket::with('usuario')->get();
            $response=array(
                'status'=>200,
                'message'=>'Datos del ticket',
                'ticket'=>$data
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
    }
        return response()->json($response,$response['status']);
    }

    public function update(Request $request, $id) {
        $jwt = new JwtAuth();
        if (!$jwt->checkToken($request->header('bearertoken'), true)->permisoAdmin) {
            $response = array(
                'status' => 406,
                'menssage' => 'No tienes permiso de administrador'
            );
        } else {
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
        if(isset($data_input['fechaCompra'])) { $ticket->fechaCompra = $data_input['fechaCompra']; }
        if(isset($data_input['precioTotal'])) { $ticket->precioTotal = $data_input['precioTotal']; }

        $ticket->save();
    
        $response = [
            'status' => 201,
            'message' => 'ticket actualizado',
            'ticket' => $ticket
        ];
        }
        return response()->json($response, $response['status']);
    }
}

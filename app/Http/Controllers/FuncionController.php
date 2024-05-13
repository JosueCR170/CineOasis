<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Funcion;
use Illuminate\Validation\Rule;

class FuncionController extends Controller
{
    //
    public function index()
    {
        $data=Funcion::all();
        $data=$data->load('peliculas');
        $data=$data->load('funcionAsientos');
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

            $salas = Funcion::getSalas();
            $rules=[
                'idPelicula'=>'required|exists:peliculas,id',
                'sala' => ['required', Rule::in($salas)],
                'fecha'=>'required|date',
                'horaInicio'=>'required|date_format:H:i:s',
                'horaFinal'=>'required|date_format:H:i:s',
                'precio'=>'required|decimal:0,4'
            ];
            $isValid=\validator($data,$rules);
            if(!$isValid->fails()){
                $funcion=new Funcion();
                $funcion->idPelicula=$data['idPelicula'];
                $funcion->sala=$data['sala'];
                $funcion->fecha=$data['fecha'];
                $funcion->horaInicio=$data['horaInicio'];
                $funcion->horaFinal=$data['horaFinal'];
                $funcion->precio=$data['precio'];
                $funcion->save();
                $response=array(
                    'status'=>201,
                    'message'=>'Funcion creada',
                    'funcion'=>$funcion
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
        $data=Funcion::find($id);
        if(is_object($data)){
            $data=$data->load('peliculas');
            $data=$data->load('funcionAsientos');
            $response=array(
                'status'=>200,
                'message'=>'Datos de la funcion',
                'funcion'=>$data
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
            $deleted=Funcion::where('id',$id)->delete();
            if($deleted)
            {
                $response=array(
                    'status'=>200,
                    'message'=>'Funcion eliminada'
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
        $funcion = Funcion::find($id);
    
        if (!$funcion) {
            $response = [
                'status' => 404,
                'message' => 'Funcion no encontrada'
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
        $salas = Funcion::getSalas();
        $rules = [
            'idPelicula'=>'exists:peliculas,id',
            'sala' => Rule::in($salas),
            'fecha'=>'date',
            'horaInicio'=>'date_format:H:i:s',
            'horaFinal'=>'date_format:H:i:s',
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
    
        if(isset($data_input['idPelicula'])) { $funcion->idPelicula = $data_input['idPelicula']; }
        if(isset($data_input['sala'])) { $funcion->sala = $data_input['sala']; }

        if(isset($data_input['fecha'])) { $funcion->fecha = $data_input['fecha']; }
        if(isset($data_input['horaInicio'])) { $funcion->horaInicio = $data_input['horaInicio']; }
        if(isset($data_input['horaFinal'])) { $funcion->horaFinal = $data_input['horaFinal']; }
        if(isset($data_input['precio'])) { $funcion->precio = $data_input['precio']; }

        $funcion->save();
    
        $response = [
            'status' => 201,
            'message' => 'Funcion actualizada',
            'funcion' => $funcion
        ];
    
        return response()->json($response, $response['status']);
    }
}

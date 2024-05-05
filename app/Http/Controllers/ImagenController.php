<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Imagen;

class ImagenController extends Controller
{
    //
    public function index()
    {
        $data=Imagen::all();
        $response=array(
            "status"=>200,
            "message"=>"Todos los registros de la categoria",
            "data"=>$data
        );
        return response()->json($response,200);
    }
    public function store(Request $request){
        $data_input = $request->input('data',null);
        if($data_input){
            $data = json_decode($data_input,true);
            $data=array_map('trim',$data);
            $rules=[
                'idPelicula'=>'required',
                'descripcion'=>'required',

            ];
            $isValid =\validator($data,$rules);
            if(!$isValid->fails()){
                $imagen = new Imagen();
                $imagen->idPelicula=$data['idPelicula']; 
                $imagen->imagen=$data['imagen'];
                $imagen->descripcion=$data['descripcion'];

                $imagen->save();
                $response = array(
                    'status'=>201,
                    'menssage'=>'pelicula creada',
                    'pelicula'=>$imagen
                );
            }else{
                $response = array(
                    'status'=>406,
                    'menssage'=>'Datos invalidos',
                    'errors'=>$isValid->errors()
                );
            }
        }else{
            $response = array(
                'status'=>400,
                'menssage'=>'No se encontro el objeto data'
            );
        }
        return response()->json($response,$response['status']);
    }

        public function show($id){
            $data=Imagen::find($id);
            if(is_object($data)){
                $response=array(
                'status'=>200,
                'menssage'=>'Imagen encontrada',
                'category'=>$data
                );
            }
            else{
                $response = array(
                    'status'=>404,
                    'menssage'=>'Recurso no encontrado'
                );

            }
            return response()->json($response,$response['status']);
        }


        public function destroy($id){
            if(isset($id)){
                $delete=Imagen::where('id',$id)->delete();
                if($delete){
                    $response=array(
                        'status'=>200,
                        'menssage'=>'Imagen eliminada',
                        );
                }else{
                    $response = array(
                        'status'=>400,
                        'menssage'=>'No se pudo eliminar la Imagen, compruebe que exista'
                    );
                }
            }else{
                $response = array(
                    'status'=>406,
                    'menssage'=>'Falta el identificador del recurso a eliminar'
                );
            }
            return response()->json($response,$response['status']);
        }


}

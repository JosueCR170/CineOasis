<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelicula;

class PeliculaController extends Controller
{
    public function index()
    {
        $data=Pelicula::all();
       $data=$data->load('imagenes');
        $response=array(
            "status"=>200,
            "message"=>"Todos los registros de la categoria",
            "data"=>$data
        );
        return response()->json($response,200);
    }
      /**
     * Metodo POST para crear un registro
     */
    public function store(Request $request){
        $data_input = $request->input('data',null);
        if($data_input){
            $data = json_decode($data_input,true);
            $data=array_map('trim',$data);
            $rules=[
                'nombre'=>'required|alpha',
                'descripcion'=>'required',
                'duracion'=>'required',
                'idioma'=>'required',
                'subtitulos'=>'required',
                'genero'=>'required',
                'fechaEstreno'=>'required',
                'calificacion'=>'nullable',
                'calidad'=>'nullable',
                'director'=>'nullable',
                'elenco'=>'nullable',
                

            ];
            $isValid =\validator($data,$rules);
            if(!$isValid->fails()){
                $pelicula = new Pelicula();
                $pelicula->nombre=$data['nombre'];
                $pelicula->descripcion=$data['descripcion'];
                $pelicula->duracion=$data['duracion'];
                $pelicula->idioma=$data['idioma'];
                $pelicula->subtitulos=$data['subtitulos'];
                $pelicula->fechaEstreno=$data['fechaEstreno'];
                $pelicula->calificacion=$data['calificacion'];
                $pelicula->director=$data['director'];
                $pelicula->elenco=$data['elenco'];
                $pelicula->save();
                $pelicula = array(
                    'status'=>201,
                    'menssage'=>'pelicula creada',
                    'category'=>$pelicula
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
            $data=Pelicula::find($id);
            if(is_object($data)){
                $data->load('imagenes');
                $response=array(
                'status'=>200,
                'menssage'=>'pelicula encontrada',
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
                $delete=Category::where('id',$id)->delete();
                if($delete){
                    $response=array(
                        'status'=>200,
                        'menssage'=>'pelicula eliminada',
                        );
                }else{
                    $response = array(
                        'status'=>400,
                        'menssage'=>'No se pudo eliminar la peliculo, compruebe que exista'
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

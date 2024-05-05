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
            "menssage"=>"Todos los registros de la categoria",
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
                'subtitulo'=>'required',
                'genero'=>'required',
                'fechaEstreno'=>'required',
                'calificacionEdad'=>'nullable',
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
                $pelicula->subtitulo=$data['subtitulo'];
                $pelicula->genero=$data['genero'];
                $pelicula->fechaEstreno=$data['fechaEstreno'];
                $pelicula->calificacionEdad=$data['calificacionEdad'];
                $pelicula->director=$data['director'];
                $pelicula->elenco=$data['elenco'];
                $pelicula->save();
                $response = array(
                    'status'=>201,
                    'menssage'=>'pelicula creada',
                    'pelicula'=>$pelicula
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
                $data=$data->load('imagenes');
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

        //patch
    public function update(Request $request, $id) {
        $user = User::find($id);
    
        if (!$user) {
            $response = [
                'status' => 404,
                'message' => 'Usuario no encontrado'
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
            'nombre' => 'alpha',
            'descripcion' => 'alpha',
            'idioma' => 'alpha',
            'subtitulo' => 'alpha',
            'genero' => 'alpha',
            'fechaEstreno' => 'date',
            'calificacionEdad' => 'numeric',
            'calidad' => 'alpha_num',
            'director' => 'alpha',
            'elenco' => 'alpha',
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
    
        if(isset($data_input['name'])) { $user->name = $data_input['name']; }
        if(isset($data_input['apellido'])) { $user->apellido = $data_input['apellido']; }
        if(isset($data_input['email'])) { $user->email = $data_input['email']; }
        if(isset($data_input['password'])) { $user->password = $data_input['password']; }
        if(isset($data_input['fechaNacimiento'])) { $user->fechaNacimiento = $data_input['fechaNacimiento']; }
        if(isset($data_input['permisoAdmin'])) { $user->permisoAdmin = $data_input['permisoAdmin']; }

        $user->save();
    
        $response = [
            'status' => 201,
            'message' => 'Usuario actualizado',
            'user' => $user
        ];
    
        return response()->json($response, $response['status']);
    }
    
}

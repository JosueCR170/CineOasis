<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelicula;
use App\Enums\ServerStatus;
use Illuminate\Validation\Rule;
use App\Helpers\JwtAuth;

class PeliculaController extends Controller
{

 

    public function index()
    {
        $data=Pelicula::all();
        $data=$data->load('imagenes');
        $response=array(
            "status"=>200,
            "menssage"=>"Todos los registros de las peliculas",
            "data"=>$data
        );
        return response()->json($response,200);
    }
      /**
     * Metodo POST para crear un registro
     */
    public function store(Request $request){

        $jwt=new JwtAuth();
       if(!$jwt->checkToken($request->header('bearertoken'),true)->permisoAdmin){
        $response = array(
            'status'=>406,
            'menssage'=>'No tienes permiso de administrador'
           
        );
       }
       else{

        $data_input = $request->input('data',null);
        if($data_input){
            $data = json_decode($data_input,true);
            $data=array_map('trim',$data);

            $idiomas = Pelicula::getIdiomas();
            $subtitulos = Pelicula::getSubtitulos();
            $clasificacion = Pelicula::getClasificacion();
            $animacion = Pelicula::getAnimacion();

            $rules = [
                'nombre' => 'required|string|max:40',
                'descripcion' => 'required',
                'duracion' => 'required|date_format:H:i',
                'idioma' => ['required', Rule::in($idiomas)],
                'subtitulo' => ['required', Rule::in($subtitulos)],
                'genero' => 'required|max:20',
                'fechaEstreno' => 'required|date',
                'calificacionEdad' => ['required', Rule::in(array_keys($clasificacion))],
                'animacion' => ['required', Rule::in($animacion)],
                'director' => 'required|max:70',
                'elenco' => 'required|max:160',
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
                $pelicula->animacion=$data['animacion'];
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
                'pelicula'=>$data
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


        public function destroy(Request $request, $id){
            $jwt = new JwtAuth();
            if (!$jwt->checkToken($request->header('bearertoken'), true)->permisoAdmin) {
                $response = array(
                    'status' => 406,
                    'message' => 'No tienes permiso de administrador'
                );
                return response()->json($response, $response['status']);
            }
            if (isset($id)) {
                $pelicula = Pelicula::find($id);
                if (!$pelicula) {
                    $response = array(
                        'status' => 404,
                        'message' => 'Pelicula no encontrada'
                    );
                    return response()->json($response, $response['status']);
                }
        
                $imagenes = $pelicula->imagenes;
        
                if ($imagenes) {
                    foreach ($imagenes as $imagen) {
                        $filename = $imagen->imagen;
                        \Storage::disk('peliculas')->delete($filename);
                    }
                }
                
                $delete = Pelicula::where('id', $id)->delete();
                if ($delete) {
                    $response = array(
                        'status' => 200,
                        'message' => 'Pelicula eliminada',
                    );
                } else {
                    $response = array(
                        'status' => 400,
                        'message' => 'No se pudo eliminar la película, compruebe que exista'
                    );
                }
            } else {
                $response = array(
                    'status' => 406,
                    'message' => 'Falta el identificador del recurso a eliminar'
                );
            }
        
            return response()->json($response, $response['status']);
        }
        

        //patch
    public function update(Request $request, $id) {
        $jwt=new JwtAuth();
        if(!$jwt->checkToken($request->header('bearertoken'),true)->permisoAdmin){
         $response = array(
             'status'=>406,
             'menssage'=>'No tienes permiso de administrador'
            
         );
        
        }
        else{

        $pelicula = Pelicula::find($id);
        if (!$pelicula) {
            $response = [
                'status' => 404,
                'message' => 'Pelicula no encontrada'
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
    
        $idiomas = Pelicula::getIdiomas();
        $subtitulos = Pelicula::getSubtitulos();
        $clasificacion = Pelicula::getClasificacion();
        $animacion = Pelicula::getAnimacion();

        $rules = [
            'nombre' => 'string|max:40',
            'duracion' => 'date_format:H:i:s',
            'idioma' =>  Rule::in($idiomas),
            'subtitulo' => Rule::in($subtitulos),
            'genero' => 'max:20',
            'fechaEstreno' => 'date',
            'calificacionEdad' => Rule::in(array_keys($clasificacion)),
            'animacion' => Rule::in($animacion),
            'director' => 'max:70',
            'elenco' => 'max:160',
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

    
        if(isset($data_input['nombre'])) { $pelicula->nombre = $data_input['nombre']; }
        if(isset($data_input['descripcion'])) { $pelicula->descripcion = $data_input['descripcion']; }
        if(isset($data_input['duracion'])) { $pelicula->duracion = $data_input['duracion']; }
        if(isset($data_input['idioma'])) { $pelicula->idioma = $data_input['idioma']; }
        if(isset($data_input['subtitulo'])) { $pelicula->subtitulo = $data_input['subtitulo']; }
        if(isset($data_input['genero'])) { $pelicula->genero = $data_input['genero']; }
        if(isset($data_input['fechaEstreno'])) { $pelicula->fechaEstreno = $data_input['fechaEstreno']; }
        if(isset($data_input['calificacionEdad'])) { $pelicula->calificacionEdad = $data_input['calificacionEdad']; }
        if(isset($data_input['animacion'])) { $pelicula->animacion = $data_input['animacion']; }
        if(isset($data_input['director'])) { $pelicula->director = $data_input['director']; }
        if(isset($data_input['elenco'])) { $pelicula->elenco = $data_input['elenco']; }

        $pelicula->save();
    
        $response = [
            'status' => 201,
            'message' => 'Pelicula actualizada',
            'Pelicula' => $pelicula
        ];
    
    }
        return response()->json($response, $response['status']);
    }
    
}

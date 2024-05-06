<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Imagen;
// use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImagenController extends Controller
{
    //
    public function index()
    {
        $data=Imagen::all();
        $response=array(
            "status"=>200,
            "message"=>"Todos los registros de las imagenes",
            "data"=>$data
        );
        return response()->json($response,200);
    }
    
 
    public function store(Request $request){
        $data_input = $request->input('data', null);
        
        // Verificar si hay una imagen cargada en la solicitud
        if ($request->hasFile('imagen')) {
            $image = $request->file('imagen');
    
            if($data_input && $image){
                $data = json_decode($data_input, true) ?: [];
                $data = array_map('trim', $data);
    
                $rules = [
                    'idPelicula' => 'required|exists:peliculas,id',
                    'descripcion' => 'required'
                ];
    
                $isValid = \validator($data, $rules);
    
                if(!$isValid->fails()){
                    $imagen = new Imagen();
                    $imagen->idPelicula = $data['idPelicula']; 
                    $imagen->imagen = base64_encode(file_get_contents($image)); 
                    $imagen->descripcion = $data['descripcion'];
    
                    $imagen->save();
                    $response = [
                        'status' => 201,
                        'message' => 'Imagen creada',
                        'imagen' => $imagen
                    ];
                } else {
                    $response = [
                        'status' => 406,
                        'message' => 'Datos inválidos',
                        'errors' => $isValid->errors()
                    ];
                }
            } else {
                $response = [
                    'status' => 400,
                    'message' => 'No se encontraron los datos de la imagen'
                ];
            }
        } else {
            $response = [
                'status' => 400,
                'message' => 'No se cargó ninguna imagen'
            ];
        }
    
        return response()->json($response, $response['status']);
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


    public function update(Request $request, $id) {
        $imagen = Imagen::find($id);
    
        if (!$imagen) {
            $response = [
                'status' => 404,
                'message' => 'Imagen no encontrada'
            ];
            return response()->json($response, $response['status']);
        }
        $data_input = $request->input('data', null);
        $image_input = $request->file('imagen');
    
        if ($data_input || $image_input) {
            $data = json_decode($data_input, true) ?: [];
            $data = array_map('trim', $data);
            $rules = [
                'idPelicula' => 'exists:peliculas,id'
            ];
            $isValid = \validator($data, $rules);
            if(!$isValid->fails()) {
                $imagen->idPelicula = $data['idPelicula']; 
                $imagen->descripcion = $data['descripcion'];
                $imagen->imagen = base64_encode(file_get_contents($image_input)); 
    
                $imagen->save();
                $response = [
                    'status' => 200,
                    'message' => 'Imagen actualizada',
                    'imagen' => $imagen
                ];
            } 
            else {
                $response = [
                    'status' => 406,
                    'message' => 'Datos inválidos',
                    'errors' => $isValid->errors()
                ];
            }
        } else {
            $response = [
                'status' => 400,
                'message' => 'No se encontraron los datos de la imagen'
            ];
        }
        return response()->json($response, $response['status']);
    }
    
}

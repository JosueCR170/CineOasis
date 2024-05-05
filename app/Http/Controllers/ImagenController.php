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
                    'idPelicula' => 'required',
                    'descripcion' => 'required',
                ];
    
                $isValid = \validator($data, $rules);
    
                if(!$isValid->fails()){
                    $imagen = new Imagen();
                    $imagen->idPelicula = $data['idPelicula']; 
                    $imagen->imagen = $image->store('imagenes/pelicula'); // Guardar la imagen en el sistema de archivos
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
    

        // public function show($id){
        //     $data=Imagen::find($id);
        //     if(is_object($data)){
        //         $response=array(
        //         'status'=>200,
        //         'menssage'=>'Imagen encontrada',
        //         'category'=>$data
        //         );
        //     }
        //     else{
        //         $response = array(
        //             'status'=>404,
        //             'menssage'=>'Recurso no encontrado'
        //         );

        //     }
        //     return response()->json($response,$response['status']);
        // }
        public function show($id) {
            $imagen = Imagen::find($id);
        
            if ($imagen) {
                // Decodificar el blob de la imagen
                $imageData = base64_decode($imagen->imagen);
        
                // Establecer el tipo de contenido de la respuesta
                $headers = [
                    'Content-Type' => 'image/png', // Cambia el tipo de contenido según el formato de la imagen
                    'Content-Length' => strlen($imageData)
                ];
        
                // Devolver la imagen como respuesta HTTP
                return response($imageData, 200, $headers);
            } else {
                // Si la imagen no se encuentra, devolver una respuesta 404
                return response()->json(['message' => 'Imagen no encontrada'], 404);
            }
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

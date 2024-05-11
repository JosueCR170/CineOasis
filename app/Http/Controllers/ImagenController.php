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
    
            // Verificar si el archivo es una imagen
            if ($image->isValid() && strpos($image->getMimeType(), 'image/') === 0) {
    
                if($data_input && $image){
                    $data = json_decode($data_input, true) ?: [];
                    $data = array_map('trim', $data);
    
                    $rules = [
                        'idPelicula' => 'required|exists:peliculas,id',
                        'descripcion' => 'required',
                        'imagen' => 'image' // Regla de validación para asegurarse de que sea una imagen
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
                    'message' => 'El archivo no es una imagen válida'
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
            
            // Verificar si hay una imagen cargada en la solicitud
            if ($request->hasFile('imagen')) {
                $image = $request->file('imagen');
        
                // Verificar si el archivo cargado es una imagen válida
                if ($image->isValid() && strpos($image->getMimeType(), 'image/') === 0) {
                    // Los datos para actualizar pueden ser proporcionados a través de la entrada 'data'
                    $data_input = $request->input('data', null);
                    
                    // Decodificar los datos de entrada si están presentes
                    $data = $data_input ? json_decode($data_input, true) : [];
                    $data = array_map('trim', $data);
        
                    // Definir las reglas de validación para los datos
                    $rules = [
                        'idPelicula' => 'exists:peliculas,id'
                        // Agrega otras reglas de validación según sea necesario
                    ];
        
                    // Validar los datos según las reglas definidas
                    $validator = \Validator::make($data, $rules);
        
                    if (!$validator->fails()) {
                        // Actualizar los campos de la imagen
                        if (isset($data['idPelicula'])) { $imagen->idPelicula = $data['idPelicula']; }
                        if (isset($data['descripcion'])) { $imagen->descripcion = $data['descripcion']; }
        
                        // Guardar la nueva imagen
                        $imagen->imagen = base64_encode(file_get_contents($image)); 
                        $imagen->save();
        
                        $response = [
                            'status' => 200,
                            'message' => 'Imagen actualizada',
                            'imagen' => $imagen
                        ];
                    } else {
                        // Los datos proporcionados no son válidos
                        $response = [
                            'status' => 406,
                            'message' => 'Datos inválidos',
                            'errors' => $validator->errors()
                        ];
                    }
                } else {
                    // El archivo cargado no es una imagen válida
                    $response = [
                        'status' => 400,
                        'message' => 'El archivo no es una imagen válida'
                    ];
                }
            } else {
                // No se proporcionó ninguna nueva imagen para actualizar
                $response = [
                    'status' => 400,
                    'message' => 'No se proporcionó ninguna imagen para actualizar'
                ];
            }
            
            return response()->json($response, $response['status']);
        }
        
    
}

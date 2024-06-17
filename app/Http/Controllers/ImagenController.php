<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Imagen;
use Illuminate\Support\Str;
use App\Helpers\JwtAuth;

class ImagenController extends Controller
{
    //funciones utilizadas por tablas que contienen solo una imagen
    public function store(Request $request, string $path)
    {
        $isValid=\Validator::make($request->all(),['file'=>'required|mimes:jpg,png,jpeg,svg']);
        if(!$isValid->fails()){
            $image=$request->file('file');
            $filename = \Str::uuid() . "." . $image->getClientOriginalExtension();
            \Storage::disk($path)->put($filename,\File::get($image));
            $response=array(
                'status'=>201,
                'message'=>'Imagen guardada',
                'filename'=>$filename,
            );
        }else{
            $response=array(
                'status'=>406,
                'message'=>'Error: no se encontró el archivo',
                'errors'=>$isValid->errors(),
            );
        }
        return response()->json($response,$response['status']);
    }

    public function update(Request $request, string $path, string $filename)
    {
        $isValid=\Validator::make($request->all(),['file'=>'required|mimes:jpg,png,jpeg,svg']);
        if(!$isValid->fails()){
            $image=$request->file('file');
            \Storage::disk($path)->put($filename, \File::get($image));
            $response=array(
                'status'=>201,
                'message'=>'Imagen actualizada',
                'filename'=>$filename,
            );
        }else{
            $response=array(
                'status'=>406,
                'message'=>'Error: no se encontró el archivo',
                'request'=>$request,
                'errors'=>$isValid->errors(),
            );
        }
        return response()->json($response,$response['status']);
    }

    public function show(string $path, string $filename){
        if(isset($filename)){
            $exist=\Storage::disk($path)->exists($filename);
            if($exist){
                $file=\Storage::disk($path)->get($filename);
                return new Response($file,200);
            }else{
                $response=array(
                    'status'=>404,
                    'message'=>'No existe la imagen',
                );
            }
        }else{
            $response=array(
                'status'=>406,
                'message'=>'No se definió el nombre de la imagen',
            );
        }
        return response()->json($response,$response['status']);
    }

    public function destroy(string $path, string $filename){
        if(isset($filename)){
            $exist=\Storage::disk($path)->exists($filename);
            if($exist){
            \Storage::disk($path)->delete($filename);
            
            $response = array(
                'status' => 200,
                'message' => 'Imagen eliminada'
            );
        } else {
            $response = array(
                'status' => 404,
                'message' => 'No existe la imagen'
            );
        }
    }else{
        $response=array(
            'status'=>406,
            'message'=>'No se definió el nombre de la imagen',
        );}
        return response()->json($response, $response['status']);
    }



    //Funciones para la tabla imagenes (una pelicula puede tener varias imagenes)
    public function index()
    {
        $data = Imagen::all();
        $response = array(
            "status" => 200,
            "message" => "Todos los registros de las imagenes",
            "data" => $data
        );
        return response()->json($response, 200);
    }

    public function showImageForPelicula(string $filename){
        if(isset($filename)){
            $exist=\Storage::disk('peliculas')->exists($filename);
            if($exist){
                $file=\Storage::disk('peliculas')->get($filename);
                return new Response($file,200);
            }else{
                $response=array(
                    'status'=>404,
                    'message'=>'No existe la imagen',
                );
            }
        }else{
            $response=array(
                'status'=>406,
                'message'=>'No se definió el nombre de la imagen',
            );
        }
        return response()->json($response,$response['status']);
    }

    public function storeImageForPelicula(Request $request)
    {
        $jwt = new JwtAuth();
        if (!$jwt->checkToken($request->header('bearertoken'), true)->permisoAdmin) {
            $response = array(
                'status' => 406,
                'menssage' => 'No tienes permiso de administrador'
            );
        } else {

            $data_input = $request->input('data', null);
            $file = $request->file('file');

            if ($data_input && $file) {
                $data = json_decode($data_input, true);
                $data = array_map('trim', $data);

                $isValid = \Validator::make($data, [
                    'idPelicula' => 'required|exists:peliculas,id',
                    'descripcion' => 'required',
                ]);

                if (!$isValid->fails()) {
                    $imagen = new Imagen();
                    $filename = \Str::uuid() . "." . $file->getClientOriginalExtension();

                    \Storage::disk('peliculas')->put($filename, \File::get($file));

                    $imagen->idPelicula = $data['idPelicula'];
                    $imagen->descripcion = $data['descripcion'];
                    $imagen->imagen = $filename;
                    $imagen->save();

                    $response = [
                        'status' => 201,
                        'message' => 'Imagen guardada',
                        'filename' => $filename
                    ];
                } else {
                    $response = [
                        'status' => 406,
                        'message' => 'Error: verifica rellenar todos los datos',
                        'error' => $isValid->errors()
                    ];
                }
            } else {
                $response = [
                    'status' => 400,
                    'message' => 'No se encontraron todos los datos necesarios'
                ];
            }
        }
        return response()->json($response, $response['status']);
    }


    public function updateImageForPelicula(Request $request, $id)
    {
        $jwt = new JwtAuth();
        if (!$jwt->checkToken($request->header('bearertoken'), true)->permisoAdmin) {
            $response = array(
                'status' => 406,
                'menssage' => 'No tienes permiso de administrador'

            );
           
        } else {
        $imagen = Imagen::find($id);
        if (!$imagen) {
            $response = [
                'status' => 404,
                'message' => 'Imagen no encontrada'
            ];
            return response()->json($response, $response['status']);
        }

        $data_input = $request->input('data', null);
        $file = $request->file('file');

        if (!$data_input && !$file) {
            $response = [
                'status' => 400,
                'message' => 'No se proporcionaron datos ni archivo para actualizar'
            ];
            return response()->json($response, $response['status']);
        }
        if ($data_input) {
            $data = json_decode($data_input, true);
            $data = array_map('trim', $data);
            $isValid = \Validator::make($data, [
                'idPelicula' => 'exists:peliculas,id'
            ]);
            if ($isValid->fails()) {
                $response = [
                    'status' => 406,
                    'message' => 'Datos inválidos',
                    'errors' => $isValid->errors()
                ];
                return response()->json($response, $response['status']);
            }
            $imagen->idPelicula = isset($data['idPelicula']) ? $data['idPelicula'] : $imagen->idPelicula;
            $imagen->descripcion = isset($data['descripcion']) ? $data['descripcion'] : $imagen->descripcion;
        }

        if ($file) {
            \Storage::disk('peliculas')->put($imagen->imagen, \File::get($file));
        }
        $imagen->save();

        $response = [
            'status' => 200,
            'message' => 'Imagen actualizada',
            'imagen' => $imagen
        ];
    }
        return response()->json($response, $response['status']);
    }


    public function destroyImagePelicula(Request $request, $id)
    {

        $jwt = new JwtAuth();
        if (!$jwt->checkToken($request->header('bearertoken'), true)->permisoAdmin) {
            $response = array(
                'status' => 406,
                'menssage' => 'No tienes permiso de administrador'

            );
           
        } else {

        if (isset($id)) {
            $imagen = Imagen::find($id);
            $delete = Imagen::where('id', $id)->delete();
            if ($delete) {

                $filename = $imagen->imagen;
                \Storage::disk('peliculas')->delete($filename);
                $response = array(
                    'status' => 200,
                    'menssage' => 'Imagen eliminada',
                );
            } else {
                $response = array(
                    'status' => 400,
                    'menssage' => 'No se pudo eliminar la Imagen, compruebe que exista'
                );
            }
        } else {
            $response = array(
                'status' => 406,
                'menssage' => 'Falta el identificador del recurso a eliminar'
            );
        }
    }
        return response()->json($response, $response['status']);
    }
}

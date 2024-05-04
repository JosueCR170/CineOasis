<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarjeta;

class TarjetaController extends Controller
{
    //
    public function index()
    {
        $data=Tarjeta::all();
        $response=array(
            "status"=>200,
            "message"=>"Todos los registros de la categoria",
            "data"=>$data
        );
        return response()->json($response,200);
    }
}

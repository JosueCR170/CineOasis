<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //
    public function index()
    {
        $data=User::all();
        $data=$data->load('tarjetas');
        $response=array(
            "status"=>200,
            "message"=>"Todos los registros de la categoria",
            "data"=>$data
        );
        return response()->json($response,200);
    }
}

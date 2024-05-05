<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TarjetaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PeliculaController;
use App\Http\Controllers\ImagenController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1')->group(
    function(){
        //RUTAS ESPECIFICAS

        //RUTAS AUTOMATICAS Restful
        Route::resource('/tarjeta',TarjetaController::class,['except'=>['create','edit']]);
        Route::resource('/user',UserController::Class,['except'=>['create','edit']]);
        Route::resource('/pelicula',PeliculaController::Class,['except'=>['create','edit']]);
        Route::resource('/imagen',ImagenController::Class,['except'=>['create','edit']]);
    }
);

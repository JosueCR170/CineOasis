<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TarjetaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PeliculaController;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\AsientoController;
use App\Http\Controllers\FuncionController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DetallesTicketController;
use App\Http\Controllers\ComidaController;
use App\Http\Controllers\DetallesComboController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1')->group(
    function(){
        //RUTAS ESPECIFICAS

        //RUTAS AUTOMATICAS Restful
        Route::resource('/tarjeta',TarjetaController::class,['except'=>['create','edit']]);
        Route::resource('/user',UserController::Class,['except'=>['create','edit']]);
        Route::resource('/pelicula',PeliculaController::class,['except'=>['create','edit']]);
        Route::resource('/imagen',ImagenController::class,['except'=>['create','edit']]);
        Route::resource('/sala',SalaController::class,['except'=>['create','edit']]);
        Route::resource('/asiento',AsientoController::class,['except'=>['create','edit']]);
        Route::resource('/funcion',FuncionController::class,['except'=>['create','edit']]);

        Route::resource('/ticket',TicketController::class,['except'=>['create','edit']]);
        Route::resource('/detalles_ticket',DetallesTicketController::class,['except'=>['create','edit']]);
        Route::resource('/comida',ComidaController::class,['except'=>['create','edit']]);
        Route::resource('/combo',DetallesComboController::class,['except'=>['create','edit']]);

    }
);

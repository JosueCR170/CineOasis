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


use App\Http\Middleware\ApiAuthMiddleware;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1')->group(
    function(){
        //RUTAS ESPECIFICAS
        Route::post('/rellenar',[AsientoController::class,'rellenar']);
        //POST
        Route::post('/user/login',[UserController::class,'login']);
        Route::post('/user/{id}', [UserController::class, 'show'])->middleware(ApiAuthMiddleware::class);
        Route::post('/user/getIdentity',[UserController::class,'getIdentity'])->middleware(ApiAuthMiddleware::class);


        Route::post('/user/store',[UserController::class,'store']);


        Route::post('/comida/store',[ComidaController::class,'store'])->middleware(ApiAuthMiddleware::class);
        Route::post('/comida/{id}', [ComidaController::class, 'show']);

        //GET
        Route::get('/user',[UserController::class, 'index'])->middleware(ApiAuthMiddleware::class);
        Route::get('/comida',[ComidaController::class, 'index']);
        Route::get('/pelicula', [PeliculaController::class, 'index']);
        Route::get('/funcion',[FuncionController::class,'index']);

        //put
        Route::put('/user/{id}', [UserController::class, 'update'])->middleware(ApiAuthMiddleware::class);
        Route::put('/comida/{id}', [ComidaController::class, 'update'])->middleware(ApiAuthMiddleware::class);

        //delete
        Route::delete('/user/{id}', [UserController::class, 'destroy'])->middleware(ApiAuthMiddleware::class);
        Route::delete('/comida/{id}', [ComidaController::class, 'destroy'])->middleware(ApiAuthMiddleware::class);



        //resource
        Route::resource('/Tarjeta',PeliculaController::class,['except'=>['create','edit']])->middleware(ApiAuthMiddleware::class);//listo
        Route::resource('/ticket',TicketController::class,['except'=>['create','edit']])->middleware(ApiAuthMiddleware::class);
        Route::resource('/detalles_ticket',DetallesTicketController::class,['except'=>['create','edit']])->middleware(ApiAuthMiddleware::class);
        Route::resource('/sala',SalaController::class,['except'=>['create','edit']])->middleware(ApiAuthMiddleware::class);
      //  Route::resource('/user',UserController::class,['except'=>['create','edit']]);
       
       
        Route::resource('/pelicula',PeliculaController::class,['except'=>['create','edit']]);
        Route::resource('/imagen',ImagenController::class,['except'=>['create','edit']]);
       
        Route::resource('/asiento',AsientoController::class,['except'=>['create','edit']]);
        Route::resource('/funcion',FuncionController::class,['except'=>['create','edit']]);

       Route::resource('/comida',ComidaController::class,['except'=>['create','edit']]);
        Route::resource('/combo',DetallesComboController::class,['except'=>['create','edit']]);

    }
);

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
        
        //POST
        Route::post('/user/login',[UserController::class,'login']);//listo
        Route::post('/user/getIdentity',[UserController::class,'getIdentity'])->middleware(ApiAuthMiddleware::class);//listo
        Route::post('/user',[UserController::class,'store']);//listo
        Route::post('/asiento',[AsientoController::class,'store'])->middleware(ApiAuthMiddleware::class);//listo
        Route::post('/pelicula',[PeliculaController::class,'store'])->middleware(ApiAuthMiddleware::class);
        Route::post('/asiento/rellenar',[AsientoController::class,'rellenar'])->middleware(ApiAuthMiddleware::class);//listo (Actualizar)
        Route::post('/comida',[ComidaController::class,'store'])->middleware(ApiAuthMiddleware::class);//listo
        //show
        Route::post('/comida/{id}', [ComidaController::class, 'show']);//listo
        Route::post('/asiento/{id}', [AsientoController::class, 'show']);//listo
        Route::post('/pelicula/{id}', [PeliculaController::class, 'show']);   
        Route::post('/user/{id}', [UserController::class, 'show'])->middleware(ApiAuthMiddleware::class);
        //GET
        Route::get('/user',[UserController::class, 'index'])->middleware(ApiAuthMiddleware::class);
        Route::get('/comida',[ComidaController::class, 'index']);//listo
        Route::get('/pelicula', [PeliculaController::class, 'index']);
        Route::get('/funcion',[FuncionController::class,'index']);
        Route::get('/asiento',[AsientoController::class,'index']);//listo
        //put
        Route::put('/user/{id}', [UserController::class, 'update'])->middleware(ApiAuthMiddleware::class);//listo
        Route::put('/comida/{id}', [ComidaController::class, 'update'])->middleware(ApiAuthMiddleware::class);//listo
        Route::put('/asiento/{id}', [AsientoController::class, 'update'])->middleware(ApiAuthMiddleware::class);//listo
        Route::put('/funcion/{id}',[FuncionController::class,'update'])->middleware(ApiAuthMiddleware::class);
        Route::put('/pelicula/{id}', [PeliculaController::class, 'update'])->middleware(ApiAuthMiddleware::class);
        //delete
        Route::delete('/user/{id}', [UserController::class, 'destroy'])->middleware(ApiAuthMiddleware::class);//listo
        Route::delete('/comida/{id}', [ComidaController::class, 'destroy'])->middleware(ApiAuthMiddleware::class);//listo
        Route::delete('/asiento/{id}', [AsientoController::class, 'destroy'])->middleware(ApiAuthMiddleware::class);//listo
        Route::delete('/funcion/{id}',[FuncionController::class,'destroy'])->middleware(ApiAuthMiddleware::class);
        Route::delete('/pelicula/{id}', [PeliculaController::class, 'destroy'])->middleware(ApiAuthMiddleware::class);
        //resource
        Route::resource('/Tarjeta',PeliculaController::class,['except'=>['create','edit']])->middleware(ApiAuthMiddleware::class);
        Route::resource('/ticket',TicketController::class,['except'=>['create','edit']])->middleware(ApiAuthMiddleware::class);
        Route::resource('/detalles_ticket',DetallesTicketController::class,['except'=>['create','edit']])->middleware(ApiAuthMiddleware::class);
        Route::resource('/sala',SalaController::class,['except'=>['create','edit']])->middleware(ApiAuthMiddleware::class);//listo
        Route::resource('/combo',DetallesComboController::class,['except'=>['create','edit']])->middleware(ApiAuthMiddleware::class); 
        

    }
);

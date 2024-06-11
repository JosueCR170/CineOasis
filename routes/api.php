<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PeliculaController;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\AsientoController;
use App\Http\Controllers\FuncionController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DetallesTicketController;
use App\Http\Controllers\ComidaController;
use App\Http\Controllers\DetallesComboController;
use App\Http\Controllers\FuncionAsientoController;


use App\Http\Middleware\ApiAuthMiddleware;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1')->group(
    function(){
        //RUTAS ESPECIFICAS

        //RUTAS IMAGEN GENERALES
        Route::get('/imagen/show/{path}/{filename}',[ImagenController::class,'show'])->middleware(ApiAuthMiddleware::class);
        Route::post('/imagen/store/{path}',[ImagenController::class,'store'])->middleware(ApiAuthMiddleware::class);
        Route::put('/imagen/update/{path}/{filename}',[ImagenController::class,'update'])->middleware(ApiAuthMiddleware::class);
        Route::delete('/imagen/delete/{path}/{filename}',[ImagenController::class,'destroy'])->middleware(ApiAuthMiddleware::class);

        //RUTAS IMAGEN PARA PELICULA
        Route::get('/imagen', [ImagenController::class, 'index']);
        Route::get('/imagen/pelicula/{nombre}', [ImagenController::class, 'showImageForPelicula']);
        Route::post('/imagen/pelicula',[ImagenController::class,'storeImageForPelicula'])->middleware(ApiAuthMiddleware::class);
        Route::put('/imagen/pelicula/{id}', [ImagenController::class, 'updateImageForPelicula'])->middleware(ApiAuthMiddleware::class);//listo
       
        //POST
        Route::post('/user/login',[UserController::class,'login']);//listo
        Route::post('/user',[UserController::class,'store']);//listo
        Route::post('/asiento',[AsientoController::class,'store'])->middleware(ApiAuthMiddleware::class);
        Route::post('/pelicula',[PeliculaController::class,'store'])->middleware(ApiAuthMiddleware::class);//listo
        Route::post('/asiento/rellenar',[AsientoController::class,'rellenar'])->middleware(ApiAuthMiddleware::class);// (Actualizar)
        Route::post('/comida',[ComidaController::class,'store'])->middleware(ApiAuthMiddleware::class);
        Route::post('/funcion',[FuncionController::class,'store'])->middleware(ApiAuthMiddleware::class);
        //show
        Route::get('/user/getIdentity',[UserController::class,'getIdentity'])->middleware(ApiAuthMiddleware::class);//listo
        Route::get('/comida/{id}', [ComidaController::class, 'show']);
        Route::get('/asiento/{id}', [AsientoController::class, 'show']);
        Route::get('/funcion/{id}', [FuncionController::class, 'show']);
        Route::get('/pelicula/{id}', [PeliculaController::class, 'show']);//listo
        Route::get('/user/{id}', [UserController::class, 'show'])->middleware(ApiAuthMiddleware::class);//listo
        
        //GET

        Route::get('/user',[UserController::class, 'index'])->middleware(ApiAuthMiddleware::class);
        Route::get('/comida',[ComidaController::class, 'index']);//
        Route::get('/pelicula', [PeliculaController::class, 'index']);
        Route::get('/funcion',[FuncionController::class,'index']);
        Route::get('/asiento',[AsientoController::class,'index']);//
        
        //put

        Route::put('/user/{id}', [UserController::class, 'update'])->middleware(ApiAuthMiddleware::class);//listo
        Route::put('/comida/{id}', [ComidaController::class, 'update'])->middleware(ApiAuthMiddleware::class);//listo
        Route::put('/asiento/{id}', [AsientoController::class, 'update'])->middleware(ApiAuthMiddleware::class);//listo
        Route::put('/funcion/{id}',[FuncionController::class,'update'])->middleware(ApiAuthMiddleware::class);//listo
        Route::put('/pelicula/{id}', [PeliculaController::class, 'update'])->middleware(ApiAuthMiddleware::class);//listo
        
        //delete

        Route::delete('/user/{id}', [UserController::class, 'destroy'])->middleware(ApiAuthMiddleware::class);//listo
        Route::delete('/comida/{id}', [ComidaController::class, 'destroy'])->middleware(ApiAuthMiddleware::class);//listo
        Route::delete('/asiento/{id}', [AsientoController::class, 'destroy'])->middleware(ApiAuthMiddleware::class);//listo
        Route::delete('/funcion/{id}',[FuncionController::class,'destroy'])->middleware(ApiAuthMiddleware::class);//listo
        Route::delete('/pelicula/{id}', [PeliculaController::class, 'destroy'])->middleware(ApiAuthMiddleware::class);//listo
       
        
        //resource
        Route::resource('/ticket',TicketController::class,['except'=>['create','edit']])->middleware(ApiAuthMiddleware::class);
        Route::resource('/detallesTicket',DetallesTicketController::class,['except'=>['create','edit']])->middleware(ApiAuthMiddleware::class);
        Route::resource('/combo',DetallesComboController::class,['except'=>['create','edit']])->middleware(ApiAuthMiddleware::class); 
        Route::resource('/funcionAsiento',FuncionAsientoController::class,['except'=>['create','edit']])->middleware(ApiAuthMiddleware::class); 
        

      
    }
);

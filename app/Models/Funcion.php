<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcion extends Model
{
    use HasFactory;

    protected $table='funciones';

    protected static $salas=[
        'A','B','C','D','E'
    ];
    protected $fillable=['fecha','horaInicio','horaFinal', 'precio'];


    public function peliculas(){
        return $this->belongsTo(Pelicula::class,'idPelicula');
    }

    public function tickets(){
        return $this->hasMany(Ticket::class,'idFuncion');
    }

    public function funcionAsientos(){
        return $this->hasMany(FuncionAsiento::class,'idFuncion');
    }


}

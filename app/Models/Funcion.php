<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcion extends Model
{
    use HasFactory;

    protected $table='funciones';
    protected $fillable=['fecha','horaInicio','horaFinal', 'precio'];

    public function salas(){
        return $this->belongsTo(Sala::class,'idSala');
    }

    public function peliculas(){
        return $this->belongsTo(Pelicula::class,'idPelicula');
    }

    public function tickets(){
        return $this->hasMany(Ticket::class,'idFuncion');
    }



}

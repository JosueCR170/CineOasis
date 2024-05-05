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
        return $this->belongsTo(Sala::class,'id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuncionAsiento extends Model
{
    use HasFactory;
    protected $table = "funcion_asiento";
    public $timestamps = false;

    public function funcion(){
        return $this->belongsTo(Funcion::class,'idFuncion');
    }

    public function asiento(){
        return $this->belongsTo(Asiento::class,'idAsiento');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sala extends Model
{
    use HasFactory;

    protected $table='salas';
    protected $fillable=['nombreSala','capacidad'];

    public function asientos(){
        return $this->hasMany(Asiento::class, 'idSala');
    }
    public function funciones(){
        return $this->hasMany(Funcion::class, 'idSala');
    }
}